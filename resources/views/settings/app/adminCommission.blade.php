@extends('layouts.app')
@section('content')
<div class="page-wrapper">
  <div class="row page-titles">
    <div class="col-md-5 align-self-center">
      <h3 class="text-themecolor">{{ trans('lang.business_model_settings')}}</h3>
    </div>
    <div class="col-md-7 align-self-center">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
        <li class="breadcrumb-item active">{{ trans('lang.business_model_settings')}}</li>
      </ol>
    </div>
  </div>
  <div class="card-body">
    <div class="row restaurant_payout_create">
      <div class="restaurant_payout_create-inner">
        <fieldset>
          <legend><i class="mr-3 mdi mdi-shopping"></i>{{ trans('lang.subscription_based_model_settings') }}</legend>
          <div class="form-group row mt-1 ">
            <div class="form-group row mt-1 ">
              <div class="col-12 switch-box">
                <div class="switch-box-inner">
                  <label class=" control-label">{{ trans('lang.subscription_based_model') }}</label>
                  <label class="switch"> <input type="checkbox" name="subscription_model" id="subscription_model"><span
                      class="slider round"></span></label>
                  <i class="text-dark fs-12 fa-solid fa fa-info" data-toggle="tooltip"
                    title="{{ trans('lang.subscription_tooltip') }}" aria-describedby="tippy-3"></i>
                </div>
              </div>
            </div>
          </div>
        </fieldset>
        <fieldset>
          <legend><i class="mr-3 mdi mdi-shopping"></i>{{trans('lang.commission_based_model_settings')}}</legend>
          <div class="form-group row width-100 switch-box">
            <div class="switch-box-inner">
              <label class=" control-label">{{ trans('lang.commission_based_model') }}</label>
              <label class="switch"> <input type="checkbox" name="enable_commission" onclick="ShowHideDiv()"
                  id="enable_commission"><span class="slider round"></span></label>
              <i class="text-dark fs-12 fa-solid fa fa-info" data-toggle="tooltip"
                title="{{ trans('lang.commission_tooltip') }}" aria-describedby="tippy-3"></i>
            </div>
          </div>
          <div class="form-group row width-50 admin_commision_detail" style="display:none">
            <label class="col-4 control-label">{{ trans('lang.commission_type')}}</label>
            <div class="col-7">
              <select class="form-control commission_type" id="commission_type">
                <option value="Percent">{{trans('lang.coupon_percent')}}</option>
                <option value="Fixed">{{trans('lang.coupon_fixed')}}</option>
              </select>
            </div>
          </div>
          <div class="form-group row width-50 admin_commision_detail" style="display:none">
            <label class="col-4 control-label">{{ trans('lang.admin_commission')}}</label>
            <div class="col-7">
              <input type="number" class="form-control commission_fix">
            </div>
          </div>
          <div class="form-group col-12 text-center">
            <button type="button" class="btn btn-primary edit-setting-btn"><i class="fa fa-save"></i>
              {{trans('lang.save')}}</button>
            <a href="{{url('/dashboard')}}" class="btn btn-default"><i
                class="fa fa-undo"></i>{{trans('lang.cancel')}}</a>
          </div>
        </fieldset>
        <fieldset>
          <legend><i class="mr-3 mdi mdi-shopping"></i>{{ trans('lang.bulk_update')}}</legend>
          <div class="form-group row width-100">
            <label class="col-3 control-label">{{ trans('lang.food_restaurant_id') }} <i
                class="text-dark fs-12 fa-solid fa fa-info" data-toggle="tooltip"
                title="{{ trans('lang.bulk_update_commission_tooltip') }}" aria-describedby="tippy-3"></i>
            </label>
            <div class="col-12">
              <select id="food_restaurant_type" class="form-control" required>
                <option value="all">{{ trans('lang.all_restaurant')}}</option>
                <option value="custom">{{ trans('lang.custom_restaurant')}}</option>
              </select>
              <select id="food_restaurant" style="display:none" multiple class="form-control mt-3" required> </select>
              <div class="form-text text-muted">
                {{ trans("lang.food_restaurant_id_help") }}
              </div>
            </div>
          </div>
          <div class="form-group row width-50">
            <label class="col-4 control-label">{{ trans('lang.commission_type')}}</label>
            <div class="col-7">
              <select class="form-control bulk_commission_type" id="bulk_commission_type">
                <option value="Percent">{{trans('lang.coupon_percent')}}</option>
                <option value="Fixed">{{trans('lang.coupon_fixed')}}</option>
              </select>
            </div>
          </div>
          <div class="form-group row width-50">
            <label class="col-4 control-label">{{ trans('lang.admin_commission')}}</label>
            <div class="col-7">
              <input type="number" value="0" class="form-control bulk_commission_fix">
            </div>
          </div>
          <div class="form-group col-12 text-center">
            <div class="col-12">
              <button type="button" id="bulk_update_btn" class="btn btn-primary edit-setting-btn"><i
                  class="fa fa-save"></i> {{ trans('lang.bulk_update')}}</button>
            </div>
          </div>
        </fieldset>
      </div>
    </div>
  </div>
  <style>
    .select2.select2-container {
      width: 100% !important;
      position: static;
      margin-top: 1rem;
    }
  </style>
  @endsection
  @section('scripts')
  <script>
    var database=firebase.firestore();
    var ref=database.collection('settings').doc("AdminCommission");
    var ref_deliverycharge=database.collection('settings').doc("DeliveryCharge");
    var restaurant=database.collection('settings').doc("restaurant");
    var photo="";
    $(document).ready(function() {
      $('#food_restaurant_type').on('change',function() {
        if($('#food_restaurant_type').val()==='custom') {
          $('#food_restaurant').show();
          $('#food_restaurant').select2({
            placeholder: "{{trans('lang.select_restaurant')}}",
            allowClear: true,
            width: '100%',
            dropdownAutoWidth: true
          });
        } else {
          $('#food_restaurant').hide();
          $('#food_restaurant').select2('destroy');
        }
      });
      database.collection('vendors').orderBy('title','asc').get().then(async function(snapshots) {
        snapshots.docs.forEach((listval) => {
          var data=listval.data();
          $('#food_restaurant').append($("<option></option>")
            .attr("value",data.id)
            .text(data.title));
        })
      });
      jQuery("#data-table_processing").show();
      ref.get().then(async function(snapshots) {
        var adminCommissionSettings=snapshots.data();
        if(adminCommissionSettings==undefined) {
          database.collection('settings').doc('AdminCommission').set({});
        }
        try {
          if(adminCommissionSettings.isEnabled) {
            $("#enable_commission").prop('checked',true);
            $(".admin_commision_detail").show();
          }
          $(".commission_fix").val(adminCommissionSettings.fix_commission);
          $("#commission_type").val(adminCommissionSettings.commissionType);
        } catch(error) {
        }
        jQuery("#data-table_processing").hide();
      })
      ref_deliverycharge.get().then(async function(snapshots_charge) {
        var deliveryChargeSettings=snapshots_charge.data();
        jQuery("#data-table_processing").hide();
        $(".deliveryCharge").val(deliveryChargeSettings.amount);
      })
      restaurant.get().then(async function(snapshots) {
        var restaurantdata=snapshots.data();
        if(restaurantdata==undefined) {
          database.collection('settings').doc('restaurant').set({});
        }
        try {
          if(restaurantdata.subscription_model) {
            $("#subscription_model").prop('checked',true);
          }
        } catch(error) {
        }
        jQuery("#data-table_processing").hide();
      })

      $(document).on("click","input[name='subscription_model']",function(e) {

        var subscription_model=$("#subscription_model").is(":checked");
        var userConfirmed=confirm(subscription_model? "{{ trans('lang.enable_subscription_plan_confirm_alert')}}":"{{ trans('lang.disable_subscription_plan_confirm_alert')}}");
        if(!userConfirmed) {
          $(this).prop("checked",!subscription_model);
          return;
        }
        database.collection('settings').doc("restaurant").update({
          'subscription_model': subscription_model,
        });
        if(subscription_model) {
          Swal.fire('Update Complete!',`Subscription model enabled.`,'success');
        } else {
          Swal.fire('Update Complete!',`Subscription model disabled.`,'success');
        }
      });
      $(".edit-setting-btn").click(function() {
        var checkboxValue=$("#enable_commission").is(":checked");
        var commission_type=$("#commission_type").val();
        var howmuch=parseInt($(".commission_fix").val());
        database.collection('settings').doc("AdminCommission").update({'isEnabled': checkboxValue,'fix_commission': howmuch,'commissionType': commission_type}).then(function(result) {
          Swal.fire('Update Complete!',`Successfully updated.`,'success');
        });
      })
      $('#bulk_update_btn').on('click',async function() {
        const commissionType=$("#bulk_commission_type").val();
        const fixCommission=$(".bulk_commission_fix").val().toString();
        const isEnabled=true;
        const adminCommission={"commissionType": commissionType,"fix_commission": fixCommission,"isEnabled": isEnabled};

        const foodRestaurantType=$('#food_restaurant_type').val();
        const selectedIds=$('#food_restaurant').val()||[];

        try {
          let total=0,processed=0;

          const getVendors=async () => {
            if(foodRestaurantType==='all') {
              return await database.collection('vendors').get();
            } else {
              const chunks=[];
              for(let i=0;i<selectedIds.length;i+=10) {
                chunks.push(selectedIds.slice(i,i+10));
              }
              const snapshots=await Promise.all(chunks.map(chunk =>
                database.collection('vendors').where('id','in',chunk).get()
              ));
              return snapshots.flatMap(snapshot => snapshot.docs);
            }
          };

          const vendorsSnapshot=await getVendors();
          total=vendorsSnapshot.length;

          if(total>0) {
            Swal.fire({title: 'Processing...',text: '0% Complete',allowOutsideClick: false,onBeforeOpen: () => Swal.showLoading()});

            for(const doc of vendorsSnapshot) {
              await doc.ref.update({"adminCommission": adminCommission});
              processed++;
              Swal.update({text: `${Math.round((processed/total)*100)}% Complete`});
            }

            Swal.fire('Update Complete!',`${total} vendors updated.`,'success');
          } else {
            Swal.fire('No vendors selected or found!','','warning');
          }
        } catch(error) {
          Swal.fire('Error','An error occurred during the update process.','error');
          console.error('Error:',error);
        }
      });


    })

    function ShowHideDiv() {
      var checkboxValue=$("#enable_commission").is(":checked");
      if(checkboxValue) {
        $(".admin_commision_detail").show();
      } else {
        $(".admin_commision_detail").hide();
      }
    }
  </script>
  @endsection