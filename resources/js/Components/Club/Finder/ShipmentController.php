<?php

namespace App\Http\Controllers;

use App\Services\FedExService;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Helpers\Countries;

class ShipmentController extends Controller
{
    protected $fedExService;

    public function __construct(FedExService $fedExService)
    {
        $this->fedExService = $fedExService;
    }

    public function createShipment(Request $request)
    {
        // dd($request->all());
        try {
            $request->merge([
                'package_liability' => filter_var($request->input('package_liability', false), FILTER_VALIDATE_BOOLEAN),
                'dry_ice' => filter_var($request->input('dry_ice', false), FILTER_VALIDATE_BOOLEAN),
                'dangerous_goods' => filter_var($request->input('dangerous_goods', false), FILTER_VALIDATE_BOOLEAN)
            ]);
            // var_dump($request->package_liability, $request->dry_ice, $request->dangerous_goods);
            // die(); 
            $validatedData = $request->validate([
                'totalNetCharge' => 'required',
                // 'shipperName' => 'string|max:255',
                'shipperPhone' => 'string|max:255',
                'shipperStreet' => 'required|string|max:100',
                'shipperCity' => 'required|string|max:100',
                // 'recipientName' => 'string|max:255',
                'recipientPhone' => 'string',
                'recipientStreet' => 'required|string|max:100',
                'recipientCity' => 'required|string|max:100',
                'shipDate' => 'date',
                'shipment_packagingType' => 'required|string',
                'shipment_serviceType' => 'string',
                'shipment_pickupType' => 'required|string',
                'imageType' => 'required|string',
                'labelStockType' => 'required|string',
                'labelResponseOptions' => 'required|string',
                'shipperstateOrProvinceCode' => 'required|string|max:2',
                'recipientstateOrProvinceCode' => 'required|string|max:2',
                'packages' => 'required|array',
                // 'customsValueAmount' => 'required',
                'customsValueQuantity' => 'required',
                // 'shipment_paymentType' => 'string',
                'package_liability' => 'boolean',
                'insurance_amount' => 'nullable | required_if:package_liability,true|numeric|min:0',
                'signature_option' => 'nullable | string|in:NO_SIGNATURE_REQUIRED,DIRECT,INDIRECT,ADULT',
                // 'saturday_delivery' => 'boolean',
                'dry_ice' => 'boolean',
                'dangerous_goods' => 'boolean',
                'ready_pickup_date' => 'nullable | required_if:shipment_pickupType,SCHEDULE_NEW_PICKUP|date',
                'ready_pickup_time' => 'nullable | required_if:shipment_pickupType,SCHEDULE_NEW_PICKUP',
                'latest_pickup_date' => 'nullable | required_if:shipment_pickupType,SCHEDULE_NEW_PICKUP|date',
                'latest_pickup_time' => 'nullable | required_if:shipment_pickupType,SCHEDULE_NEW_PICKUP'
            ]);

            // var_dump($validatedData['totalNetCharge'], 'validate');
            // die();

            // Calculate the total weight
            $totalWeight = 0;
            $packages = $validatedData['packages'];
            foreach ($packages as $package) {
                $totalWeight += $package['package_weight_amount'];
            }

            $shipperCountryCode = session('fromCountry');
            $recipientCountryCode = session('toCountry');
            // $shipperCountryCode = 'US';
            // $recipientCountryCode = 'US';
            $fromZip = session('zipcodeFrom');
            $toZip = session('zipcodeTo');
            $totalNetCharge = session('totalNetCharge');

            // var_dump($validatedData['totalNetCharge'], 'totalNetCharge');
            // die();

            $specialHandlingOptions = [];
            if (!empty($validatedData['package_liability'])) {
                $specialHandlingOptions = [
                    'insuranceAmount' => $validatedData['insurance_amount'] ?? null,
                    'signatureOption' => $validatedData['signature_option'] ?? 'NO_SIGNATURE_REQUIRED',
                    // 'saturdayDelivery' => !empty($validatedData['saturday_delivery']),
                    'dryIce' => !empty($validatedData['dry_ice']),
                    'dangerousGoods' => !empty($validatedData['dangerous_goods'])
                ];
            }

            $pickupDetail = null;
            if ($validatedData['shipment_pickupType'] === 'SCHEDULE_NEW_PICKUP') {
                $pickupDetail = [
                    'readyPickupDateTime' => date('c', strtotime($validatedData['ready_pickup_date'] . ' ' . $validatedData['ready_pickup_time'])),
                    'latestPickupDateTime' => date('c', strtotime($validatedData['latest_pickup_date'] . ' ' . $validatedData['latest_pickup_time']))
                ];
            }

            // var_dump($pickupDetail, "specialHandleOptions");
            // die();


            $responseData = $this->fedExService->createShipment(
                // $validatedData['shipperName'],
                $validatedData['shipperPhone'],
                // session('shipperStreetName'),
                $validatedData['shipperStreet'],
                $validatedData['shipperCity'],
                $validatedData['shipperstateOrProvinceCode'],
                $fromZip,
                $shipperCountryCode,
                // $validatedData['recipientName'],
                $validatedData['recipientPhone'],
                // session('recipientStreetName'),
                $validatedData['recipientStreet'],
                $validatedData['recipientCity'],
                $validatedData['recipientstateOrProvinceCode'],
                $toZip,
                $recipientCountryCode,
                $validatedData['shipDate'],
                $validatedData['shipment_pickupType'],
                $validatedData['shipment_serviceType'],
                $validatedData['shipment_packagingType'],
                $totalWeight,
                // $validatedData['shipment_paymentType'],
                $validatedData['imageType'],
                $validatedData['labelStockType'],
                $validatedData['labelResponseOptions'],
                $packages,
                $validatedData['totalNetCharge'],
                // $totalNetCharge,
                // $validatedData['customsValueAmount'],
                $validatedData['customsValueQuantity'],
                $specialHandlingOptions,
                $pickupDetail
            );

            // var_dump($responseData, "responseData");
            // die();

            if (isset($responseData['output']['transactionShipments'][0])) {
                $shipmentData = $responseData['output']['transactionShipments'][0];
                $ratingDetails = $shipmentData['completedShipmentDetail']['shipmentRating']['shipmentRateDetails'][0];
                $operationalDetail = $shipmentData['completedShipmentDetail']['operationalDetail'];
            
                // Calculate 30% of totalNetCharge and add it to totalBaseCharge
                $additionalCharge = intval($ratingDetails['totalNetCharge'] * 0.3);
                $ratingDetails['totalBaseCharge'] += $additionalCharge;
            
                return view('shipments.createdShipment', [
                    'trackingId' => $shipmentData['masterTrackingNumber'],
                    'serviceType' => $shipmentData['serviceType'],
                    'serviceName' => $shipmentData['serviceName'],
                    'shipDate' => $shipmentData['shipDatestamp'],
                    'deliveryDate' => $operationalDetail['deliveryDate'],
                    'deliveryDay' => $operationalDetail['deliveryDay'],
                    'deliveryTime' => $operationalDetail['publishedDeliveryTime'],
                    'totalBaseCharge' => $ratingDetails['totalBaseCharge'], // Updated value
                    'totalFreightDiscounts' => $ratingDetails['totalFreightDiscounts'],
                    'totalNetFreight' => $ratingDetails['totalNetFreight'],
                    'fuelSurchargePercent' => $ratingDetails['fuelSurchargePercent'],
                    'totalSurcharges' => $ratingDetails['totalSurcharges'],
                    'totalTaxes' => $ratingDetails['totalTaxes'],
                    'totalNetCharge' => $ratingDetails['totalNetCharge'],
                    'currency' => $ratingDetails['currency'],
                    'totalBillingWeight' => $ratingDetails['totalBillingWeight'],
                    'currencyExchangeRate' => $ratingDetails['currencyExchangeRate'],
                    'surchargeDetails' => $ratingDetails['surcharges'],
                    'transactionId' => $responseData['transactionId']
                ]);
            }
            
            
            

            // Error handling for FedEx response
            $errorData = json_decode($responseData, true);

            return view('shipments.error', [
                'errorMessage' => isset($errorData['errors'][0]['message']) ? $errorData['errors'][0]['message'] : 'Unknown error',
                'transactionId' => $errorData['transactionId'],
                'errorCode' => $errorData['errors'][0]['code'],
                'paramKey' => $errorData['errors'][0]['parameterList'][0]['key'],
                'paramValue' => $errorData['errors'][0]['parameterList'][0]['value']
            ]);

            // return back()
            // ->withInput()
            // ->with('error', 'Failed to create shipment. Please verify your shipping details and try again.');
            // return redirect()->route('shipment')->with('error', 'Failed to create shipment. Please try again.');
        } catch (\Exception $e) {
            Log::error('Shipment creation failed: ' . $e->getMessage());

            $errorMessage = app()->environment('local') ?
                $e->getMessage() :
                'There was an error processing your shipment. Please try again or contact support.';

            return view('shipments.error', [
                'errorMessage' => $errorMessage
            ]);
        }
    }

//     public function showSummary(Request $request)
// {
//     dd($request->all());
//     $request->merge([
//         'package_liability' => filter_var($request->input('package_liability', false), FILTER_VALIDATE_BOOLEAN),
//         'dry_ice' => filter_var($request->input('dry_ice', false), FILTER_VALIDATE_BOOLEAN),
//         'dangerous_goods' => filter_var($request->input('dangerous_goods', false), FILTER_VALIDATE_BOOLEAN)
//     ]);

//     $validatedData = $request->validate([
//         'totalNetCharge' => 'required',
//         'shipperPhone' => 'string|max:255',
//         'shipperStreet' => 'required|string|max:100',
//         'shipperCity' => 'required|string|max:100',
//         'recipientPhone' => 'string',
//         'recipientStreet' => 'required|string|max:100',
//         'recipientCity' => 'required|string|max:100',
//         'shipDate' => 'date',
//         'shipment_packagingType' => 'required|string',
//         'shipment_serviceType' => 'string',
//         'shipment_pickupType' => 'required|string',
//         'imageType' => 'required|string',
//         'labelStockType' => 'required|string',
//         'labelResponseOptions' => 'required|string',
//         'shipperstateOrProvinceCode' => 'required|string|max:2',
//         'recipientstateOrProvinceCode' => 'required|string|max:2',
//         'packages' => 'required|array',
//         'customsValueQuantity' => 'required',
//         'package_liability' => 'boolean',
//         'insurance_amount' => 'nullable|required_if:package_liability,true|numeric|min:0',
//         'signature_option' => 'nullable|string|in:NO_SIGNATURE_REQUIRED,DIRECT,INDIRECT,ADULT',
//         'dry_ice' => 'boolean',
//         'dangerous_goods' => 'boolean',
//         'ready_pickup_date' => 'nullable|required_if:shipment_pickupType,SCHEDULE_NEW_PICKUP|date',
//         'ready_pickup_time' => 'nullable|required_if:shipment_pickupType,SCHEDULE_NEW_PICKUP',
//         'latest_pickup_date' => 'nullable|required_if:shipment_pickupType,SCHEDULE_NEW_PICKUP|date',
//         'latest_pickup_time' => 'nullable|required_if:shipment_pickupType,SCHEDULE_NEW_PICKUP'
//     ]);

//     $totalWeight = 0;
//     foreach ($validatedData['packages'] as $package) {
//         $totalWeight += $package['package_weight_amount'];
//     }

//     $specialHandlingOptions = [];
//     if (!empty($validatedData['package_liability'])) {
//         $specialHandlingOptions = [
//             'insuranceAmount' => $validatedData['insurance_amount'] ?? null,
//             'signatureOption' => $validatedData['signature_option'] ?? 'NO_SIGNATURE_REQUIRED',
//             'dryIce' => !empty($validatedData['dry_ice']),
//             'dangerousGoods' => !empty($validatedData['dangerous_goods'])
//         ];
//     }

//     $pickupDetail = null;
//     if ($validatedData['shipment_pickupType'] === 'SCHEDULE_NEW_PICKUP') {
//         $pickupDetail = [
//             'readyPickupDateTime' => date('c', strtotime($validatedData['ready_pickup_date'] . ' ' . $validatedData['ready_pickup_time'])),
//             'latestPickupDateTime' => date('c', strtotime($validatedData['latest_pickup_date'] . ' ' . $validatedData['latest_pickup_time']))
//         ];
//     }

//     $summaryData = [
//         'shipperAddress' => [
//             'street' => $validatedData['shipperStreet'],
//             'city' => $validatedData['shipperCity'],
//             'state' => $validatedData['shipperstateOrProvinceCode'],
//             'country' => session('fromCountry'),
//             'zipCode' => session('zipcodeFrom'),
//             'phone' => $validatedData['shipperPhone']
//         ],
//         'recipientAddress' => [
//             'street' => $validatedData['recipientStreet'],
//             'city' => $validatedData['recipientCity'],
//             'state' => $validatedData['recipientstateOrProvinceCode'],
//             'country' => session('toCountry'),
//             'zipCode' => session('zipcodeTo'),
//             'phone' => $validatedData['recipientPhone']
//         ],
//         'shipmentDetails' => [
//             'serviceType' => $validatedData['shipment_serviceType'],
//             'packagingType' => $validatedData['shipment_packagingType'],
//             'pickupType' => $validatedData['shipment_pickupType'],
//             'packages' => $validatedData['packages'],
//             'totalWeight' => $totalWeight,
//             'totalCharge' => $validatedData['totalNetCharge'],
//             'specialHandling' => $specialHandlingOptions,
//             'pickupDetails' => $pickupDetail
//         ]
//     ];

//     session(['shipment_form_data' => $request->all()]);

//     return view('shipments.summary', [
//         'summaryData' => $summaryData,
//         'formData' => $request->all()
//     ]);
// }

public function showSummary(Request $request)
{
    
    $request->merge([
        'package_liability' => filter_var($request->input('package_liability', false), FILTER_VALIDATE_BOOLEAN),
        'dry_ice' => filter_var($request->input('dry_ice', false), FILTER_VALIDATE_BOOLEAN),
        'dangerous_goods' => filter_var($request->input('dangerous_goods', false), FILTER_VALIDATE_BOOLEAN)
    ]);

    $validatedData = $request->validate([
        'totalNetCharge' => 'required|numeric|min:0',
        'shipperPhone' => 'string|max:255',
        'shipperStreet' => 'required|string|max:100',
        'shipperCity' => 'required|string|max:100',
        'recipientPhone' => 'string',
        'recipientStreet' => 'required|string|max:100',
        'recipientCity' => 'required|string|max:100',
        'shipDate' => 'date',
        'shipment_packagingType' => 'required|string',
        'shipment_serviceType' => 'string',
        'shipment_pickupType' => 'required|string',
        'imageType' => 'required|string',
        'labelStockType' => 'required|string',
        'labelResponseOptions' => 'required|string',
        'shipperstateOrProvinceCode' => 'required|string|max:2',
        'recipientstateOrProvinceCode' => 'required|string|max:2',
        'packages' => 'required|array',
        'customsValueQuantity' => 'required',
        'package_liability' => 'boolean',
        'insurance_amount' => 'nullable|required_if:package_liability,true|numeric|min:0',
        'signature_option' => 'nullable|string|in:NO_SIGNATURE_REQUIRED,DIRECT,INDIRECT,ADULT',
        'dry_ice' => 'boolean',
        'dangerous_goods' => 'boolean',
        'ready_pickup_date' => 'nullable|required_if:shipment_pickupType,SCHEDULE_NEW_PICKUP|date',
        'ready_pickup_time' => 'nullable|required_if:shipment_pickupType,SCHEDULE_NEW_PICKUP',
        'latest_pickup_date' => 'nullable|required_if:shipment_pickupType,SCHEDULE_NEW_PICKUP|date',
        'latest_pickup_time' => 'nullable|required_if:shipment_pickupType,SCHEDULE_NEW_PICKUP'
    ]);

    // Calculate total weight
    $totalWeight = 0;
    foreach ($validatedData['packages'] as $package) {
        $totalWeight += $package['package_weight_amount'];
    }

    // Calculate markup
    $markupPercentage = 30; // Define markup percentage
    $markupAmount = ($validatedData['totalNetCharge'] * $markupPercentage) / 100; // Calculate 30% of totalNetCharge
    $totalWithMarkup = $validatedData['totalNetCharge'] + $markupAmount; // Add markup to totalNetCharge
    // dd($totalWithMarkup);

    $specialHandlingOptions = [];
    if (!empty($validatedData['package_liability'])) {
        $specialHandlingOptions = [
            'insuranceAmount' => $validatedData['insurance_amount'] ?? null,
            'signatureOption' => $validatedData['signature_option'] ?? 'NO_SIGNATURE_REQUIRED',
            'dryIce' => !empty($validatedData['dry_ice']),
            'dangerousGoods' => !empty($validatedData['dangerous_goods'])
        ];
    }

    $pickupDetail = null;
    if ($validatedData['shipment_pickupType'] === 'SCHEDULE_NEW_PICKUP') {
        $pickupDetail = [
            'readyPickupDateTime' => date('c', strtotime($validatedData['ready_pickup_date'] . ' ' . $validatedData['ready_pickup_time'])),
            'latestPickupDateTime' => date('c', strtotime($validatedData['latest_pickup_date'] . ' ' . $validatedData['latest_pickup_time']))
        ];
    }

    $summaryData = [
        'shipperAddress' => [
            'street' => $validatedData['shipperStreet'],
            'city' => $validatedData['shipperCity'],
            'state' => $validatedData['shipperstateOrProvinceCode'],
            'country' => session('fromCountry'),
            'zipCode' => session('zipcodeFrom'),
            'phone' => $validatedData['shipperPhone']
        ],
        'recipientAddress' => [
            'street' => $validatedData['recipientStreet'],
            'city' => $validatedData['recipientCity'],
            'state' => $validatedData['recipientstateOrProvinceCode'],
            'country' => session('toCountry'),
            'zipCode' => session('zipcodeTo'),
            'phone' => $validatedData['recipientPhone']
        ],
        'shipmentDetails' => [
            'serviceType' => $validatedData['shipment_serviceType'],
            'packagingType' => $validatedData['shipment_packagingType'],
            'pickupType' => $validatedData['shipment_pickupType'],
            'packages' => $validatedData['packages'],
            'totalWeight' => $totalWeight,
            'totalCharge' => $validatedData['totalNetCharge'],
            'markupAmount' => $markupAmount, // Include markup details
            'totalWithMarkup' => $totalWithMarkup, // Include final total charge with markup
            'specialHandling' => $specialHandlingOptions,
            'pickupDetails' => $pickupDetail
        ]
    ];

    // Store form data in session
    session(['shipment_form_data' => $request->all()]);

    return view('shipments.summary', [
        'summaryData' => $summaryData,
        'formData' => $request->all()
    ]);
}


}
