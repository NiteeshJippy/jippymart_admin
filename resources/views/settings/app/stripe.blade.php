@extends('layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="card">
        <div class="payment-top-tab mt-3 mb-3">
            <ul class="nav nav-tabs card-header-tabs align-items-end">
                <li class="nav-item">
                    <a class="nav-link active stripe_active_label" href="{!! url('settings/payment/stripe') !!}"><i
                            class="fa fa-envelope-o mr-2"></i>{{trans('lang.app_setting_stripe')}}<span
                            class="badge ml-2"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link cod_active_label" href="{!! url('settings/payment/cod') !!}"><i
                            class="fa fa-envelope-o mr-2"></i>{{trans('lang.app_setting_cod_short')}}<span
                            class="badge ml-2"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link razorpay_active_label" href="{!! url('settings/payment/razorpay') !!}"><i
                            class="fa fa-envelope-o mr-2"></i>{{trans('lang.app_setting_razorpay')}}<span
                            class="badge ml-2"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link paypal_active_label" href="{!! url('settings/payment/paypal') !!}"><i
                            class="fa fa-envelope-o mr-2"></i>{{trans('lang.app_setting_paypal')}}<span
                            class="badge ml-2"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link paytm_active_label" href="{!! url('settings/payment/paytm') !!}"><i
                            class="fa fa-envelope-o mr-2"></i>{{trans('lang.app_setting_paytm')}}<span
                            class="badge ml-2"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link wallet_active_label" href="{!! url('settings/payment/wallet') !!}"><i
                            class="fa fa-envelope-o mr-2"></i>{{trans('lang.app_setting_wallet')}}<span
                            class="badge ml-2"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link payfast_active_label" href="{!! url('settings/payment/payfast') !!}"><i
                            class="fa fa-envelope-o mr-2"></i>{{trans('lang.payfast')}}<span class="badge ml-2"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link paystack_active_label" href="{!! url('settings/payment/paystack') !!}"><i
                            class="fa fa-envelope-o mr-2"></i>{{trans('lang.app_setting_paystack_lable')}}<span
                            class="badge ml-2"></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link flutterWave_active_label" href="{!! url('settings/payment/flutterwave') !!}"><i
                            class="fa fa-envelope-o mr-2"></i>{{trans('lang.flutterWave')}}<span
                            class="badge ml-2"></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mercadopago_active_label" href="{!! url('settings/payment/mercadopago') !!}"><i
                            class="fa fa-envelope-o mr-2"></i>{{trans('lang.mercadopago')}}<span
                            class="badge ml-2"></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link xendit_active_label"
                       href="{!! url('settings/payment/xendit') !!}"><i
                            class="fa fa-envelope-o mr-2"></i>{{trans('lang.app_setting_xendit')}}<span
                            class="badge ml-2"></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link orangepay_active_label"
                       href="{!! url('settings/payment/orangepay') !!}"><i
                            class="fa fa-envelope-o mr-2"></i>{{trans('lang.app_setting_orangepay')}}<span
                            class="badge ml-2"></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link midtrans_active_label"
                       href="{!! url('settings/payment/midtrans') !!}"><i
                            class="fa fa-envelope-o mr-2"></i>{{trans('lang.app_setting_midtrans')}}<span
                            class="badge ml-2"></span></a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="row restaurant_payout_create">
                <div class="restaurant_payout_create-inner">
                    <fieldset>
                        <legend><i class="mr-3 fa fa-cc-stripe"></i>{{trans('lang.app_setting_stripe')}}</legend>
                        <div class="form-check width-100">
                            <input type="checkbox" class="enable_stripe" id="enable_stripe">
                            <label class="col-3 control-label"
                                for="enable_stripe">{{trans('lang.app_setting_enable_stripe')}}</label>
                            <div class="form-text text-muted">
                                {!! trans('lang.app_setting_enable_stripe_help') !!}
                            </div>
                        </div>
                        <div class="form-group row width-100">
                            <label class="col-3 control-label">{{trans('lang.app_setting_stripe_key')}}</label>
                            <div class="col-7">
                                <input type="password" class="form-control stripe_key">
                                <div class="form-text text-muted">
                                    {!! trans('lang.app_setting_stripe_key_help') !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row width-100">
                            <label class="col-3 control-label">{{trans('lang.app_setting_stripe_secret')}}</label>
                            <div class="col-7">
                                <input type="password" class=" col-7 form-control stripe_secret">
                                <div class="form-text text-muted">
                                    {!! trans('lang.app_setting_stripe_secret_help') !!}
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend><i class="mr-3 fa fa-cc-stripe"></i>{{trans('lang.withdraw_setting')}}</legend>
                        <div class="form-check width-100">
                            <input type="checkbox" class="withdraw_enable" id="withdraw_enable">
                            <label class="col-3 control-label"
                                for="withdraw_enable">{{trans('lang.app_setting_enable_stripe')}}</label>
                            <div class="form-text text-muted">
                                {!! trans('lang.withdraw_setting_enable_stripe_help') !!}
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="form-group col-12 text-center btm-btn">
            <button type="button" class="btn btn-primary edit-setting-btn"><i class="fa fa-save"></i>
                {{trans('lang.save')}}</button>
            <a href="{{url('/dashboard')}}" class="btn btn-default"><i
                    class="fa fa-undo"></i>{{trans('lang.cancel')}}</a>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    var database = firebase.firestore();
    var ref = database.collection('settings').doc('stripeSettings');
    var codData = database.collection('settings').doc('CODSettings');
    var razorpayData = database.collection('settings').doc('razorpaySettings');
    var paypalData = database.collection('settings').doc('paypalSettings');
    var paytmData = database.collection('settings').doc('PaytmSettings');
    var walletData = database.collection('settings').doc('walletSettings');
    var payFastSettings = database.collection('settings').doc('payFastSettings');
    var payStackSettings = database.collection('settings').doc('payStack');
    var flutterWaveSettings = database.collection('settings').doc('flutterWave');
    var MercadopagoSettings = database.collection('settings').doc('MercadoPago');
    var xenditSettings = database.collection('settings').doc('xendit_settings');
    var orangePaySettings = database.collection('settings').doc('orange_money_settings');
    var midtransSettings = database.collection('settings').doc('midtrans_settings');
    $(document).ready(function () {
        jQuery("#data-table_processing").show();
        ref.get().then(async function (snapshots) {
            var stripe = snapshots.data();
            if (stripe == undefined) {
                database.collection('settings').doc('stripeSettings').set({}).then(function (result) {
                    location.reload();
                });
            }
            try {
                if (stripe.isEnabled) {
                    $(".enable_stripe").prop('checked', true);
                    jQuery(".stripe_active_label span").addClass('badge-success');
                    jQuery(".stripe_active_label span").text('Active');
                }
                if (stripe.isWithdrawEnabled) {
                    $(".withdraw_enable").prop('checked', true);
                }
                $(".stripe_key").val(stripe.stripeKey);
                $(".stripe_secret").val(stripe.stripeSecret);
                codData.get().then(async function (codSnapshots) {
                    var cod = codSnapshots.data();
                    if (cod.isEnabled) {
                        jQuery(".cod_active_label span").addClass('badge-success');
                        jQuery(".cod_active_label span").text('Active');
                    }
                })
                razorpayData.get().then(async function (razorpaySnapshots) {
                    var razorPay = razorpaySnapshots.data();
                    if (razorPay.isEnabled) {
                        jQuery(".razorpay_active_label span").addClass('badge-success');
                        jQuery(".razorpay_active_label span").text('Active');
                    }
                })
                paypalData.get().then(async function (paypalSnapshots) {
                    var paypal = paypalSnapshots.data();
                    if (paypal.isEnabled) {
                        jQuery(".paypal_active_label span").addClass('badge-success');
                        jQuery(".paypal_active_label span").text('Active');
                    }
                })
                paytmData.get().then(async function (codSnapshots) {
                    var paytm = codSnapshots.data();
                    if (paytm.isEnabled) {
                        jQuery(".paytm_active_label span").addClass('badge-success');
                        jQuery(".paytm_active_label span").text('Active');
                    }
                })
                walletData.get().then(async function (walletSnapshots) {
                    var wallet = walletSnapshots.data();
                    if (wallet.isEnabled) {
                        jQuery(".wallet_active_label span").addClass('badge-success');
                        jQuery(".wallet_active_label span").text('Active');
                    }
                })
                payFastSettings.get().then(async function (payFastSnapshots) {
                    var payFast = payFastSnapshots.data();
                    if (payFast.isEnable) {
                        jQuery(".payfast_active_label span").addClass('badge-success');
                        jQuery(".payfast_active_label span").text('Active');
                    }
                })
                payStackSettings.get().then(async function (payStackSnapshots) {
                    var payStack = payStackSnapshots.data();
                    if (payStack.isEnable) {
                        jQuery(".paystack_active_label span").addClass('badge-success');
                        jQuery(".paystack_active_label span").text('Active');
                    }
                })
                flutterWaveSettings.get().then(async function (flutterWaveSnapshots) {
                    var flutterWave = flutterWaveSnapshots.data();
                    if (flutterWave.isEnable) {
                        jQuery(".flutterWave_active_label span").addClass('badge-success');
                        jQuery(".flutterWave_active_label span").text('Active');
                    }
                })
                MercadopagoSettings.get().then(async function (mercadopagoSnapshots) {
                    var mercadopago = mercadopagoSnapshots.data();
                    if (mercadopago.isEnabled) {
                        jQuery(".mercadopago_active_label span").addClass('badge-success');
                        jQuery(".mercadopago_active_label span").text('Active');
                    }
                })
                xenditSettings.get().then(async function (xenditSnapshots) {
                    var xendit = xenditSnapshots.data();
                    if (xendit.enable) {
                        jQuery(".xendit_active_label span").addClass('badge-success');
                        jQuery(".xendit_active_label span").text('Active');
                    }
                })
                orangePaySettings.get().then(async function (orangePaySnapshots) {
                    var orangePay = orangePaySnapshots.data();
                    if (orangePay.enable) {
                        jQuery(".orangepay_active_label span").addClass('badge-success');
                        jQuery(".orangepay_active_label span").text('Active');
                    }
                })
                midtransSettings.get().then(async function (midtransSnapshots) {
                    var midtrans = midtransSnapshots.data();
                    if (midtrans.enable) {
                        jQuery(".midtrans_active_label span").addClass('badge-success');
                        jQuery(".midtrans_active_label span").text('Active');
                    }
                })
            } catch (error) {
            }
            jQuery("#data-table_processing").hide();
        })
        $(".edit-setting-btn").click(function () {
            var stripeKey = $(".stripe_key").val();
            var stripeSecret = $(".stripe_secret").val();
            var isStripeEnabled = $(".enable_stripe").is(":checked");
            var isWithdrawEnabled = $(".withdraw_enable").is(":checked");
            database.collection('settings').doc("stripeSettings").update({
                'isEnabled': isStripeEnabled,
                'stripeKey': stripeKey,
                'stripeSecret': stripeSecret,
                'isWithdrawEnabled': isWithdrawEnabled
            }).then(function (result) {
                window.location.href = '{{ url("settings/payment/stripe")}}';
            });
        })
    })
</script>
@endsection
