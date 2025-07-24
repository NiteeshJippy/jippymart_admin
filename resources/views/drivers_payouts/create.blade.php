@extends('layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.drivers_payout_plural')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{url('/driversPayouts')}}">{{trans('lang.drivers_payout_plural')}}</a>
                </li>
                <li class="breadcrumb-item">{{trans('lang.drivers_payout_create')}}</li>
            </ol>
        </div>
    </div>
    <div class="card-body">
        <div class="error_top"></div>
        <div class="row restaurant_payout_create">
            <div class="restaurant_payout_create-inner">
                <fieldset>
                    <legend>{{trans('lang.drivers_payout_create')}}</legend>
                    <div class="form-group row width-50">
                        <label class="col-4 control-label">{{ trans('lang.drivers_payout_driver_name')}}</label>
                        <div class="col-7">
                            <select id="select_restaurant" class="form-control">
                                <option value="">{{ trans('lang.select_driver') }}</option>
                            </select>
                            <div class="form-text text-muted">
                                {{ trans("lang.drivers_payout_driver_name_help") }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row width-50">
                        <label class="col-4 control-label">{{trans('lang.drivers_payout_amount')}}</label>
                        <div class="col-7">
                            <input type="number" class="form-control payout_amount">
                            <div class="form-text text-muted">
                                {{ trans("lang.drivers_payout_amount_placeholder") }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row width-100">
                        <label class="col-2 control-label">{{ trans('lang.restaurants_payout_note')}}</label>
                        <div class="col-12">
                            <textarea type="text" rows="7" class="form-control form-control payout_note"></textarea>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="form-group col-12 text-center btm-btn">
        <button type="button" class="btn btn-primary save-form-btn"><i class="fa fa-save"></i>
            {{trans('lang.save')}}
        </button>
        <?php if ($id != '') { ?>
            <a href="{!! route('driver.payout',$id) !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{trans('lang.cancel')}}</a>
        <?php } else { ?>
            <a href="{!! route('driversPayouts') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{trans('lang.cancel')}}</a>
        <?php } ?>
    </div>
</div>
</div>
@endsection
@section('scripts')
<script>
    var driver_id = "<?php echo $id; ?>";
    if (driver_id) {
        $('#select_restaurant').prop('disabled', true);
    }
    var database = firebase.firestore();
    var drivers = [];
    var email_templates = database.collection('email_templates').where('type', '==', 'payout_request');
    var emailTemplatesData = null;
    var adminEmail = '';
    var emailSetting = database.collection('settings').doc('emailSetting');
    var currentCurrency = '';
    var currencyAtRight = false;
    var decimal_degits = 0;
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    refCurrency.get().then(async function(snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });
    async function remainingPrice(driverID) {
        var remaining = 0;
        drivers.forEach((driver) => {
            if (driver.id == driverID) {
                if (isNaN(driver.wallet_amount) || driver.wallet_amount == undefined) {
                    remaining = 0;
                } else {
                    remaining = driver.wallet_amount;
                }
            }
        });
        return remaining;
    }
    async function remainingPriceOLD(driverID) {
        var paid_price = 0;
        var total_price = 0;
        var remaining = 0;
        await database.collection('driver_payouts').where('driverID', '==', driverID).get().then(async function(payoutSnapshots) {
            payoutSnapshots.docs.forEach((payout) => {
                var payoutData = payout.data();
                paid_price = parseFloat(paid_price) + parseFloat(payoutData.amount);
            })
            await database.collection('restaurant_orders').where('driverID', '==', driverID).where("status", "in", ["Order Completed"]).get().then(async function(orderSnapshots) {
                orderSnapshots.docs.forEach((order) => {
                    var orderData = order.data();
                    if (orderData.deliveryCharge != undefined && orderData.tip_amount != undefined) {
                        var orderDataTotal = parseFloat(orderData.deliveryCharge) + parseFloat(orderData.tip_amount);
                        total_price = parseFloat(total_price) + parseFloat(orderDataTotal);
                    } else if (orderData.deliveryCharge != undefined) {
                        var orderDataTotal = parseFloat(orderData.deliveryCharge);
                        total_price = parseFloat(total_price) + parseFloat(orderDataTotal);
                    } else if (orderData.tip_amount != undefined) {
                        var orderDataTotal = parseFloat(orderData.tip_amount);
                        total_price = parseFloat(total_price) + parseFloat(orderDataTotal);
                    }
                })
                remaining = parseFloat(total_price) - parseFloat(paid_price);
            });
        });
        return remaining;
    }
    $(document).ready(function() {
        $("#data-table_processing").show();
        email_templates.get().then(async function(snapshots) {
            emailTemplatesData = snapshots.docs[0].data();
        });
        emailSetting.get().then(async function(snapshots) {
            var emailSettingData = snapshots.data();
            adminEmail = emailSettingData.userName;
        });
        database.collection('users').where('role', '==', 'driver').orderBy('firstName').get().then(async function(snapshots) {
            snapshots.docs.forEach((listval) => {
                var data = listval.data();
                drivers.push(data);
                var driver_value = data.firstName && data.lastName ? data.firstName + ' ' + data.lastName : data.phoneNumber;
                if (driver_id == data.id) {
                    $('#select_restaurant').append($("<option></option>").attr("selected", 'selected').attr("value", data.id).text(driver_value));
                } else {
                    $('#select_restaurant').append($("<option></option>").attr("value", data.id).text(driver_value));
                }
            })
        });
        $("#data-table_processing").hide();
    });
    var userName = '';
    var userContact = '';
    var userEmail = '';
    var payoutId = "<?php echo uniqid(); ?>";
    $(".save-form-btn").click(async function() {
            var driverID = $("#select_restaurant").val();
            var amount = parseFloat($(".payout_amount").val());
            var note = $(".payout_note").val();
            if (driverID == '') {
                $(".error_top").show();
                $(window).scrollTop(0);
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.driver_name_error')}}</p>");
            } else if (isNaN(amount) || amount <= 0) {
                $(".error_top").show();
                $(window).scrollTop(0);
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.driver_amount_error')}}</p>");
                window.scrollTo(0, 0);
            } else if(note == ''){
                $(".error_top").show();
                $(window).scrollTop(0);
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.restaurant_note_error')}}</p>");
            }else{
                var remaining = await remainingPrice(driverID);
                if(remaining < amount){
                    $(".error_top").show().html("<p>Insufficient driver wallet balance</p>");
                    window.scrollTo(0, 0);
                    return;
                }
                var driverData = await getDriver(driverID);
                if (remaining > 0) {
                    var date = new Date();
                    await database.collection('driver_payouts').doc(payoutId).set({
                        'driverID': driverID,
                        'amount': amount,
                        'adminNote': note,
                        'id': payoutId,
                        'paidDate': date,
                        'paymentStatus': 'Success'
                    });
                    var price = remaining - amount;
                    await database.collection('users').doc(driverID).update({
                        'wallet_amount': price
                    });
                    if (currencyAtRight) {
                        amount = parseInt(amount).toFixed(decimal_degits) + "" + currentCurrency;
                    } else {
                        amount = currentCurrency + "" + parseInt(amount).toFixed(decimal_degits);
                    }
                    var formattedDate = new Date();
                    var month = formattedDate.getMonth() + 1;
                    var day = formattedDate.getDate();
                    var year = formattedDate.getFullYear();
                    month = month < 10 ? '0' + month : month;
                    day = day < 10 ? '0' + day : day;
                    formattedDate = day + '-' + month + '-' + year;
                    var subject = emailTemplatesData.subject.replace(/{userid}/g, driverID);
                    var message = emailTemplatesData.message
                        .replace(/{userid}/g, driverID)
                        .replace(/{date}/g, formattedDate)
                        .replace(/{amount}/g, amount)
                        .replace(/{payoutrequestid}/g, payoutId)
                        .replace(/{username}/g, userName)
                        .replace(/{usercontactinfo}/g, userContact);
                    var url = "{{url('send-email')}}";
                    var sendEmailStatus = await sendEmail(url, subject, message, [adminEmail, userEmail]);
                    if (sendEmailStatus) {
                        window.location.href = "{{ route('driversPayouts') }}";
                    }
                } else {
                    $(".error_top").show();
                    $(window).scrollTop(0);
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{trans('lang.driver_insufficient_payment_error')}}</p>");
                }
            }
        });
    async function getDriver(driverId) {
        var vendorId = '';
        await database.collection('users').where('id', "==", driverId).get().then(async function(vendorSnapshots) {
            var driverData = vendorSnapshots.docs[0].data();
            userName = driverData.firstName + ' ' + driverData.lastName;
            userContact = driverData.phoneNumber;
            userEmail = driverData.email;
        });
        return vendorId;
    }
</script>
@endsection
