<?php

namespace App\Http\Controllers;

use App\Models\CheckoutDetail;
use App\Models\Event;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Academy;

class StripeCheckoutController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        try {
            // Log the incoming request data
            Log::info('Checkout request received', [
                'request_data' => $request->all()
            ]);

            // Validate the request
            $validator = Validator::make($request->all(), [
                'event_id' => 'required|exists:events,id',
                'price' => 'required|numeric|min:0',
                'user_type' => 'required|in:Adult,Minor,Children',
                'profile_id' => 'required|exists:profiles,id',
                'academy_id' => 'nullable|exists:academies,id',
                'academy_name' => 'required|string|max:255',
                'division_id' => 'required|exists:event_to_divisions,id'
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed', [
                    'errors' => $validator->errors()->toArray(),
                    'request_data' => $request->all()
                ]);
                return response()->json(['message' => $validator->errors()->first()], 422);
            }

            Log::info('Starting Stripe checkout session creation', [
                'event_id' => $request->event_id,
                'price' => $request->price,
                'user_type' => $request->user_type,
                'user_id' => auth()->id(),
                'profile_id' => $request->profile_id,
                'academy_id' => $request->academy_id,
                'academy_name' => $request->academy_name
            ]);

            // Initialize Stripe client
            $stripeKey = config('services.stripe.secret');
            if (empty($stripeKey)) {
                Log::error('Stripe secret key is not configured');
                return response()->json(['message' => 'Payment configuration error'], 500);
            }

            $stripeClient = new \Stripe\StripeClient($stripeKey);

            // Find the event
            $event = Event::findOrFail($request->event_id);
            
            Log::info('Event found', ['event' => $event->toArray()]);

            // Create Stripe session
            try {
                // Prepare metadata
                $metadata = [
                    'event_id' => $event->id,
                    'user_type' => $request->user_type,
                    'user_id' => auth()->id(),
                    'profile_id' => $request->profile_id,
                    'price' => $request->price,
                    'academy_name' => $request->academy_name,
                    'division_id' => $request->division_id
                ];

                // Only add academy_id to metadata if it's provided
                if ($request->filled('academy_id')) {
                    $metadata['academy_id'] = $request->academy_id;
                }

                Log::info('Creating Stripe session with metadata', ['metadata' => $metadata]);

                $session = $stripeClient->checkout->sessions->create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => $event->title,
                            ],
                            'unit_amount' => (int)($request->price * 100), // Convert to cents
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => route('stripe.success', ['event_id' => $event->id]),
                    'cancel_url' => route('stripe.cancel', ['event_id' => $event->id]),
                    'metadata' => $metadata
                ]);

                Log::info('Stripe session created successfully', [
                    'session_id' => $session->id,
                    'metadata' => $metadata
                ]);

                return response()->json(['sessionId' => $session->id]);
            } catch (\Exception $e) {
                Log::error('Stripe API error', [
                    'error' => $e->getMessage(),
                    'error_type' => method_exists($e, 'getStripeCode') ? $e->getStripeCode() : null,
                    'http_status' => method_exists($e, 'getHttpStatus') ? $e->getHttpStatus() : null,
                    'request_id' => method_exists($e, 'getRequestId') ? $e->getRequestId() : null
                ]);
                return response()->json(['message' => 'Payment service error'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Stripe checkout error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'message' => 'Failed to create checkout session. Please try again later.',
                'error_details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function handleWebhook(Request $request)
    {
        try {
            Log::info('Webhook received', [
                'headers' => $request->headers->all(),
                'payload' => $request->getContent()
            ]);

            $payload = $request->getContent();
            $sig_header = $request->header('Stripe-Signature');
            $endpoint_secret = config('services.stripe.webhook_secret');

            if (empty($endpoint_secret)) {
                Log::error('Stripe webhook secret is not configured');
                return response()->json(['error' => 'Webhook secret not configured'], 500);
            }

            try {
                $event = Webhook::constructEvent(
                    $payload, $sig_header, $endpoint_secret
                );

                Log::info('Stripe webhook event constructed successfully', [
                    'type' => $event->type,
                    'id' => $event->id
                ]);

                switch ($event->type) {
                    case 'checkout.session.completed':
                        $session = $event->data->object;
                        Log::info('Processing checkout.session.completed', [
                            'session_id' => $session->id,
                            'payment_status' => $session->payment_status,
                            'metadata' => $session->metadata
                        ]);
                        $this->handleSuccessfulPayment($session);
                        break;
                    case 'checkout.session.expired':
                        $session = $event->data->object;
                        Log::info('Processing checkout.session.expired', [
                            'session_id' => $session->id
                        ]);
                        $this->handleExpiredPayment($session);
                        break;
                    case 'payment_intent.payment_failed':
                        $paymentIntent = $event->data->object;
                        Log::info('Processing payment_intent.payment_failed', [
                            'payment_intent_id' => $paymentIntent->id
                        ]);
                        $this->handleFailedPayment($paymentIntent);
                        break;
                    default:
                        Log::info('Unhandled webhook event type', [
                            'type' => $event->type
                        ]);
                }

                return response()->json(['status' => 'success']);
            } catch (\Stripe\Exception\SignatureVerificationException $e) {
                Log::error('Invalid webhook signature', [
                    'error' => $e->getMessage(),
                    'signature' => $sig_header
                ]);
                return response()->json(['error' => 'Invalid signature'], 400);
            } catch (\Exception $e) {
                Log::error('Webhook error', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'payload' => $payload
                ]);
                return response()->json(['error' => 'Webhook error'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Unexpected webhook error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Unexpected error'], 500);
        }
    }

    private function handleSuccessfulPayment($session)
    {
        try {
            Log::info('Starting successful payment handling', [
                'session_id' => $session->id,
                'metadata' => $session->metadata,
                'payment_status' => $session->payment_status
            ]);

            // Verify the session is paid
            if ($session->payment_status !== 'paid') {
                Log::warning('Session not paid', [
                    'session_id' => $session->id,
                    'payment_status' => $session->payment_status
                ]);
                return;
            }

            // Validate required metadata
            if (empty($session->metadata->user_id) || empty($session->metadata->event_id) || empty($session->metadata->profile_id)) {
                Log::error('Missing required metadata', [
                    'session_id' => $session->id,
                    'metadata' => $session->metadata
                ]);
                return;
            }

            // Check if checkout detail already exists
            $existingCheckout = CheckoutDetail::where('stripe_session_id', $session->id)->first();
            if ($existingCheckout) {
                Log::info('Checkout detail already exists', [
                    'checkout_id' => $existingCheckout->id,
                    'session_id' => $session->id
                ]);
                return;
            }

            // Create checkout detail record
            try {
                // Validate academy data
                $academyId = isset($session->metadata->academy_id) ? (int)$session->metadata->academy_id : 0;
                $academyName = $session->metadata->academy_name ?? '';

                // Additional validation for academy data
                if ($academyId === 0 && empty($academyName)) {
                    throw new \Exception('Academy information is missing');
                }

                // If academy_id is provided (not 0), verify it exists
                if ($academyId !== 0) {
                    $academy = Academy::find($academyId);
                    if (!$academy) {
                        throw new \Exception('Selected academy not found');
                    }
                    // Use the official academy name from database
                    $academyName = $academy->name;
                }

                Log::info('Creating checkout detail with academy info', [
                    'academy_id' => $academyId,
                    'academy_name' => $academyName,
                    'metadata' => $session->metadata
                ]);

                $checkoutDetailData = [
                    'user_id' => $session->metadata->user_id,
                    'event_id' => $session->metadata->event_id,
                    'profile_id' => $session->metadata->profile_id,
                    'amount' => $session->metadata->price,
                    'user_type' => $session->metadata->user_type,
                    'academy_id' => $academyId,
                    'academy_name' => $academyName,
                    'division_id' => $session->metadata->division_id,
                    'payment_status' => 'completed',
                    'stripe_session_id' => $session->id,
                    'payment_details' => [
                        'completed_at' => now(),
                        'stripe_session_id' => $session->id,
                        'payment_intent_id' => $session->payment_intent,
                        'amount_paid' => $session->amount_total / 100,
                        'currency' => $session->currency,
                        'customer_email' => $session->customer_email,
                        'customer_name' => $session->customer_details->name ?? null
                    ]
                ];

                Log::info('About to create checkout detail with data:', [
                    'checkout_data' => $checkoutDetailData
                ]);

                $checkoutDetail = CheckoutDetail::create($checkoutDetailData);

                Log::info('Payment completed and checkout detail created successfully', [
                    'checkout_id' => $checkoutDetail->id,
                    'session_id' => $session->id,
                    'user_id' => $session->metadata->user_id,
                    'event_id' => $session->metadata->event_id,
                    'profile_id' => $session->metadata->profile_id,
                    'academy_id' => $academyId,
                    'academy_name' => $academyName
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to create checkout detail', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'session_id' => $session->id,
                    'metadata' => $session->metadata
                ]);
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error handling successful payment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => $session->id
            ]);
            throw $e;
        }
    }

    private function handleExpiredPayment($session)
    {
        try {
            $checkoutDetail = CheckoutDetail::where('stripe_session_id', $session->id)->first();
            if ($checkoutDetail) {
                $checkoutDetail->update([
                    'payment_status' => 'expired',
                    'payment_details' => array_merge($checkoutDetail->payment_details ?? [], [
                        'expired_at' => now(),
                        'stripe_session_id' => $session->id
                    ])
                ]);
                Log::info('Payment marked as expired', ['checkout_id' => $checkoutDetail->id]);
            }
        } catch (\Exception $e) {
            Log::error('Error handling expired payment', [
                'error' => $e->getMessage(),
                'session_id' => $session->id
            ]);
        }
    }

    private function handleFailedPayment($paymentIntent)
    {
        try {
            $checkoutDetail = CheckoutDetail::where('stripe_session_id', $paymentIntent->metadata->checkout_id)->first();
            if ($checkoutDetail) {
                $checkoutDetail->update([
                    'payment_status' => 'failed',
                    'payment_details' => array_merge($checkoutDetail->payment_details ?? [], [
                        'failed_at' => now(),
                        'payment_intent_id' => $paymentIntent->id,
                        'failure_reason' => $paymentIntent->last_payment_error->message ?? 'Unknown error'
                    ])
                ]);
                Log::info('Payment marked as failed', [
                    'checkout_id' => $checkoutDetail->id,
                    'reason' => $paymentIntent->last_payment_error->message ?? 'Unknown error'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error handling failed payment', [
                'error' => $e->getMessage(),
                'payment_intent_id' => $paymentIntent->id
            ]);
        }
    }

    public function success(Request $request)
    {
        try {
            Log::info('Processing success page request', [
                'event_id' => $request->event_id,
                'user_id' => auth()->id(),
                'session_id' => $request->session_id
            ]);

            // Verify payment status with Stripe
            Stripe::setApiKey(config('services.stripe.secret'));
            
            // Get the latest checkout detail for this event and user
            $checkoutDetail = CheckoutDetail::where('event_id', $request->event_id)
                ->where('user_id', auth()->id())
                ->where('payment_status', 'completed')
                ->latest()
                ->first();

            if (!$checkoutDetail) {
                Log::warning('No completed payment found', [
                    'event_id' => $request->event_id,
                    'user_id' => auth()->id()
                ]);
                return redirect()->route('event.show', ['id' => $request->event_id])
                    ->with('error', 'Payment not completed. Please try again.');
            }

            // Verify with Stripe that the payment is actually completed
            try {
                $session = Session::retrieve($checkoutDetail->stripe_session_id);
                
                if ($session->payment_status !== 'paid') {
                    Log::warning('Payment not paid according to Stripe', [
                        'session_id' => $session->id,
                        'payment_status' => $session->payment_status,
                        'checkout_id' => $checkoutDetail->id
                    ]);
                    return redirect()->route('event.show', ['id' => $request->event_id])
                        ->with('error', 'Payment not completed. Please try again.');
                }

                // Update checkout detail with latest payment information
                $checkoutDetail->update([
                    'payment_details' => array_merge($checkoutDetail->payment_details ?? [], [
                        'last_verified_at' => now(),
                        'payment_status' => $session->payment_status,
                        'customer_email' => $session->customer_email,
                        'customer_name' => $session->customer_details->name ?? null
                    ])
                ]);

                Log::info('Payment verified successfully', [
                    'checkout_id' => $checkoutDetail->id,
                    'session_id' => $session->id
                ]);

                return redirect()->route('event.show', [
                    'id' => $request->event_id,
                    'success' => 'true',
                    'payment_status' => 'completed'
                ])
                    ->with('success', 'Payment completed successfully! Your registration is now complete.')
                    ->with('showSuccessMessage', true);
            } catch (\Stripe\Exception\ApiErrorException $e) {
                Log::error('Stripe API error during payment verification', [
                    'error' => $e->getMessage(),
                    'checkout_id' => $checkoutDetail->id
                ]);
                return redirect()->route('event.show', ['id' => $request->event_id])
                    ->with('error', 'Error verifying payment. Please contact support.');
            }
        } catch (\Exception $e) {
            Log::error('Payment success error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('event.show', ['id' => $request->event_id])
                ->with('error', 'Error processing payment completion. Please contact support.');
        }
    }

    public function cancel(Request $request)
    {
        try {
            return redirect()->route('event.show', ['id' => $request->event_id])
                ->with('error', 'Payment was cancelled. Please try again.');
        } catch (\Exception $e) {
            Log::error('Payment cancel error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('event.show', ['id' => $request->event_id])
                ->with('error', 'Error processing payment cancellation. Please try again.');
        }
    }
}
