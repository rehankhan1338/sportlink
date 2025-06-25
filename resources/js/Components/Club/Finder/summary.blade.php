@extends('layouts.layout')
@section('title', 'Shipment Summary')
@section('css_content', 'css/summary.css')

@section('content')
@php
function formatText($text) {
    $text = str_replace('_', ' ', $text);
    return ucwords(strtolower($text));
}
@endphp
<div class="container mt-5 mb-5">
    <div class="card main">
        <div class="card-header">
            <h3>Review Your Shipment Details</h3>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <!-- Shipper Details -->
                <div class="col-md-6">
                    <h4 class="text-primary">From</h4>
                    <div class="custom-border mb-4" style="border-bottom: 2px dashed #ccc; width: 30%; margin-left: auto; margin-right: auto; "></div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <p><strong>Address:</strong> {{ $summaryData['shipperAddress']['street'] }}</p>
                            <p><strong>City:</strong> {{ $summaryData['shipperAddress']['city'] }}</p>
                            <p><strong>State:</strong> {{ $summaryData['shipperAddress']['state'] }}</p>
                            <p><strong>Zip Code:</strong> {{ $summaryData['shipperAddress']['zipCode'] }}</p>
                            <p><strong>Country:</strong> {{ $summaryData['shipperAddress']['country'] }}</p>
                            <p><strong>Phone:</strong> {{ $summaryData['shipperAddress']['phone'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Recipient Details -->
                <div class="col-md-6">
                    <h4 class="text-primary">To</h4>
                    <div class="custom-border mb-4" style="border-bottom: 2px dashed #ccc; width: 30%; margin-left: auto; margin-right: auto; "></div>
                                            
                    <div class="card mb-3">
                        <div class="card-body">
                            <p><strong>Address:</strong> {{ $summaryData['recipientAddress']['street'] }}</p>
                            <p><strong>City:</strong> {{ $summaryData['recipientAddress']['city'] }}</p>
                            <p><strong>State:</strong> {{ $summaryData['recipientAddress']['state'] }}</p>
                            <p><strong>Zip Code:</strong> {{ $summaryData['recipientAddress']['zipCode'] }}</p>
                            <p><strong>Country:</strong> {{ $summaryData['recipientAddress']['country'] }}</p>
                            <p><strong>Phone:</strong> {{ $summaryData['recipientAddress']['phone'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="custom-border mb-4" style="border-bottom: 2px dashed #4A008A; width: 100%; margin-left: auto; margin-right: auto; "></div>

            <!-- Shipment Details -->
            <div class="mt-4">
                <h4 class="text-primary">Shipment Details</h4>
                <div class="custom-border mb-4" style="border-bottom: 2px dashed #ccc; width: 30%; margin-left: auto; margin-right: auto; "></div>

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-6">
                                <p><strong>Service Type:</strong> {{ formatText($summaryData['shipmentDetails']['serviceType']) }}</p>
                                <p><strong>Packaging Type:</strong> {{ formatText($summaryData['shipmentDetails']['packagingType']) }}</p>
                                <p><strong>Pickup Type:</strong> {{ formatText($summaryData['shipmentDetails']['pickupType']) }}</p>
                            </div>
                            
                        </div>
                        <div class="row">
                                @if(!empty($summaryData['shipmentDetails']['specialHandling']))
                                    <h5>Special Handling</h5>
                                    @if(isset($summaryData['shipmentDetails']['specialHandling']['insuranceAmount']))
                                        <p><strong>Insurance Amount:</strong> ${{ $summaryData['shipmentDetails']['specialHandling']['insuranceAmount'] }}</p>
                                    @endif
                                    <p><strong>Signature Option:</strong> {{ $summaryData['shipmentDetails']['specialHandling']['signatureOption'] }}</p>
                                    <p><strong>Dry Ice:</strong> {{ $summaryData['shipmentDetails']['specialHandling']['dryIce'] ? 'Yes' : 'No' }}</p>
                                    <p><strong>Dangerous Goods:</strong> {{ $summaryData['shipmentDetails']['specialHandling']['dangerousGoods'] ? 'Yes' : 'No' }}</p>
                                @endif
                            </div>

                        <!-- Package Details -->
                        <div class="d-flex flex-column align-items-center justify-content-center mt-3 mx-md-5">
                            <h5>Packages</h5>
                            @foreach($summaryData['shipmentDetails']['packages'] as $index => $package)
                                <div class="card mb-3 mx-md-5" style="border: solid 1px #4A008A; width: 50%;">
                                    <div class="card-body">
                                        <p><strong>Package {{ $index + 1 }}</strong></p>
                                        <p>Weight: {{ $package['package_weight_amount'] }} {{ $package['package_weight_unit'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!-- Calculation Details -->
<div class="mt-4">
    <h4 class="text-primary">Calculation Details</h4>
    <div class="custom-border mb-4" style="border-bottom: 2px dashed #ccc; width: 30%; margin-left: auto; margin-right: auto; "></div>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Total Weight:</strong> {{ $summaryData['shipmentDetails']['totalWeight'] }} lbs</p>
            <p><strong>Total Charge (Base):</strong> ${{ $summaryData['shipmentDetails']['totalCharge'] }}</p>
            <p><strong>Markup (30%):</strong> ${{ $summaryData['shipmentDetails']['markupAmount'] }}</p>
            <p><strong>Total Charge (with Markup):</strong> ${{ $summaryData['shipmentDetails']['totalWithMarkup'] }}</p>
        </div>
    </div>
</div>

            <!-- Hidden Form with All Data -->
            <form action="{{ route('create') }}" method="POST" class="mt-4">
                @csrf
                @foreach($formData as $key => $value)
                    @if(is_array($value))
                        @foreach($value as $k => $v)
                            @if(is_array($v))
                                @foreach($v as $subK => $subV)
                                    <input type="hidden" name="{{ $key }}[{{ $k }}][{{ $subK }}]" value="{{ $subV }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}[{{ $k }}]" value="{{ $v }}">
                            @endif
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">    
                    @endif
                @endforeach

    <!-- Add Markup Amount Explicitly -->
    <input type="hidden" name="shipmentDetails[markupAmount]" value="{{ $summaryData['shipmentDetails']['markupAmount'] }}">
    <input type="hidden" name="shipmentDetails[totalWithMarkup]" value="{{ $summaryData['shipmentDetails']['totalWithMarkup'] }}">


                <div class="d-flex justify-content-between custom-button-group">
                    <button type="button" onclick="window.history.back()" class="btn btn-secondary">Edit Details</button>
                    <button type="submit" class="btn" style="background-color: #1D4E89; color: white;">Confirm and Create Shipment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
