@extends('layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="container-fluid">
      <div class="admin-top-section pt-4"> 
        <div class="row">
            <div class="col-12">
                <div class="d-flex top-title-section pb-4 justify-content-between">
                    <div class="d-flex top-title-left align-self-center">
                        <span class="icon mr-3"><img src="{{ asset('images/building-four.png') }}"></span>
                       <div class="top-title-breadcrumb"> 
                        <h3 class="mb-0 restaurantTitle">{{trans('lang.user_plural')}}</h3>
                          <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                            <li class="breadcrumb-item"><a href="{!! route('users') !!}">{{trans('lang.user_plural')}}</a></li>
                            <li class="breadcrumb-item active">{{trans('lang.user_details')}}</li>
                        </ol>
                       </div>
                    </div>
                    <div class="d-flex top-title-right align-self-center">
                       <div class="card-header-right"> 
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#addWalletModal"class="btn-primary btn rounded-full add-wallate"><i class="mdi mdi-plus mr-2"></i>{{trans('lang.add_wallet_amount')}}</a>
                       </div>
                    </div>
                </div>
            </div>
        </div> 
      </div>
      <div class="resttab-sec mb-4">  
        <div class="menu-tab">
            <ul>
                <li class="active">
                    <a href="{{route('users.view',$id)}}">{{trans('lang.tab_basic')}}</a>
                </li>
                <li>
                    <a href="{{route('orders')}}?userId={{$id}}">{{trans('lang.tab_orders')}}</a>
                </li>
                <li>
                    <a href="{{route('users.walletstransaction',$id)}}">{{trans('lang.wallet_transaction')}}</a>
                </li>
            </ul>
        </div>  
        <div class="row">
            <div class="col-md-3">
                <div class="card card-box-with-icon bg--1">
                    <div class="card-body d-flex justify-content-between align-items-center">
                       <div class="card-box-with-content">
                        <h4 class="text-dark-2 mb-1 h4 total_orders" id="total_orders">00</h4>
                        <p class="mb-0 small text-dark-2">{{trans('lang.dashboard_total_orders')}}</p>
                       </div>
                        <span class="box-icon ab"><img src="{{ asset('images/active_restaurant.png') }}"></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-box-with-icon bg--3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                       <div class="card-box-with-content">
                        <h4 class="text-dark-2 mb-1 h4 wallet_balance" id="wallet_balance">$0.00</h4>
                        <p class="mb-0 small text-dark-2">{{trans('lang.wallet_Balance')}}</p>
                       </div>
                        <span class="box-icon ab"><img src="{{ asset('images/total_payment.png') }}"></span>
                    </div>
                </div>
            </div>
        </div>   
       </div>
       <div class="restaurant_info-section">
         <div class="card border">
           <div class="card-header d-flex justify-content-between align-items-center border-bottom pb-3">
            <div class="card-header-title">
                <h3 class="text-dark-2 mb-0 h4">{{trans('lang.user_details')}}</h3>
            </div>
           </div>
           <div class="card-body">
              <div class="row">
                 <div class="col-md-6">
                    <div class="restaurant_info_left">
                        <div class="d-flex mb-1">
                            <div class="sis-img profile_image" id="profile_image">
                            </div>
                            <div class="sis-content pl-4">
                                <ul class="p-0 info-list mb-0">
                                    <li class="d-flex align-items-center mb-2">
                                        <label class="mb-0 font-wi font-semibold text-dark-2">{{trans('lang.first_name')}}</label>
                                        <span class="user_name" id="user_name"></span>
                                    </li>
                                    <li class="d-flex align-items-center mb-2">
                                        <label class="mb-0 font-wi font-semibold text-dark-2">{{trans('lang.email')}}</label>
                                        <span class="email"></span>
                                    </li>
                                    <li class="d-flex align-items-center mb-2">
                                        <label class="mb-0 font-wi font-semibold text-dark-2">{{trans('lang.user_phone')}}</label>
                                        <span class="phone"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label class="mb-0 font-wi font-semibold text-dark-2">{{trans('lang.address')}}</label>
                                <p><span class="address"></span></p>
                            </div>
                        </div>
                    </div>
                 </div>
                 <div class="col-md-6">
                    <div class="map-box">
                        <div class="mapouter" style="display:none;"><div class="gmap_canvas"><iframe class="gmap_iframe" width="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=600&amp;height=225&amp;hl=en&amp;q=University of Oxford&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe><a href="https://sprunkiplay.com/">Sprunki Game</a></div><style>.mapouter{position:relative;text-align:right;width:100%;height:225px;}.gmap_canvas {overflow:hidden;background:none!important;width:100%;height:225px;}.gmap_iframe {height:225px!important;}</style></div>
                    </div>
                 </div>
              </div>
           </div>
        </div>
       </div>
    </div>
</div>
    <div class="modal fade" id="addWalletModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered location_modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title locationModalTitle">{{trans('lang.add_wallet_amount')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="">
                        <div class="form-row">
                            <div class="form-group row">
                                <div class="form-group row width-100">
                                    <label class="col-12 control-label">{{trans('lang.amount')}}</label>
                                    <div class="col-12">
                                        <input type="number" name="amount" class="form-control" id="amount">
                                        <div id="wallet_error" style="color:red"></div>
                                    </div>
                                </div>
                                <div class="form-group row width-100">
                                    <label class="col-12 control-label">{{trans('lang.note')}}</label>
                                    <div class="col-12">
                                        <input type="text" name="note" class="form-control" id="note">
                                    </div>
                                </div>
                                <div class="form-group row width-100">
                                    <div id="user_account_not_found_error" class="align-items-center" style="color:red">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary save-form-btn">{{trans('submit')}}</a>
                        </button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal"
                                aria-label="Close">{{trans('close')}}</a>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var id = "{{$id}}";
        var database = firebase.firestore();
        var ref = database.collection('users').where("id", "==", id);
        var photo = "";
        var placeholderImage = '';
        var placeholder = database.collection('settings').doc('placeHolderImage');
        placeholder.get().then(async function (snapshotsimage) {
            var placeholderImageData = snapshotsimage.data();
            placeholderImage = placeholderImageData.image;
        });
        var currentCurrency = '';
        var currencyAtRight = false;
        var decimal_degits = 0;
        var refCurrency = database.collection('currencies').where('isActive', '==', true);
        refCurrency.get().then(async function (snapshots) {
            var currencyData = snapshots.docs[0].data();
            currentCurrency = currencyData.symbol;
            currencyAtRight = currencyData.symbolAtRight;
            if (currencyData.decimal_degits) {
                decimal_degits = currencyData.decimal_degits;
            }
            $(".currentCurrency").text(currencyData.symbol);
        });
        var email_templates = database.collection('email_templates').where('type', '==', 'wallet_topup');
        var emailTemplatesData = null;
        database.collection('restaurant_orders').where('authorID', '==', id).get().then(async function (orderSnapshots) {
            var paymentData = orderSnapshots.docs;
            $("#total_orders").text(paymentData.length);
        })
        $(document).ready(async function () {
            jQuery("#data-table_processing").show();
            await email_templates.get().then(async function (snapshots) {
                emailTemplatesData = snapshots.docs[0].data();
            });
            ref.get().then(async function (snapshots) {
                var user = snapshots.docs[0].data();
                $(".user_name").text(user.firstName + ' ' + user.lastName);
                if (user.hasOwnProperty('email') && user.email) {
                    $(".email").text(shortEmail(user.email));
                } else {
                    $('.email').html("{{trans('lang.not_mentioned')}}");
                }
                if (user.hasOwnProperty('phoneNumber') && user.phoneNumber) {
                    $(".phone").text(shortEditNumber(user.phoneNumber));
                } else {
                    $('.phone').html("{{trans('lang.not_mentioned')}}");
                }
                var wallet_balance = 0;
                if (user.hasOwnProperty('wallet_amount') && user.wallet_amount != null && !isNaN(user.wallet_amount)) {
                    wallet_balance = user.wallet_amount;
                }
                if (currencyAtRight) {
                    wallet_balance = parseFloat(wallet_balance).toFixed(decimal_degits) + "" + currentCurrency;
                } else {
                    wallet_balance = currentCurrency + "" + parseFloat(wallet_balance).toFixed(decimal_degits);
                }
                $('.wallet_balance').html(wallet_balance);
                var image = "";
                if (user.profilePictureURL != "" && user.profilePictureURL != null) {
                    image = '<img onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" width="100px" id="" height="auto" src="' + user.profilePictureURL + '">';
                } else {
                    image = '<img width="100px" id="" height="auto" src="' + placeholderImage + '">';
                }
                $('.profile_image').html(image);
                var address = '';
                if (user.hasOwnProperty('shippingAddress') && Array.isArray(user.shippingAddress)) {
                    shippingAddress = user.shippingAddress;
                    address+='<div id="append_list1" class="res-search-list row">';
                    shippingAddress.forEach((listval) => {
                        var defaultBtnHtml = '';
                        if (listval.isDefault == true) {
                            if (listval.hasOwnProperty('location') && listval.location) {
                                var lat = listval.location.latitude; 
                                var lng = listval.location.longitude; 
                                var mapSrc = `https://maps.google.com/maps?width=600&height=225&hl=en&q=${lat},${lng}&t=&z=14&ie=UTF8&iwloc=B&output=embed`;
                                $(".mapouter").show();
                                $(".gmap_iframe").attr("src", mapSrc);
                            } else {
                                $(".mapouter").html("<p>No map available</p>");
                            }
                            defaultBtnHtml = '<span class="badge badge-success ml-2 py-2 px-2" type="button" >Default</span>';
                        }
                        address = address + '<div class="transactions-list-wrap mt-4 col-md-12">';
                        address +='<div class="bg-white rounded-lg mb-3 transactions-list-view shadow-sm">';
                        address +='<div class="gold-members d-flex align-items-start transactions-list">';
                        address = address + '<div class="media transactions-list-left w-100">';
                        address = address + '<div class="media-body"><h6>' + listval.address + "," + listval.locality + " " + listval.landmark + '</h6>';
                        address = address + '<span class="badge badge-info py-2 px-2">' + listval.addressAs + '</span>' + defaultBtnHtml ;
                        address += '</div></div>';
                        address = address + '</div> </div></div>';    
                     });
                    address +='</div>';
                } 
                if (address != "") {
                    $('.address').html(address);
                } else {
                    $('.address').html("<h5>{{trans('lang.not_mentioned')}}</h5>");
                }
               
                jQuery("#data-table_processing").hide();
            });
        });
        $(".save-form-btn").click(function () {
            var date = firebase.firestore.FieldValue.serverTimestamp();
            var amount = $('#amount').val();
            if (amount == '') {
                $('#wallet_error').text('{{trans("lang.add_wallet_amount_error")}}')
                return false;
            }
            var note = $('#note').val();
            database.collection('users').where('id', '==', id).get().then(async function (snapshot) {
                if (snapshot.docs.length > 0) {
                    var data = snapshot.docs[0].data();
                    var walletAmount = 0;
                    if (data.hasOwnProperty('wallet_amount') && !isNaN(data.wallet_amount) && data.wallet_amount != null) {
                        walletAmount = data.wallet_amount;
                    }
                    var newWalletAmount = parseFloat(walletAmount) + parseFloat(amount);
                    database.collection('users').doc(id).update({
                        'wallet_amount': newWalletAmount
                    }).then(function (result) {
                        var tempId = database.collection("tmp").doc().id;
                        database.collection('wallet').doc(tempId).set({
                            'amount': parseFloat(amount),
                            'date': date,
                            'isTopUp': true,
                            'id': tempId,
                            'order_id': '',
                            'payment_method': 'Wallet',
                            'payment_status': 'success',
                            'user_id': id,
                            'note': note,
                            'transactionUser': "user",
                        }).then(async function (result) {
                            if (currencyAtRight) {
                                amount = parseInt(amount).toFixed(decimal_degits) + "" + currentCurrency;
                                newWalletAmount = newWalletAmount.toFixed(decimal_degits) + "" + currentCurrency;
                            } else {
                                amount = currentCurrency + "" + parseInt(amount).toFixed(decimal_degits);
                                newWalletAmount = currentCurrency + "" + newWalletAmount.toFixed(decimal_degits);
                            }
                            var formattedDate = new Date();
                            var month = formattedDate.getMonth() + 1;
                            var day = formattedDate.getDate();
                            var year = formattedDate.getFullYear();
                            month = month < 10 ? '0' + month : month;
                            day = day < 10 ? '0' + day : day;
                            formattedDate = day + '-' + month + '-' + year;
                            var message = emailTemplatesData.message;
                            message = message.replace(/{username}/g, data.firstName + ' ' + data.lastName);
                            message = message.replace(/{date}/g, formattedDate);
                            message = message.replace(/{amount}/g, amount);
                            message = message.replace(/{paymentmethod}/g, 'Wallet');
                            message = message.replace(/{transactionid}/g, tempId);
                            message = message.replace(/{newwalletbalance}/g, newWalletAmount);
                            emailTemplatesData.message = message;
                            var url = "{{url('send-email')}}";
                            var sendEmailStatus = await sendEmail(url, emailTemplatesData.subject, emailTemplatesData.message, [data.email]);
                            if (sendEmailStatus) {
                                window.location.reload();
                            }
                        })
                    })
                } else {
                    $('#user_account_not_found_error').text('{{trans("lang.user_detail_not_found")}}');
                }
            });
        });
    </script>
@endsection