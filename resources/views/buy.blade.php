@extends('layouts.app')

@push('styles')
<style type="text/css">
    
    #card-element input[name="cvc"] {
        -webkit-text-security: disc;
        text-security: disc;
    }

    .StripeElement {
        box-sizing: border-box;
        height: 40px;
        padding: 10px 12px;
        border: 1px solid transparent;
        border-radius: 4px;
        background-color: white;
        box-shadow: 0 1px 3px 0 #e6ebf1;
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
    }
    .StripeElement--focus {
        box-shadow: 0 1px 3px 0 #cfd7df;
    }
    .StripeElement--invalid {
        border-color: #fa755a;
    }
    .StripeElement--webkit-autofill {
        background-color: #fefde5 !important;
    }
    .error {
        color: #dc3545;
    }

</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div>
                <p class="text-center">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    @elseif(session('danger'))
                        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                            {{ session('danger') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    @endif
                </p>
            </div>
            <div>
                <a href="{{ route('home') }}">Back</a>
            </div>
            <div class="card mt-1">
                <div class="card-header text-center">
                    <h4>{{ __('Payment') }}</h4>
                </div>

                <div class="card-body p-5">
                    <form action="{{ route('payment') }}" method="post" id="payment_form">
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <table class="table table-condensed table-bordered table-responsive">
                                    <tr>
                                        <td width="75%">
                                            <span><strong>{{ $product->name }}</strong></span>
                                            <br />
                                            <span>{{ $product->description }}</span>
                                        </td>
                                        <td width="25%">
                                            <h6>{{ env('CURRENCY_SYMBOL') .' '. number_format($product->price, 2) }}</h6>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="75%">
                                            <span>Total Amount to Pay</span>
                                        </td>
                                        <td width="25%">
                                            <strong>{{ env('CURRENCY_SYMBOL') .' '. number_format($product->price, 2) }}</strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="row">
                                    <div class="col-6 form-group mt-2">
                                        <input type="text" name="card_holder_name" id="card_holder_name" class="form-control" value="" placeholder="Card Holder Name" autocomplete="off">
                                    </div>
                                    <div class="col-6 form-group mt-2">
                                        <input type="text" name="phone_number" id="phone_number" class="form-control" value="" placeholder="Phone Number" autocomplete="off">
                                    </div>

                                    <div class="col-12 form-group mt-2">
                                        <input type="text" name="address" id="address" class="form-control" value="" placeholder="Address Line 1" autocomplete="off">
                                    </div>

                                    <div class="col-6 form-group mt-2">
                                        <input type="text" name="city" id="city" class="form-control" value="" placeholder="City" autocomplete="off">
                                    </div>
                                    <div class="col-6 form-group mt-2">
                                        <input type="text" name="state" id="state" class="form-control" value="" placeholder="State" autocomplete="off">
                                    </div>

                                    <div class="col-6 form-group mt-2">
                                        <input type="text" name="postal_code" id="postal_code" class="form-control" value="" placeholder="Postal Code" autocomplete="off">
                                    </div>
                                    <div class="col-6 form-group mt-2">
                                        <select name="country" id="country" class="form-control">
                                            <option value="">Country</option>
                                            <option value="IN">India</option>
                                            <option value="US">USA</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <div id="card-element"></div>
                            </div>

                            <div class="col-12 mt-3">
                                <p class="text-center">
                                    <span id="error-message" class="text-danger" style="display: none;"></span>
                                </p>
                            </div>

                            <div class="col-12 mt-3 text-center">
                                <button type="submit" id="pay" class="btn btn-sm btn-info">Pay Now (<strong>{{ env('CURRENCY_SYMBOL') .' '. number_format($product->price, 2) }}</strong>)</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ url()->asset('assets/js/jquery.validate.min.js') }}" type="text/javascript"></script>
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
    
    var style = {
        base: {
            color: '#32325d',
            lineHeight: '18px',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };
    
    var stripe = Stripe('{{ env("STRIPE_KEY") }}');

    const elements = stripe.elements(); // Create an instance of Elements.
    const card = elements.create('card', { cardStyle: style, hidePostalCode: true });

    card.mount('#card-element');

    var form = document.getElementById('payment_form');

    $(form).validate({
        rules: {
            card_holder_name    : { required: true, minlength: 2, maxlength: 50 },
            phone_number        : { required: true, minlength: 8, maxlength: 16 },
            address             : { required: true, minlength: 3, maxlength: 200 },
            city                : { required: true, minlength: 2, maxlength: 50 },
            state               : { required: true, minlength: 2, maxlength: 50 },
            postal_code         : { required: true, minlength: 4, maxlength: 10, digits: true },
            country             : { required: true }
        }
    });
    
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createPaymentMethod('card', card, {
            billing_details: {
                name: document.getElementById('card_holder_name').value,
                email: "{{ auth()->user()->email }}",
            },
        }).then(function(result) {
            
            if (result.error) {
                // Display any errors to the user
                $('#error-message').text(result.error.message).show();
                return;
            }


            var payment_method = document.createElement('input');
            payment_method.setAttribute('type', 'hidden');
            payment_method.setAttribute('name', 'payment_method');
            payment_method.setAttribute('value', result.paymentMethod.id);
            form.appendChild(payment_method);

            var product_id = document.createElement('input');
            product_id.setAttribute('type', 'hidden');
            product_id.setAttribute('name', 'product_id');
            product_id.setAttribute('value', "{{ $product->id }}");
            form.appendChild(product_id);

            form.submit();
        });
        
    });

    $(document).ready(function() {

        $(document).on('click', 'button.close', function() {
            $(this).closest('.alert').remove();
        });

    });

</script>
@endpush
