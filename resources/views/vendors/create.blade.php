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
            <h3 class="text-themecolor">{{trans('lang.create_vendor')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item"><a
                        href="{!! route('restaurants') !!}">{{trans('lang.restaurant_plural')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.create_vendor')}}</li>
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
                            <legend>{{trans('lang.admin_area')}}</legend>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.first_name')}}</label>
                                <div class="col-7">
                                    <input type="text" class="form-control user_first_name" required>
                                    <div class="form-text text-muted">
                                        {{ trans("lang.user_first_name_help") }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.last_name')}}</label>
                                <div class="col-7">
                                    <input type="text" class="form-control user_last_name">
                                    <div class="form-text text-muted">
                                        {{ trans("lang.user_last_name_help") }}
                                    </div>
                                </div>
                            </div>


                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.email')}}</label>
                                <div class="col-7">
                                    <input type="email" class="form-control user_email" required>
                                    <div class="form-text text-muted">
                                        {{ trans("lang.user_email_help") }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.password')}}</label>
                                <div class="col-7">
                                    <input type="password" class="form-control user_password res_password" required>
                                    <div class="form-text text-muted">
                                        {{ trans("lang.user_password_help") }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-3 control-label">{{trans('lang.user_phone')}}</label>
                                <div class="col-md-6">
                                    <div class="phone-box position-relative" id="phone-box">
                                        <select name="country" id="country_selector">
                                            <?php foreach ($newcountries as $keycy => $valuecy) { ?>
                                            <?php    $selected = ""; ?>
                                            <option <?php    echo $selected; ?> code="<?php    echo $valuecy->code; ?>"
                                                value="<?php    echo $keycy; ?>">
                                                +<?php    echo $valuecy->phoneCode; ?> {{$valuecy->countryName}}
                                            </option>
                                            <?php } ?>
                                        </select>


                                        <input type="text" class="form-control user_phone"
                                            onkeypress="return chkAlphabets2(event,'error2')">
                                        <div id="error2" class="err"></div>
                                    </div>
                                </div>
                                <div class="form-text text-muted w-50">
                                    {{ trans("lang.user_phone_help") }}
                                </div>
                            </div>

                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.restaurant_image')}}</label>
                                <input type="file" onChange="handleFileSelectowner(event)" class="col-7">
                                <div id="uploding_image_owner"></div>
                                <div class="uploaded_image_owner" style="display:none;"><img id="uploaded_image_owner"
                                        src="" width="150px" height="150px;"></div>
                            </div>

                        </fieldset>
                        <fieldset>
                            <legend>{{ trans('lang.subscription_details') }}</legend>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{ trans('lang.select_subscription_plan') }}</label>
                                <div class="col-7">
                                    <select class="form-control" id="subscription_plan">
                                        <option value="" selected> {{ trans('lang.select_subscription_plan') }}</option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>{{trans('restaurant')}} {{trans('lang.active_deactive')}}</legend>
                            <div class="form-group row">

                                <div class="form-group row width-50">
                                    <div class="form-check width-100">
                                        <input type="checkbox" id="is_active">
                                        <label class="col-3 control-label"
                                            for="is_active">{{trans('lang.active')}}</label>
                                    </div>
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
    var restaurantOwnerId="";
    var restaurantOwnerOnline=false;
    var ownerphoto='';
    var ownerFileName='';

    var restaurant_id=database.collection("tmp").doc().id;
    var subscriptionPlanRef=database.collection('subscription_plans');

    database.collection('settings').doc("AdminCommission").get().then(async function(snapshots) {
        var adminCommissionSettings=snapshots.data();
        $(".commission_fix").val(adminCommissionSettings.fix_commission);
        $("#commission_type").val(adminCommissionSettings.commissionType);
    });

    var email_templates=database.collection('email_templates').where('type','==','new_vendor_signup');

    var emailTemplatesData=null;

    var adminEmail='';

    var emailSetting=database.collection('settings').doc('emailSetting');

    var currentCurrency='';
    var currencyAtRight=false;
    var refCurrency=database.collection('currencies').where('isActive','==',true);
    refCurrency.get().then(async function(snapshots) {
        var currencyData=snapshots.docs[0].data();
        currentCurrency=currencyData.symbol;
        currencyAtRight=currencyData.symbolAtRight;
    });


    $(document).ready(async function() {

        jQuery("#country_selector").select2({
            templateResult: formatState,
            templateSelection: formatState2,
            placeholder: "Select Country",
            allowClear: true
        });
        subscriptionPlanRef.where('isEnable','==',true).get().then(async function(
            snapshots) {
            snapshots.docs.forEach((listval) => {
                var data=listval.data();
                $('#subscription_plan').append($("<option></option>")
                    .attr("value",data.id)
                    .text(data.name));
            });
        });
        jQuery("#data-table_processing").show();

        await email_templates.get().then(async function(snapshots) {
            emailTemplatesData=snapshots.docs[0].data();
        });

        await emailSetting.get().then(async function(snapshots) {
            var emailSettingData=snapshots.data();

            adminEmail=emailSettingData.userName;
        });

        jQuery("#data-table_processing").hide();
    })

    $(".save-form-btn").click(async function() {

        $(".error_top").hide();

        var userFirstName=$(".user_first_name").val();
        var userLastName=$(".user_last_name").val();
        var email=$(".user_email").val();
        var password=$(".user_password").val();
        var country_code=$("#country_selector").val();
        var userPhone=$(".user_phone").val();
        var reststatus=true;

        var restaurant_active=false;
        if($("#is_active").is(':checked')) {
            restaurant_active=true;
        }

        var user_name=userFirstName+" "+userLastName;
        var user_id="<?php echo uniqid(); ?>";
        var name=userFirstName+" "+userLastName;

        var subscriptionPlanId=$('#subscription_plan').val();

        if(userFirstName=='') {

            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.enter_owners_name_error')}}</p>");
            window.scrollTo(0,0);
        } else if(userLastName=='') {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.enter_owners_last_name_error')}}</p>");
            window.scrollTo(0,0);
        } else if(email=='') {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.enter_owners_email')}}</p>");
            window.scrollTo(0,0);
        } else if(email=='') {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.enter_owners_email')}}</p>");
            window.scrollTo(0,0);
        } else if(password=='') {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.enter_owners_password_error')}}</p>");
            window.scrollTo(0,0);
        } else if(!country_code) {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.select_country_code')}}</p>");
            window.scrollTo(0,0);
        } else if(userPhone=='') {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.enter_owners_phone')}}</p>");
            window.scrollTo(0,0);
        } else {
            jQuery("#data-table_processing").show();
            if(subscriptionPlanId&&subscriptionPlanId!='') {
                var subscriptionData=await getSubscriptionDetails(subscriptionPlanId);
            } else {
                var subscriptionData=null;
            }
            firebase.auth().createUserWithEmailAndPassword(email,password)
                .then(async function(firebaseUser) {
                    user_id=firebaseUser.user.uid;
                    await storeImageData().then(async (IMG) => {
                                database.collection('users').doc(user_id).set({
                                    'appIdentifier': "web",
                                    'firstName': userFirstName,
                                    'lastName': userLastName,
                                    'email': email,
                                    'countryCode': country_code,
                                    'phoneNumber': userPhone,
                                    'profilePictureURL': IMG.ownerImage,
                                    'provider': 'email',
                                    'role': 'vendor',
                                    'id': user_id,
                                    'vendorID': null,
                                    'active': restaurant_active,
                                    'createdAt': createdAt,
                                    'isDocumentVerify': false,
                                    'subscription_plan': subscriptionData!=null? subscriptionData:null,
                                    'subscriptionPlanId': subscriptionData!=null? subscriptionData.id:null,
                                    'subscriptionExpiryDate': subscriptionData!=null? subscriptionData.expiryDate:null
                                }).then(async function(result) {
                                    if(subscriptionData!=null) {
                                        historyData={'subscriptionData': subscriptionData,'userId': user_id,'expire_date': subscriptionData.expiryDate}
                                        await addSubscriptionHistory(historyData);
                                    }
                                    var formattedDate=new Date();
                                        var month=formattedDate.getMonth()+1;
                                        var day=formattedDate.getDate();
                                        var year=formattedDate.getFullYear();

                                        month=month<10? '0'+month:month;
                                        day=day<10? '0'+day:day;

                                        formattedDate=day+'-'+month+'-'+year;

                                        var message=emailTemplatesData.message;
                                        message=message.replace(/{userid}/g,user_id);
                                        message=message.replace(/{username}/g,userFirstName+' '+userLastName);
                                        message=message.replace(/{useremail}/g,email);
                                        message=message.replace(/{userphone}/g,userPhone);

                                        message=message.replace(/{date}/g,formattedDate);

                                        emailTemplatesData.message=message;

                                        var url="{{url('send-email')}}";

                                        var sendEmailStatus=await sendEmail(url,emailTemplatesData.subject,emailTemplatesData.message,[adminEmail]);

                                        if(sendEmailStatus) {
                                            jQuery("#data-table_processing").hide();
                                            window.location.href='{{ route("vendors")}}';
                                        }

                                })
                         
                    }).catch(err => {
                        jQuery("#data-table_processing").hide();
                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append("<p>"+err+"</p>");
                        window.scrollTo(0,0);
                    });

                }).catch(function(error) {
                    jQuery("#data-table_processing").hide();

                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>"+error+"</p>");
                });
        }

    })


    async function storeImageData() {
        var newPhoto=[];
        newPhoto['ownerImage']='';
        try {
            if(ownerphoto!='') {
                ownerphoto=ownerphoto.replace(/^data:image\/[a-z]+;base64,/,"")
                var uploadTask=await storageRef.child(ownerFileName).putString(ownerphoto,'base64',{
                    contentType: 'image/jpg'
                });
                var downloadURL=await uploadTask.ref.getDownloadURL();
                newPhoto['ownerImage']=downloadURL;
                ownerphoto=downloadURL;
            }
        } catch(error) {
            console.log("ERR ===",error);
        }
        return newPhoto;
    }

    function handleFileSelectowner(evt) {
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
                        ownerphoto=filePayload;
                        ownerFileName=filename;
                        $("#uploaded_image_owner").attr('src',ownerphoto);
                        $(".uploaded_image_owner").show();

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

    async function getSubscriptionDetails(subscriptionId) {
        var data='';
        await database.collection('subscription_plans').where('id','==',subscriptionId).get().then(async function(
            snapshot) {
            data=snapshot.docs[0].data();
            var currentDate=new Date();
            if(data.expiryDay!='-1') {
                currentDate.setDate(currentDate.getDate()+parseInt(data.expiryDay));
                data.expiryDate=firebase.firestore.Timestamp.fromDate(currentDate);
            } else {
                data.expiryDate=null;
            }

        })
        return data;
    }
    async function addSubscriptionHistory(historyData) {
        var id_order=database.collection('tmp').doc().id;
        var createdAt=firebase.firestore.FieldValue.serverTimestamp();

        var userId=historyData.userId;
        await database.collection('subscription_history').doc(id_order).set({
            'id': id_order,
            'user_id': historyData.userId,
            'expiry_date': historyData.expire_date,
            'createdAt': createdAt,
            'subscription_plan': historyData.subscriptionData,
            'payment_type': 'cod'
        })
    }

</script>
@endsection