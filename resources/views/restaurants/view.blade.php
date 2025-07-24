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
                                <h3 class="mb-0 restaurantTitle">{{trans('lang.restaurant_plural')}}</h3>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a
                                            href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                                    <li class="breadcrumb-item"><a
                                            href="{!! route('restaurants') !!}">{{trans('lang.restaurant_plural')}}</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{trans('lang.restaurant_details')}}</li>
                                </ol>
                            </div>
                        </div>
                        <div class="d-flex top-title-right align-self-center">
                            <div class="card-header-right">
                                <a href="javascript:void(0)" data-toggle="modal" data-target="#addWalletModal"
                                    class="btn-primary btn rounded-full add-wallate"><i
                                        class="mdi mdi-plus mr-2"></i>{{trans('lang.add_wallet_amount')}}</a>
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
                        <a href="{{route('restaurants.view', $id)}}">{{trans('lang.tab_basic')}}</a>
                    </li>
                    <li>
                        <a href="{{route('restaurants.foods', $id)}}">{{trans('lang.tab_foods')}}</a>
                    </li>
                    <li>
                        <a href="{{route('restaurants.orders', $id)}}">{{trans('lang.tab_orders')}}</a>
                    </li>
                    <li>
                        <a href="{{route('restaurants.coupons', $id)}}">{{trans('lang.tab_promos')}}</a>
                    </li>
                    <li>
                        <a href="{{route('restaurants.payout', $id)}}">{{trans('lang.tab_payouts')}}</a>
                    </li>
                    <li>
                        <a
                            href="{{route('payoutRequests.restaurants.view', $id)}}">{{trans('lang.tab_payout_request')}}</a>
                    </li>
                    <li>
                        <a href="{{route('restaurants.booktable', $id)}}">{{trans('lang.dine_in_future')}}</a>
                    </li>
                    <li id="restaurant_wallet"></li>
                    <li id="subscription_plan"></li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-box-with-icon bg--1">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-box-with-content">
                                <h4 class="text-dark-2 mb-1 h4 rest_count" id="total_orders">06</h4>
                                <p class="mb-0 small text-dark-2">{{trans('lang.dashboard_total_orders')}}</p>
                            </div>
                            <span class="box-icon ab"><img src="{{ asset('images/active_restaurant.png') }}"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-box-with-icon bg--2">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-box-with-content">
                                <h4 class="text-dark-2 mb-1 h4 rest_active_count" id="total_earnings">$0.00</h4>
                                <p class="mb-0 small text-dark-2">{{trans('lang.dashboard_total_earnings')}}</p>
                            </div>
                            <span class="box-icon ab"><img src="{{ asset('images/total_earning.png') }}"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-box-with-icon bg--3">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-box-with-content">
                                <h4 class="text-dark-2 mb-1 h4 total_transaction" id="total_payment">$0.00</h4>
                                <p class="mb-0 small text-dark-2">{{trans('lang.total_payments')}}</p>
                            </div>
                            <span class="box-icon ab"><img src="{{ asset('images/total_payment.png') }}"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-box-with-icon bg--5">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-box-with-content">
                                <h4 class="text-dark-2 mb-1 h4 commission_earned" id="remaining_amount">$0.00</h4>
                                <p class="mb-0 small text-dark-2">{{trans('lang.remaining_payments')}}</p>
                            </div>
                            <span class="box-icon ab"><img src="{{ asset('images/remaining_payment.png') }}"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="text-dark-2 mb-0 h4">{{trans('lang.subscription_details')}}</h3>
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#changeSubscriptionModal"
                            class="btn-primary btn rounded-full change-plan"><i
                                class="mdi mdi-plus mr-2"></i>{{trans('lang.change_subscription_plan')}}</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-box-with-icon bg--9">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-box-with-content">
                                <h4 class="text-dark-2 mb-1 h4 plan_name"></h4>
                                <p class="mb-0 small text-dark-2">{{trans('lang.plan_name')}}</p>
                            </div>
                            <span class="box-icon ab"><img src="{{ asset('images/basic.png') }}"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-box-with-icon bg--5">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-box-with-content">
                                <h4 class="text-dark-2 mb-1 h4 number_of_days"></h4>
                                <p class="mb-0 small text-dark-2">{{trans('lang.number_of_days')}}</p>
                            </div>
                            <span class="box-icon ab"><img src="{{ asset('images/countdown.png') }}"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-box-with-icon bg--14">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-box-with-content">
                                <h4 class="text-dark-2 mb-1 h4 plan_expire_date"></h4>
                                <p class="mb-0 small text-dark-2">{{trans('lang.expiry_date')}}</p>
                            </div>
                            <span class="box-icon ab"><img src="{{ asset('images/calendar.png') }}"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-box-with-icon bg--6">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-box-with-content">
                                <h4 class="text-dark-2 mb-1 h4 plan_price"></h4>
                                <p class="mb-0 small text-dark-2">{{trans('lang.total_price')}}</p>
                            </div>
                            <span class="box-icon ab"><img src="{{ asset('images/price.png') }}"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="restaurant_info-section">
            <div class="card border">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom pb-3">
                    <div class="card-header-title">
                        <h3 class="text-dark-2 mb-0 h4">{{trans('lang.restaurant_details')}}</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="restaurant_info_left">
                                <div class="d-flex mb-1">
                                    <div class="sis-img restaurant_image" id="restaurant_image">
                                    </div>
                                    <div class="sis-content pl-4">
                                        <div class="sis-content-title d-flex align-items-center mb-1">
                                            <h5 class="text-dark-2 mb-0 font-18 font-semibold"><span
                                                    class="restaurant_name"></span></h5><span class="sis-review"
                                                id="restaurant_reviewcount"></span>
                                        </div>
                                        <ul class="p-0 info-list mb-0">
                                            <li class="d-flex align-items-center mb-2">
                                                <label
                                                    class="mb-0 font-wi font-semibold text-dark-2">{{trans('lang.restaurant_phone')}}</label>
                                                <span class="restaurant_phone"></span>
                                            </li>
                                            <li class="d-flex align-items-center mb-2">
                                                <label
                                                    class="mb-0 font-wi font-semibold text-dark-2">{{trans('lang.restaurant_address')}}</label>
                                                <span class="restaurant_address"></span>
                                            </li>
                                            <li class="d-flex align-items-center mb-2">
                                                <label
                                                    class="mb-0 font-wi font-semibold text-dark-2">{{trans('lang.restaurant_cuisines')}}</label>
                                                <span class="restaurant_cuisines"></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label
                                            class="mb-0 font-wi font-semibold text-dark-2">{{trans('lang.restaurant_description')}}</label>
                                        <p><span class="restaurant_description"></span></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label
                                            class="mb-0 font-wi font-semibold text-dark-2">{{trans('lang.wallet_Balance')}}</label>
                                        <p><span class="wallet"></span></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label
                                            class="mb-0 font-wi font-semibold text-dark-2">{{trans('lang.zone')}}</label>
                                        <p><span id="zone_name"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="map-box">
                                <div class="mapouter">
                                    <div class="gmap_canvas"><iframe class="gmap_iframe" width="100%" frameborder="0"
                                            scrolling="no" marginheight="0" marginwidth="0"
                                            src="https://maps.google.com/maps?width=600&amp;height=225&amp;hl=en&amp;q=University of Oxford&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe><a
                                            href="https://sprunkiplay.com/">Sprunki Game</a></div>
                                    <style>
                                        .mapouter {
                                            position: relative;
                                            text-align: right;
                                            width: 100%;
                                            height: 225px;
                                        }

                                        .gmap_canvas {
                                            overflow: hidden;
                                            background: none !important;
                                            width: 100%;
                                            height: 225px;
                                        }

                                        .gmap_iframe {
                                            height: 225px !important;
                                        }
                                    </style>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="restaurant_info-section">
            <div class="row">
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-header d-flex justify-content-between align-items-center border-bottom pb-3">
                            <div class="card-header-title">
                                <h3 class="text-dark-2 mb-0 h4">{{trans('lang.vendor_details')}}</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="mb-1 font-wi font-semibold text-dark-2">{{trans('lang.name')}}</label>
                                    <p><span class="vendor_name"></span></p>
                                </div>
                                <div class="col-md-3">
                                    <label
                                        class="mb-1 font-wi font-semibold text-dark-2">{{trans('lang.restaurant_phone')}}</label>
                                    <p><span class="vendor_phoneNumber"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <label
                                        class="mb-1 font-wi font-semibold text-dark-2">{{trans('lang.email')}}</label>
                                    <p><span class="vendor_email"></span></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label
                                        class="mb-1 font-wi font-semibold text-dark-2">{{trans('lang.dine_in_future')}}</label>
                                    <p><span class="dine_in_future"></span></p>
                                </div>
                                <div class="col-md-9">
                                    <label
                                        class="mb-1 font-wi font-semibold text-dark-2">{{trans('lang.restaurant_status')}}</label>
                                    <p><span class="vendor_avtive"></span></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-9">
                                    <label
                                        class="mb-1 font-wi font-semibold text-dark-2">{{trans('lang.open_closed')}}</label>
                                    <p><span class="restaurant_is_open"></span></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-9">
                                    <label
                                        class="mb-1 font-wi font-semibold text-dark-2">{{trans('lang.admin_commission')}}</label>
                                    <p><span class="admin_commission"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-header d-flex justify-content-between align-items-center border-bottom pb-3">
                            <div class="card-header-title">
                                <h3 class="text-dark-2 mb-0 h4">{{trans('lang.gallery')}}</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="restaurant_gallery">
                                <div id="photos"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="restaurant_info-section">
            <div class="row">
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-header d-flex justify-content-between align-items-center border-bottom pb-3">
                            <div class="card-header-title">
                                <h3 class="text-dark-2 mb-0 h4">{{trans('lang.working_hours')}}</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 Monday_working_hours">
                                    <label
                                        class="mb-1 font-wi font-semibold text-dark-2">{{trans('lang.monday')}}</label>
                                </div>
                                <div class="col-md-3 Tuesday_working_hours">
                                    <label
                                        class="mb-1 font-wi font-semibold text-dark-2">{{trans('lang.tuesday')}}</label>
                                </div>
                                <div class="col-md-3 Wednesday_working_hours">
                                    <label
                                        class="mb-1 font-wi font-semibold text-dark-2">{{trans('lang.wednesday')}}</label>
                                </div>
                                <div class="col-md-3 Thursday_working_hours">
                                    <label
                                        class="mb-1 font-wi font-semibold text-dark-2">{{trans('lang.thursday')}}</label>
                                </div>
                                <div class="col-md-3 Friday_working_hours">
                                    <label
                                        class="mb-1 font-wi font-semibold text-dark-2">{{trans('lang.friday')}}</label>
                                </div>
                                <div class="col-md-3 Satuarday_working_hours">
                                    <label
                                        class="mb-1 font-wi font-semibold text-dark-2">{{trans('lang.Saturday')}}</label>
                                </div>
                                <div class="col-md-3 Sunday_working_hours">
                                    <label
                                        class="mb-1 font-wi font-semibold text-dark-2">{{trans('lang.sunday')}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-header d-flex justify-content-between align-items-center border-bottom pb-3">
                            <div class="card-header-title">
                                <h3 class="text-dark-2 mb-0 h4">{{trans('lang.services')}}</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="restaurant_service">
                                <ul class="p-0" id="filtershtml">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-header d-flex justify-content-between align-items-center border-bottom pb-3">
                            <div class="card-header-title">
                                <h3 class="text-dark-2 mb-0 h4">{{trans('lang.active_subscription_plan')}}</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-3">
                                    <label
                                        class="mb-1 font-wi font-semibold text-dark-2">{{trans('lang.plan_name')}}</label>
                                    <p><span class="plan_name"></span></p>
                                </div>
                                <div class="col-md-3">
                                    <label
                                        class="mb-1 font-wi font-semibold text-dark-2">{{trans('lang.plan_type')}}</label>
                                    <p><span class="plan_type"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <label
                                        class="mb-1 font-wi font-semibold text-dark-2">{{trans('lang.plan_expires_at')}}</label>
                                    <p><span class="plan_expire_at"></span></p>
                                </div>
                                <div class="col-md-3">
                                    <label
                                        class="mb-1 font-wi font-semibold text-dark-2">{{trans('lang.order_limit')}}</label>
                                    <p><span class="order_limit"></span></p>
                                </div>
                                <div class="col-md-3">
                                    <label
                                        class="mb-1 font-wi font-semibold text-dark-2">{{trans('lang.item_limit')}}</label>
                                    <p><span class="item_limit"></span></p>
                                </div>
                                <div class="col-md-3 update-limit-div" style="display:none">
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#updateLimitModal" 
                                        class="btn-primary btn rounded-full update-limit">{{trans('lang.update_plan_limit')}}</a>
                                </div>
                                <div class="col-md-6">
                                    <label
                                        class="mb-1 font-wi font-semibold text-dark-2">{{trans('lang.available_features')}}</label>
                                    <p><span class="plan_features"></span></p>
                                </div>
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
                    <button type="button" class="btn btn-primary save-form-btn">{{trans('submit')}}</a></button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">
                        {{trans('close')}}</a>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="changeSubscriptionModal" tabindex="-1" role="dialog" aria-hidden="true" style="width: 100%">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 1200px;">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="text-dark-2 h5 mb-0">{{ trans('lang.business_plans') }}</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-lg-12 ml-lg-auto mr-lg-auto">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex top-title-section pb-4 mb-2 justify-content-between">
                                    <div class="d-flex top-title-left align-start-center">
                                        <div class="top-title">
                                            <h3 class="mb-0">{{ trans('lang.choose_your_business_plan') }}</h3>
                                            <p class="mb-0 text-dark-2">
                                                {{ trans('lang.choose_your_business_plan_description') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="row" id="default-plan"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="checkoutSubscriptionModal" tabindex="-1" role="dialog" aria-hidden="true"
    style="width: 100%">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 1200px;">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="text-dark-2 h5 mb-0">{{ trans('lang.shift_to_plan') }}</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <form class="">
                    <div class="subscription-section">
                        <div class="subscription-section-inner">
                            <div class="card-body">
                                <div class="row" id="plan-details"></div>
                                <div class="pay-method-section pt-4">
                                    <h6 class="text-dark-2 h6 mb-3 pb-3">{{ trans('lang.pay_via_online') }}</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="pay-method-box d-flex align-items-center">
                                                <div class="pay-method-icon">
                                                    <img src="{{ asset('images/wallet_icon_ic.png') }}">
                                                </div>
                                                <div class="form-check">
                                                    <h6 class="text-dark-2 h6 mb-0">{{ trans('lang.manual_pay') }}</h6>
                                                    <input type="radio" id="manual_pay" name="payment_method"
                                                        value="manual_pay" checked="">
                                                    <label class="control-label mb-0" for="manual_pay"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer border-top">
                                <div class="align-items-center justify-content-between">
                                    <div class="edit-form-group btm-btn text-right">
                                        <div class="card-block-active-plan">
                                            <a href="" class="btn btn-default rounded-full mr-2"
                                                data-dismiss="modal">{{ trans('lang.cancel_plan') }}</a>
                                            <input type="hidden" id="plan_id" name="plan_id" value="">
                                            <button type="button" class="btn-primary btn rounded-full"
                                                onclick="finalCheckout()">{{ trans('lang.change_plan') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="updateLimitModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered location_modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title locationModalTitle">{{trans('lang.update_plan_limit')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="">
                    <div class="form-row">
                        <div class="form-group row">
                            <div class="form-group row width-100">
                                <label class="control-label">{{ trans('lang.maximum_order_limit') }}</label>
                                <div class="form-check width-100">
                                    <input type="radio" id="unlimited_order" name="set_order_limit" value="unlimited"
                                        checked>
                                    <label class="control-label"
                                        for="unlimited_order">{{ trans('lang.unlimited') }}</label>
                                </div>
                                <div class="d-flex">
                                    <div class="form-check width-50 limited_order_div">
                                        <input type="radio" id="limited_order" name="set_order_limit" value="limited">
                                        <label class="control-label"
                                            for="limited_order">{{ trans('lang.limited') }}</label>
                                    </div>
                                    <div class="form-check width-50 d-none order-limit-div">
                                        <input type="number" id="order_limit" class="form-control"
                                            placeholder="{{ trans('lang.ex_1000') }}">
                                    </div>
                                </div>
                                <span class="order_limit_err"></span>
                            </div>
                            <div class="form-group row width-100">
                                <label class="control-label">{{ trans('lang.maximum_item_limit') }}</label>
                                <div class="form-check width-100">
                                    <input type="radio" id="unlimited_item" name="set_item_limit" value="unlimited"
                                        checked>
                                    <label class="control-label"
                                        for="unlimited_item">{{ trans('lang.unlimited') }}</label>
                                </div>
                                <div class="d-flex ">
                                    <div class="form-check width-50 limited_item_div  ">
                                        <input type="radio" id="limited_item" name="set_item_limit" value="limited">
                                        <label class="control-label"
                                            for="limited_item">{{ trans('lang.limited') }}</label>
                                    </div>
                                    <div class="form-check width-50 d-none item-limit-div">
                                        <input type="number" id="item_limit" class="form-control"
                                            placeholder="{{ trans('lang.ex_1000') }}">
                                    </div>
                                </div>
                                <span class="item_limit_err"></span>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary update-plan-limit">{{trans('submit')}}</a></button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">
                        {{trans('close')}}</a>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    var id="<?php echo $id;?>";
    var database=firebase.firestore();
    var ref=database.collection('vendors').where("id","==",id);
    var photo="";
    var vendorAuthor='';
    var restaurantOwnerId="";
    var restaurantOwnerOnline=false;
    var workingHours=[];
    var timeslotworkSunday=[];
    var timeslotworkMonday=[];
    var timeslotworkTuesday=[];
    var timeslotworkWednesday=[];
    var timeslotworkFriday=[];
    var timeslotworkSatuarday=[];
    var timeslotworkThursday=[];
    var placeholderImage='';
    var placeholder=database.collection('settings').doc('placeHolderImage');
    placeholder.get().then(async function(snapshotsimage) {
        var placeholderImageData=snapshotsimage.data();
        placeholderImage=placeholderImageData.image;
    })
    var currentCurrency='';
    var currencyAtRight=false;
    var decimal_degits=0;
    var refCurrency=database.collection('currencies').where('isActive','==',true);
    refCurrency.get().then(async function(snapshots) {
        var currencyData=snapshots.docs[0].data();
        currentCurrency=currencyData.symbol;
        currencyAtRight=currencyData.symbolAtRight;
        if(currencyData.decimal_degits) {
            decimal_degits=currencyData.decimal_degits;
        }
    });
    var commisionModel=false;
    var AdminCommission='';
    database.collection('settings').doc("AdminCommission").get().then(async function(snapshots) {
        var commissionSetting=snapshots.data();
        if(commissionSetting.isEnabled==true) {
            commisionModel=true;
        }
        if(commissionSetting.commissionType=="Percent") {
            AdminCommission=commissionSetting.fix_commission+''+'%';
        } else {
            if(currencyAtRight) {
                AdminCommission=commissionSetting.fix_commission.toFixed(decimal_degits)+currentCurrency;
            } else {
                AdminCommission=currentCurrency+commissionSetting.fix_commission.toFixed(decimal_degits);
            }
        }
    });
    var subscriptionModel=false;
    database.collection('settings').doc("restaurant").get().then(async function(snapshots) {
        var businessModelSettings=snapshots.data();
        if(businessModelSettings.hasOwnProperty('subscription_model')&&businessModelSettings.subscription_model==true) {
            subscriptionModel=true;
        }
    });
    var email_templates=database.collection('email_templates').where('type','==','wallet_topup');
    var emailTemplatesData=null;
    $(".save-form-btn").click(function() {
        var date=firebase.firestore.FieldValue.serverTimestamp();
        var amount=$('#amount').val();
        if(amount=='') {
            $('#wallet_error').text('{{trans("lang.add_wallet_amount_error")}}')
            return false;
        }
        var note=$('#note').val();
        database.collection('users').where('id','==',vendorAuthor).get().then(async function(snapshot) {
            if(snapshot.docs.length>0) {
                var data=snapshot.docs[0].data();
                var walletAmount=0;
                if(data.hasOwnProperty('wallet_amount')&&!isNaN(data.wallet_amount)&&data.wallet_amount!=null) {
                    walletAmount=data.wallet_amount;
                }
                user_id=data.id;
                var newWalletAmount=parseFloat(walletAmount)+parseFloat(amount);
                database.collection('users').doc(vendorAuthor).update({
                    'wallet_amount': newWalletAmount
                }).then(function(result) {
                    var tempId=database.collection("tmp").doc().id;
                    database.collection('wallet').doc(tempId).set({
                        'amount': parseFloat(amount),
                        'date': date,
                        'isTopUp': true,
                        'id': tempId,
                        'order_id': '',
                        'payment_method': 'Wallet',
                        'payment_status': 'success',
                        'user_id': user_id,
                        'note': note,
                        'transactionUser': "vendor",
                    }).then(async function(result) {
                        if(currencyAtRight) {
                            amount=parseInt(amount).toFixed(decimal_degits)+""+currentCurrency;
                            newWalletAmount=newWalletAmount.toFixed(decimal_degits)+""+currentCurrency;
                        } else {
                            amount=currentCurrency+""+parseInt(amount).toFixed(decimal_degits);
                            newWalletAmount=currentCurrency+""+newWalletAmount.toFixed(decimal_degits);
                        }
                        var formattedDate=new Date();
                        var month=formattedDate.getMonth()+1;
                        var day=formattedDate.getDate();
                        var year=formattedDate.getFullYear();
                        month=month<10? '0'+month:month;
                        day=day<10? '0'+day:day;
                        formattedDate=day+'-'+month+'-'+year;
                        var message=emailTemplatesData.message;
                        message=message.replace(/{username}/g,data.firstName+' '+data.lastName);
                        message=message.replace(/{date}/g,formattedDate);
                        message=message.replace(/{amount}/g,amount);
                        message=message.replace(/{paymentmethod}/g,'Wallet');
                        message=message.replace(/{transactionid}/g,tempId);
                        message=message.replace(/{newwalletbalance}/g,newWalletAmount);
                        emailTemplatesData.message=message;
                        var url="{{url('send-email')}}";
                        var sendEmailStatus=await sendEmail(url,emailTemplatesData.subject,emailTemplatesData.message,[data.email]);
                        if(sendEmailStatus) {
                            window.location.reload();
                        }
                    })
                })
            } else {
                $('#user_account_not_found_error').text('{{trans("lang.user_detail_not_found")}}');
            }
        });
    });
    async function getWalletBalance(vendorId) {
        database.collection('users').where('id','==',vendorId).get().then(async function(snapshot) {
            if(snapshot.docs.length>0) {
                restaurant=snapshot.docs[0].data();
                var wallet_balance=0;
                if(restaurant.hasOwnProperty('wallet_amount')&&restaurant.wallet_amount!=null&&!isNaN(restaurant.wallet_amount)) {
                    wallet_balance=restaurant.wallet_amount;
                }
                if(currencyAtRight) {
                    wallet_balance=parseFloat(wallet_balance).toFixed(decimal_degits)+""+currentCurrency;
                } else {
                    wallet_balance=currentCurrency+""+parseFloat(wallet_balance).toFixed(decimal_degits);
                }
                $('.wallet').html(wallet_balance);
            }
        });
    }
    $(document).ready(async function() {
        jQuery("#data-table_processing").show();
        await email_templates.get().then(async function(snapshots) {
            emailTemplatesData=snapshots.docs[0].data();
        });
        var orders=await getTotalOrders();
        var earnings=await getTotalEarnings();
        var payment=await getTotalpayment();
        var remaining=earnings-payment;
        if(currencyAtRight) {
            remaining_with_currency=parseFloat(remaining).toFixed(decimal_degits)+""+currentCurrency;
        } else {
            remaining_with_currency=currentCurrency+""+parseFloat(remaining).toFixed(decimal_degits);
        }
        $("#remaining_amount").text(remaining_with_currency);
        ref.get().then(async function(snapshots) {
            jQuery("#data-table_processing").hide();
            if(!snapshots.empty) {
                var restaurant=snapshots.docs[0].data();
                vendorAuthor=restaurant.author;
                $(".restaurant_name").text(restaurant.title);
                var rating=0;
                if(restaurant.hasOwnProperty('reviewsCount')&&restaurant.reviewsCount!=0) {
                    rating=Math.round(parseFloat(restaurant.reviewsSum)/parseInt(restaurant.reviewsCount));
                } else {
                    rating=0;
                }
                walletRoute="{{route('users.walletstransaction', ':id')}}";
                walletRoute=walletRoute.replace(":id",restaurant.author);
                $('#restaurant_wallet').append('<a href="'+walletRoute+'">{{trans("lang.wallet_transaction")}}</a>');
                $('#subscription_plan').append('<a href="'+"{{route('vendor.subscriptionPlanHistory', ':id')}}".replace(':id',restaurant.author)+'">'+'{{trans('lang.subscription_history')}}'+'</a>');
                const walletBalance=getWalletBalance(restaurant.author);
                const getStoreName=getStoreNameFunction('<?php echo $id; ?>');
                var review='<ul class="rating" data-rating="'+rating+'">';
                review=review+'<li class="rating__item"></li>';
                review=review+'<li class="rating__item"></li>';
                review=review+'<li class="rating__item"></li>';
                review=review+'<li class="rating__item"></li>';
                review=review+'<li class="rating__item"></li>';
                review=review+'</ul>';
                if(restaurant.reviewsCount==null||restaurant.reviewsCount==undefined||restaurant.reviewsCount=='') {
                    restaurant.reviewsCount=0;
                }
                var restaurant_reviewcount=restaurant.reviewsCount+'<i class="mdi mdi-star"></i>';
                $("#restaurant_reviewcount").append(restaurant_reviewcount);
                var days=['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
                var currentdate=new Date();
                var currentDay=days[currentdate.getDay()];
                hour=currentdate.getHours();
                minute=currentdate.getMinutes();
                if(hour<10) {
                    hour='0'+hour
                }
                if(minute<10) {
                    minute='0'+minute
                }
                var currentHours=hour+':'+minute;
                $(".vendor_avtive").text("Closed").removeClass("green").addClass("red");
                
                // Check if restaurant is manually set to closed
                if(restaurant.hasOwnProperty('isOpen') && restaurant.isOpen === false) {
                    $(".vendor_avtive").text("Closed").removeClass("green").addClass("red");
                } else {
                    // Check working hours only if restaurant is not manually closed
                    var isOpenBasedOnHours = false;
                    if(restaurant.hasOwnProperty('workingHours')) {
                        for(i=0;i<restaurant.workingHours.length;i++) {
                            var day=restaurant.workingHours[i]['day'];
                            if(restaurant.workingHours[i]['day']==currentDay) {
                                if(restaurant.workingHours[i]['timeslot'].length!=0) {
                                    for(j=0;j<restaurant.workingHours[i]['timeslot'].length;j++) {
                                        var timeslot=restaurant.workingHours[i]['timeslot'][j];
                                        var from=timeslot[`from`];
                                        var to=timeslot[`to`];
                                        if(currentHours>=from&&currentHours<=to) {
                                            isOpenBasedOnHours = true;
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        // If no working hours are set, use default times (9:30 AM to 10:00 PM)
                        var defaultFrom = '09:30';
                        var defaultTo = '22:00';
                        if(currentHours >= defaultFrom && currentHours <= defaultTo) {
                            isOpenBasedOnHours = true;
                        }
                    }
                    
                    if(isOpenBasedOnHours) {
                        $(".vendor_avtive").text("Open").removeClass("red").addClass("green");
                    }
                }
                if(restaurant.hasOwnProperty('subscriptionExpiryDate')&&restaurant.hasOwnProperty('subscriptionPlanId')&&restaurant.subscriptionPlanId!=''&&restaurant.subscriptionPlanId!=null) {
                    $(".update-limit-div").show();
                    $(".plan_name").html(restaurant.subscription_plan.name);
                    $(".plan_type").html(restaurant.subscription_plan.type);
                    if(restaurant.subscriptionExpiryDate!=null&&restaurant.subscriptionExpiryDate!='') {
                        date=restaurant.subscriptionExpiryDate.toDate().toDateString();
                        time=restaurant.subscriptionExpiryDate.toDate().toLocaleTimeString('en-US');
                        $(".plan_expire_at").html(date+' '+time);
                        $(".plan_expire_date").html(date);
                    } else {
                        $(".plan_expire_at").html("{{trans('lang.unlimited')}}");
                        $(".plan_expire_date").html("{{trans('lang.unlimited')}}");
                    }
                    var number_of_days=restaurant.subscription_plan.expiryDay=="-1"? 'Unlimited':restaurant.subscription_plan.expiryDay+" Days";
                    $(".number_of_days").html(number_of_days);
                    if(currencyAtRight) {
                        $(".plan_price").html(parseFloat(restaurant.subscription_plan.price).toFixed(decimal_degits)+currentCurrency);
                    } else {
                        $(".plan_price").html(currentCurrency+parseFloat(restaurant.subscription_plan.price).toFixed(decimal_degits));
                    }
                    $('.order_limit').html((restaurant.subscription_plan.orderLimit=='-1')? "{{trans('lang.unlimited')}}":restaurant.subscription_plan.orderLimit);
                    $('.item_limit').html((restaurant.subscription_plan.itemLimit=='-1')? "{{trans('lang.unlimited')}}":restaurant.subscription_plan.itemLimit);
                    if(restaurant.subscription_plan.hasOwnProperty('features')) {
                        const translations={
                            chatingOption: "{{ trans('lang.chat') }}",
                            dineInOption: "{{ trans('lang.dine_in') }}",
                            generateQrCode: "{{ trans('lang.generate_qr_code') }}",
                            mobileAppAccess: "{{ trans('lang.mobile_app') }}"
                        };
                        var features=restaurant.subscription_plan.features;
                        var html=`<ul class="pricing-card-list text-dark-2">
                                            ${features.chat? `<li>${translations.chatingOption}</li>`:''}
                                            ${features.dineIn? `<li>${translations.dineInOption}</li>`:''}
                                            ${features.qrCodeGenerate? `<li>${translations.generateQrCode}</li>`:''}
                                            ${features.restaurantMobileApp? `<li>${translations.mobileAppAccess}</li>`:''}    
                                    </ul>`;
                        $('.plan_features').html(html);
                    }
                } else {
                    $(".plan_name").html('No Active Plan');
                    $(".plan_type").html('N/A');
                    $(".plan_expire_at").html('N/A');
                    $(".plan_expire_date").html('N/A');
                    $(".number_of_days").html('N/A');
                    $(".plan_price").html('N/A');
                    $(".order_limit").html('N/A');
                    $(".item_limit").html('N/A');
                    $(".plan_features").html('N/A');
                }
                if(restaurant.hasOwnProperty('workingHours')) {
                    for(i=0;i<restaurant.workingHours.length;i++) {
                        var day=restaurant.workingHours[i]['day'];
                        var timeslotHtml='';
                        if(restaurant.workingHours[i]['timeslot'].length!=0) {
                            for(j=0;j<restaurant.workingHours[i]['timeslot'].length;j++) {
                                var timeslot=restaurant.workingHours[i]['timeslot'][j];
                                var from=timeslot['from'],to=timeslot['to'];
                                var fromTime=(parseInt(from.split(":")[0])%12||12)+':'+from.split(":")[1]+(parseInt(from.split(":")[0])>=12? ' PM':' AM');
                                var toTime=(parseInt(to.split(":")[0])%12||12)+':'+to.split(":")[1]+(parseInt(to.split(":")[0])>=12? ' PM':' AM');
                                timeslotHtml+=`<p class="mb-2">${fromTime} - ${toTime}</p>`;
                            }
                        } else {
                            // Show default times when no working hours are set
                            timeslotHtml='<p class="mb-2">9:30 AM - 10:00 PM</p>';
                        }
                        $("."+day+"_working_hours").append(timeslotHtml);
                    }
                } else {
                    // If no workingHours property exists, show default times for all days
                    var defaultTimeslotHtml = '<p class="mb-2">9:30 AM - 10:00 PM</p>';
                    $(".Monday_working_hours").append(defaultTimeslotHtml);
                    $(".Tuesday_working_hours").append(defaultTimeslotHtml);
                    $(".Wednesday_working_hours").append(defaultTimeslotHtml);
                    $(".Thursday_working_hours").append(defaultTimeslotHtml);
                    $(".Friday_working_hours").append(defaultTimeslotHtml);
                    $(".Satuarday_working_hours").append(defaultTimeslotHtml);
                    $(".Sunday_working_hours").append(defaultTimeslotHtml);
                }
                if(restaurant.hasOwnProperty('adminCommission')) {
                    if(restaurant.adminCommission.commissionType=='Percent') {
                        $('.admin_commission').html(restaurant.adminCommission.fix_commission+"%");
                    } else {
                        if(currencyAtRight) {
                            $('.admin_commission').html(restaurant.adminCommission.fix_commission+currentCurrency);
                        } else {
                            $('.admin_commission').html(currentCurrency+restaurant.adminCommission.fix_commission);
                        }
                    }
                }
                var photos='<ul class="p-0">';
                restaurant.photos.forEach((photo) => {
                    photos=photos+'<li><img width="100px" id="" height="auto" src="'+photo+'"></span></li>';
                })
                photos=photos+'</ul>'
                if(restaurant.photos&&restaurant.photos!=""&&restaurant.photos!=null&&restaurant.photos.length>0) {
                    $("#photos").html(photos);
                } else {
                    $("#photos").html('<p>photos not available.</p>');
                }
                var image="";
                if(restaurant.photo!=""&&restaurant.photo!=null) {
                    image='<img onerror="this.onerror=null;this.src=\''+placeholderImage+'\'" width="200px" id="" height="auto" src="'+restaurant.photo+'">';
                } else {
                    image='<img width="200px" id="" height="auto" src="'+placeholderImage+'">';
                }
                $("#restaurant_image").html(image);
                $(".reviewhtml").html(review);
                filtershtml='';
                for(var key in restaurant.filters) {
                    if(restaurant.filters[key]=="Yes") {
                        filtershtml=filtershtml+'<li><span class="mdi mdi-check green mr-2"></span>'+key+'</li>';
                    } else {
                        filtershtml=filtershtml+'<li><span class="mdi mdi-close red mr-2"></span>'+key+'</li>';
                    }
                }
                $("#filtershtml").html(filtershtml);
                // Handle multiple categories display
                if (restaurant.categoryTitle && Array.isArray(restaurant.categoryTitle)) {
                    // Multiple categories
                    $(".restaurant_cuisines").text(restaurant.categoryTitle.join(", "));
                } else if (restaurant.categoryID) {
                    // Single category (backward compatibility)
                    await database.collection('vendor_categories').get().then(async function(snapshots) {
                        snapshots.docs.forEach((listval) => {
                            var data=listval.data();
                            if(data.id==restaurant.categoryID) {
                                $(".restaurant_cuisines").text(data.title);
                            }
                        })
                    });
                }
                $(".opentime").text(restaurant.opentime);
                $(".closetime").text(restaurant.closetime);
                $(".restaurant_address").text(restaurant.location);
                $(".restaurant_latitude").text(restaurant.latitude);
                $(".restaurant_longitude").text(restaurant.longitude);
                $(".restaurant_description").text(restaurant.description);
                if(restaurant.hasOwnProperty('enabledDiveInFuture')&&restaurant.enabledDiveInFuture==true) {
                    $(".dine_in_future").html("ON").removeClass("red").addClass("green");
                } else {
                    $(".dine_in_future").html("OFF").removeClass("green").addClass("red");
                }
                
                // Display isOpen status
                if(restaurant.hasOwnProperty('isOpen') && restaurant.isOpen === false) {
                    $(".restaurant_is_open").html("Closed").removeClass("green").addClass("red");
                } else {
                    $(".restaurant_is_open").html("Open").removeClass("red").addClass("green");
                }
                restaurantOwnerOnline=restaurant.isActive;
                photo=restaurant.photo;
                restaurantOwnerId=restaurant.author;
                await database.collection('users').where("id","==",restaurant.author).get().then(async function(snapshots) {
                    snapshots.docs.forEach((listval) => {
                        var user=listval.data();
                        $(".vendor_name").html(user.firstName+" "+user.lastName);
                        if(user.email!=""&&user.email!=null) {
                            $(".vendor_email").html(shortEmail(user.email));
                        }
                        else {
                            $(".vendor_email").html("");
                        }
                        if(user.phonenumber!=""&&user.phoneNumber!=null) {
                            $(".vendor_phoneNumber").html(shortEditNumber(user.phoneNumber));
                        }
                        else {
                            $(".vendor_phoneNumber").html("");
                        }
                    })
                });
                await database.collection('vendor_categories').get().then(async function(snapshots) {
                    snapshots.docs.forEach((listval) => {
                        var data=listval.data();
                        $('#restaurant_cuisines').append($("<option></option>")
                            .attr("value",data.id)
                            .text(data.title));
                    });
                    
                    // Handle multiple category selection for existing restaurant
                    if (restaurant.categoryID) {
                        if (Array.isArray(restaurant.categoryID)) {
                            // Multiple categories
                            $('#restaurant_cuisines').val(restaurant.categoryID);
                        } else {
                            // Single category (backward compatibility)
                            $('#restaurant_cuisines').val([restaurant.categoryID]);
                        }
                    }
                });
                if(restaurant.hasOwnProperty('phonenumber')) {
                    $(".restaurant_phone").text(shortEditNumber(restaurant.phonenumber));
                }
                else {
                    $(".restaurant_phone").text();
                }
                if(restaurant.hasOwnProperty('coordinates')&&restaurant.coordinates) {
                    var lat=restaurant.coordinates.latitude;
                    var lng=restaurant.coordinates.longitude;
                    var mapSrc=`https://maps.google.com/maps?width=600&height=225&hl=en&q=${lat},${lng}&t=&z=14&ie=UTF8&iwloc=B&output=embed`;
                    $(".gmap_iframe").attr("src",mapSrc);
                } else {
                    $(".mapouter").html("<p>No map available</p>");
                }
                if(restaurant.hasOwnProperty('zoneId')&&restaurant.zoneId!='') {
                    database.collection('zone').doc(restaurant.zoneId).get().then(async function(snapshots) {
                        let zone=snapshots.data();
                        $("#zone_name").text(zone.name);
                    });
                }
                jQuery("#data-table_processing").hide();
            }
        })
        $(".save_restaurant_btn").click(function() {
            var restaurantname=$(".restaurant_name").val();
            // Handle multiple category selection
            var categoryIDs = $("#restaurant_cuisines").val() || [];
            var categoryTitles = [];
            $("#restaurant_cuisines option:selected").each(function() {
                if ($(this).val() !== "") {
                    categoryTitles.push($(this).text());
                }
            });
            var address=$(".restaurant_address").val();
            var latitude=parseFloat($(".restaurant_latitude").val());
            var longitude=parseFloat($(".restaurant_longitude").val());
            var description=$(".restaurant_description").val();
            var phonenumber=$(".restaurant_phone").val();
            database.collection('vendors').doc(id).update({
                'title': restaurantname,
                'description': description,
                'latitude': latitude,
                'longitude': longitude,
                'location': address,
                'photo': photo,
                'categoryID': categoryIDs,
                'categoryTitle': categoryTitles,
                'phonenumber': phonenumber
            }).then(function(result) {
                window.location.href='{{ route("restaurants")}}';
            });
        })
    })
    var storageRef=firebase.storage().ref('images');
    function handleFileSelect(evt) {
        var f=evt.target.files[0];
        var reader=new FileReader();
        reader.onload=(function(theFile) {
            return function(e) {
                var filePayload=e.target.result;
                var val=f.name;
                var ext=val.split('.')[1];
                var docName=val.split('fakepath')[1];
                var filename=(f.name).replace(/C:\\fakepath\\/i,'')
                var timestamp=Number(new Date());
                var uploadTask=storageRef.child(filename).put(theFile);
                uploadTask.on('state_changed',function(snapshot) {
                    var progress=(snapshot.bytesTransferred/snapshot.totalBytes)*100;
                    console.log('Upload is '+progress+'% done');
                    jQuery("#uploding_image").text("Image is uploading...");
                },function(error) {
                },function() {
                    uploadTask.snapshot.ref.getDownloadURL().then(function(downloadURL) {
                        jQuery("#uploding_image").text("Upload is completed");
                        photo=downloadURL;
                    });
                });
            };
        })(f);
        reader.readAsDataURL(f);
    }
    async function getStoreNameFunction(vendorId) {
        var vendorName='';
        await database.collection('vendors').where('id','==',vendorId).get().then(async function(snapshots) {
            if(!snapshots.empty) {
                var vendorData=snapshots.docs[0].data();
                vendorName=vendorData.title;
                $('.restaurantTitle').html('{{trans("lang.restaurant_plural")}} - '+vendorName);
                if(vendorData.dine_in_active==true) {
                    $(".dine_in_future").show();
                }
            }
        });
        return vendorName;
    }
    async function getTotalOrders() {
        await database.collection('restaurant_orders').where('vendorID','==','<?php echo $id; ?>').get().then(async function(orderSnapshots) {
            var paymentData=orderSnapshots.docs;
            $("#total_orders").text(paymentData.length);
        })
    }
    async function getTotalEarnings() {
        var totalEarning=0;
        var adminCommission=0;
        await database.collection('restaurant_orders').where('vendorID','==','<?php echo $id; ?>').where('status','in',["Order Completed"]).get().then(async function(orderSnapshots) {
            var paymentData=orderSnapshots.docs;
            paymentData.forEach((order) => {
                var orderData=order.data();
                var price=0;
                if(orderData.adminCommission!=undefined) {
                    var commission=parseInt(orderData.adminCommission);
                    adminCommission=commission+adminCommission;
                }
                orderData.products.forEach((product) => {
                    if(product.price>0&&product.quantity!=0) {
                        var productTotal=parseInt(product.price)*parseInt(product.quantity);
                        price=price+productTotal;
                    }
                })
                totalEarning=totalEarning+price;
            })
            if(currencyAtRight) {
                totalEarningwithCurrency=parseFloat(totalEarning).toFixed(decimal_degits)+""+currentCurrency;
            } else {
                totalEarningwithCurrency=currentCurrency+""+parseFloat(totalEarning).toFixed(decimal_degits);
            }
            $("#total_earnings").text(totalEarningwithCurrency);
        })
        return totalEarning;
    }
    async function getTotalpayment(driverID) {
        var paid_price=0;
        var total_price=0;
        var remaining=0;
        await database.collection('payouts').where('vendorID','==','<?php echo $id; ?>').get().then(async function(payoutSnapshots) {
            payoutSnapshots.docs.forEach((payout) => {
                var payoutData=payout.data();
                if(payoutData.amount&&parseFloat(payoutData.amount)!=undefined&&parseFloat(payoutData.amount)!=''&&parseFloat(payoutData.amount)!=NaN) {
                    paid_price=parseFloat(paid_price)+parseFloat(payoutData.amount);
                }
            })
        });
        $("#total_payment").text(paid_price);
        return paid_price;
    }
    $("#changeSubscriptionModal").on('shown.bs.modal',function() {
        getSubscriptionPlan();
    });
    $("#changeSubscriptionModal").on('hide.bs.modal',function() {
        $("#default-plan").html('');
    });
    $("#checkoutSubscriptionModal").on('hide.bs.modal',function() {
        $("#plan-details").html('');
    });
    async function getSubscriptionPlan() {
        var activeSubscriptionId='';
        var snapshots=await database.collection('subscription_history').where('user_id','==',vendorAuthor).orderBy('createdAt','desc').get();
        if(snapshots.docs.length>0) {
            var data=snapshots.docs[0].data();
            activeSubscriptionId=data.subscription_plan.id;
        }
        database.collection('subscription_plans').where('isEnable','==',true).get().then(async function(snapshots) {
            let plans=[];
            snapshots.docs.map(doc => {
                let data=doc.data();
                plans.push({
                    ...data
                }); // Include document ID if needed
            });
            plans.sort((a,b) => a.place-b.place);
            var html='';
            plans.map(async (data) => {
                var activeClass=(data.id==activeSubscriptionId)? '<span class="badge badge-success">{{trans("lang.active")}}</span>':'';
                if(data.id=="J0RwvxCWhZzQQD7Kc2Ll") {
                    if(commisionModel) {
                        commissionData=data;
                        planId=data.id;
                        html+=`<div class="col-md-3 mb-3 pricing-card pricing-card-commission">
                                    <div class="pricing-card-inner">
                                        <div class="pricing-card-top">
                                            <div class="d-flex align-items-center pb-4">
                                                <span class="pricing-card-icon mr-4"><img src="${data.image}"></span>
                                            </div>
                                            <div class="pricing-card-price">
                                                <h3 class="text-dark-2">${data.name} ${activeClass}</h3>
                                                <span class="price-day">${AdminCommission} {{ trans('lang.commision_per_order') }}</span>
                                            </div>
                                        </div>
                                        <div class="pricing-card-content pt-3 mt-3 border-top">
                                            <ul class="pricing-card-list text-dark-2">`;
                        html+=`<li><span class="mdi mdi-check"></span>{{trans('lang.pay_commission_of')}} ${AdminCommission} {{trans('lang.on_each_order')}} </li>`
                        data.plan_points.map(async (list) => {
                            html+=`<li><span class="mdi mdi-check"></span>${list}</li>`
                        });
                        html+=`<li><span class="mdi mdi-check"></span>{{ trans('lang.unlimited') }} {{ trans('lang.orders') }}</li>`
                        html+=`<li><span class="mdi mdi-check"></span>{{ trans('lang.unlimited') }} {{ trans('lang.products') }}</li>`
                        html+=`</ul>
                                        </div>`;
                        var buttonText=(activeClass=='')?
                                    "{{ trans('lang.select_plan') }}":
                                    "{{ trans('lang.renew_plan') }}";                
                        
                        html+=`<div class="pricing-card-btm">
                                            <a href="javascript:void(0)" onClick="chooseSubscriptionPlan('${data.id}')" class="btn rounded-full active-btn btn-primary">${buttonText}</a>
                                        </div>`;
                        
                        html+=`</div>
                        </div>`;
                    }
                } else {
                    if(subscriptionModel) {
                        const translations={
                            chatingOption: "{{ trans('lang.chating_option') }}",
                            dineInOption: "{{ trans('lang.dinein_option') }}",
                            generateQrCode: "{{ trans('lang.generate_qr_code') }}",
                            mobileAppAccess: "{{ trans('lang.mobile_app_access') }}"
                        };
                        var features=data.features;
                        var buttonText=(activeClass=='')?
                                    "{{ trans('lang.select_plan') }}":
                                    "{{ trans('lang.renew_plan') }}";
                                 
                        html+=`<div class="col-md-3 mt-2 pricing-card pricing-card-subscription ${data.name}">
                            <div class="pricing-card-inner">
                                <div class="pricing-card-top">
                                <div class="d-flex align-items-center pb-4">
                                    <span class="pricing-card-icon mr-4"><img src="${data.image}"></span>
                                    <h2 class="text-dark-2">${data.name} ${activeClass}</h2>
                                </div>
                                <p class="text-muted">${data.description}</p>
                                <div class="pricing-card-price">
                                    <h3 class="text-dark-2">${currencyAtRight? parseFloat(data.price).toFixed(decimal_degits)+currentCurrency:currentCurrency+parseFloat(data.price).toFixed(decimal_degits)}</h3>
                                    <span class="price-day">${data.expiryDay==-1? "{{ trans('lang.unlimited') }}":data.expiryDay} Days</span>
                                </div>
                                </div>
                                <div class="pricing-card-content pt-3 mt-3 border-top">
                                <ul class="pricing-card-list text-dark-2">
                                    ${features.chat? `<li><span class="mdi mdi-check"></span>${translations.chatingOption}</li>`:`<li><span class="mdi mdi-close"></span>${translations.chatingOption}</li>`}
                                    ${features.dineIn? `<li><span class="mdi mdi-check"></span>${translations.dineInOption}</li>`:`<li><span class="mdi mdi-close"></span>${translations.dineInOption}</li>`}
                                    ${features.qrCodeGenerate? `<li><span class="mdi mdi-check"></span>${translations.generateQrCode}</li>`:`<li><span class="mdi mdi-close"></span>${translations.generateQrCode}</li>`}
                                    ${features.restaurantMobileApp? `<li><span class="mdi mdi-check"></span>${translations.mobileAppAccess}</li>`:`<li><span class="mdi mdi-close"></span>${translations.mobileAppAccess}</li>`}    
                                    <li><span class="mdi mdi-check"></span>${data.orderLimit==-1? "{{ trans('lang.unlimited') }}":data.orderLimit} {{ trans('lang.orders') }}</li>
                                    <li><span class="mdi mdi-check"></span>${data.itemLimit==-1? "{{ trans('lang.unlimited') }}":data.itemLimit} {{ trans('lang.products') }}</li>
                                </ul>
                                </div>`;
                        
                            html+=`<div class="pricing-card-btm">
                                        <a href="javascript:void(0)" onClick="chooseSubscriptionPlan('${data.id}')" class="btn rounded-full">${buttonText}</a>
                                    </div>`;
                        
                        html+=`</div>
                        </div>`;
                    }
                }
            });
            $('#default-plan').append(html);
        });
    }
    async function showPlanDetail(planId) {
        $("#plan_id").val(planId);
        var activePlan='';
        var snapshots=await database.collection('subscription_history').where('user_id','==',vendorAuthor).orderBy('createdAt','desc').get();
        if(snapshots.docs.length>0) {
            var data=snapshots.docs[0].data();
            activePlan=data.subscription_plan;
        }
        var choosedPlan='';
        var snapshot=await database.collection('subscription_plans').doc(planId).get();
        if(snapshot.exists) {
            choosedPlan=snapshot.data();
        }
        let html='';
        let choosedPlan_price=currencyAtRight? parseFloat(choosedPlan.price).toFixed(decimal_degits)+currentCurrency
            :currentCurrency+parseFloat(choosedPlan.price).toFixed(decimal_degits);
        if(activePlan) {
            let activePlan_price=currencyAtRight? parseFloat(activePlan.price).toFixed(decimal_degits)+currentCurrency
                :currentCurrency+parseFloat(activePlan.price).toFixed(decimal_degits);
            html+=` 
            <div class="col-md-8">
                <div class="subscription-card-left"> 
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <div class="subscription-card text-center">
                                <div class="d-flex align-items-center pb-3 justify-content-center">
                                    <span class="pricing-card-icon mr-4"><img src="${activePlan.image}"></span>
                                    <h2 class="text-dark-2 mb-0 font-weight-semibold">${activePlan.id=="J0RwvxCWhZzQQD7Kc2Ll"? "{{ trans('lang.commission') }}":activePlan.name}</h2>
                                </div>
                                <h3 class="text-dark-2">${activePlan.id=="J0RwvxCWhZzQQD7Kc2Ll"? AdminCommission+" {{ trans('lang.base_plan') }}":activePlan_price}</h3>
                                <p class="text-center">${activePlan.id=="J0RwvxCWhZzQQD7Kc2Ll"? "Free":activePlan.expiryDay==-1? "{{ trans('lang.unlimited') }}":activePlan.expiryDay+" Days"}</p>
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <img src="{{asset('images/left-right-arrow.png')}}">
                        </div>
                        <div class="col-md-5">
                            <div class="subscription-card text-center">
                                <div class="d-flex align-items-center pb-3 justify-content-center">
                                    <span class="pricing-card-icon mr-4"><img src="${choosedPlan.image}"></span>
                                    <h2 class="text-dark-2 mb-0 font-weight-semibold">${choosedPlan.name}
                                    </h2>
                                </div>
                                <h3 class="text-dark-2">${choosedPlan_price}</h3>
                                <p class="text-center">${choosedPlan.expiryDay=="-1"? "{{ trans('lang.unlimited') }}":choosedPlan.expiryDay+" {{ trans('lang.days') }}"}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="subscription-card-right">
                    <div
                        class="d-flex justify-content-between align-items-center py-3 px-3 text-dark-2">
                        <span class="font-weight-medium">{{ trans('lang.validity') }}</span>
                        <span class="font-weight-semibold">${choosedPlan.expiryDay=="-1"? "{{ trans('lang.unlimited') }}":choosedPlan.expiryDay+" {{ trans('lang.days') }}"}</span>
                    </div>
                    <div
                        class="d-flex justify-content-between align-items-center py-3 px-3 text-dark-2">
                        <span class="font-weight-medium">{{ trans('lang.price') }}</span>
                        <span class="font-weight-semibold">${choosedPlan_price}</span>
                    </div>
                    <div
                        class="d-flex justify-content-between align-items-center py-3 px-3 text-dark-2">
                        <span class="font-weight-medium">{{ trans('lang.bill_status') }}</span>
                        <span class="font-weight-semibold">{{ trans('lang.migrate_to_new_plan') }}</span>
                    </div>
                </div>
            </div>`;
        } else {
            html+=` 
            <div class="col-md-6">
                <div class="subscription-card-left"> 
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="subscription-card text-center">
                                <div class="d-flex align-items-center pb-3 justify-content-center">
                                    <span class="pricing-card-icon mr-4"><img src="${choosedPlan.image}"></span>
                                    <h2 class="text-dark-2 mb-0 font-weight-semibold">${choosedPlan.name}
                                    </h2>
                                </div>
                                <h3 class="text-dark-2">${choosedPlan_price}</h3>
                                <p class="text-center">${choosedPlan.id=="J0RwvxCWhZzQQD7Kc2Ll"? "Free":choosedPlan.expiryDay=="-1"? "{{ trans('lang.unlimited') }}":choosedPlan.expiryDay+" {{ trans('lang.days') }}"}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="subscription-card-right">
                    <div
                        class="d-flex justify-content-between align-items-center py-3 px-3 text-dark-2">
                        <span class="font-weight-medium">{{ trans('lang.validity') }}</span>
                        <span class="font-weight-semibold">${choosedPlan.id=="J0RwvxCWhZzQQD7Kc2Ll"? "Unlimited":choosedPlan.expiryDay=="-1"? "{{ trans('lang.unlimited') }}":choosedPlan.expiryDay+" {{ trans('lang.days') }}"}</span>
                    </div>
                    <div
                        class="d-flex justify-content-between align-items-center py-3 px-3 text-dark-2">
                        <span class="font-weight-medium">{{ trans('lang.price') }}</span>
                        <span class="font-weight-semibold">${choosedPlan_price}</span>
                    </div>
                    <div
                        class="d-flex justify-content-between align-items-center py-3 px-3 text-dark-2">
                        <span class="font-weight-medium">{{ trans('lang.bill_status') }}</span>
                        <span class="font-weight-semibold">{{ trans('lang.migrate_to_new_plan') }}</span>
                    </div>
                </div>
            </div>`;
        }
        $("#plan-details").html(html);
    }
    function chooseSubscriptionPlan(planId) {
        $("#changeSubscriptionModal").modal('hide');
        $("#checkoutSubscriptionModal").modal('show');
        showPlanDetail(planId);
    }
    async function finalCheckout() {
        let planId=$("#plan_id").val();
        if(planId!=undefined&&planId!=''&&planId!=null) {
            var userId=vendorAuthor;
            var vendorId=id;
            var id_order=database.collection('tmp').doc().id;
            var snapshot=await database.collection('subscription_plans').doc(planId).get();
            if(snapshot.exists) {
                var planData=snapshot.data();
                var createdAt=firebase.firestore.FieldValue.serverTimestamp();
                if(planData.expiryDay=="-1") {
                    var expiryDay=null
                } else {
                    var currentDate=new Date();
                    currentDate.setDate(currentDate.getDate()+parseInt(planData.expiryDay));
                    var expiryDay=firebase.firestore.Timestamp.fromDate(currentDate);
                }
                database.collection('users').doc(userId).update({
                    'subscription_plan': planData,
                    'subscriptionPlanId': planId,
                    'subscriptionExpiryDate': expiryDay
                })
                database.collection('vendors').doc(vendorId).update({
                    'subscription_plan': planData,
                    'subscriptionPlanId': planId,
                    'subscriptionExpiryDate': expiryDay,
                    'subscriptionTotalOrders':planData.orderLimit
                });
                database.collection('subscription_history').doc(id_order).set({
                    'id': id_order,
                    'user_id': userId,
                    'expiry_date': expiryDay,
                    'createdAt': createdAt,
                    'subscription_plan': planData,
                    'payment_type': "Manual Pay"
                }).then(async function(snapshot) {
                    window.location.reload();
                })
            }
        }
    }
    $('input[name="set_item_limit"]').on('change',function() {

        if($('#limited_item').is(':checked')) {
            $('.item-limit-div').removeClass('d-none');
        } else {
            $('.item-limit-div').addClass('d-none');
        }
    });
    $('input[name="set_order_limit"]').on('change',function() {
        if($('#limited_order').is(':checked')) {
            $('.order-limit-div').removeClass('d-none');
        } else {
            $('.order-limit-div').addClass('d-none');
        }
    });
     $("#updateLimitModal").on('shown.bs.modal',function() {
            database.collection('users').where('id','==',vendorAuthor).get().then(async function(snapshot){
                var data=snapshot.docs[0].data();
                if(data.subscription_plan.itemLimit!='-1') {
                    $("#limited_item").prop('checked',true);
                    $('.item-limit-div').removeClass('d-none');
                    $('#item_limit').val(data.subscription_plan.itemLimit);
                } else {
                    $("#unlimited_item").prop('checked',true);
                }
                if(data.subscription_plan.orderLimit!='-1') {
                    $("#limited_order").prop('checked',true);
                    $('.order-limit-div').removeClass('d-none');
                    $('#order_limit').val(data.subscription_plan.orderLimit);
                } else {
                    $("#unlimited_order").prop('checked',true);
                }
            })
     })
    $('.update-plan-limit').click(async function() {

        var set_item_limit=$('input[name="set_item_limit"]:checked').val();
        var item_limit=(set_item_limit=='limited')? $('#item_limit').val():'-1';
        var set_order_limit=$('input[name="set_order_limit"]:checked').val();
        var order_limit=(set_order_limit=='limited')? $('#order_limit').val():'-1';

        if(set_item_limit=='limited'&&$('#item_limit').val()=='') {
            $(".item_limit_err").html("<p>{{ trans('lang.enter_item_limit') }}</p>");
            return false;
        } else if(set_order_limit=='limited'&&$('#order_limit').val()=='') {
            $(".order_limit_err").html("<p>{{ trans('lang.enter_order_limit') }}</p>");
            return false;
        } else {
            await database.collection('users').doc(vendorAuthor).update({
                'subscription_plan.orderLimit': order_limit,
                'subscription_plan.itemLimit': item_limit,
            }).then(async function(result) {
                await database.collection('vendors').doc("{{$id}}").update({
                    'subscription_plan.orderLimit': order_limit,
                    'subscription_plan.itemLimit': item_limit,
                    'subscriptionTotalOrders':order_limit
                }).then(async function(result){
                    window.location.reload();
                })
            });
        }
    })
</script>
@endsection