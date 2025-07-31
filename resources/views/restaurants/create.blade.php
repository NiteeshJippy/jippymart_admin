@extends('layouts.app')

@section('content')
<?php
$countries = file_get_contents(public_path('countriesdata.json'));
$countries = json_decode($countries);
$countries = (array) $countries;
$newcountries = array();
$newcountriesjs = array();
foreach ($countries as $keycountry => $valuecountry) {
    $newcountries[$valuecountry->phoneCode] = $valuecountry;
    $newcountriesjs[$valuecountry->phoneCode] = $valuecountry->code;
}
?>
<div class="page-wrapper">
    <div class="row page-titles">

        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.restaurant_plural')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item"><a
                        href="{!! route('restaurants') !!}">{{trans('lang.restaurant_plural')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.create_restaurant')}}</li>
            </ol>
        </div>
        <div>

            <div class="card-body">
                <div id="data-table_processing" class="dataTables_processing panel panel-default"
                    style="display: none;">{{trans('lang.processing')}}
                </div>
                <div class="error_top"></div>
                <div class="row restaurant_payout_create">
                    <div class="restaurant_payout_create-inner">
                        <fieldset>
                            <legend>{{trans('lang.restaurant_details')}}</legend>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.restaurant_name')}}</label>
                                <div class="col-7">
                                    <input type="text" class="form-control restaurant_name" required>
                                    <div class="form-text text-muted">
                                        {{ trans("lang.restaurant_name_help") }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.vendor')}}</label>
                                <div class="col-7">
                                    <select id='restaurant_vendors' class="form-control" required>
                                        <option value="">{{ trans("lang.select_vendor") }}</option>
                                    </select>
                                    <div class="form-text text-muted">
                                        {{ trans("lang.vendor_help") }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.category')}}</label>
                                <div class="col-7">
                                <div id="selected_categories" class="mb-2"></div>
                                <input type="text" id="category_search" class="form-control mb-2" placeholder="Search categories...">
                                <select id='restaurant_cuisines' class="form-control" multiple required>
                                    <option value="">Select Cuisines</option>
                                    <!-- options populated dynamically -->
                                </select>
                                <div class="form-text text-muted">
                                    {{ trans("lang.restaurant_cuisines_help") }} (Hold Ctrl/Cmd to select multiple)
                                </div>
                                </div>
                            </div>                       
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">Vendor Cuisine</label>
                                <div class="col-7">
                                    <select id='restaurant_vendor_cuisines' class="form-control" required>
                                        <option value="">Select Vendor Cuisine</option>
                                    </select>
                                    <div class="form-text text-muted">
                                        Please select the vendor cuisine.
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row width-50">
                            <label class="col-3 control-label">Admin Commission</label>
                             <div class="col-7">
                             <input type="number" class="form-control admin_commission" name="admin_commission" min="0" step="0.01">
                                  <div class="form-text text-muted">
                               Enter the admin commission percentage or amount.
                              </div>
                             </div>
                            </div>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.restaurant_phone')}}</label>
                                <div class="col-md-12">
                                    <div class="phone-box position-relative">
                                        <select name="country" id="country_selector1">
                                            <?php foreach ($newcountries as $keycy => $valuecy) { ?>
                                            <?php    $selected = ""; ?>
                                            <option <?php    echo $selected; ?> code="<?php    echo $valuecy->code; ?>"
                                                value="<?php    echo $keycy; ?>">
                                                +<?php    echo $valuecy->phoneCode; ?> {{$valuecy->countryName}}
                                            </option>
                                            <?php } ?>
                                        </select>


                                        <input type="text" class="form-control restaurant_phone"
                                            onkeypress="return chkAlphabets2(event,'error2')">
                                        <div id="error2" class="err"></div>
                                    </div>
                                </div>
                                <div class="form-text text-muted">
                                    {{ trans("lang.restaurant_phone_help") }}
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.restaurant_address')}}</label>
                                <div class="col-7">
                                    <input type="text" class="form-control restaurant_address" required>
                                    <div class="form-text text-muted">
                                        {{ trans("lang.restaurant_address_help") }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.zone')}}<span
                                        class="required-field"></span></label>
                                <div class="col-7">
                                    <select id='zone' class="form-control">
                                        <option value="">{{ trans("lang.select_zone") }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row width-50" style="display:none">
                                <label class="col-4 control-label">{{ trans('lang.commission_type')}}</label>
                                <div class="col-7">
                                    <select class="form-control commission_type" id="commission_type">
                                        <option value="Percent">{{trans('lang.coupon_percent')}}</option>
                                        <option value="Fixed">{{trans('lang.coupon_fixed')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row width-50" style="display:none">
                                <label class="col-4 control-label">{{ trans('lang.admin_commission')}}</label>
                                <div class="col-7">
                                    <input type="number" value="0" class="form-control commission_fix">
                                </div>
                            </div>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.restaurant_latitude')}}</label>
                                <div class="col-7">
                                    <input class="form-control restaurant_latitude" type="number" min="-90" max="90">
                                    <div class="form-text text-muted">
                                        {{ trans("lang.restaurant_latitude_help") }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.restaurant_longitude')}}</label>
                                <div class="col-7">
                                    <input class="form-control restaurant_longitude" type="number" min="-180" max="180">
                                    <div class="form-text text-muted">
                                        {{ trans("lang.restaurant_longitude_help") }}
                                    </div>
                                </div>
                                <div class="form-text text-muted ml-3">
                                    Don't Know your cordinates ? use <a target="_blank"
                                        href="https://www.latlong.net/">Latitude and Longitude Finder</a>
                                </div>
                            </div>
                            <div class="form-group row width-100">
                                <label class="col-3 control-label ">{{trans('lang.restaurant_description')}}</label>
                                <div class="col-7">
                                    <textarea rows="7" class="restaurant_description form-control"
                                        id="restaurant_description"></textarea>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend>{{trans('lang.gallery')}}</legend>

                            <div class="form-group row width-50 restaurant_image">
                                <div class="">
                                    <div id="photos"></div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div>
                                    <input type="file" id="galleryImage" onChange="handleFileSelect(event,'photos')">
                                    <div id="uploding_image_photos"></div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset style="display:none;">
                            <legend>{{trans('lang.services')}}</legend>

                            <div class="form-group row">

                                <div class="form-check width-100">
                                    <input type="checkbox" id="Free_Wi_Fi">
                                    <label class="col-3 control-label"
                                        for="Free_Wi_Fi">{{trans('lang.free_wi_fi')}}</label>
                                </div>
                                <div class="form-check width-100">
                                    <input type="checkbox" id="Good_for_Breakfast">
                                    <label class="col-3 control-label"
                                        for="Good_for_Breakfast">{{trans('lang.good_for_breakfast')}}</label>
                                </div>
                                <div class="form-check width-100">
                                    <input type="checkbox" id="Good_for_Dinner">
                                    <label class="col-3 control-label"
                                        for="Good_for_Dinner">{{trans('lang.good_for_dinner')}}</label>
                                </div>
                                <div class="form-check width-100">
                                    <input type="checkbox" id="Good_for_Lunch">
                                    <label class="col-3 control-label"
                                        for="Good_for_Lunch">{{trans('lang.good_for_lunch')}}</label>
                                </div>

                                <div class="form-check width-100">
                                    <input type="checkbox" id="Live_Music">
                                    <label class="col-3 control-label"
                                        for="Live_Music">{{trans('lang.live_music')}}</label>
                                </div>

                                <div class="form-check width-100">
                                    <input type="checkbox" id="Outdoor_Seating">
                                    <label class="col-3 control-label"
                                        for="Outdoor_Seating">{{trans('lang.outdoor_seating')}}</label>
                                </div>

                                <div class="form-check width-100">
                                    <input type="checkbox" id="Takes_Reservations">
                                    <label class="col-3 control-label"
                                        for="Takes_Reservations">{{trans('lang.takes_reservations')}}</label>
                                </div>

                                <div class="form-check width-100">
                                    <input type="checkbox" id="Vegetarian_Friendly">
                                    <label class="col-3 control-label"
                                        for="Vegetarian_Friendly">{{trans('lang.vegetarian_friendly')}}</label>
                                </div>

                            </div>
                        </fieldset>

                        <fieldset>
                            <legend>{{trans('lang.working_hours')}}</legend>

                            <div class="form-group row">

                                <div class="form-group row width-100">
                                    <div class="col-7">
                                        <button type="button" class="btn btn-primary  add_working_hours_restaurant_btn">
                                            <i></i>{{trans('lang.add_working_hours')}}
                                        </button>
                                    </div>
                                </div>
                                <div class="working_hours_div" style="display:none">


                                    <div class="form-group row mb-0">
                                        <label class="col-1 control-label">{{trans('lang.sunday')}}</label>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary add_more_sunday"
                                                onclick="addMorehour('Sunday','sunday', '1')">
                                                {{trans('lang.add_more')}}
                                            </button>
                                        </div>
                                    </div>


                                    <div class="restaurant_discount_options_Sunday_div restaurant_discount mb-5"
                                        style="display:none">


                                        <table class="booking-table" id="working_hour_table_Sunday">
                                            <tr>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.from')}}</label>
                                                </th>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.to')}}</label>
                                                </th>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.actions')}}</label>
                                                </th>
                                            </tr>

                                        </table>

                                    </div>

                                    <div class="form-group row mb-0">
                                        <label class="col-1 control-label">{{trans('lang.monday')}}</label>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary add_more_sunday"
                                                onclick="addMorehour('Monday','monday', '1')">
                                                {{trans('lang.add_more')}}
                                            </button>
                                        </div>
                                    </div>

                                    <div class="restaurant_discount_options_Monday_div restaurant_discount mb-5"
                                        style="display:none">

                                        <table class="booking-table" id="working_hour_table_Monday">
                                            <tr>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.from')}}</label>
                                                </th>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.to')}}</label>
                                                </th>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.actions')}}</label>
                                                </th>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <label class="col-1 control-label">{{trans('lang.tuesday')}}</label>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary"
                                                onclick="addMorehour('Tuesday','tuesday', '1')">
                                                {{trans('lang.add_more')}}
                                            </button>
                                        </div>
                                    </div>

                                    <div class="restaurant_discount_options_Tuesday_div restaurant_discount mb-5"
                                        style="display:none">

                                        <table class="booking-table" id="working_hour_table_Tuesday">
                                            <tr>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.from')}}</label>
                                                </th>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.to')}}</label>
                                                </th>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.actions')}}</label>
                                                </th>
                                            </tr>

                                        </table>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <label class="col-1 control-label">{{trans('lang.wednesday')}}</label>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary"
                                                onclick="addMorehour('Wednesday','wednesday', '1')">
                                                {{trans('lang.add_more')}}
                                            </button>
                                        </div>
                                    </div>


                                    <div class="restaurant_discount_options_Wednesday_div restaurant_discount mb-5"
                                        style="display:none">
                                        <table class="booking-table" id="working_hour_table_Wednesday">
                                            <tr>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.from')}}</label>
                                                </th>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.to')}}</label>
                                                </th>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.actions')}}</label>
                                                </th>
                                            </tr>

                                        </table>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <label class="col-1 control-label">{{trans('lang.thursday')}}</label>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary"
                                                onclick="addMorehour('Thursday','thursday', '1')">
                                                {{trans('lang.add_more')}}
                                            </button>
                                        </div>
                                    </div>

                                    <div class="restaurant_discount_options_Thursday_div restaurant_discount mb-5"
                                        style="display:none">
                                        <table class="booking-table" id="working_hour_table_Thursday">
                                            <tr>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.from')}}</label>
                                                </th>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.to')}}</label>
                                                </th>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.actions')}}</label>
                                                </th>
                                            </tr>

                                        </table>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <label class="col-1 control-label">{{trans('lang.friday')}}</label>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary"
                                                onclick="addMorehour('Friday','friday', '1')">
                                                {{trans('lang.add_more')}}
                                            </button>
                                        </div>
                                    </div>
                                    <div class="restaurant_discount_options_Friday_div restaurant_discount mb-5"
                                        style="display:none">
                                        <table class="booking-table" id="working_hour_table_Friday">
                                            <tr>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.from')}}</label>
                                                </th>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.to')}}</label>
                                                </th>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.actions')}}</label>
                                                </th>
                                            </tr>

                                        </table>
                                    </div>


                                    <div class="form-group row mb-0">
                                        <label class="col-1 control-label">{{trans('lang.Saturday')}}</label>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary"
                                                onclick="addMorehour('Saturday','Saturday','1')">
                                                {{trans('lang.add_more')}}
                                            </button>
                                        </div>
                                    </div>
                                    <div class="restaurant_discount_options_Saturday_div restaurant_discount mb-5"
                                        style="display:none">
                                        <table class="booking-table" id="working_hour_table_Saturday">
                                            <tr>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.from')}}</label>
                                                </th>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.to')}}</label>
                                                </th>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.actions')}}</label>
                                                </th>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </fieldset>


                        <fieldset>
                            <legend>{{trans('restaurant')}} {{trans('lang.active_deactive')}}</legend>
                            <div class="form-group row">
                                <div class="form-group row width-50">
                                    <div class="form-check width-100">
                                        <input type="checkbox" id="is_open" checked>
                                        <label class="col-3 control-label" for="is_open">{{ trans('lang.open_closed') }}</label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        

                        <fieldset id="dine_in_div" style="display: none;">
                            <legend>{{trans('lang.dine_in_future_setting')}}</legend>

                            <div class="form-group row">

                                <div class="form-group row width-100">
                                    <div class="form-check width-100">
                                        <input type="checkbox" id="dine_in_feature" class="">
                                        <label class="col-3 control-label"
                                            for="dine_in_feature">{{trans('lang.enable_dine_in_feature')}}</label>
                                    </div>
                                </div>
                                <div class="divein_div" style="display:none">


                                    <div class="form-group row width-50">
                                        <label class="col-3 control-label">{{trans('lang.Opening_Time')}}</label>
                                        <div class="col-7">
                                            <input type="time" class="form-control" id="openDineTime" required>
                                        </div>
                                    </div>

                                    <div class="form-group row width-50">
                                        <label class="col-3 control-label">{{trans('lang.Closing_Time')}}</label>
                                        <div class="col-7">
                                            <input type="time" class="form-control" id="closeDineTime" required>
                                        </div>
                                    </div>

                                    <div class="form-group row width-50">
                                        <label class="col-3 control-label">Cost</label>
                                        <div class="col-7">
                                            <input type="number" class="form-control restaurant_cost" required>
                                        </div>
                                    </div>
                                    <div class="form-group row width-100 restaurant_image">
                                        <label class="col-3 control-label">Menu Card Images</label>
                                        <div class="">
                                            <div id="photos_menu_card"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div>
                                            <input type="file" onChange="handleFileSelectMenuCard(event)">
                                            <div id="uploaded_image_menu"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </fieldset>

                        <fieldset style="display: none;">
                            <legend>{{trans('lang.deliveryCharge')}}</legend>

                            <div class="form-group row">

                                <div class="form-group row width-100">
                                    <label class="col-4 control-label">{{ trans('lang.delivery_charges_per')}} <span
                                            class="global_distance_type"></span></label>
                                    <div class="col-7">
                                        <input type="number" class="form-control" id="delivery_charges_per_km">
                                    </div>
                                </div>
                                <div class="form-group row width-100">
                                    <label class="col-4 control-label">{{trans('lang.minimum_delivery_charges')}}</label>
                                    <div class="col-7">
                                        <input type="number" class="form-control" id="minimum_delivery_charges">
                                    </div>
                                </div>
                                <div class="form-group row width-100">
                                    <label
                                        class="col-4 control-label">{{ trans('lang.minimum_delivery_charges_within')}}
                                        <span class="global_distance_type"></span></label>
                                    <div class="col-7">
                                        <input type="number" class="form-control"
                                            id="minimum_delivery_charges_within_km">
                                    </div>
                                </div>

                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>{{trans('lang.special_offer')}}</legend>

                            <div class="form-group row">

                                <div class="form-check width-100">
                                    <input type="checkbox" id="specialDiscountEnable">
                                    <label class="col-3 control-label"
                                        for="specialDiscountEnable">{{trans('lang.special_discount_enable')}}</label>
                                </div>
                                <div class="form-group row width-100">
                                    <div class="col-7">
                                        <button type="button" class="btn btn-primary  add_special_offer_restaurant_btn">
                                            <i></i>{{trans('lang.add_special_offer')}}
                                        </button>
                                    </div>
                                </div>
                                <div class="special_offer_div" style="display:none">


                                    <div class="form-group row">
                                        <label class="col-1 control-label">{{trans('lang.sunday')}}</label>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary add_more_sunday"
                                                onclick="addMoreButton('Sunday','sunday', '1')">
                                                {{trans('lang.add_more')}}
                                            </button>
                                        </div>
                                    </div>


                                    <div class="restaurant_discount_options_Sunday_div restaurant_discount"
                                        style="display:none">


                                        <table class="booking-table" id="special_offer_table_Sunday">
                                            <tr>
                                                <th>
                                                    <label
                                                        class="col-3 control-label">{{trans('lang.Opening_Time')}}</label>
                                                </th>
                                                <th>
                                                    <label
                                                        class="col-3 control-label">{{trans('lang.Closing_Time')}}</label>
                                                </th>
                                                <th>
                                                    <label
                                                        class="col-3 control-label">{{trans('lang.coupon_discount')}}</label>
                                                </th>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.coupon_discount')}}
                                                        {{trans('lang.type')}}</label>
                                                </th>

                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.actions')}}</label>
                                                </th>
                                            </tr>

                                        </table>

                                    </div>

                                    <div class="form-group row">
                                        <label class="col-1 control-label">{{trans('lang.monday')}}</label>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary add_more_sunday"
                                                onclick="addMoreButton('Monday','monday', '1')">
                                                {{trans('lang.add_more')}}
                                            </button>
                                        </div>
                                    </div>

                                    <div class="restaurant_discount_options_Monday_div restaurant_discount"
                                        style="display:none">

                                        <table class="booking-table" id="special_offer_table_Monday">
                                            <tr>
                                                <th>
                                                    <label
                                                        class="col-3 control-label">{{trans('lang.Opening_Time')}}</label>
                                                </th>
                                                <th>
                                                    <label
                                                        class="col-3 control-label">{{trans('lang.Closing_Time')}}</label>
                                                </th>
                                                <th>
                                                    <label
                                                        class="col-3 control-label">{{trans('lang.coupon_discount')}}</label>
                                                </th>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.coupon_discount')}}
                                                        {{trans('lang.type')}}</label>
                                                </th>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.actions')}}</label>
                                                </th>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-1 control-label">{{trans('lang.tuesday')}}</label>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary"
                                                onclick="addMoreButton('Tuesday','tuesday', '1')">
                                                {{trans('lang.add_more')}}
                                            </button>
                                        </div>
                                    </div>

                                    <div class="restaurant_discount_options_Tuesday_div restaurant_discount"
                                        style="display:none">

                                        <table class="booking-table" id="special_offer_table_Tuesday">
                                            <tr>
                                                <th>
                                                    <label
                                                        class="col-3 control-label">{{trans('lang.Opening_Time')}}</label>
                                                </th>
                                                <th>
                                                    <label
                                                        class="col-3 control-label">{{trans('lang.Closing_Time')}}</label>
                                                </th>
                                                <th>
                                                    <label
                                                        class="col-3 control-label">{{trans('lang.coupon_discount')}}</label>
                                                </th>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.coupon_discount')}}
                                                        {{trans('lang.type')}}</label>
                                                </th>

                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.actions')}}</label>
                                                </th>
                                            </tr>

                                        </table>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-1 control-label">{{trans('lang.wednesday')}}</label>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary"
                                                onclick="addMoreButton('Wednesday','wednesday', '1')">
                                                {{trans('lang.add_more')}}
                                            </button>
                                        </div>
                                    </div>


                                    <div class="restaurant_discount_options_Wednesday_div restaurant_discount"
                                        style="display:none">
                                        <table class="booking-table" id="special_offer_table_Wednesday">
                                            <tr>
                                                <th>
                                                    <label
                                                        class="col-3 control-label">{{trans('lang.Opening_Time')}}</label>
                                                </th>
                                                <th>
                                                    <label
                                                        class="col-3 control-label">{{trans('lang.Closing_Time')}}</label>
                                                </th>
                                                <th>
                                                    <label
                                                        class="col-3 control-label">{{trans('lang.coupon_discount')}}</label>
                                                </th>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.coupon_discount')}}
                                                        {{trans('lang.type')}}</label>
                                                </th>

                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.actions')}}</label>
                                                </th>
                                            </tr>

                                        </table>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-1 control-label">{{trans('lang.thursday')}}</label>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary"
                                                onclick="addMoreButton('Thursday','thursday', '1')">
                                                {{trans('lang.add_more')}}
                                            </button>
                                        </div>
                                    </div>

                                    <div class="restaurant_discount_options_Thursday_div restaurant_discount"
                                        style="display:none">
                                        <table class="booking-table" id="special_offer_table_Thursday">
                                            <tr>
                                                <th>
                                                    <label
                                                        class="col-3 control-label">{{trans('lang.Opening_Time')}}</label>
                                                </th>
                                                <th>
                                                    <label
                                                        class="col-3 control-label">{{trans('lang.Closing_Time')}}</label>
                                                </th>
                                                <th>
                                                    <label
                                                        class="col-3 control-label">{{trans('lang.coupon_discount')}}</label>
                                                </th>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.coupon_discount')}}
                                                        {{trans('lang.type')}}</label>
                                                </th>

                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.actions')}}</label>
                                                </th>
                                            </tr>

                                        </table>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-1 control-label">{{trans('lang.friday')}}</label>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary"
                                                onclick="addMoreButton('Friday','friday', '1')">
                                                {{trans('lang.add_more')}}
                                            </button>
                                        </div>
                                    </div>

                                    <div class="restaurant_discount_options_Friday_div restaurant_discount"
                                        style="display:none">
                                        <table class="booking-table" id="special_offer_table_Friday">
                                            <tr>
                                                <th>
                                                    <label
                                                        class="col-3 control-label">{{trans('lang.Opening_Time')}}</label>
                                                </th>
                                                <th>
                                                    <label
                                                        class="col-3 control-label">{{trans('lang.Closing_Time')}}</label>
                                                </th>
                                                <th>
                                                    <label
                                                        class="col-3 control-label">{{trans('lang.coupon_discount')}}</label>
                                                </th>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.coupon_discount')}}
                                                        {{trans('lang.type')}}</label>
                                                </th>

                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.actions')}}</label>
                                                </th>
                                            </tr>

                                        </table>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-1 control-label">{{trans('lang.Saturday')}}</label>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary"
                                                onclick="addMoreButton('Saturday','Saturday','1')">
                                                {{trans('lang.add_more')}}
                                            </button>
                                        </div>
                                    </div>
                                    <div class="restaurant_discount_options_Saturday_div restaurant_discount"
                                        style="display:none">
                                        <table class="booking-table" id="special_offer_table_Saturday">
                                            <tr>
                                                <th>
                                                    <label
                                                        class="col-3 control-label">{{trans('lang.Opening_Time')}}</label>
                                                </th>
                                                <th>
                                                    <label
                                                        class="col-3 control-label">{{trans('lang.Closing_Time')}}</label>
                                                </th>
                                                <th>
                                                    <label
                                                        class="col-3 control-label">{{trans('lang.coupon_discount')}}</label>
                                                </th>
                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.coupon_discount')}}
                                                        {{trans('lang.type')}}</label>
                                                </th>

                                                <th>
                                                    <label class="col-3 control-label">{{trans('lang.actions')}}</label>
                                                </th>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                            </div>

                        </fieldset>
                        <fieldset id="story_upload_div" style="display: none;">
                            <legend>Story</legend>

                            <div class="form-group row width-50 vendor_image">
                                <label class="col-3 control-label">Choose humbling GIF/Image</label>
                                <div class="">
                                    <div id="story_thumbnail"></div>
                                </div>
                            </div>


                            <div class="form-group row">
                                <div>
                                    <input type="file" id="file" onChange="handleStoryThumbnailFileSelect(event)">
                                    <div id="uploding_story_thumbnail"></div>
                                </div>
                            </div>


                            <div class="form-group row vendor_image">
                                <label class="col-3 control-label">Select Story Video</label>
                                <div class="">
                                    <div id="story_vedios" class="row"></div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div>
                                    <input type="file" id="video_file" onChange="handleStoryFileSelect(event)">
                                    <div id="uploding_story_video"></div>
                                </div>
                            </div>


                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="form-group col-12 text-center">
                <button type="button" class="btn btn-primary  save-form-btn"><i class="fa fa-save"></i>
                    {{trans('lang.save')}}
                </button>
                <a href="{!! route('restaurants') !!}" class="btn btn-default"><i
                        class="fa fa-undo"></i>{{trans('lang.cancel')}}</a>
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.1.1/compressor.min.js"
    integrity="sha512-VaRptAfSxXFAv+vx33XixtIVT9A/9unb1Q8fp63y1ljF+Sbka+eMJWoDAArdm7jOYuLQHVx5v60TQ+t3EA8weA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    var createdAt=firebase.firestore.FieldValue.serverTimestamp();
    var database=firebase.firestore();
    var ref_deliverycharge=database.collection('settings').doc("DeliveryCharge");
    var storageRef=firebase.storage().ref('images');
    var photo="";
    var menuPhotoCount=0;
    var restaurantMenuPhotos="";
    var restaurant_menu_photos=[];
    var restaurant_menu_filename=[];
    var restaurantPhoto='';
    var resPhotoFileName='';
    var restaurantOwnerId="";
    var restaurantOwnerOnline=false;
    var photocount=0;
    var restaurnt_photos=[];
    var restaurant_photos_filename=[];
    var workingHours=[];
    var timeslotworkSunday=[];
    var timeslotworkMonday=[];
    var timeslotworkTuesday=[];
    var timeslotworkWednesday=[];
    var timeslotworkFriday=[];
    var timeslotworkSaturday=[];
    var timeslotworkThursday=[];

    var specialDiscount=[];
    var timeslotSunday=[];
    var timeslotMonday=[];
    var timeslotTuesday=[];
    var timeslotWednesday=[];
    var timeslotFriday=[];
    var timeslotSaturday=[];
    var timeslotThursday=[];
    var storevideoDuration=0;

    var story_vedios=[];
    var story_thumbnail='';
    var story_thumbnail_filename='';
    var storyCount=0;
    var storyRef=firebase.storage().ref('Story');
    var storyImagesRef=firebase.storage().ref('Story/images');
    var restaurant_id=database.collection("tmp").doc().id;

    database.collection('settings').doc("AdminCommission").get().then(async function(snapshots) {
        var adminCommissionSettings=snapshots.data();
        $(".commission_fix").val(adminCommissionSettings.fix_commission);
        $("#commission_type").val(adminCommissionSettings.commissionType);
    });
    database.collection('settings').doc("story").get().then(async function(snapshots) {
        var story_data=snapshots.data();
        if(story_data.isEnabled) {
            $("#story_upload_div").show();
        }
        storevideoDuration=story_data.videoDuration;
    });

    database.collection('zone').where('publish','==',true).orderBy('name','asc').get().then(async function(snapshots) {
        snapshots.docs.forEach((listval) => {
            var data=listval.data();
            var area=[];
            data.area.forEach((location) => {
                area.push({'latitude': location.latitude,'longitude': location.longitude});
            });
            $('#zone').append($("<option></option>")
                .attr("value",data.id)
                .attr("data-area",JSON.stringify(area))
                .text(data.name));
        })
    });

    var email_templates=database.collection('email_templates').where('type','==','new_vendor_signup');

    var emailTemplatesData=null;

    var adminEmail='';

    var emailSetting=database.collection('settings').doc('emailSetting');


    database.collection('settings').doc("specialDiscountOffer").get().then(async function(snapshots) {
        var specialDiscountOffer=snapshots.data();
        specialDiscountOfferisEnable=specialDiscountOffer.isEnable;
    });


    var currentCurrency='';
    var currencyAtRight=false;
    var refCurrency=database.collection('currencies').where('isActive','==',true);
    refCurrency.get().then(async function(snapshots) {
        var currencyData=snapshots.docs[0].data();
        currentCurrency=currencyData.symbol;
        currencyAtRight=currencyData.symbolAtRight;
    });


    $(document).ready(async function() {

        jQuery("#country_selector1").select2({
            templateResult: formatState,
            templateSelection: formatState2,
            placeholder: "Select Country",
            allowClear: true
        });
    
        jQuery("#data-table_processing").show();

        await email_templates.get().then(async function(snapshots) {
            emailTemplatesData=snapshots.docs[0].data();
        });

        await emailSetting.get().then(async function(snapshots) {
            var emailSettingData=snapshots.data();

            adminEmail=emailSettingData.userName;
        });

        database.collection('vendor_categories').where('publish','==',true).get().then(async function(snapshots) {
            snapshots.docs.forEach((listval) => {
                var data=listval.data();
                $('#restaurant_cuisines').append($("<option></option>")
                    .attr("value",data.id)
                    .text(data.title));
            })
        });
        // Add vendor_cuisines dropdown population
        database.collection('vendor_cuisines').where('publish','==',true).get().then(async function(snapshots) {
            snapshots.docs.forEach((listval) => {
                var data = listval.data();
                $('#restaurant_vendor_cuisines').append($('<option></option>')
                    .attr('value', data.id)
                    .text(data.title));
            });
        }).catch(function(error) {
            console.error('Error fetching vendor_cuisines:', error);
        });
        database.collection('users').where('role','==','vendor').get().then(async function(snapshots) {
            console.log('Found vendors:', snapshots.docs.length);
            if (snapshots.docs.length === 0) {
                console.log('No vendors found in database');
                // Add a placeholder option
                $('#restaurant_vendors').append($("<option></option>")
                    .attr("value", "")
                    .text("No vendors available"));
            } else {
                snapshots.docs.forEach((listval) => {
                    var data = listval.data();
                    console.log('Vendor data:', data);
                    if ((data.vendorID == "" || data.vendorID == null) && data.firstName != "") {
                        $('#restaurant_vendors').append($("<option></option>")
                            .attr("value", data.id)
                            .text(data.firstName + " " + data.lastName));
                        console.log('Added vendor option:', data.firstName + " " + data.lastName, 'with ID:', data.id);
                    } else {
                        console.log('Skipping vendor:', data.firstName, '- vendorID:', data.vendorID);
                    }
                });
            }
            console.log('Total vendor options:', $('#restaurant_vendors option').length);
        }).catch(function(error) {
            console.error('Error fetching vendors:', error);
            // Add error option
            $('#restaurant_vendors').append($("<option></option>")
                .attr("value", "")
                .text("Error loading vendors"));
        });
        jQuery("#data-table_processing").hide();

        ref_deliverycharge.get().then(async function(snapshots_charge) {
            var deliveryChargeSettings=snapshots_charge.data();
            try {
                if(deliveryChargeSettings.vendor_can_modify) {
                    $("#delivery_charges_per_km").val(deliveryChargeSettings.delivery_charges_per_km);
                    $("#minimum_delivery_charges").val(deliveryChargeSettings.minimum_delivery_charges);
                    $("#minimum_delivery_charges_within_km").val(deliveryChargeSettings.minimum_delivery_charges_within_km);
                } else {
                    $("#delivery_charges_per_km").val(deliveryChargeSettings.delivery_charges_per_km);
                    $("#minimum_delivery_charges").val(deliveryChargeSettings.minimum_delivery_charges);
                    $("#minimum_delivery_charges_within_km").val(deliveryChargeSettings.minimum_delivery_charges_within_km);
                    $("#delivery_charges_per_km").prop('disabled',true);
                    $("#minimum_delivery_charges").prop('disabled',true);
                    $("#minimum_delivery_charges_within_km").prop('disabled',true);
                }
            } catch(error) {

            }
        });

        // 1. Filter dropdown options based on search
        $('#category_search').on('keyup', function() {
            var search = $(this).val().toLowerCase();
            $('#restaurant_cuisines option').each(function() {
                if ($(this).val() === "") {
                    $(this).show();
                    return;
                }
                var text = $(this).text().toLowerCase();
                if (text.indexOf(search) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // 2. When selecting from dropdown, add tag (multi-select support)
        $('#restaurant_cuisines').on('change', function() {
            updateSelectedCategoryTags();
        });

        // 3. Remove tag and unselect in dropdown
        $('#selected_categories').on('click', '.remove-tag', function() {
            var value = $(this).parent().data('value');
            $('#restaurant_cuisines option[value="' + value + '"]').prop('selected', false);
            updateSelectedCategoryTags();
        });

        // 4. Update tags display
        function updateSelectedCategoryTags() {
            var selected = $('#restaurant_cuisines').val() || [];
            var html = '';
            $('#restaurant_cuisines option:selected').each(function() {
                if ($(this).val() !== "") {
                    html += '<span class="selected-category-tag" data-value="' + $(this).val() + '">' +
                        $(this).text() +
                        '<span class="remove-tag">&times;</span></span>';
                }
            });
            $('#selected_categories').html(html);
        }

        // 5. Preselect for edit (if you have preselected IDs)
        // Example: var preselected = ['1','3'];
        // $('#restaurant_cuisines').val(preselected).trigger('change');
    })

    function checkLocationInZone(area,address_lng,address_lat) {
        var vertices_x=[];
        var vertices_y=[];
        for(j=0;j<area.length;j++) {
            var geopoint=area[j];
            vertices_x.push(geopoint.longitude);
            vertices_y.push(geopoint.latitude);
        }
        var points_polygon=(vertices_x.length)-1;
        if(is_in_polygon(points_polygon,vertices_x,vertices_y,address_lng,address_lat)) {
            return true;
        } else {
            return false;
        }
    }

    function is_in_polygon($points_polygon,$vertices_x,$vertices_y,$longitude_x,$latitude_y) {
        $i=$j=$c=$point=0;
        for($i=0,$j=$points_polygon;$i<$points_polygon;$j=$i++) {
            $point=$i;
            if($point==$points_polygon)
                $point=0;
            if((($vertices_y[$point]>$latitude_y!=($vertices_y[$j]>$latitude_y))&&($longitude_x<($vertices_x[$j]-$vertices_x[$point])*($latitude_y-$vertices_y[$point])/($vertices_y[$j]-$vertices_y[$point])+$vertices_x[$point])))
                $c=!$c;
        }
        return $c;
    }

    $(".save-form-btn").click(async function() {

        $(".error_top").hide();

        var restaurantname=$(".restaurant_name").val();
        // Handle multiple category selection
        var categoryIDs = $("#restaurant_cuisines").val() || [];
        var categoryTitles = [];
        $("#restaurant_cuisines option:selected").each(function() {
            if ($(this).val() !== "") {
                categoryTitles.push($(this).text());
            }
        });
        var restaurantOwner=$("#restaurant_owners option:selected").val();
        var address=$(".restaurant_address").val();
        var latitude=parseFloat($(".restaurant_latitude").val());
        var longitude=parseFloat($(".restaurant_longitude").val());
        var description=$(".restaurant_description").val();
        var rescountry_code=$("#country_selector1").val();
        var phonenumber=$(".restaurant_phone").val();
        var commissionType=$("#commission_type").val();
        var fixCommission=$(".commission_fix").val();
        var zoneId=$('#zone option:selected').val();
        var zoneArea=$('#zone option:selected').data('area');
        var isInZone=false;
        if(zoneId&&zoneArea) {
            isInZone=checkLocationInZone(zoneArea,longitude,latitude);
        }

        var enabledDiveInFuture=$("#dine_in_feature").is(':checked');
        var isOpen = $("#is_open").is(':checked');
        var specialDiscountEnable=false;
        var restaurantCost=$(".restaurant_cost").val();

        var selectedOwnerId = $("#restaurant_vendors option:selected").val();
        console.log('Selected vendor ID:', selectedOwnerId);
        console.log('All vendor options:', $('#restaurant_vendors option').map(function() { return {value: $(this).val(), text: $(this).text()}; }).get());
        console.log('Selected vendor text:', $("#restaurant_vendors option:selected").text());
        
        // Check if vendor is selected and valid
        if(selectedOwnerId && selectedOwnerId != '' && selectedOwnerId != null && selectedOwnerId != undefined && selectedOwnerId !== "No vendors available" && selectedOwnerId !== "Error loading vendors") {
            var vendorData=await getOwnerDetails(selectedOwnerId);
            console.log('Vendor data:', vendorData);
            if(vendorData != undefined && vendorData != null && vendorData != ""){
                var user_name=vendorData.firstName +" "+vendorData.lastName;
                var subscriptionPlanId = vendorData.subscriptionPlanId ? vendorData.subscriptionPlanId : null;
                var subscription_plan = vendorData.subscription_plan ? vendorData.subscription_plan : null;
                var subscriptionOrderLimit=vendorData.subscription_plan ? vendorData.subscription_plan.orderLimit : null;
                var subscriptionExpiryDate = vendorData.subscriptionExpiryDate ? vendorData.subscriptionExpiryDate : null;
                var user_id = vendorData.id;
                var user_profilepic = vendorData.profilePictureURL ? vendorData.profilePictureURL : null;
            } else{
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.owner_detail_not_fount')}}</p>");
                window.scrollTo(0,0);
                return;
            }
        } else {
            console.log('No vendor selected, using admin as default...');
            // For now, let's allow creating restaurant without vendor selection
            // You can modify this logic based on your requirements
            var user_name = "Admin Created";
            var subscriptionPlanId = null;
            var subscription_plan = null;
            var subscriptionOrderLimit = null;
            var subscriptionExpiryDate = null;
            var user_id = "admin_created";
            var user_profilepic = null;
            
            console.log('Using default values for vendor data');
        }
      

        var openDineTime=$("#openDineTime").val();
        var openDineTime_val=$("#openDineTime").val();
        if(openDineTime) {
            openDineTime=new Date('1970-01-01T'+openDineTime+'Z')
                .toLocaleTimeString('en-US',{
                    timeZone: 'UTC',
                    hour12: true,
                    hour: 'numeric',
                    minute: 'numeric'
                });
        }

        var closeDineTime=$("#closeDineTime").val();
        var closeDineTime_val=$("#closeDineTime").val();
        if(closeDineTime) {
            closeDineTime=new Date('1970-01-01T'+closeDineTime+'Z')
                .toLocaleTimeString('en-US',{
                    timeZone: 'UTC',
                    hour12: true,
                    hour: 'numeric',
                    minute: 'numeric'
                });
        }

        if($("#specialDiscountEnable").is(':checked')) {
            specialDiscountEnable=true;
        }
        var specialDiscount=[];

        var sunday={
            'day': 'Sunday',
            'timeslot': timeslotSunday
        };
        var monday={
            'day': 'Monday',
            'timeslot': timeslotMonday
        };
        var tuesday={
            'day': 'Tuesday',
            'timeslot': timeslotTuesday
        };
        var wednesday={
            'day': 'Wednesday',
            'timeslot': timeslotWednesday
        };
        var thursday={
            'day': 'Thursday',
            'timeslot': timeslotThursday
        };
        var friday={
            'day': 'Friday',
            'timeslot': timeslotFriday
        };
        var Saturday={
            'day': 'Saturday',
            'timeslot': timeslotSaturday
        };

        specialDiscount.push(monday);
        specialDiscount.push(tuesday);
        specialDiscount.push(wednesday);
        specialDiscount.push(thursday);
        specialDiscount.push(friday);
        specialDiscount.push(Saturday);
        specialDiscount.push(sunday);

        var workingHours=[];

        var sunday={
            'day': 'Sunday',
            'timeslot': timeslotworkSunday
        };
        var monday={
            'day': 'Monday',
            'timeslot': timeslotworkMonday
        };
        var tuesday={
            'day': 'Tuesday',
            'timeslot': timeslotworkTuesday
        };
        var wednesday={
            'day': 'Wednesday',
            'timeslot': timeslotworkWednesday
        };
        var thursday={
            'day': 'Thursday',
            'timeslot': timeslotworkThursday
        };
        var friday={
            'day': 'Friday',
            'timeslot': timeslotworkFriday
        };
        var Saturday={
            'day': 'Saturday',
            'timeslot': timeslotworkSaturday
        };

        workingHours.push(monday);
        workingHours.push(tuesday);
        workingHours.push(wednesday);
        workingHours.push(thursday);
        workingHours.push(friday);
        workingHours.push(Saturday);
        workingHours.push(sunday);

        // Check if any working hours are set, if not set default times
        var hasWorkingHours = false;
        for (var i = 0; i < workingHours.length; i++) {
            if (workingHours[i].timeslot.length > 0) {
                hasWorkingHours = true;
                break;
            }
        }
        
        // If no working hours are set, add default times (9:30 AM to 10:00 PM) for all days
        if (!hasWorkingHours) {
            var defaultTimeslot = {
                'from': '09:30',
                'to': '22:00'
            };
            
            for (var i = 0; i < workingHours.length; i++) {
                workingHours[i].timeslot = [defaultTimeslot];
            }
        }

        var Free_Wi_Fi="No";
        if($("#Free_Wi_Fi").is(":checked")) {
            Free_Wi_Fi="Yes";
        }
        var Good_for_Breakfast="No";
        if($("#Good_for_Breakfast").is(':checked')) {
            Good_for_Breakfast="Yes";
        }
        var Good_for_Dinner="No";
        if($("#Good_for_Dinner").is(':checked')) {
            Good_for_Dinner="Yes";
        }
        var Good_for_Lunch="No";
        if($("#Good_for_Lunch").is(':checked')) {
            Good_for_Lunch="Yes";
        }
        var Live_Music="No";
        if($("#Live_Music").is(':checked')) {
            Live_Music="Yes";
        }
        var Outdoor_Seating="No";
        if($("#Outdoor_Seating").is(':checked')) {
            Outdoor_Seating="Yes";
        }
        var Takes_Reservations="No";
        if($("#Takes_Reservations").is(':checked')) {
            Takes_Reservations="Yes";
        }
        var Vegetarian_Friendly="No";
        if($("#Vegetarian_Friendly").is(':checked')) {
            Vegetarian_Friendly="Yes";
        }

        var filters_new={
            "Free Wi-Fi": Free_Wi_Fi,
            "Good for Breakfast": Good_for_Breakfast,
            "Good for Dinner": Good_for_Dinner,
            "Good for Lunch": Good_for_Lunch,
            "Live Music": Live_Music,
            "Outdoor Seating": Outdoor_Seating,
            "Takes Reservations": Takes_Reservations,
            "Vegetarian Friendly": Vegetarian_Friendly
        };

        var vendorCuisine = $("#restaurant_vendor_cuisines option:selected").val();

        if(restaurantname=='') {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.restaurant_name_error')}}</p>");
            window.scrollTo(0,0);
        } else if(categoryIDs.length === 0 || (categoryIDs.length === 1 && categoryIDs[0] === '')) {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.restaurant_cuisine_error')}}</p>");
            window.scrollTo(0,0);
        } else if(vendorCuisine=='') {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>Please select the vendor cuisine.</p>");
            window.scrollTo(0,0);
        } else if(!rescountry_code) {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.select_rescountry_code')}}</p>");
            window.scrollTo(0,0);
        } else if(phonenumber=='') {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.restaurant_phone_error')}}</p>");
            window.scrollTo(0,0);
        } else if(address=='') {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.restaurant_address_error')}}</p>");
            window.scrollTo(0,0);
        } else if(zoneId=='') {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.select_zone_help')}}</p>");
            window.scrollTo(0,0);
        } else if(isNaN(latitude)) {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.restaurant_lattitude_error')}}</p>");
            window.scrollTo(0,0);
        } else if(latitude<-90||latitude>90) {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.restaurant_lattitude_limit_error')}}</p>");
            window.scrollTo(0,0);
        } else if(isNaN(longitude)) {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.restaurant_longitude_error')}}</p>");
            window.scrollTo(0,0);
        } else if(longitude<-180||longitude>180) {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.restaurant_longitude_limit_error')}}</p>");
            window.scrollTo(0,0);
        } else if(isInZone==false) {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.invalid_location_zone')}}</p>");
            window.scrollTo(0,0);
        } else if(description=='') {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.restaurant_description_error')}}</p>");
            window.scrollTo(0,0);

        } else {
            console.log('Starting restaurant creation process...');
            console.log('Restaurant name:', restaurantname);
            console.log('Category IDs:', categoryIDs);
            console.log('Category Titles:', categoryTitles);
            console.log('Vendor Cuisine:', vendorCuisine);
            console.log('Working Hours:', workingHours);
            jQuery("#data-table_processing").show();
            
            // Add timeout to prevent infinite loading
            var saveTimeout = setTimeout(function() {
                jQuery("#data-table_processing").hide();
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>Request timed out. Please try again.</p>");
                window.scrollTo(0,0);
            }, 60000); // 60 seconds timeout
    
            if(story_vedios.length>0||story_thumbnail!='') {
                if(story_vedios.length>0&&story_thumbnail=='') {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{trans('lang.story_error')}}</p>");
                    window.scrollTo(0,0);
                    return false;
                } else if(story_thumbnail&&story_vedios.length==0) {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{trans('lang.story_error')}}</p>");
                    window.scrollTo(0,0);
                    return false;
                } else {
                    await storeStoryImageData().then(async (IMG) => {
                        database.collection('story').doc(restaurant_id).set({
                            'createdAt': new Date(),
                            'vendorID': restaurant_id,
                            'videoThumbnail': IMG.storyThumbnailImage,
                            'videoUrl': story_vedios,
                        });
                    }).catch(err => {
                        clearTimeout(saveTimeout);
                        jQuery("#data-table_processing").hide();
                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append("<p>"+err+"</p>");
                        window.scrollTo(0,0);
                    });

                }
            }

            var delivery_charges_per_km=parseInt($("#delivery_charges_per_km").val());
            var minimum_delivery_charges=parseInt($("#minimum_delivery_charges").val());
            var minimum_delivery_charges_within_km=parseInt($("#minimum_delivery_charges_within_km").val());
            var DeliveryCharge={
                'delivery_charges_per_km': delivery_charges_per_km,
                'minimum_delivery_charges': minimum_delivery_charges,
                'minimum_delivery_charges_within_km': minimum_delivery_charges_within_km
            };
            const adminCommission={"commissionType": commissionType,"fix_commission": parseInt(fixCommission),"isEnabled": true};
            try {
                // Store all images
                console.log('Storing images...');
                const IMG = await storeImageData();
                console.log('Story image stored successfully');
                
                const GalleryIMG = await storeGalleryImageData();
                console.log('Gallery images stored successfully:', GalleryIMG);
                
                const MenuIMG = await storeMenuImageData();
                console.log('Menu images stored successfully:', MenuIMG);
                
                // Create restaurant data
                const coordinates = new firebase.firestore.GeoPoint(latitude, longitude);
                const restaurantData = {
                    'title': restaurantname,
                    'description': description,
                    'latitude': latitude,
                    'longitude': longitude,
                    'location': address,
                    'photo': (Array.isArray(GalleryIMG) && GalleryIMG.length > 0) ? GalleryIMG[0] : null,
                    'categoryID': categoryIDs,
                    'categoryTitle': categoryTitles,
                    'vendorCuisineID': vendorCuisine,
                    'countryCode': rescountry_code,
                    'phonenumber': phonenumber,
                    'coordinates': coordinates,
                    'id': restaurant_id,
                    'filters': filters_new,
                    'photos': GalleryIMG,
                    'author': user_id,
                    'authorName': user_name,
                    'authorProfilePic': user_profilepic,
                    'isOpen': isOpen,
                    'hidephotos': false,
                    'createdAt': createdAt,
                    'enabledDiveInFuture': enabledDiveInFuture,
                    'restaurantMenuPhotos': MenuIMG,
                    'restaurantCost': restaurantCost,
                    'openDineTime': openDineTime,
                    'closeDineTime': closeDineTime,
                    'workingHours': workingHours,
                    'specialDiscount': specialDiscount,
                    'specialDiscountEnable': specialDiscountEnable,
                    'zoneId': zoneId,
                    'adminCommission': adminCommission,
                    'subscription_plan': subscription_plan,
                    'subscriptionPlanId': subscriptionPlanId,
                    'subscriptionExpiryDate': subscriptionExpiryDate,
                    'subscriptionTotalOrders': subscriptionOrderLimit
                };
                
                // Update user vendorID if not admin created
                if (user_id !== "admin_created") {
                    console.log('Updating user vendorID:', user_id, 'with restaurant ID:', restaurant_id);
                    await database.collection('users').doc(user_id).update({
                        'vendorID': restaurant_id,
                    });
                    console.log('User vendorID updated successfully');
                } else {
                    console.log('Skipping user update for admin created restaurant');
                }
                
                // Create restaurant
                await database.collection('vendors').doc(restaurant_id).set(restaurantData);
                console.log('Restaurant created successfully');
                
                clearTimeout(saveTimeout);
                jQuery("#data-table_processing").hide();
                window.location.href = '{{ route("restaurants")}}';
                
            } catch (error) {
                clearTimeout(saveTimeout);
                console.error('Error in restaurant creation process:', error);
                jQuery("#data-table_processing").hide();
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>Error creating restaurant: " + error.message + "</p>");
                window.scrollTo(0, 0);
            }
        
        }

    })


    $(document).on("click",".remove-btn",function() {
        var id=$(this).attr('data-id');
        var photo_remove=$(this).attr('data-img');

        $("#photo_"+id).remove();
        index=restaurnt_photos.indexOf(photo_remove);
        if(index>-1) {
            restaurnt_photos.splice(index,1); // 2nd parameter means remove one item only
        }

    });
    $(document).on("click",".remove-menu-btn",function() {
        var id=$(this).attr('data-id');
        var photo_remove=$(this).attr('data-img');

        $("#photo_menu"+id).remove();
        index=restaurant_menu_photos.indexOf(photo_remove);
        if(index>-1) {
            restaurant_menu_photos.splice(index,1); // 2nd parameter means remove one item only
        }

    });

    async function storeImageData() {
        var newPhoto=[];
        newPhoto['storyThumbnailImage']='';
        try {
            if(story_thumbnail!='') {
                story_thumbnail=story_thumbnail.replace(/^data:image\/[a-z]+;base64,/,"")
                var uploadTask=await storageRef.child(story_thumbnail_filename).putString(story_thumbnail,'base64',{
                    contentType: 'image/jpg'
                });
                var downloadURL=await uploadTask.ref.getDownloadURL();
                newPhoto['storyThumbnailImage']=downloadURL;
            }
        } catch(error) {
            console.log("ERR ===",error);
        }
        return newPhoto;
    }

    function handleFileSelect(evt,type) {
        var f=evt.target.files[0];
        var reader=new FileReader();

        new Compressor(f,{
            quality: <?php echo env('IMAGE_COMPRESSOR_QUALITY', 0.8); ?>,
            success(result) {
                f=result;

                reader.onload=(function(theFile) {
                    return function(e) {

                        var filePayload=e.target.result;
                        var val=f.name;
                        var ext=val.split('.')[1];
                        var docName=val.split('fakepath')[1];
                        var filename=(f.name).replace(/C:\\fakepath\\/i,'')

                        var timestamp=Number(new Date());
                        var filename=filename.split('.')[0]+"_"+timestamp+'.'+ext;
                        photo=filePayload;
                        // if (type == "photo") {
                        //     restaurantPhoto = filePayload;
                        //     resPhotoFileName = filename;
                        // }
                        if(photo) {
                            // if (type == 'photo') {
                            //     $("#uploaded_image").attr('src', photo);
                            //     $(".uploaded_image").show();
                            // } else 
                            if(type=='photos') {

                                photocount++;
                                photos_html='<span class="image-item" id="photo_'+photocount+'"><span class="remove-btn" data-id="'+photocount+'" data-img="'+photo+'"><i class="fa fa-remove"></i></span><img width="100px" id="" height="auto" src="'+photo+'"></span>';
                                $("#photos").append(photos_html);
                                restaurnt_photos.push(photo);
                                restaurant_photos_filename.push(filename);
                            }
                        }
                    };
                })(f);
                reader.readAsDataURL(f);
            },
            error(err) {
                console.log(err.message);
            },
        });
    }

    function chkAlphabets2(event,msg) {
        if(!(event.which>=48&&event.which<=57)
        ) {
            document.getElementById(msg).innerHTML="Accept only Number";
            return false;
        }
        else {
            document.getElementById(msg).innerHTML="";
            return true;
        }
    }

    function formatState(state) {
        if(!state.id) {
            return state.text;
        }
        var baseUrl="<?php echo URL::to('/');?>/scss/icons/flag-icon-css/flags";
        var $state=$(
            '<span><img src="'+baseUrl+'/'+newcountriesjs[state.element.value].toLowerCase()+'.svg" class="img-flag" /> '+state.text+'</span>'
        );
        return $state;
    }

    function formatState2(state) {
        if(!state.id) {
            return state.text;
        }

        var baseUrl="<?php echo URL::to('/');?>/scss/icons/flag-icon-css/flags"
        var $state=$(
            '<span><img class="img-flag" /> <span></span></span>'
        );
        $state.find("span").text(state.text);
        $state.find("img").attr("src",baseUrl+"/"+newcountriesjs[state.element.value].toLowerCase()+".svg");

        return $state;
    }
    var newcountriesjs='<?php echo json_encode($newcountriesjs); ?>';
    var newcountriesjs=JSON.parse(newcountriesjs);

    async function storeGalleryImageData() {
        var newPhoto=[];
        try {
            if(restaurnt_photos.length>0) {
                const photoPromises=restaurnt_photos.map(async (resPhoto,index) => {
                    resPhoto=resPhoto.replace(/^data:image\/[a-z]+;base64,/,"");
                    const uploadTask=await storageRef.child(restaurant_photos_filename[index]).putString(resPhoto,'base64',{
                        contentType: 'image/jpg'
                    });
                    const downloadURL=await uploadTask.ref.getDownloadURL();
                    return {index,downloadURL};
                });
                const photoResults=await Promise.all(photoPromises);
                photoResults.sort((a,b) => a.index-b.index);
                newPhoto=photoResults.map(photo => photo.downloadURL);
            }
            return newPhoto;
        } catch(error) {
            console.error('Error storing gallery images:', error);
            throw error;
        }
    }

    function handleFileSelectGallary(evt,type) {
        var f=evt.target.files[0];
        var reader=new FileReader();

        new Compressor(f,{
            quality: <?php echo env('IMAGE_COMPRESSOR_QUALITY', 0.8); ?>,
            success(result) {
                f=result;

                reader.onload=(function(theFile) {
                    return function(e) {

                        var filePayload=e.target.result;
                        var val=f.name;
                        var ext=val.split('.')[1];
                        var docName=val.split('fakepath')[1];
                        var filename=(f.name).replace(/C:\\fakepath\\/i,'')

                        var timestamp=Number(new Date());
                        var filename=filename.split('.')[0]+"_"+timestamp+'.'+ext;
                        photo=filePayload;
                        if(photo) {
                            photocount++;
                            photos_html='<span class="image-item" id="photo_'+photocount+'"><span class="remove-btn" data-id="'+photocount+'" data-img="'+photo+'"><i class="fa fa-remove"></i></span><img width="100px" id="" height="auto" src="'+photo+'"></span>';
                            $("#photos").append(photos_html);
                            restaurnt_photos.push(photo);
                            restaurant_photos_filename.push(filename);
                        }
                    };
                })(f);
                reader.readAsDataURL(f);
            },
            error(err) {
                console.log(err.message);
            },
        });
    }


    function handleStoryFileSelect(evt) {
        var f=evt.target.files[0];
        var reader=new FileReader();
        var story_video_duration=$("#story_video_duration").val();
        var isVideo=document.getElementById('video_file');
        var videoValue=isVideo.value;
        var allowedExtensions=/(\.mp4)$/i;;

        if(!allowedExtensions.exec(videoValue)) {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>Error: Invalid video type</p>");
            window.scrollTo(0,0);
            isVideo.value='';
            return false;
        }

        var video=document.createElement('video');


        video.preload='metadata';

        video.onloadedmetadata=function() {

            window.URL.revokeObjectURL(video.src);


            if(video.duration>storevideoDuration) {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>Error: Story video duration maximum allow to "+storevideoDuration+" seconds</p>");
                window.scrollTo(0,0);
                evt.target.value='';
                return false;
            }

            reader.onload=(function(theFile) {
                return function(e) {

                    var filePayload=e.target.result;
                    var val=f.name;
                    var ext=val.split('.')[1];
                    var docName=val.split('fakepath')[1];
                    var filename=(f.name).replace(/C:\\fakepath\\/i,'')

                    var timestamp=Number(new Date());
                    var filename=filename.split('.')[0]+"_"+timestamp+'.'+ext;

                    var uploadTask=storyRef.child(filename).put(theFile);
                    uploadTask.on('state_changed',function(snapshot) {

                        var progress=(snapshot.bytesTransferred/snapshot.totalBytes)*100;
                        console.log('Upload is '+progress+'% done');
                        jQuery("#uploding_story_video").text("video is uploading...");
                    },function(error) {},function() {
                        uploadTask.snapshot.ref.getDownloadURL().then(function(downloadURL) {
                            jQuery("#uploding_story_video").text("Upload is completed");
                            setTimeout(function() {
                                jQuery("#uploding_story_video").empty();
                            },3000);

                            var nextCount=$("#story_vedios").children().length;
                            html='<div class="col-md-3" id="story_div_'+nextCount+'">\n'+
                                '<div class="video-inner"><video width="320px" height="240px"\n'+
                                '                                   controls="controls">\n'+
                                '                            <source src="'+downloadURL+'"\n'+
                                '            type="video/mp4"></video><span class="remove-story-video" data-id="'+nextCount+'" data-img="'+downloadURL+'"><i class="fa fa-remove"></i></span></div></div>';

                            jQuery("#story_vedios").append(html);
                            story_vedios.push(downloadURL);
                            $("#video_file").val('');
                        });
                    });

                };
            })(f);
            reader.readAsDataURL(f);
        }
        video.src=URL.createObjectURL(f);
    }


    $(document).on("click",".remove-story-video",function() {
        var id=$(this).attr('data-id');
        var photo_remove=$(this).attr('data-img');

        $("#story_div_"+id).remove();
        index=story_vedios.indexOf(photo_remove);
        $("#video_file").val('');
        if(index>-1) {
            story_vedios.splice(index,1); // 2nd parameter means remove one item only
        }

        var newhtml='';
        if(story_vedios.length>0) {
            for(var i=0;i<story_vedios.length;i++) {
                newhtml+='<div class="col-md-3" id="story_div_'+i+'">\n'+
                    '<div class="video-inner"><video width="320px" height="240px"\n'+
                    'controls="controls">\n'+
                    '<source src="'+story_vedios[i]+'"\n'+
                    'type="video/mp4"></video><span class="remove-story-video" data-id="'+i+'" data-img="'+story_vedios[i]+'"><i class="fa fa-remove"></i></span></div></div>';
            }
        }
        jQuery("#story_vedios").html(newhtml);
        deleteStoryfromCollection();
    });

    $(document).on("click",".remove-story-thumbnail",function() {
        var photo_remove=$(this).attr('data-img');

        $("#story_thumbnail").empty();
        $('#file').val('');
        story_thumbnail='';
        deleteStoryfromCollection();
    });

    function deleteStoryfromCollection() {
        if(story_vedios.length==0&&story_thumbnail=='') {
            database.collection('story').where('vendorID','==',restaurant_id).get().then(async function(snapshot) {
                if(snapshot.docs.length>0) {

                    database.collection('story').doc(restaurant_id).delete();
                }
            });
        }
    }
    async function storeStoryImageData() {
        var newPhoto=[];
        newPhoto['storyThumbnailImage']='';
        try {
            if(story_thumbnail!='') {
                story_thumbnail=story_thumbnail.replace(/^data:image\/[a-z]+;base64,/,"")
                var uploadTask=await storageRef.child(story_thumbnail_filename).putString(story_thumbnail,'base64',{
                    contentType: 'image/jpg'
                });
                var downloadURL=await uploadTask.ref.getDownloadURL();
                newPhoto['storyThumbnailImage']=downloadURL;
            }
        } catch(error) {
            console.log("ERR ===",error);
        }
        return newPhoto;
    }

    function handleStoryThumbnailFileSelect(evt) {

        var f=evt.target.files[0];
        var reader=new FileReader();

        var fileInput=
            document.getElementById('file');

        var filePath=fileInput.value;

        // Allowing file type
        var allowedExtensions=/(\.jpg|\.jpeg|\.png|\.gif)$/i;;

        if(!allowedExtensions.exec(filePath)) {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>Error: Invalid File type</p>");
            window.scrollTo(0,0);
            fileInput.value='';
            return false;
        }
        reader.onload=(function(theFile) {
            return function(e) {

                var filePayload=e.target.result;
                var val=f.name;
                var ext=val.split('.')[1];
                var docName=val.split('fakepath')[1];
                var filename=(f.name).replace(/C:\\fakepath\\/i,'')

                var timestamp=Number(new Date());
                var filename=filename.split('.')[0]+"_"+timestamp+'.'+ext;
                story_thumbnail=filePayload;
                story_thumbnail_filename=filename;
                var html='<div class="col-md-3"><div class="thumbnail-inner"><span class="remove-story-thumbnail" data-img="'+story_thumbnail+'"><i class="fa fa-remove"></i></span><img id="story_thumbnail_image" src="'+story_thumbnail+'" width="150px" height="150px;"></div></div>';
                jQuery("#story_thumbnail").html(html);


            };
        })(f);
        reader.readAsDataURL(f);
    }

    async function storeMenuImageData() {
        var newPhoto=[];
        try {
            if(restaurant_menu_photos.length>0) {
                await Promise.all(restaurant_menu_photos.map(async (menuPhoto,index) => {
                    menuPhoto=menuPhoto.replace(/^data:image\/[a-z]+;base64,/,"");
                    var uploadTask=await storageRef.child(restaurant_menu_filename[index]).putString(menuPhoto,'base64',{
                        contentType: 'image/jpg'
                    });
                    var downloadURL=await uploadTask.ref.getDownloadURL();
                    newPhoto.push(downloadURL);
                }));
            }
            return newPhoto;
        } catch(error) {
            console.error('Error storing menu images:', error);
            throw error;
        }
    }

    function handleFileSelectMenuCard(evt) {
        var f=evt.target.files[0];
        var reader=new FileReader();

        new Compressor(f,{
            quality: <?php echo env('IMAGE_COMPRESSOR_QUALITY', 0.8); ?>,
            success(result) {
                f=result;

                reader.onload=(function(theFile) {
                    return function(e) {

                        var filePayload=e.target.result;
                        var val=f.name;
                        var ext=val.split('.')[1];
                        var docName=val.split('fakepath')[1];
                        var filename=(f.name).replace(/C:\\fakepath\\/i,'')

                        var timestamp=Number(new Date());
                        var filename=filename.split('.')[0]+"_"+timestamp+'.'+ext;
                        photo=filePayload;

                        if(photo) {

                            menuPhotoCount++;
                            photos_html='<span class="image-item" id="photo_menu'+menuPhotoCount+'"><span class="remove-menu-btn" data-id="'+menuPhotoCount+'" data-img="'+photo+'"><i class="fa fa-remove"></i></span><img width="100px" id="" height="auto" src="'+photo+'"></span>';
                            $("#photos_menu_card").append(photos_html);
                            restaurant_menu_photos.push(photo);
                            restaurant_menu_filename.push(filename);
                        }
                    };
                })(f);
                reader.readAsDataURL(f);
            },
            error(err) {
                console.log(err.message);
            },
        });
    }

    $("#dine_in_feature").change(function() {
        if(this.checked) {
            $(".divein_div").show();
        } else {
            $(".divein_div").hide();
        }
    });


    $(".add_special_offer_restaurant_btn").click(function() {
        if(specialDiscountOfferisEnable) {
            $(".special_offer_div").show();
        } else {
            alert("{{trans('lang.special_offer_disabled')}}");
            return false;
        }
    })

    var countAddButton=1;

    function addMoreButton(day,day2,count) {
        count=countAddButton;
        $(".restaurant_discount_options_"+day+"_div").show();

        $('#special_offer_table_'+day+' tr:last').after('<tr>'+
            '<td class="" style="width:10%;"><input type="time" class="form-control" id="openTime'+day+count+'"></td>'+
            '<td class="" style="width:10%;"><input type="time" class="form-control" id="closeTime'+day+count+'"></td>'+
            '<td class="" style="width:30%;">'+
            '<input type="number" class="form-control" id="discount'+day+count+'" style="width:60%;">'+
            '<select id="discount_type'+day+count+'" class="form-control" style="width:40%;"><option value="percentage"/>%</option><option value="amount"/>'+currentCurrency+'</option></select>'+
            '</td>'+
            '<td style="width:30%;"><select id="type'+day+count+'" class="form-control"><option value="delivery"/>Delivery Discount</option><option value="dinein"/>Dine-In Discount</option></select></td>'+
            '<td class="action-btn" style="width:20%;">'+
            '<button type="button" class="btn btn-primary save_option_day_button'+day+count+'" onclick="addMoreFunctionButton(`'+day2+'`,`'+day+'`,'+countAddButton+')" style="width:62%;"><i class="fa fa-save"></i> Save</button>'+
            '</td></tr>');
        countAddButton++;

    }

    function addMoreFunctionButton(day1,day2,count) {
        var discount=$("#discount"+day2+count).val();
        var discount_type=$('#discount_type'+day2+count).val();
        var type=$('#type'+day2+count).val();
        var closeTime=$("#closeTime"+day2+count).val();
        var openTime=$("#openTime"+day2+count).val();

        $(".error_top").hide();
        $(".error_top").html("");
        if(openTime=='') {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>Please enter special offer start time</p>");
            window.scrollTo(0,0);
        }
        else if(closeTime=='') {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>Please enter special offer close time</p>");
            window.scrollTo(0,0);
        }

        else if(openTime>closeTime) {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>Close time can not be less than Open time</p>");
            window.scrollTo(0,0);
        }
        else if(discount=="") {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>Please Enter discount</p>");
            window.scrollTo(0,0);
        } else if(discount>100||discount==0) {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>Please Enter valid discount</p>");
            window.scrollTo(0,0);
        } else {

            if(typeof timeslotSunday==='undefined') timeslotSunday=[];
            if(typeof timeslotMonday==='undefined') timeslotMonday=[];
            if(typeof timeslotTuesday==='undefined') timeslotTuesday=[];
            if(typeof timeslotWednesday==='undefined') timeslotWednesday=[];
            if(typeof timeslotThursday==='undefined') timeslotThursday=[];
            if(typeof timeslotFriday==='undefined') timeslotFriday=[];
            if(typeof timeslotSaturday==='undefined') timeslotSaturday=[];

            var isDuplicate=false;
            var existingTimeslots=[];


            if(day1=='sunday') {
                existingTimeslots=timeslotSunday;
            } else if(day1=='monday') {
                existingTimeslots=timeslotMonday;
            } else if(day1=='tuesday') {
                existingTimeslots=timeslotTuesday;
            } else if(day1=='wednesday') {
                existingTimeslots=timeslotWednesday;
            } else if(day1=='thursday') {
                existingTimeslots=timeslotThursday;
            } else if(day1=='friday') {
                existingTimeslots=timeslotFriday;
            } else if(day1=='Saturday') {
                existingTimeslots=timeslotSaturday;
            }

            function timeToDate(time) {
                var [hours,minutes]=time.split(':');
                return new Date(0,0,0,hours,minutes); // Using "0" date and month for comparison
            }

            var newOpenTime=timeToDate(openTime);
            var newCloseTime=timeToDate(closeTime);

            existingTimeslots.forEach(function(slot) {
                var existingStart=timeToDate(slot.from);
                var existingEnd=timeToDate(slot.to);

                // Check if the new slot is inside the existing slot
                if((newOpenTime<existingEnd&&newCloseTime>existingStart)) {
                    if(slot.discount_type!==type) {
                        isDuplicate=false;  // Allow the new slot with a different type
                    } else {
                        isDuplicate=true;   // Same time range and type -> duplicate
                    }
                }
            });


            if(isDuplicate) {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>This time slot for "+type+" already exists. Please select a different time slot.</p>");
                window.scrollTo(0,0);
            }
            else {
                var timeslotVar={
                    'discount': discount,
                    'from': openTime,
                    'to': closeTime,
                    'type': discount_type,
                    'discount_type': type
                };


                if(day1=='sunday') {
                    timeslotSunday.push(timeslotVar);
                } else if(day1=='monday') {
                    timeslotMonday.push(timeslotVar);
                } else if(day1=='tuesday') {
                    timeslotTuesday.push(timeslotVar);
                } else if(day1=='wednesday') {
                    timeslotWednesday.push(timeslotVar);
                } else if(day1=='thursday') {
                    timeslotThursday.push(timeslotVar);
                } else if(day1=='friday') {
                    timeslotFriday.push(timeslotVar);
                } else if(day1=='Saturday') {
                    timeslotSaturday.push(timeslotVar);
                }


                $(".save_option_day_button"+day2+count).hide();
                $("#discount"+day2+count).attr('disabled',"true");
                $("#discount_type"+day2+count).attr('disabled',"true");
                $("#type"+day2+count).attr('disabled',"true");
                $("#closeTime"+day2+count).attr('disabled',"true");
                $("#openTime"+day2+count).attr('disabled',"true");
            }
        }

    }


    $(".add_working_hours_restaurant_btn").click(function() {
        $(".working_hours_div").show();
    })
    var countAddhours=1;

    function addMorehour(day,day2,count) {
        count=countAddhours;
        $(".restaurant_discount_options_"+day+"_div").show();
        $('#working_hour_table_'+day+' tr:last').after('<tr>'+
            '<td class="" style="width:50%;"><input type="time" class="form-control" id="from'+day+count+'"></td>'+
            '<td class="" style="width:50%;"><input type="time" class="form-control" id="to'+day+count+'"></td>'+
            '<td><button type="button" class="btn btn-primary save_option_day_button'+day+count+'" onclick="addMoreFunctionhour(`'+day2+'`,`'+day+'`,'+countAddhours+')" style="width:62%;"><i class="fa fa-save" title="Save""></i></button>'+
            '</td></tr>');
        countAddhours++;

    }

    function chkNumbers(event,msg) {
        var charCode=event.which? event.which:event.keyCode;
        if(charCode<48||charCode>57) {
            document.getElementById(msg).innerHTML="Accept only numbers";
            return false;
        } else {
            document.getElementById(msg).innerHTML="";
            return true;
        }
    }

    function addMoreFunctionhour(day1,day2,count) {
        var to=$("#to"+day2+count).val();
        var from=$("#from"+day2+count).val();
        if(from=='') {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>Please enter restaurant open time</p>");
            window.scrollTo(0,0);
        }
        else if(to=='') {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>Please enter restaurant close time</p>");
            window.scrollTo(0,0);

        }
        else if(from>to) {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>To time can not be less than From time</p>");
            window.scrollTo(0,0);
        } else {

            var timeslotworkVar={
                'from': from,
                'to': to,
            };


            if(day1=='sunday') {
                timeslotworkSunday.push(timeslotworkVar);
            } else if(day1=='monday') {
                timeslotworkMonday.push(timeslotworkVar);
            } else if(day1=='tuesday') {
                timeslotworkTuesday.push(timeslotworkVar);
            } else if(day1=='wednesday') {
                timeslotworkWednesday.push(timeslotworkVar);
            } else if(day1=='thursday') {
                timeslotworkThursday.push(timeslotworkVar);
            } else if(day1=='friday') {
                timeslotworkFriday.push(timeslotworkVar);
            } else if(day1=='Saturday') {
                timeslotworkSaturday.push(timeslotworkVar);
            }

            $(".save_option_day_button"+day2+count).hide();
            $("#to"+day2+count).attr('disabled',"true");
            $("#from"+day2+count).attr('disabled',"true");
        }

    }
  
    async function getOwnerDetails(selectedOwnerId) {
        var data='';
        try {
            console.log('Fetching owner details for ID:', selectedOwnerId);
            await database.collection('users').doc(selectedOwnerId).get().then(async function(
                snapshot) {
                data=snapshot.data();
                console.log('Owner details fetched:', data);
            })
            return data;
        } catch(error) {
            console.error('Error getting owner details:', error);
            return null;
        }
    }

    $('#subscription_plan').on('change',function() {
        var id=$(this).val();
        database.collection('subscription_plans').where('id','==',id).get().then(async function(snapshot) {
            var data=snapshot.docs[0].data();
            if(data.type=="paid") {
                if(data.features.dineIn==false) {
                    $('#dine_in_div').addClass('d-none');
                } else {
                    $('#dine_in_div').removeClass('d-none');
                }
            } else {
                $('#dine_in_div').removeClass('d-none');
            }
        })
    })

</script>
@endsection  