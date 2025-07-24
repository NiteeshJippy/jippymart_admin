@extends('layouts.app')
@section('content')
    <div class="page-wrapper">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{trans('lang.on_board_plural')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{!! route('on-board') !!}">{{trans('lang.on_board_plural')}}</a>
                    </li>
                </ol>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card pb-4">
                <div class="card-body">
                    <div class="error_top"></div>
                    <div class="row restaurant_payout_create">
                        <div class="restaurant_payout_create-inner">
                            <fieldset>
                                <legend>{{trans('lang.on_board_details')}}</legend>
                                <div class="form-group row width-100">
                                    <label class="col-3 control-label">{{trans('lang.title')}}<span
                                            class="required-field"></span></label>
                                    <div class="col-7">
                                        <input type="text" class="form-control title">
                                        <div class="form-text text-muted">
                                            {{ trans("lang.title_help") }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row width-100">
                                    <label class="col-3 control-label">{{trans('lang.description')}}<span
                                            class="required-field"></span></label>
                                    <div class="col-7">
                                        <textarea rows="6" id="description" class="description form-control"></textarea>
                                        <div class="form-text text-muted">
                                            {{ trans("lang.description_help") }}
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="form-group col-12 text-center btm-btn">
                        <button type="button" class="btn btn-primary  edit-setting-btn"><i class="fa fa-save"></i> {{
                        trans('lang.save')}}</button>
                        <a href="{!! route('on-board') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{
                        trans('lang.cancel')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var database = firebase.firestore();
        var requestId = "{{$id}}";
        $(document).ready(function () {
            $('.onboard_menu').addClass('active');
            jQuery("#data-table_processing").show();
            var ref = database.collection('on_boarding').where("id", "==", requestId);
            ref.get().then(async function (snapshots) {
                var data = snapshots.docs[0].data();
                $(".title").val(data.title);
                $(".description").val(data.description);
                jQuery("#data-table_processing").hide();
            })
        });
        $(".edit-setting-btn").click(function () {
            jQuery("#overlay").show();
            var title = $(".title").val();
            var description = $(".description").val();
            if (title == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.title_help')}}</p>");
                window.scrollTo(0, 0);
            } else if (description == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.description_help')}}</p>");
                window.scrollTo(0, 0);
            } else {
                database.collection('on_boarding').doc(requestId).update({
                    'title': title,
                    'description': description,
                }).then(function (result) {
                    window.location.href = '{{ route("on-board")}}';
                }).catch(function (error) {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>" + error + "</p>");
                })
            }
        })
    </script>
@endsection
