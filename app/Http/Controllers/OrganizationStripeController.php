<?php

namespace App\Http\Controllers;

use App\Models\OrganizationStripeDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrganizationStripeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'stripe_details.account_type' => 'required|in:individual,company',
            'stripe_details.business_name' => 'required_if:stripe_details.account_type,company',
            'stripe_details.business_tax_id' => 'required_if:stripe_details.account_type,company',
            'stripe_details.account_holder_name' => 'required',
            'stripe_details.account_number' => 'required',
            'stripe_details.routing_number' => 'required',
            'stripe_details.bank_account_type' => 'required|in:checking,savings',
            'stripe_details.address.line1' => 'required',
            'stripe_details.address.city' => 'required',
            'stripe_details.address.state' => 'required',
            'stripe_details.address.postal_code' => 'required',
            'stripe_details.address.country' => 'required',
            'stripe_details.email' => 'required|email',
            'stripe_details.phone' => 'required'
        ]);

        $stripeDetails = OrganizationStripeDetail::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'account_type' => $request->input('stripe_details.account_type'),
                'business_name' => $request->input('stripe_details.business_name'),
                'business_tax_id' => $request->input('stripe_details.business_tax_id'),
                'account_holder_name' => $request->input('stripe_details.account_holder_name'),
                'account_number' => $request->input('stripe_details.account_number'),
                'routing_number' => $request->input('stripe_details.routing_number'),
                'bank_account_type' => $request->input('stripe_details.bank_account_type'),
                'address_line1' => $request->input('stripe_details.address.line1'),
                'address_city' => $request->input('stripe_details.address.city'),
                'address_state' => $request->input('stripe_details.address.state'),
                'address_postal_code' => $request->input('stripe_details.address.postal_code'),
                'address_country' => $request->input('stripe_details.address.country'),
                'email' => $request->input('stripe_details.email'),
                'phone' => $request->input('stripe_details.phone')
            ]
        );

        return response()->json([
            'message' => 'Stripe details saved successfully',
            'stripe_details' => $stripeDetails
        ]);
    }

    public function show()
    {
        $stripeDetails = OrganizationStripeDetail::where('user_id', Auth::id())->first();
        
        return response()->json([
            'stripe_details' => $stripeDetails
        ]);
    }
}
