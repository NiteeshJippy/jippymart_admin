<div class="navbar-header">
    <a class="navbar-brand" href="<?php echo URL::to('/'); ?>">
        <b>
            <img src="{{ asset('/images/logo_web.png') }}" alt="homepage" class="dark-logo" width="100%" id="logo_web">
            <img src="{{ asset('images/logo-light-icon.png') }}" alt="homepage" class="light-logo">
        </b>
        <span>
        </span>
    </a>
</div>
<div class="navbar-collapse">
    <ul class="navbar-nav mr-auto mt-md-0">
        <li class="nav-item"><a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark"
                                href="javascript:void(0)"><i class="mdi mdi-menu"></i></a></li>
        <li class="nav-item m-l-0"><a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark"
                                       href="javascript:void(0)"><svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none">
<path fill-rule="evenodd" clip-rule="evenodd" d="M0.791687 9.49999C0.791687 4.69051 4.69054 0.791656 9.50002 0.791656C14.3095 0.791656 18.2084 4.69051 18.2084 9.49999C18.2084 14.3095 14.3095 18.2083 9.50002 18.2083C4.69054 18.2083 0.791687 14.3095 0.791687 9.49999ZM9.50002 2.37499C5.56499 2.37499 2.37502 5.56496 2.37502 9.49999C2.37502 13.435 5.56499 16.625 9.50002 16.625C13.4351 16.625 16.625 13.435 16.625 9.49999C16.625 5.56496 13.4351 2.37499 9.50002 2.37499ZM9.85861 5.57561C10.1678 5.88478 10.1678 6.38603 9.85861 6.6952L7.64757 8.90624H12.8613C13.2985 8.90624 13.653 9.26068 13.653 9.69791C13.653 10.1351 13.2985 10.4896 12.8613 10.4896H7.64757L9.85861 12.7006C10.1678 13.0098 10.1678 13.511 9.85861 13.8202C9.54945 14.1294 9.04819 14.1294 8.73903 13.8202L5.17653 10.2577C4.86736 9.94853 4.86736 9.44728 5.17653 9.13811L8.73903 5.57561C9.04819 5.26645 9.54945 5.26645 9.85861 5.57561Z" fill="white"/>
</svg></a></li>
    </ul>
    <div style="visibility: hidden;" class="language-list icon d-flex align-items-center text-light ml-2"
         id="language_dropdown_box">
        <div class="language-select">
            <i class="fa fa-globe"></i>
        </div>
        <div class="language-options">
            <select class="form-control changeLang text-dark" id="language_dropdown">
            </select>
        </div>
    </div>
    <ul class="navbar-nav my-lg-0 border-left pl-2 ml-2">
        <li class="nav-item dropdown">
           <div class="d-flex align-items-center dropdown-toggle text-muted waves-effect waves-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> 
            <a class="nav-link " href="">
               <img src="{{ asset('/images/users/user-new.png') }}" alt="user" class="profile-pic"></a>
               <div class="nav-item-user">
                <h5 class="text-dark mb-0">{{  Auth::user()->name }}</h5>
                <!--<p class="mb-0">foodie@admin.com</p>-->
               </div>  
             </div>
            <div class="dropdown-menu dropdown-menu-right scale-up">
                <ul class="dropdown-user">
                    <li>
                        <div class="dw-user-box">
                            <div class="u-img"><img src="{{ asset('/images/users/user-2.png') }}" alt="user"
                                                    style="max-width: 45px;"></div>
                            <div class="u-text">
                                <?php
                                ?>
                                <h4>{{  Auth::user()->name }}</h4>
                                <p class="text-muted">{{ session()->has('user_role') ? session()->get('user_role') : '' }}</p>
                            </div>
                        </div>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ route('users.profile') }}"><i
                                    class="ti-user"></i> {!! trans('lang.user_profile') !!}</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();"><i
                                    class="fa fa-power-off"></i> {{ __('Logout') }}</a></li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </ul>
            </div>
        </li>
    </ul>
</div>