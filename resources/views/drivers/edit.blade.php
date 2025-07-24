@extends('layouts.app')
@section('content')
<?php
$countries = file_get_contents(public_path('countriesdata.json'));
$countries = json_decode($countries);
$countries = (array)$countries;
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
            <h3 class="text-themecolor">{{trans('lang.driver_plural')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{!! route('drivers') !!}">{{trans('lang.driver_plural')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.driver_edit')}}</li>
            </ol>
        </div>
     
    </div>
  
     
        <div class="container-fluid">
            
      <div class="resttab-sec mb-4">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <a href="{{route('orders')}}?driverId={{$id}}">
                    <div class="card card-box-with-icon bg--1">
                        <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="card-box-with-content">
                            <h4 class="text-dark-2 mb-1 h4 rest_count" id="total_orders">1</h4>
                            <p class="mb-0 small text-dark-2">{{trans('lang.dashboard_total_orders')}}</p>
                        </div>
                            <span class="box-icon ab"><img src="https://staging.foodie.siswebapp.com/images/active_restaurant.png"></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{route('payoutRequests.drivers.view',$id)}}">
                    <div class="card card-box-with-icon bg--2">
                        <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="card-box-with-content">
                            <h4 class="text-dark-2 mb-1 h4 wallet_amount" id="wallet_amount">$0.00</h4>
                            <p class="mb-0 small text-dark-2">{{trans('lang.wallet_Balance')}}</p>
                        </div>
                            <span class="box-icon ab"><img src="https://staging.foodie.siswebapp.com/images/total_earning.png"></span>
                        </div>
                    </div>
                </a>
            </div>
         
          
        </div>
      </div>



            <div class="card-body">
                <div class="error_top"></div>
                <div class="row restaurant_payout_create">
                    <div class="restaurant_payout_create-inner">
                        <fieldset>
                            <legend>{{trans('lang.driver_details')}}</legend>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.first_name')}}</label>
                                <div class="col-7">
                                    <input type="text" class="form-control user_first_name">
                                    <div class="form-text text-muted">{{trans('lang.first_name_help')}}</div>
                                </div>
                            </div>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.last_name')}}</label>
                                <div class="col-7">
                                    <input type="text" class="form-control user_last_name">
                                    <div class="form-text text-muted">{{trans('lang.last_name_help')}}</div>
                                </div>
                            </div>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.email')}}</label>
                                <div class="col-7">
                                    <input type="text" class="form-control user_email" disabled>
                                    <div class="form-text text-muted">{{trans('lang.user_email_help')}}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 control-label">{{trans('lang.user_phone')}}</label>
                                <div class="col-md-6">
                                    <div class="phone-box position-relative" id="phone-box"> 
                                        <select name="country" id="country_selector">
                                            <?php foreach ($newcountries as $keycy => $valuecy) { ?>
                                            <?php $selected = ""; ?>
                                            <option <?php echo $selected; ?> code="<?php echo $valuecy->code; ?>"
                                                    value="<?php echo $keycy; ?>">
                                                +<?php echo $valuecy->phoneCode; ?> {{$valuecy->countryName}}</option>
                                            <?php } ?>
                                        </select>
                                        <input type="text" class="form-control user_phone" disabled onkeypress="return chkAlphabets2(event,'error2')">
                                        <div id="error2" class="err"></div>
                                    </div>
                                </div>
                                    <div class="form-text text-muted">{{trans('lang.user_phone_help')}}</div>
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
                            <div class="form-group row width-100">
                                <div class="col-12">
                                    <h6>{{ trans("lang.know_your_cordinates") }}<a target="_blank" href="https://www.latlong.net/">{{
                                            trans("lang.latitude_and_longitude_finder") }}</a></h6>
                                </div>
                            </div>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.user_latitude')}}</label>
                                <div class="col-7">
                                    <input type="number" class="form-control user_latitude">
                                    <div class="form-text text-muted">{{trans('lang.user_latitude_help')}}</div>
                                </div>
                            </div>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.user_longitude')}}</label>
                                <div class="col-7">
                                    <input type="number" class="form-control user_longitude">
                                    <div class="form-text text-muted">{{trans('lang.user_longitude_help')}}</div>
                                </div>
                            </div>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.profile_image')}}</label>
                                <div class="col-7">
                                    <input type="file" onChange="handleFileSelect(event)" class="">
                                    <div class="form-text text-muted">{{trans('lang.profile_image_help')}}</div>
                                </div>
                                <div class="placeholder_img_thumb user_image">
                                </div>
                                <div id="uploding_image"></div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>{{trans('driver')}} {{trans('lang.active_deactive')}}</legend>
                            <div class="form-group row width-100">
                                <div class="form-check">
                                    <input type="checkbox" id="is_active">
                                    <label class="col-3 control-label" for="is_active">{{trans('lang.active')}}</label>
                                </div>
                                <div class="form-check provider_type">
                                    <input type="checkbox" id="reset_password">
                                    <label class="col-3 control-label" for="reset_password">{{trans('lang.reset_driver_password')}}</label>
                                    <div class="form-text text-muted w-100">
                                        {{ trans("lang.note_reset_driver_password_email") }}
                                    </div>
                                </div>
                                <div class="form-button provider_type" style="margin-top: 16px;margin-left: 20px;">
                                    <button type="button" class="btn btn-primary" id="send_mail">{{trans('lang.send_mail')}}
                                    </button>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>{{trans('lang.bankdetails')}}</legend>
                            <div class="form-group row">
                                <div class="form-group row width-100">
                                    <label class="col-4 control-label">{{
                                        trans('lang.bank_name')}}</label>
                                    <div class="col-7">
                                        <input type="text" name="bank_name" class="form-control" id="bankName">
                                    </div>
                                </div>
                                <div class="form-group row width-100">
                                    <label class="col-4 control-label">{{
                                        trans('lang.branch_name')}}</label>
                                    <div class="col-7">
                                        <input type="text" name="branch_name" class="form-control" id="branchName">
                                    </div>
                                </div>
                                <div class="form-group row width-100">
                                    <label class="col-4 control-label">{{
                                        trans('lang.holer_name')}}</label>
                                    <div class="col-7">
                                        <input type="text" name="holer_name" class="form-control" id="holderName">
                                    </div>
                                </div>
                                <div class="form-group row width-100">
                                    <label class="col-4 control-label">{{
                                        trans('lang.account_number')}}</label>
                                    <div class="col-7">
                                        <input type="text" name="account_number" class="form-control" id="accountNumber">
                                    </div>
                                </div>
                                <div class="form-group row width-100">
                                    <label class="col-4 control-label">{{
                                        trans('lang.other_information')}}</label>
                                    <div class="col-7">
                                        <input type="text" name="other_information" class="form-control" id="otherDetails">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="form-group col-12 text-center btm-btn">
                <button type="button" class="btn btn-primary edit-form-btn"><i class="fa fa-save"></i> {{
                    trans('lang.save')}}
                </button>
                <a href="{!! route('drivers') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{
                    trans('lang.cancel')}}</a>
            </div>
        </div>




