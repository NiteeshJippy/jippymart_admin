@extends('layouts.app')
@section('content')
	<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{ trans('lang.document_verification')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{ trans('lang.document_verification')}}</li>
            </ol>
        </div>
    </div>
        <div class="card-body">
      	  <div class="row restaurant_payout_create">
            <div class="restaurant_payout_create-inner">
              <fieldset>
                <legend>{{trans('lang.document_verification')}}</legend>
                    <div class="form-check width-100">
                      <input type="checkbox" class="form-check-inline" id="enable_restaurant">
                        <label class="col-5 control-label" for="enable_restaurant">{{ trans('lang.enable_document_verification_restaurant')}}</label>
                    </div>
                    <div class="form-check width-100">
                        <input type="checkbox" class="form-check-inline" id="enable_driver">
                        <label class="col-5 control-label"
                            for="enable_driver">{{ trans('lang.enable_document_verification_driver')}}</label>
                    </div>
              </fieldset>
            </div>
          </div>
          <div class="form-group col-12 text-center">
            <button type="button" class="btn btn-primary edit-setting-btn" ><i class="fa fa-save"></i> {{trans('lang.save')}}</button>
            <a href="{{url('/dashboard')}}" class="btn btn-default"><i class="fa fa-undo"></i>{{trans('lang.cancel')}}</a>
          </div>
        </div>
 @endsection
@section('scripts')
<script>
    var database = firebase.firestore();
    var ref = database.collection('settings').doc("document_verification_settings");
    $(document).ready(function(){
        jQuery("#data-table_processing").show();
        ref.get().then( async function(snapshots){
          var documentVerification = snapshots.data();
          if(documentVerification == undefined){
              database.collection('settings').doc('document_verification_settings').set({});
          }
          try{
              if(documentVerification.isDriverVerification){
                  $("#enable_driver").prop('checked',true);
              }
              if (documentVerification.isRestaurantVerification) {
                  $("#enable_restaurant").prop('checked', true);
              }
          }catch (error){
          }
          jQuery("#data-table_processing").hide();
        })
        $(".edit-setting-btn").click(function(){
          var enableDriver = $("#enable_driver").is(":checked");
          var enableRestaurant = $("#enable_restaurant").is(":checked");
        database.collection('settings').doc("document_verification_settings").update({'isDriverVerification':enableDriver,'isRestaurantVerification':enableRestaurant}).then(function(result) {
                            window.location.href = '{{ url("settings/app/documentVerification")}}';
                });
        })
    })
</script>
@endsection
