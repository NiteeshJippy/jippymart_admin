@extends('layouts.app')
@section('content')
    <div class="page-wrapper">
        <div class="card">
            <div class="payment-top-tab mt-3 mb-3">
                <ul class="nav nav-tabs card-header-tabs align-items-end">
                    <li class="nav-item">
                        <a class="nav-link stripe_active_label" href="{!! url('settings/payment/stripe') !!}"><i
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
                        <a class="nav-link razorpay_active_label"
                           href="{!! url('settings/payment/razorpay') !!}"><i
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
                                class="fa fa-envelope-o mr-2"></i>{{trans('lang.payfast')}}<span
                                class="badge ml-2"></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link paystack_active_label" href="{!! url('settings/payment/paystack') !!}"><i
                                class="fa fa-envelope-o mr-2"></i>{{trans('lang.app_setting_paystack_lable')}}<span
                                class="badge ml-2"></span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link flutterWave_active_label"
                           href="{!! url('settings/payment/flutterwave') !!}"><i
                                class="fa fa-envelope-o mr-2"></i>{{trans('lang.flutterWave')}}<span
                                class="badge ml-2"></span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mercadopago_active_label"
                           href="{!! url('settings/payment/mercadopago') !!}"><i
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
                        <a class="nav-link active orangepay_active_label"
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
                            <legend>{{trans('lang.app_setting_orangepay')}}</legend>
                            <div class="form-check width-100">
                                <input type="checkbox" class=" enable_orangepay" id="enable_orangepay">
                                <label class="col-3 control-label"
                                       for="enable_orangepay">{{trans('lang.app_setting_enable_orangepay')}}</label>
                            </div>
                            <div class="form-check width-100">
                                <input type="checkbox" class="sand_box_mode" id="sand_box_mode">
                                <label class="col-3 control-label"
                                       for="sand_box_mode">{{trans('lang.app_setting_enable_sandbox_mode')}}</label>
                            </div>
                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.app_setting_orangepay_merchantKey')}}</label>
                                <div class="col-7">
                                    <input type="password" class="form-control orangepay_merchantKey">
                                    <div class="form-text text-muted">
                                        {!! trans('lang.app_setting_orangepay_merchantKey_help') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.app_setting_orangepay_auth')}}</label>
                                <div class="col-7">
                                    <input type="password" class="form-control orangepay_auth">
                                    <div class="form-text text-muted">
                                        {!! trans('lang.app_setting_orangepay_auth_help') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.app_setting_orangepay_clientid')}}</label>
                                <div class="col-7">
                                    <input type="password" class="form-control orangepay_clientId">
                                    <div class="form-text text-muted">
                                        {!! trans('lang.app_setting_orangepay_clientid_help') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.app_setting_orangepay_secret')}}</label>
                                <div class="col-7">
                                    <input type="password" class=" form-control orangepay_secret">
                                    <div class="form-text text-muted">
                                        {!! trans('lang.app_setting_orangepay_secret_help') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.app_setting_orangepay_cancelurl')}}</label>
                                <div class="col-7">
                                    <input type="text" class=" form-control orangepay_cancelUrl">
                                    <div class="form-text text-muted">
                                        {!! trans('lang.app_setting_orangepay_cancelurl_help') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.app_setting_orangepay_notifyurl')}}</label>
                                <div class="col-7">
                                    <input type="text" class=" form-control orangepay_notifyUrl">
                                    <div class="form-text text-muted">
                                        {!! trans('lang.app_setting_orangepay_notifyurl_help') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.app_setting_orangepay_returnurl')}}</label>
                                <div class="col-7">
                                    <input type="text" class=" form-control orangepay_returnUrl">
                                    <div class="form-text text-muted">
                                        {!! trans('lang.app_setting_orangepay_returnurl_help') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.image')}}</label>
                                <div class="col-7">
                                    <input type="file" class=" col-7 form-control razorpay-image"
                                           onChange="handleFileSelect(event)">
                                    <div class="form-text text-muted">
                                        {!! trans('lang.payment_method_image_help') !!}
                                    </div>
                                    <div class="placeholder_img_thumb payment_image"></div>
                                    <div id="uploding_image"></div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="form-group col-12 text-center btm-btn">
                <button type="button" class="btn btn-primary edit-setting-btn"><i
                        class="fa fa-save"></i> {{trans('lang.save')}}</button>
                <a href="{{url('/dashboard')}}" class="btn btn-default"><i
                        class="fa fa-undo"></i>{{trans('lang.cancel')}}</a>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var database = firebase.firestore();
        var paymentRef = database.collection('settings').doc('orange_money_settings');
        var mercadoPagoData = database.collection('settings').doc('MercadoPago');
        var stripeData = database.collection('settings').doc('stripeSettings');
        var codData = database.collection('settings').doc('CODSettings');
        var razorpayData = database.collection('settings').doc('razorpaySettings');
        var paypalData = database.collection('settings').doc('paypalSettings');
        var paytmData = database.collection('settings').doc('PaytmSettings');
        var walletData = database.collection('settings').doc('walletSettings');
        var payFastSettings = database.collection('settings').doc('payFastSettings');
        var payStackSettings = database.collection('settings').doc('payStack');
        var flutterWaveSettings = database.collection('settings').doc('flutterWave');
        var xenditSettings = database.collection('settings').doc('xendit_settings');
        var midtransSettings = database.collection('settings').doc('midtrans_settings');
        var photo = "";
        var fileName = "";
        var ImageFile = "";
        var storageRef = firebase.storage().ref('images');
        var storage = firebase.storage();
        $(document).ready(function () {
            $('.setting_menu').addClass('active').attr('aria-expanded', true);
            $('.setting_payment_menu').addClass('active');
            $('.setting_sub_menu').addClass('in').attr('aria-expanded', true);
            jQuery("#overlay").show();
            paymentRef.get().then(async function (paymentSnapshots) {
                var payment = paymentSnapshots.data();
                if (payment.enable) {
                    $("#enable_orangepay").prop('checked', true);
                    jQuery(".razorpay_active_label span").addClass('badge-success');
                    jQuery(".razorpay_active_label span").text('Active');
                }
                if (payment.isSandbox) {
                    $("#sand_box_mode").prop('checked', true);
                }
                $('.orangepay_clientId').val(payment.clientId);
                $('.orangepay_secret').val(payment.clientSecret);
                $('.orangepay_merchantKey').val(payment.merchantKey);
                $('.orangepay_auth').val(payment.auth);
                $('.orangepay_cancelUrl').val(payment.cancelUrl);
                $('.orangepay_notifyUrl').val(payment.notifyUrl);
                $('.orangepay_returnUrl').val(payment.returnUrl);
                if (payment.hasOwnProperty('image')) {
                    if (payment.image != '' && payment.image != null) {
                        $(".payment_image").append('<span class="image-item"><span class="remove-btn"><i class="fa fa-remove"></i></span><img class="rounded" style="width:50px" src="' + payment.image + '" alt="image"></span>');
                        photo = payment.image;
                        ImageFile = payment.image;
                    } else {
                        photo = "";
                    }
                }
                jQuery("#overlay").hide();
            })
            codData.get().then(async function (codSnapshots) {
                var cod = codSnapshots.data();
                if (cod.isEnabled) {
                    jQuery(".cod_active_label span").addClass('badge-success');
                    jQuery(".cod_active_label span").text('Active');
                }
            })
            razorpayData.get().then(async function (razorpaySnapshots) {
                var razorpay = razorpaySnapshots.data();
                if (razorpay.isEnabled) {
                    jQuery(".razorpay_active_label span").addClass('badge-success');
                    jQuery(".razorpay_active_label span").text('Active');
                }
            })
            flutterWaveSettings.get().then(async function (flutterWaveSnapshots) {
                var flutterWave = flutterWaveSnapshots.data();
                if (flutterWave.isEnable) {
                    jQuery(".flutterWave_active_label span").addClass('badge-success');
                    jQuery(".flutterWave_active_label span").text('Active');
                }
            })
            mercadoPagoData.get().then(async function (mercadoPagoSnapshots) {
                var mercadoPago = mercadoPagoSnapshots.data();
                if (mercadoPago.isEnabled) {
                    jQuery(".mercadopago_active_label span").addClass('badge-success');
                    jQuery(".mercadopago_active_label span").text('Active');
                }
            })
            payStackSettings.get().then(async function (payStackSnapshots) {
                var payStack = payStackSnapshots.data();
                if (payStack.isEnable) {
                    jQuery(".paystack_active_label span").addClass('badge-success');
                    jQuery(".paystack_active_label span").text('Active');
                }
            })  
            payFastSettings.get().then(async function (payfastSnapshots) {
                var payfast = payfastSnapshots.data();
                if (payfast.isEnable) {
                    jQuery(".payfast_active_label span").addClass('badge-success');
                    jQuery(".payfast_active_label span").text('Active');
                }
            })
            paypalData.get().then(async function (paypalSnapshots) {
                var paypal = paypalSnapshots.data();
                if (paypal.isEnabled) {
                    jQuery(".paypal_active_label span").addClass('badge-success');
                    jQuery(".paypal_active_label span").text('Active');
                }
            })
            paytmData.get().then(async function (paytmSnapshots) {
                var paytm = paytmSnapshots.data();
                if (paytm.isEnabled) {
                    jQuery(".paytm_active_label span").addClass('badge-success');
                    jQuery(".paytm_active_label span").text('Active');
                }
            })
            stripeData.get().then(async function (stripSnapshots) {
                var strip = stripSnapshots.data();
                if (strip.isEnabled) {
                    jQuery(".stripe_active_label span").addClass('badge-success');
                    jQuery(".stripe_active_label span").text('Active');
                }
            })
            walletData.get().then(async function (walletSnapshots) {
                var wallet = walletSnapshots.data();
                if (wallet.isEnabled) {
                    jQuery(".wallet_active_label span").addClass('badge-success');
                    jQuery(".wallet_active_label span").text('Active');
                }
            })
            xenditSettings.get().then(async function (xenditSnapshots) {
                var xendit = xenditSnapshots.data();
                if (xendit.enable) {
                    jQuery(".xendit_active_label span").addClass('badge-success');
                    jQuery(".xendit_active_label span").text('Active');
                }
            })
            midtransSettings.get().then(async function (midtransSnapshots) {
                var midtrans = midtransSnapshots.data();
                if (midtrans.enable) {
                    jQuery(".midtrans_active_label span").addClass('badge-success');
                    jQuery(".midtrans_active_label span").text('Active');
                }
            })
        });
        $(".edit-setting-btn").click(function () {
            var returnUrl = $(".orangepay_returnUrl").val();
            var notifyUrl = $(".orangepay_notifyUrl").val();
            var cancelUrl = $(".orangepay_cancelUrl").val();
            var auth = $(".orangepay_auth").val();
            var merchantKey = $(".orangepay_merchantKey").val();
            var clientId = $(".orangepay_clientId").val();
            var clientSecret = $(".orangepay_secret").val();
            var isEnabled = $("#enable_orangepay").is(":checked");
            var isSandBox = $("#sand_box_mode").is(":checked");
            storeImageData().then(IMG => {
                database.collection('settings').doc("orange_money_settings").update({
                    'enable': isEnabled,
                    'isSandbox': isSandBox,
                    'merchantKey': merchantKey,
                    'auth': auth,
                    'clientId': clientId,
                    'clientSecret': clientSecret,
                    'cancelUrl': cancelUrl,
                    'notifyUrl': notifyUrl,
                    'returnUrl': returnUrl,
                    'image': IMG
                }).then(function (result) {
                    window.location.href = '{{ url("settings/payment/orangepay")}}';
                });
            }).catch(err => {
                jQuery("#overlay").hide();
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>" + err + "</p>");
                window.scrollTo(0, 0);
            });
        });
        async function storeImageData() {
            var newPhoto = '';
            try {
                if (ImageFile != "" && photo != ImageFile) {
                    var OldImageUrlRef = await storage.refFromURL(ImageFile);
                    imageBucket= OldImageUrlRef.bucket;
                    var envBucket = "<?php echo env('FIREBASE_STORAGE_BUCKET'); ?>";
                    if (imageBucket == envBucket) {
                        await OldImageUrlRef.delete().then(() => {
                            console.log("Old file deleted!")
                        }).catch((error) => {
                            console.log("ERR File delete ===", error);
                        });
                    }else{
                        console.log('Bucket not matched');
                    }
                }
                if (photo != ImageFile) {
                    photo = photo.replace(/^data:image\/[a-z]+;base64,/, "")
                    var uploadTask = await storageRef.child(fileName).putString(photo, 'base64', {contentType: 'image/jpg'});
                    var downloadURL = await uploadTask.ref.getDownloadURL();
                    newPhoto = downloadURL;
                    photo = downloadURL;
                } else {
                    newPhoto = photo;
                }
            } catch (error) {
                console.log("ERR ===", error);
            }
            return newPhoto;
        }
        function handleFileSelect(evt) {
            var f = evt.target.files[0];
            var reader = new FileReader();
            reader.onload = (function (theFile) {
                return function (e) {
                    var filePayload = e.target.result;
                    var val = f.name;
                    var ext = val.split('.')[1];
                    var docName = val.split('fakepath')[1];
                    var filename = (f.name).replace(/C:\\fakepath\\/i, '')
                    var timestamp = Number(new Date());
                    var filename = filename.split('.')[0] + "_" + timestamp + '.' + ext;
                    photo = filePayload;
                    fileName = filename;
                    $(".payment_image").empty();
                    $(".payment_image").append('<span class="image-item"><span class="remove-btn"><i class="fa fa-remove"></i></span><img class="rounded" style="width:50px" src="' + filePayload + '" alt="image"></span>');
                };
            })(f);
            reader.readAsDataURL(f);
        }
    </script>
@endsection