</div>
@endsection
@section('scripts')
<script>
    var id = "<?php echo $id; ?>";
    var database = firebase.firestore();
    var ref = database.collection('users').where("id", "==", id);
    var photo = "";
    var fileName='';
    var userImageFile='';
    var placeholderImage = '';
    var placeholder = database.collection('settings').doc('placeHolderImage');
    var user_active_deactivate = false;
    var currentCurrency = '';
    var provider  = '';
    var currencyAtRight = false;
    var decimal_degits = 0;
    placeholder.get().then(async function(snapshotsimage) {
        var placeholderImageData = snapshotsimage.data();
        placeholderImage = placeholderImageData.image;
    })
    var refData = ref.get().then(async function(snapshots) {
        var userData = snapshots.docs[0].data();
        provider = userData.provider;
        if(!userData.hasOwnProperty("provider")){
            $(".provider_type").show();
        }
        else if(userData.hasOwnProperty("provider") && provider == "email"){
            $(".provider_type").show();
        }
        else
        {
            $(".provider_type").hide();
        }
    });
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    var append_list = '';
    refCurrency.get().then(async function(snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });
    database.collection('zone').where('publish', '==', true).orderBy('name','asc').get().then(async function (snapshots) {
        snapshots.docs.forEach((listval) => {
            var data = listval.data();
            $('#zone').append($("<option></option>")
                .attr("value", data.id)
                .text(data.name));
        })
    });
    $("#send_mail").click(function() {
        if ($("#reset_password").is(":checked")) {
            var email = $(".user_email").val();
            firebase.auth().sendPasswordResetEmail(email)
                .then((res) => {
                    alert('{{trans("lang.driver_mail_sent")}}');
                })
                .catch((error) => {
                    console.log('Error password reset: ', error);
                });
        } else {
            alert('{{trans("lang.error_reset_driver_password")}}');
        }
    });
    $(document).ready(function() {
        jQuery("#data-table_processing").show();
        jQuery("#country_selector").select2({
            templateResult: formatState,
            templateSelection: formatState2,
            placeholder: "Select Country",
            allowClear: true
        });
        ref.get().then(async function(snapshots) {
            if(!snapshots.empty){
                var user = snapshots.docs[0].data();
                $(".user_first_name").val(user.firstName);  
                $(".user_last_name").val(user.lastName);
                if(user.email != ""){
                    $(".user_email").val(shortEmail(user.email));
                }
                else{
                    $(".user_email").val("");
                }
                if(user.phoneNumber != ""){
                    $(".user_phone").val(shortEditNumber(user.phoneNumber));
                }
                else
                {
                    $(".user_phone").val("");
                }
                if (user.hasOwnProperty('location') ) {
                    if (Number.isNaN(user.location.latitude)) {
                        $(".user_latitude").val("");
                    } else {
                        $(".user_latitude").val(user.location.latitude);
                    }
                    if (Number.isNaN(user.location.longitude)) {
                        $(".user_longitude").val("");
                    }
                    else
                    {
                        $(".user_longitude").val(user.location.longitude);
                    }
                }
                if (user.isActive) {
                    $(".user_active").prop('checked', true);
                }
                if (user.active) {
                    $("#is_active").prop("checked", true);
                    user_active_deactivate = true;
                }
                if (user.profilePictureURL != '' && user.profilePictureURL != null) {
                    photo = user.profilePictureURL;
                    userImageFile = user.profilePictureURL;
                    $(".user_image").append('<img onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" class="rounded" style="width:50px" src="' + photo + '" alt="image">');
                } else {
                    $(".user_image").append('<img class="rounded" style="width:50px" src="' + placeholderImage + '" alt="image">');
                }
                if (user.hasOwnProperty('zoneId') && user.zoneId != '') {
                    $("#zone").val(user.zoneId);
                }
                var orderRef = database.collection('restaurant_orders').where("driverID", "==", id);
                orderRef.get().then(async function(snapshotsorder) {
                    var orders = snapshotsorder.size;
                    $("#total_orders").text(orders);
                });
                if (currencyAtRight) {
                    var wallet_amount = parseFloat(user.wallet_amount).toFixed(decimal_degits) + currentCurrency;
                } else {
                    var wallet_amount = currentCurrency + parseFloat(user.wallet_amount).toFixed(decimal_degits);
                }
                if (user.wallet_amount) {
                    $('#wallet_amount').text(wallet_amount);
                }
                if (isNaN(user.wallet_amount)) {
                    if (currencyAtRight) {
                        var wallet_amount = parseFloat(0).toFixed(decimal_degits) + currentCurrency;
                    } else {
                        var wallet_amount = currentCurrency + parseFloat(0).toFixed(decimal_degits);
                    }
                    $("#wallet_amount").text(wallet_amount);
                }
                if (user.userBankDetails) {
                    if (user.userBankDetails.bankName != undefined) {
                        $("#bankName").val(user.userBankDetails.bankName);
                    }
                    if (user.userBankDetails.branchName != undefined) {
                        $("#branchName").val(user.userBankDetails.branchName);
                    }
                    if (user.userBankDetails.holderName != undefined) {
                        $("#holderName").val(user.userBankDetails.holderName);
                    }
                    if (user.userBankDetails.accountNumber != undefined) {
                        $("#accountNumber").val(user.userBankDetails.accountNumber);
                    }
                    if (user.userBankDetails.otherDetails != undefined) {
                        $("#otherDetails").val(user.userBankDetails.otherDetails);
                    }
                }
                jQuery("#data-table_processing").hide();
            }
        })
        $(".edit-form-btn").click(function() {
            var userFirstName = $(".user_first_name").val();
            var userLastName = $(".user_last_name").val();
            var email = $(".user_email").val();
            var countryCode = '+' + jQuery("#country_selector").val();
            var userPhone = $(".user_phone").val();
            var zoneId = $('#zone option:selected').val();
            var active = $(".user_active").is(":checked");
            var user_active_deactivate = false;
            if ($("#is_active").is(':checked')) {
                user_active_deactivate = true;
            }
            var latitude = parseFloat($(".user_latitude").val());
            var longitude = parseFloat($(".user_longitude").val());
            if (userFirstName == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.enter_owners_name_error')}}</p>");
                window.scrollTo(0, 0);
            } else if (userLastName == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.enter_owners_last_name_error')}}</p>");
                window.scrollTo(0, 0);
            } else if (userPhone == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.enter_owners_phone')}}</p>");
                window.scrollTo(0, 0);
            } else if (zoneId == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.select_zone_help')}}</p>");
                window.scrollTo(0, 0);
            } 
            else {
                var bankName = $("#bankName").val();
                var branchName = $("#branchName").val();
                var holderName = $("#holderName").val();
                var accountNumber = $("#accountNumber").val();
                var otherDetails = $("#otherDetails").val();
                var userBankDetails = {
                    'bankName': bankName,
                    'branchName': branchName,
                    'holderName': holderName,
                    'accountNumber': accountNumber,
                    'accountNumber': accountNumber,
                    'otherDetails': otherDetails,
                };
                jQuery("#data-table_processing").show();
                storeImageData().then(IMG => {
                        database.collection('users').doc(id).update({
                            'firstName': userFirstName,
                            'lastName': userLastName,
                            'email': email,
                            'countryCode': countryCode,
                            'phoneNumber': userPhone,
                            'isActive': active,
                            'profilePictureURL': IMG,
                            'location.latitude': latitude,
                            'location.longitude': longitude,
                            'role': 'driver',
                            'active': user_active_deactivate,
                            'userBankDetails': userBankDetails,
                            'zoneId': zoneId
                        }).then(function(result) {
                            jQuery("#data-table_processing").hide();
                            window.location.href = '{{ route("drivers")}}';
                        });
                }).catch(err => {
                    jQuery("#data-table_processing").hide();
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>" + err + "</p>");
                    window.scrollTo(0, 0);
                });
            }
        })
    })
    var storageRef = firebase.storage().ref('images');
    var storage = firebase.storage();
    async function storeImageData() {
        var newPhoto = '';
        try {
            if (userImageFile != "" && photo != userImageFile) {
                var userOldImageUrlRef = await storage.refFromURL(userImageFile);
                imageBucket = userOldImageUrlRef.bucket; 
                    var envBucket = "<?php echo env('FIREBASE_STORAGE_BUCKET'); ?>";
                    if (imageBucket == envBucket) {
                        await userOldImageUrlRef.delete().then(() => {
                            console.log("Old file deleted!")
                        }).catch((error) => {
                            console.log("ERR File delete ===", error);
                        });
                    } else {
                        console.log('Bucket not matched');  
                    }
            }
            if (photo != userImageFile) {
                photo = photo.replace(/^data:image\/[a-z]+;base64,/, "")
                var uploadTask = await storageRef.child(fileName).putString(photo, 'base64', {
                    contentType: 'image/jpg'
                });
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
    function formatState(state) {
            if (!state.id) {
                return state.text;
            }
            var baseUrl = "<?php echo URL::to('/');?>/scss/icons/flag-icon-css/flags";
            var $state = $(
                '<span><img src="' + baseUrl + '/' + newcountriesjs[state.element.value].toLowerCase() + '.svg" class="img-flag" /> ' + state.text + '</span>'
            );
            return $state;
    }
        function formatState2(state) {
            if (!state.id) {
                return state.text;
            }
            var baseUrl = "<?php echo URL::to('/');?>/scss/icons/flag-icon-css/flags"
            var $state = $(
                '<span><img class="img-flag" /> <span></span></span>'
            );
            $state.find("span").text(state.text);
            $state.find("img").attr("src", baseUrl + "/" + newcountriesjs[state.element.value].toLowerCase() + ".svg");
            return $state;
        }
        var newcountriesjs = '<?php echo json_encode($newcountriesjs); ?>';
        var newcountriesjs = JSON.parse(newcountriesjs);
    function handleFileSelect(evt) {
        var f = evt.target.files[0];
        var reader = new FileReader();
        reader.onload = (function(theFile) {
            return function(e) {
                var filePayload = e.target.result;
                var val = f.name;
                var ext = val.split('.')[1];
                var docName = val.split('fakepath')[1];
                var filename = (f.name).replace(/C:\\fakepath\\/i, '')
                var timestamp = Number(new Date());
                var filename = filename.split('.')[0] + "_" + timestamp + '.' + ext;
                photo = filePayload;
                fileName = filename;
                $(".user_image").empty();
                $(".user_image").append('<img class="rounded" style="width:50px" src="' + photo + '" alt="image">');
            };
        })(f);
        reader.readAsDataURL(f);
    }
    function chkAlphabets2(event, msg) {
        if (!(event.which >= 48 && event.which <= 57)) {
            document.getElementById(msg).innerHTML = "Accept only Number";
            return false;
        } else {
            document.getElementById(msg).innerHTML = "";
            return true;
        }
    }
</script>   
@endsection