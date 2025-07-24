@php
$user = Auth::user();
$role_has_permission = App\Models\Permission::where('role_id', $user->role_id)->pluck('permission')->toArray();
@endphp
<div class="sidebar-search">
    <input type="text" id="sideBarSearchInput" placeholder="Search Menu" autocomplete="one-time-code" onkeyup="filterMenu()">
</div>

<nav class="sidebar-nav">
    <ul id="sidebarnav">
        <li>
            <a class="waves-effect waves-dark" href="{!! url('dashboard') !!}" aria-expanded="false">
                <i class="mdi mdi-home"></i>
                <span class="hide-menu">{{trans('lang.dashboard')}}</span>
            </a>
        </li>
        @if(in_array('god-eye', $role_has_permission) || in_array('zone', $role_has_permission))
        <li class="nav-subtitle"><span class="nav-subtitle-span">{{trans('lang.live_monitoring')}}</span></li>
        @endif
        @if(in_array('god-eye', $role_has_permission))
        <li>
            <a class="waves-effect waves-dark" href="{!! url('map') !!}" aria-expanded="false">
                <i class="mdi mdi-home-map-marker"></i>
                <span class="hide-menu">{{trans('lang.god_eye')}}</span>
            </a>
        </li>
        @endif
        @if(in_array('zone', $role_has_permission))
        <li>
            <a class="waves-effect waves-dark" href="{!! url('zone') !!}" aria-expanded="false">
                <i class="mdi mdi-map-marker-circle"></i>
                <span class="hide-menu">{{trans('lang.zone')}}</span>
            </a>
        </li>
        @endif
        @if(in_array('admins', $role_has_permission) || in_array('roles', $role_has_permission))
        <li class="nav-subtitle"><span class="nav-subtitle-span">{{trans('lang.access_management')}}</span></li>
        <li><a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                <i class="mdi mdi-lock-outline"></i>
                <span class="hide-menu">{{trans('lang.access_control')}}</span>
            </a>
            <ul aria-expanded="false" class="collapse">
                @if(in_array('roles', $role_has_permission))
                <li><a href="{!! url('role') !!}">{{trans('lang.role_plural')}}</a></li>
                @endif
                @if(in_array('admins', $role_has_permission))
                <li><a href="{!! url('admin-users') !!}">{{trans('lang.admin_plural')}}</a></li>
                @endif
            </ul>
        </li>
        @endif
        @if(in_array('users', $role_has_permission) || in_array('vendors',$role_has_permission) || in_array('approve_vendors',$role_has_permission) ||
        in_array('pending_vendors',$role_has_permission))
        <li class="nav-subtitle"><span class="nav-subtitle-span">{{trans('lang.customer_and_vendor_management')}}</span></li>
        @endif
        @if(in_array('users', $role_has_permission))
        <li>
            <a class="waves-effect waves-dark" href="{!! url('users') !!}" aria-expanded="false">
                <i class="mdi mdi-account-multiple"></i>
                <span class="hide-menu">{{trans('lang.user_customer')}}</span>
            </a>
        </li>
        @endif
        @if(in_array('vendors',$role_has_permission) || in_array('approve_vendors',$role_has_permission) ||
        in_array('pending_vendors',$role_has_permission))
        <li>
            <a class="has-arrow waves-effect waves-dark driver_menu" href="#" aria-expanded="false">
                <i class="mdi mdi-account-card-details"></i>
                <span class="hide-menu">{{trans('lang.owner_vendor')}}</span>
            </a>
            <ul aria-expanded="false" class="collapse driver_sub_menu">
                @if(in_array('vendors',$role_has_permission))
                <li class="all_driver_menu"><a href="{!! url('vendors') !!}">{{trans('lang.all_vendors')}}</a></li>
                @endif
                @if(in_array('approve_vendors',$role_has_permission))
                <li class="approve_driver_menu"><a href="{!! url('vendors/approved') !!}">{{trans('lang.approved_vendors')}}</a></li>
                @endif
                @if(in_array('pending_vendors',$role_has_permission))
                <li class="pending_driver_menu"><a href="{!! url('vendors/pending') !!}">{{trans('lang.approval_pending_vendors')}}</a></li>
                @endif
            </ul>
        </li>
        @endif
        @if(in_array('drivers',$role_has_permission) || in_array('approve_drivers',$role_has_permission) ||
        in_array('pending_drivers',$role_has_permission) || in_array('restaurants', $role_has_permission))
        <li class="nav-subtitle"><span class="nav-subtitle-span">{{trans('lang.restaurant_and_driver_management')}}</span></li>
        @endif
        @if(in_array('restaurants', $role_has_permission))
        <li><a class="waves-effect waves-dark" href="{!! url('restaurants') !!}" aria-expanded="false">
                <i class="mdi mdi-shopping"></i>
                <span class="hide-menu">{{trans('lang.restaurant_plural')}}</span>
            </a>
        </li>
        @endif
        @if(in_array('drivers',$role_has_permission) || in_array('approve_drivers',$role_has_permission) ||
        in_array('pending_drivers',$role_has_permission))
        <li>
            <a class="has-arrow waves-effect waves-dark driver_menu" href="#" aria-expanded="false">
                <i class="mdi mdi-account-card-details"></i>
                <span class="hide-menu">{{trans('lang.driver_plural')}}</span>
            </a>
            <ul aria-expanded="false" class="collapse driver_sub_menu">
                @if(in_array('drivers',$role_has_permission))
                <li class="all_driver_menu"><a href="{!! url('drivers') !!}">{{trans('lang.all_drivers')}}</a></li>
                @endif
                @if(in_array('approve_drivers',$role_has_permission))
                <li class="approve_driver_menu"><a href="{!! url('drivers/approved') !!}">{{trans('lang.approved_drivers')}}</a></li>
                @endif
                @if(in_array('pending_drivers',$role_has_permission))
                <li class="pending_driver_menu"><a href="{!! url('drivers/pending') !!}">{{trans('lang.approval_pending_drivers')}}</a></li>
                @endif
            </ul>
        </li>
        @endif
        @if(in_array('reports', $role_has_permission))
        <li class="nav-subtitle"><span class="nav-subtitle-span">{{trans('lang.report_and_analytics')}}</span></li>
        <li><a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                <i class="mdi mdi-calendar-check"></i>
                <span class="hide-menu">{{trans('lang.report_plural')}}</span>
            </a>
            <ul aria-expanded="false" class="collapse">
                <li><a href="{!! url('/report/sales') !!}">{{trans('lang.reports_sale')}}</a></li>
            </ul>
        </li>
        @endif
        @if(in_array('category', $role_has_permission) || in_array('foods', $role_has_permission) || in_array('item-attribute', $role_has_permission) || in_array('review-attribute', $role_has_permission))
        <li class="nav-subtitle"><span class="nav-subtitle-span">{{trans('lang.menu_and_food_management')}}</span></li>
        @endif

        <!-- @if(in_array('cuisines', $role_has_permission))
            <li><a class="waves-effect waves-dark" href="{!! url('cuisines') !!}" aria-expanded="false">
                    <i class="mdi mdi-clipboard-text"></i>
                    <span class="hide-menu">{{trans('lang.cuisines_plural')}}</span>
                </a>
            </li>
        @endif -->
        <li><a class="waves-effect waves-dark" href="{!! url('cuisines') !!}" aria-expanded="false">
                    <i class="mdi mdi-clipboard-text"></i>
                    <span class="hide-menu">{{trans('lang.cuisines_plural')}}</span>
                </a>
            </li>

        @if(in_array('category', $role_has_permission))
        <li><a class="waves-effect waves-dark" href="{!! url('categories') !!}" aria-expanded="false">
                <i class="mdi mdi-clipboard-text"></i>
                <span class="hide-menu">{{trans('lang.category_plural')}}</span>
            </a>
        </li>
        @endif


        @if(in_array('foods', $role_has_permission))
        <li>
            <a class="waves-effect waves-dark" href="{!! url('foods') !!}" aria-expanded="false">
                <i class="mdi mdi-food"></i>
                <span class="hide-menu">{{trans('lang.food_plural')}}</span>
            </a>
        </li>
        @endif
        @if(in_array('item-attribute', $role_has_permission) || in_array('review-attribute', $role_has_permission))
        <li><a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                <i class="mdi mdi-plus-box"></i>
                <span class="hide-menu">{{trans('lang.attribute_plural')}}</span>
            </a>
            <ul aria-expanded="false" class="collapse">
                @if(in_array('item-attribute', $role_has_permission))
                <li><a href="{!! route('attributes') !!}">{{trans('lang.item_attribute_id')}}</a></li>
                @endif
                @if(in_array('review-attribute', $role_has_permission))
                <li><a href="{!! route('reviewattributes') !!}">{{trans('lang.review_attribute_plural')}}</a></li>
                @endif
            </ul>
        </li>
        @endif
        <li class="nav-subtitle"><span class="nav-subtitle-span">{{trans('lang.business_setup')}}</span></li>
        <li><a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                <i class="mdi mdi-credit-card"></i>
                <span class="hide-menu">{{trans('lang.subscription_plans')}}</span>
            </a>
            <ul aria-expanded="false" class="collapse">
                <li><a href="{!! route('subscription-plans.index') !!}">{{trans('lang.subscription_plans')}}</a></li>
                <li><a href="{!! route('vendor.subscriptionPlanHistory') !!}">{{trans('lang.vendor_subscription_history_plural')}}</a></li>
            </ul>
        </li>
        @if(in_array('orders', $role_has_permission) || in_array('gift-cards', $role_has_permission) || in_array('coupons', $role_has_permission) || in_array('documents', $role_has_permission))
        <li class="nav-subtitle"><span class="nav-subtitle-span">{{trans('lang.order_and_promotions_management')}}</span></li>
        @endif
        @if(in_array('orders', $role_has_permission))
        <li><a class="waves-effect waves-dark" href="{!! url('orders') !!}" aria-expanded="false">
                <i class="mdi mdi-library-books"></i>
                <span class="hide-menu">{{trans('lang.order_plural')}}</span>
            </a>
        </li>
        @endif
        @if(in_array('gift-cards', $role_has_permission))
        <li><a class="waves-effect waves-dark" href="{!! url('gift-card') !!}" aria-expanded="false">
                <i class="mdi mdi-wallet-giftcard"></i>
                <span class="hide-menu">{{trans('lang.gift_card_plural')}}</span>
            </a>
        </li>
        @endif
        @if(in_array('coupons', $role_has_permission))
        <li><a class="waves-effect waves-dark" href="{!! url('coupons') !!}" aria-expanded="false">
                <i class="mdi mdi-sale"></i>
                <span class="hide-menu">{{trans('lang.coupon_plural')}}</span>
            </a>
        </li>
        @endif
        @if(in_array('documents', $role_has_permission))
        <li><a class="waves-effect waves-dark" href="{!! url('documents') !!}" aria-expanded="false">
                <i class="mdi mdi-file-document"></i>
                <span class="hide-menu">{{trans('lang.document_plural')}}</span>
            </a>
        </li>
        @endif
        @if(in_array('general-notifications', $role_has_permission) || in_array('dynamic-notifications', $role_has_permission))
        <li class="nav-subtitle"><span class="nav-subtitle-span">{{trans('lang.notification_management')}}</span></li>
        <li>
            <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                <i class="mdi mdi-table"></i>
                <span class="hide-menu">{{trans('lang.notification_plural')}}</span>
            </a>
            <ul aria-expanded="false" class="collapse">
                @if(in_array('general-notifications', $role_has_permission))
                <li><a href="{!! url('notification') !!}">{{trans('lang.general_notification')}}</a></li>
                @endif
                @if(in_array('dynamic-notifications', $role_has_permission))
                <li><a href="{!! url('dynamic-notification') !!}">{{trans('lang.dynamic_notification')}}</a></li>
                @endif
            </ul>
        </li>
        @endif
        @if(
        in_array('payments', $role_has_permission) || in_array('restaurant-payouts', $role_has_permission) || in_array('driver-payments', $role_has_permission)
        || in_array('driver-payouts', $role_has_permission) || in_array('wallet-transaction', $role_has_permission) || in_array('payout-request', $role_has_permission)
        )
        <li class="nav-subtitle"><span class="nav-subtitle-span">{{trans('lang.payment_and_transactions')}}</span></li>
        <li><a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                <i class="mdi mdi-bank"></i>
                <span class="hide-menu">{{trans('lang.payment_plural')}}</span>
            </a>
            <ul aria-expanded="false" class="collapse">
                @if(in_array('payments', $role_has_permission))
                <li><a href="{!! url('payments') !!}">{{trans('lang.restaurant_payments')}}</a></li>
                @endif
                @if(in_array('restaurant-payouts', $role_has_permission))
                <li><a href="{!! url('restaurantsPayouts') !!}">{{trans('lang.restaurants_payout_plural')}}</a></li>
                @endif
                @if(in_array('driver-payments', $role_has_permission))
                <li><a href="{!! url('driverpayments') !!}">{{trans('lang.driver_plural')}}
                        {{trans('lang.payment_plural')}}</a></li>
                @endif
                @if(in_array('driver-payouts', $role_has_permission))
                <li><a href="{!! url('driversPayouts') !!}">{{trans('lang.drivers_payout')}}</a></li>
                @endif
                @if(in_array('wallet-transaction', $role_has_permission))
                <li><a href="{!! url('walletstransaction') !!}">{{trans('lang.wallet_transaction')}}</a></li>
                @endif
                @if(in_array('payout-request', $role_has_permission))
                <li><a href="{!! url('payoutRequests/restaurants') !!}">{{trans('lang.payout_request')}}</a></li>
                @endif
            </ul>
        </li>
        @endif
        @if(in_array('banners', $role_has_permission) || in_array('cms', $role_has_permission) || in_array('on-board', $role_has_permission) || in_array('email-template', $role_has_permission))
        <li class="nav-subtitle"><span class="nav-subtitle-span">{{trans('lang.design_and_content_management')}}</span></li>
        @endif
        @if(in_array('banners', $role_has_permission))
        <li>
            <a class="waves-effect waves-dark" href="{!! url('banners') !!}" aria-expanded="false">
                <i class="mdi mdi-monitor-multiple "></i>
                <span class="hide-menu">{{trans('lang.menu_items')}}</span>
            </a>
        </li>
        @endif
        @if(in_array('cms', $role_has_permission))
        <li><a class="waves-effect waves-dark" href="{!! url('cms') !!}" aria-expanded="false">
                <i class="mdi mdi-book-open-page-variant"></i>
                <span class="hide-menu">{{trans('lang.cms_plural')}}</span>
            </a>
        </li>
        @endif
        @if(in_array('on-board', $role_has_permission))
        <li><a class="waves-effect waves-dark onboard_menu" href="{!! url('on-board') !!}" aria-expanded="false">
                <i class="mdi mdi-cellphone"></i>
                <span class="hide-menu">{{trans('lang.on_board_plural')}}</span>
            </a>
        </li>
        @endif
        @if(in_array('email-template', $role_has_permission))
        <li><a class="waves-effect waves-dark" href="{!! url('email-templates') !!}" aria-expanded="false">
                <i class="mdi mdi-email"></i>
                <span class="hide-menu">{{trans('lang.email_templates')}}</span>
            </a>
        </li>
        @endif
        @if(in_array('global-setting', $role_has_permission) || in_array('currency', $role_has_permission) || in_array('payment-method', $role_has_permission)
        || in_array('admin-commission', $role_has_permission) || in_array('radius', $role_has_permission) || in_array('dinein', $role_has_permission)
        || in_array('tax', $role_has_permission) || in_array('delivery-charge', $role_has_permission) || in_array('language', $role_has_permission)
        || in_array('special-offer', $role_has_permission) || in_array('terms', $role_has_permission) || in_array('privacy', $role_has_permission)
        || in_array('home-page', $role_has_permission) || in_array('footer', $role_has_permission) || in_array('document-verification', $role_has_permission))
        <li class="nav-subtitle"><span class="nav-subtitle-span">{{trans('lang.settings_and_configurations')}}</span></li>
        <li><a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                <i class="mdi mdi-settings"></i>
                <span class="hide-menu">{{trans('lang.app_setting')}}</span>
            </a>
            <ul aria-expanded="false" class="collapse">
                @if(in_array('global-setting', $role_has_permission))
                <li><a href="{!! url('settings/app/globals') !!}">{{trans('lang.app_setting_globals')}}</a></li>
                @endif
                @if(in_array('currency', $role_has_permission))
                <li><a href="{!! url('settings/currencies') !!}">{{trans('lang.currency_plural')}}</a></li>
                @endif
                @if(in_array('payment-method', $role_has_permission))
                <li><a href="{!! url('settings/payment/stripe') !!}">{{trans('lang.payment_methods')}}</a></li>
                @endif
                @if(in_array('admin-commission', $role_has_permission))
                <li>
                    <a href="{!! url('settings/app/adminCommission') !!}">{{trans('lang.business_model_settings')}}</a>
                </li>
                @endif
                @if(in_array('radius', $role_has_permission))
                <li><a href="{!! url('settings/app/radiusConfiguration') !!}">{{trans('lang.radios_configuration')}}</a>
                </li>
                @endif
                @if(in_array('dinein', $role_has_permission))
                <li><a href="{!! url('settings/app/bookTable') !!}">{{trans('lang.dine_in_future_setting')}}</a></li>
                @endif
                @if(in_array('tax', $role_has_permission))
                <li><a href="{!! url('tax') !!}">{{trans('lang.vat_setting')}}</a></li>
                @endif
                @if(in_array('delivery-charge', $role_has_permission))
                <li><a href="{!! url('settings/app/deliveryCharge') !!}">{{trans('lang.deliveryCharge')}}</a></li>
                @endif
                @if(in_array('document-verification', $role_has_permission))
                <li><a href="{!! url('settings/app/documentVerification') !!}">{{trans('lang.document_verification')}}</a></li>
                @endif
                @if(in_array('language', $role_has_permission))
                <li><a href="{!! url('settings/app/languages') !!}">{{trans('lang.languages')}}</a></li>
                @endif
                @if(in_array('special-offer', $role_has_permission))
                <li><a href="{!! url('settings/app/specialOffer') !!}">{{trans('lang.special_offer')}}</a></li>
                @endif
                @if(in_array('terms', $role_has_permission))
                <li><a href="{!! url('termsAndConditions') !!}">{{trans('lang.terms_and_conditions')}}</a></li>
                @endif
                @if(in_array('privacy', $role_has_permission))
                <li><a href="{!! url('privacyPolicy') !!}">{{trans('lang.privacy_policy')}}</a></li>
                @endif
                @if(in_array('home-page', $role_has_permission))
                <li><a href="{!! url('homepageTemplate') !!}">{{trans('lang.homepageTemplate')}}</a></li>
                @endif
                @if(in_array('footer', $role_has_permission))
                <li><a href="{!! url('footerTemplate') !!}">{{trans('lang.footer_template')}}</a></li>
                @endif
            </ul>
        </li>
        @endif
    </ul>
    <p class="web_version"></p>
</nav>
<script>
    function filterMenu() {
    const searchInput = document.getElementById('sideBarSearchInput').value.toLowerCase();
    const menuItems = document.getElementById('sidebarnav').getElementsByTagName('li');
    for (let i = 0; i < menuItems.length; i++) {
        const item = menuItems[i];
        const itemText = item.textContent.toLowerCase();
        if (itemText.indexOf(searchInput) === -1) {
            item.style.display = 'none';
        } else {
            item.style.display = '';
        }
    }
}
</script>
