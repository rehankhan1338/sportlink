@extends('layouts.layout')
@section('title', 'Shipment Created')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="tab" style="width: 1200px; margin: 0 auto; height: 15px; background-color: #2B2929; border-radius: 50px; position: relative; top: 8px; z-index: -1;"></div>
        <div class="col-lg-10" style="width: 80%;">
            <div class="card shadow-lg border-0 rounded-3 custom-shadow">
                <div class="card-header bg-gradient text-white py-4" style="border: none; background: white;">
                    <div class="custom-border mb-4" style="border-bottom: 2px dashed #ccc; width: 100%; margin-left: auto; margin-right: auto; "></div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="mb-0" style="color: #1F3A5F; font-weight: 600;"> 
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Shipment Created Successfully
                        </h4>
                        <span class="badge bg-light text-dark" style="font-size: 1rem; border: 2px #4a008a solid;">{{ $trackingId }}</span>
                    </div>
                </div>

                <div class="card-body p-4 custom-padding">
                    <!-- Tracking Status -->
                    <div class="text-center mb-4">
                        <div class="badge bg-success p-3 rounded-pill custom-background">
                            <i class="bi bi-box-seam me-2"></i>
                            Shipment Ready for Pickup
                        </div>
                    </div>

                    <!-- Shipment Details -->
                    <div class="row g-4" style="flex-direction: column; align-content: center;">
                        <div class="col-md-6" style="width: 100%;">
                            <div class="card h-100 border-0 bg-light">
                                <div class="card-body" style="color: #1F3A5F !important;">
                                    <h5 class="card-title mb-4" style="font-weight: 600;">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Shipment Details
                                    </h5>
                                    <div class="d-flex flex-column gap-3">
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Service</span>
                                            <span class="fw-bold">{{ $serviceName }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Ship Date</span>
                                            <span class="fw-bold">{{ $shipDate }}</span>
                                        </div>
                                        <!--<div class="d-flex justify-content-between">-->
                                        <!--    <span class="text-muted">Estimated Delivery</span>-->
                                        <!--    <span class="fw-bold">{{ $deliveryDay }} - {{ $deliveryDate }} by {{ $deliveryTime }}</span>-->
                                        <!--</div>-->
                                        <!--<div class="d-flex justify-content-between">-->
                                        <!--    <span class="text-muted">Transaction ID</span>-->
                                        <!--    <span class="fw-bold">{{ $transactionId }}</span>-->
                                        <!--</div>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="custom-border mb-4" style="border-bottom: 2px dashed #ccc; width: 90%; margin-left: auto; margin-right: auto; "></div>

                        <!-- Cost Breakdown -->
                        <div class="col-md-6" style="width: 100%; margin-top: 0 !important;">
    <div class="card h-100 border-0 bg-light">
        <div class="card-body" style="color: #1F3A5F !important;">
            <h5 class="card-title mb-4" style="font-weight: 600;">
                <i class="bi bi-currency-dollar me-2"></i>
                Cost Breakdown
            </h5>
            <div class="d-flex flex-column gap-3">
                <!-- Base Charge -->
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Base Charge</span>
                    <span class="fw-bold">${{ number_format($totalBaseCharge, 2) }}</span>
                </div>

                <!-- Freight Discounts -->
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Freight Discounts</span>
                    <span class="fw-bold">- ${{ number_format($totalFreightDiscounts, 2) }}</span>
                </div>

                <!-- Net Freight -->
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Net Freight</span>
                    <span class="fw-bold">${{ number_format($totalNetFreight, 2) }}</span>
                </div>

                <!-- Fuel Surcharge -->
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Fuel Surcharge ({{ number_format($fuelSurchargePercent, 2) }}%)</span>
                    <span class="fw-bold">${{ number_format($totalSurcharges, 2) }}</span>
                </div>

                <!-- Taxes (if applicable) -->
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Taxes</span>
                    <span class="fw-bold">${{ number_format($totalTaxes, 2) }}</span>
                </div>

                <!-- Divider -->
                <hr style="border: 1px solid #1D4E89; margin: .3rem 0 !important;">

                <!-- Total Amount -->
                <div class="d-flex justify-content-between" style="font-size: 1.25rem;">
                    <span class="text-muted fw-bold">Total Amount</span>
                    <span class="fw-bold text-primary">${{ number_format($totalNetCharge, 2) }} {{ $currency }}</span>
                </div>

                <!-- Additional Info -->
                <div class="mt-3 text-muted">
                    <p>Weight: {{ $totalBillingWeight['value'] }} {{ $totalBillingWeight['units'] }}</p>
                    <p>Currency Exchange Rate: {{ $currencyExchangeRate['rate'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>


                    </div>
                    
                    <div class="custom-border mb-4" style="border-bottom: 2px dashed #ccc; width: 90%; margin-left: auto; margin-right: auto; margin-top: 1.5rem; "></div>
                    <!-- Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="card-title mb-4" style="color: #1F3A5F !important;">
                                        <i class="bi bi-file-earmark-text me-2"></i>
                                        Documents & Actions
                                    </h5>
                                    <div class="d-flex flex-wrap gap-3">
    <!-- Proceed to Payment Button -->
    <button type="button" class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#paymentModal" style="display: block; margin: 0 auto; ">
        <i class="bi bi-credit-card me-2"></i>
        Proceed to Payment
    </button>
</div>

                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="custom-border mb-4" style="border-bottom: 2px dashed #1F3A5F; width: 100%; margin-left: auto; margin-right: auto; margin-top: 1.5rem;"></div>
                    
                    <div class="row mt-4">
                        <div class="card" style="border: none;">
                            <div class="card-body">
                                <div class="barcode">
                                    <img src="img/barcode.gif" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="display: flex; align-items: center; min-height: calc(100% - 1rem); justify-content: center; ">
        <div class="modal-content" style="border: 5px solid #4991C4; box-shadow: 15px 15px 0 #4991C4;">
            <div class="modal-header" style="background-color: #6CB4D9; border-radius: 0;">
                <h5 class="modal-title" id="paymentModalLabel" style="color: #1F3A5F;">Select Payment Method</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color: #4991C4; color: #1F3A5F;"></button>
            </div>
            <div class="modal-body" style="margin: 1.5rem;">
                <form id="paymentForm" action="#" method="POST">
                    @csrf
                    <input type="hidden" name="amount" value="{{ $totalNetCharge }}">
                    <input type="hidden" name="currency" value="{{ $currency }}">
                    <input type="hidden" name="trackingId" value="{{ $trackingId }}">
                    <div class="d-flex justify-content-around" style="flex-direction: column; gap: 30px;">
                        <button type="button" class="btn btn-outline-primary" onclick="selectPaymentRoute('{{ route('payment') }}')">
                            <img src="img/paypal.png" alt="PayPal" style="width: 25px; height: 25px; margin-right: 5px;">PayPal
                        </button>
                        <button type="button" class="btn btn-outline-info" onclick="selectPaymentRoute('{{ route('stripe.payment') }}')">
                            <img src="img/stripe.png" alt="PayPal" style="width: 25px; height: 25px; margin-right: 5px;">Stripe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function selectPaymentRoute(route) {
        const form = document.getElementById('paymentForm');
        form.action = route;
        form.submit();
    }
</script>


<style>
    .card {
        transition: transform 0.2s;
    }

    /*.card:hover {
        transform: translateY(-5px);
    }*/

    .btn {
        border-radius: 50px;
        padding: 10px 20px;
    }
    
    .custom-shadow{
        border-radius: 5px 5px 50px 50px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
    }
    
    .custom-background{
        background-color: #4a008a !important;
    }
    
    .card-body.p-4.custom-padding{
        padding-top: 0 !important;
    }
    
    @media (max-width: 768px) {
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 0.5rem;
            align-items: start;
        }
    }
</style>
@endsection