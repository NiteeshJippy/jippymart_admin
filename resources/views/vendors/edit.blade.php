@extends('layouts.app')
@section('content')
    <?php
    $countries = file_get_contents(public_path('countriesdata.json'));
    $countries = json_decode($countries);
    $countries = (array) $countries;
    $newcountries = [];
    $newcountriesjs = [];
    foreach ($countries as $keycountry => $valuecountry) {
        $newcountries[$valuecountry->phoneCode] = $valuecountry;
        $newcountriesjs[$valuecountry->phoneCode] = $valuecountry->code;
    }
    ?>
    <div class="page-wrapper">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{ trans('lang.vendor_plural2') }}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ trans('lang.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{!! route('restaurants') !!}">{{ trans('lang.vendor_plural2') }}</a></li>
                    <li class="breadcrumb-item active">{{ trans('lang.vendor_edit') }}</li>
                </ol>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="resttab-sec">
                        <div class="menu-tab">
                            <ul>
                                <li class="active restaurantRouteLi" style="display:none;">
                                    <a href="{{ route('vendor.edit', $id) }}">{{ trans('lang.profile') }}</a>
                                </li>
                                <li class="restaurantRouteLi" style="display:none;">
                                    <a class="restaurantRoute">{{ trans('lang.restaurant') }}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="error_top"></div>
                        <div class="row restaurant_payout_create">
                            <div class="restaurant_payout_create-inner">
                                <fieldset>
                                    <legend>{{ trans('lang.admin_area') }}</legend>
                                    <div class="form-group row width-50">
                                        <label class="col-3 control-label">{{ trans('lang.first_name') }}</label>
                                        <div class="col-7">
                                            <input type="text" class="form-control user_first_name" required>
                                            <div class="form-text text-muted">
                                                {{ trans('lang.user_first_name_help') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row width-50">
                                        <label class="col-3 control-label">{{ trans('lang.last_name') }}</label>
                                        <div class="col-7">
                                            <input type="text" class="form-control user_last_name">
                                            <div class="form-text text-muted">
                                                {{ trans('lang.user_last_name_help') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row width-50">
                                        <label class="col-3 control-label">{{ trans('lang.email') }}</label>
                                        <div class="col-7">
                                            <input type="email" class="form-control user_email" disabled required>
                                            <div class="form-text text-muted">
                                                {{ trans('lang.user_email_help') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row width-50">
                                        <label class="col-3 control-label">{{ trans('lang.user_phone') }}</label>
                                        <div class="col-md-12">
                                            <div class="phone-box position-relative" id="phone-box">
                                                <select name="country" id="country_selector">
                                                    <?php foreach ($newcountries as $keycy => $valuecy) { ?>
                                                    <?php $selected = ''; ?>
                                                    <option <?php echo $selected; ?> code="<?php echo $valuecy->code; ?>"
                                                        value="<?php echo $keycy; ?>">
                                                        +<?php echo $valuecy->phoneCode; ?> {{ $valuecy->countryName }}
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                                <input type="text" class="form-control user_phone" disabled
                                                    onkeypress="return chkNumbers(event,'error3')">
                                                <div id="error3" class="err"></div>
                                            </div>
                                        </div>
                                        <div class="form-text text-muted w-50">
                                            {{ trans('lang.user_phone_help') }}
                                        </div>
                                    </div>
                                    <div class="form-group row width-100">
                                        <label class="col-3 control-label">{{ trans('lang.restaurant_image') }}</label>
                                        <input type="file" onChange="handleFileSelectowner(event,'vendor')"
                                            class="col-7">
                                        <div id="uploding_image_owner"></div>
                                        <div class="uploaded_image_owner" style="display:none;">
                                            
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend>{{ trans('lang.restaurant_subscription_model') }}</legend>
                                    <div class="form-group row">
                                        <label class="col-4 control-label">{{ trans('lang.subscription_model') }}</label>
                                        <div class="col-7">
                                            <select class="form-control" id="restaurant_subscription_model">
                                                <option value="" selected>
                                                    {{ trans('lang.select_subscription_plan') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row width-100">
                                        <label class="col-4 control-label">{{ trans('lang.change_expiry_date') }}</label>
                                        <div class="col-7">
                                            <input type="date" name="change_expiry_date" class="form-control"
                                                id="change_expiry_date" value="">
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend>{{ trans('restaurant') }} {{ trans('lang.active_deactive') }}</legend>
                                    <div class="form-group row width-100">
                                        <div class="form-check">
                                            <input type="checkbox" id="is_active">
                                            <label class="col-3 control-label"
                                                for="is_active">{{ trans('lang.active') }}</label>
                                        </div>
                                        <div class="form-check provider_type" style="display:none;">
                                            <input type="checkbox" id="reset_password">
                                            <label class="col-3 control-label"
                                                for="reset_password">{{ trans('lang.reset_restaurant_password') }}</label>
                                            <div class="form-text text-muted w-100 col-12">
                                                {{ trans('lang.note_reset_restaurant_password_email') }}
                                            </div>
                                        </div>
                                        <div class="form-button provider_type"
                                            style="margin-top: 16px;margin-left: 20px;display:none;">
                                            <button type="button" class="btn btn-primary"
                                                id="send_mail">{{ trans('lang.send_mail') }}</button>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend>{{ trans('lang.bankdetails') }}</legend>
                                    <div class="form-group row width-100">
                                        <label class="col-4 control-label">{{ trans('lang.bank_name') }}</label>
                                        <div class="col-7">
                                            <input type="text" name="bank_name" class="form-control" id="bankName">
                                        </div>
                                    </div>
                                    <div class="form-group row width-100">
                                        <label class="col-4 control-label">{{ trans('lang.branch_name') }}</label>
                                        <div class="col-7">
                                            <input type="text" name="branch_name" class="form-control"
                                                id="branchName">
                                        </div>
                                    </div>
                                    <div class="form-group row width-100">
                                        <label class="col-4 control-label">{{ trans('lang.holer_name') }}</label>
                                        <div class="col-7">
                                            <input type="text" name="holer_name" class="form-control"
                                                id="holderName">
                                        </div>
                                    </div>
                                    <div class="form-group row width-100">
                                        <label class="col-4 control-label">{{ trans('lang.account_number') }}</label>
                                        <div class="col-7">
                                            <input type="text" name="account_number" class="form-control"
                                                id="accountNumber">
                                        </div>
                                    </div>
                                    <div class="form-group row width-100">
                                        <label class="col-4 control-label">{{ trans('lang.other_information') }}</label>
                                        <div class="col-7">
                                            <input type="text" name="other_information" class="form-control"
                                                id="otherDetails">
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-12 text-center btm-btn">
                    <button type="button" class="btn btn-primary edit-form-btn"><i class="fa fa-save"></i>
                        {{ trans('lang.save') }}</button>
                    <a href="{!! route('restaurants') !!}" class="btn btn-default"><i
                            class="fa fa-undo"></i>{{ trans('lang.cancel') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.1.1/compressor.min.js"
        integrity="sha512-VaRptAfSxXFAv+vx33XixtIVT9A/9unb1Q8fp63y1ljF+Sbka+eMJWoDAArdm7jOYuLQHVx5v60TQ+t3EA8weA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var user_id = "<?php echo $id; ?>";
        var rest_id = null;
        database.collection('users').doc(user_id).get().then(function(snapshot) {
            var data = snapshot.data();
            if (data.hasOwnProperty('vendorID') && data.vendorID != null && data.vendorID != '') {
                rest_id = data.vendorID;
            }
        });
    
        var placeholderImage = '';
        var placeholder = database.collection('settings').doc('placeHolderImage');
        var database = firebase.firestore();
        var storage = firebase.storage();
        var storageRef = firebase.storage().ref('images');
        var ownerFileName = '';
        var ownerOldImageFile = '';
        var photocount = 0;
        var ownerphoto = '';
        var ownerPhoto = '';
        var ownerId = '';
        database.collection('subscription_plans').where('isEnable', '==', true).orderBy('name', 'asc').get().then(
            snapshots => {
                snapshots.docs.forEach(doc => {
                    const {
                        expiryDay,
                        createdAt,
                        id,
                        name,
                        type
                    } = doc.data();
                    if (expiryDay && createdAt) {
                        const expiryDate = new Date(createdAt.toDate());
                        expiryDate.setDate(expiryDate.getDate() + parseInt(expiryDay, 10));
                        if (type !== "free" && expiryDate > new Date()) {
                            $('#restaurant_subscription_model').append($("<option>").val(id).text(name));
                        } else {
                            $('#restaurant_subscription_model').append($("<option>").val(id).text(name));
                        }
                    }
                });
            });
        $("#send_mail").click(function() {
            if ($("#reset_password").is(":checked")) {
                var email = $(".user_email").val();
                firebase.auth().sendPasswordResetEmail(email)
                    .then((res) => {
                        alert('{{ trans('lang.restaurant_mail_sent') }}');
                    })
                    .catch((error) => {
                        console.log('Error password reset: ', error);
                    });
            } else {
                alert('{{ trans('lang.error_reset_restaurant_password') }}');
            }
        });
        var currentCurrency = '';
        var currencyAtRight = false;
        var refCurrency = database.collection('currencies').where('isActive', '==', true);
        refCurrency.get().then(async function(snapshots) {
            var currencyData = snapshots.docs[0].data();
            currentCurrency = currencyData.symbol;
            currencyAtRight = currencyData.symbolAtRight;
        });
        $(document).ready(async function() {
            jQuery("#data-table_processing").show();
            await placeholder.get().then(async function(snapshotsimage) {
                var placeholderImageData = snapshotsimage.data();
                placeholderImage = placeholderImageData.image;
            });
            jQuery("#country_selector").select2({
                templateResult: formatState,
                templateSelection: formatState2,
                placeholder: "Select Country",
                allowClear: true
            });

            var userRes = await database.collection('users').doc(user_id).get();
            if (userRes.exists) {
                jQuery("#data-table_processing").show();
                var user = userRes.data();
                $(".user_first_name").val(user.firstName);
                $(".user_last_name").val(user.lastName);
                if (user.subscriptionPlanId) {
                    $('#restaurant_subscription_model').val(user.subscriptionPlanId);
                }
                if (user.subscriptionExpiryDate) {
                    const expiresAt = new Date(user.subscriptionExpiryDate.toDate());
                    const [year, month, day] = expiresAt.toISOString().slice(0, 10).split("-");
                    const formattedDate = `${year}-${month}-${day}`;
                    $('#change_expiry_date').val(formattedDate);
                }

                if (user.countryCode) {
                    $("#country_selector").val(user.countryCode.replace('+', '')).trigger('change');
                }
                if (user.email) {
                    $(".user_email").val(shortEmail(user.email));
                }
                if (user.phoneNumber) {
                    $(".user_phone").val(shortEditNumber(user.phoneNumber));
                }
                if (user.provider == "email") {
                    $(".provider_type").show();
                } else {
                    $(".provider_type").hide();
                }
                if (user.hasOwnProperty('profilePictureURL') && user.profilePictureURL != null && user
                    .profilePictureURL != '') {
                    ownerphoto = user.profilePictureURL;
                    ownerOldImageFile = user.profilePictureURL;
                    $(".uploaded_image_owner").html('<img id="uploaded_image_owner" width="150px" height="150px;" src="'+user.profilePictureURL+'" alt="image" onerror="this.onerror=null;this.src=\''+placeholderImage+'\'">');
                    $(".uploaded_image_owner").show();
                } else {
                    $(".uploaded_image_owner").html();
                    $(".uploaded_image_owner").show('<img id="uploaded_image_owner" width="150px" height="150px;" src="'+placeholderImage+'" alt="image">');
                }
                if (user.active) {
                    restaurant_active = true;
                    $("#is_active").prop("checked", true);
                }
                if (user.userBankDetails) {
                    const bankDetails = user.userBankDetails;
                    if (bankDetails.bankName) {
                        $("#bankName").val(bankDetails.bankName);
                    }
                    if (bankDetails.branchName) {
                        $("#branchName").val(bankDetails.branchName);
                    }
                    if (bankDetails.holderName) {
                        $("#holderName").val(bankDetails.holderName);
                    }
                    if (bankDetails.accountNumber) {
                        $("#accountNumber").val(bankDetails.accountNumber);
                    }
                    if (bankDetails.otherDetails) {
                        $("#otherDetails").val(bankDetails.otherDetails);
                    }
                }
                if (user.vendorID != null && user.vendorID != '') {
                    $('.restaurantRouteLi').show();
                    var route1 = '{{ route('restaurants.edit', ':id') }}';
                    route1 = route1.replace(':id', user.vendorID);
                    $('.restaurantRoute').attr('href', route1);
                } else {}
                jQuery("#data-table_processing").hide();
            }
            $(".edit-form-btn").click(async function() {
                var userFirstName = $(".user_first_name").val();
                var userLastName = $(".user_last_name").val();
                var email = $(".user_email").val();
                var countryCode = '+' + jQuery("#country_selector").val();
                var userPhone = $(".user_phone").val();
                var subscriptionPlanId = $('#restaurant_subscription_model').val();
                var subscriptionPlanData = '';
                var change_expiry_date = $('#change_expiry_date').val();
                if (subscriptionPlanId != null && subscriptionPlanId != '') {
                    subscriptionPlanData = (await database.collection('subscription_plans').doc(
                        subscriptionPlanId).get()).data();
                } else {
                    subscriptionPlanData = null;
                    subscriptionPlanId = null;
                }
                if (change_expiry_date != '' && change_expiry_date != null) {
                    var subscriptionPlanExpiryDate = firebase.firestore.Timestamp.fromDate(new Date(
                        change_expiry_date));
                } else {
                    if (subscriptionPlanData && subscriptionPlanData.expiryDay != '-1') {
                        var currentDate = new Date();
                        currentDate.setDate(currentDate.getDate() + parseInt(subscriptionPlanData
                            .expiryDay));
                        var subscriptionPlanExpiryDate = firebase.firestore.Timestamp.fromDate(
                            currentDate);
                    } else {
                        var subscriptionPlanExpiryDate = null
                    }

                }
                var restaurant_active = false;
                if ($("#is_active").is(':checked')) {
                    restaurant_active = true;
                }
                if (userFirstName == '') {
                    showError("{{ trans('lang.enter_owners_name_error') }}");
                } else if (userLastName == '') {
                    showError("{{ trans('lang.enter_owners_last_name_error') }}");
                } else if (userPhone == '') {
                    showError("{{ trans('lang.enter_owners_phone') }}");
                } else if (subscriptionPlanId == '') {
                    showError("{{ trans('lang.subscriptionplan_error') }}");
                } else {
                    jQuery("#data-table_processing").show();
                    var bankDetails = {
                        'bankName': $("#bankName").val(),
                        'branchName': $("#branchName").val(),
                        'holderName': $("#holderName").val(),
                        'accountNumber': $("#accountNumber").val(),
                        'otherDetails': $("#otherDetails").val()
                    };
                    await storeImageData().then(async (IMG) => {
                        updateSubscriptionHistory(user_id, subscriptionPlanId,
                            subscriptionPlanExpiryDate, subscriptionPlanData,rest_id).then(
                            async function() {
                                await database.collection('users').doc(user_id)
                                    .update({
                                        'firstName': userFirstName,
                                        'lastName': userLastName,
                                        'email': email,
                                        'countryCode': countryCode,
                                        'phoneNumber': userPhone,
                                        'profilePictureURL': IMG.ownerImage,
                                        'active': restaurant_active,
                                        'userBankDetails': bankDetails,
                                        'subscriptionExpiryDate': subscriptionPlanExpiryDate
                                    }).then(async function(result) {
                                        if (rest_id != null) {
                                            await geoFirestore.collection('vendors').doc(rest_id).update({
                                                'authorName': userFirstName +' ' +userLastName,
                                                'authorProfilePic': IMG.ownerImage,
                                                'subscriptionExpiryDate': subscriptionPlanExpiryDate,
                                            });
                                        }
                                        jQuery("#data-table_processing").hide();
                                        Swal.fire('Update Complete!',`User updated.`,'success');
                                    });
                            });
                    }).catch(err => {
                        jQuery("#data-table_processing").hide();
                        showError(err);
                    });
                }
            });
        });
        async function updateSubscriptionHistory(user_id, subscriptionPlanId, subscriptionPlanExpiryDate,
            subscriptionPlanData,rest_id) {
            try {
                const userRef = database.collection('users').doc(user_id);
                const userDoc = await userRef.get();
                const data = userDoc.data();
                if (data.subscriptionPlanId !== subscriptionPlanId && subscriptionPlanData != null) {
                    if (rest_id != null) {
                        await geoFirestore
                        .collection('vendors')
                        .doc(rest_id).update({
                            'subscription_plan': subscriptionPlanData,
                            'subscriptionPlanId': subscriptionPlanId,
                            'subscriptionTotalOrders':subscriptionPlanData.orderLimit
                        });
                    }
                    database.collection('users').doc(user_id).update({
                        'subscription_plan': subscriptionPlanData,
                        'subscriptionPlanId': subscriptionPlanId,
                    });
                    var id_order = database.collection("tmp").doc().id;
                    database.collection('subscription_history').doc(id_order).set({
                        'id': id_order,
                        'user_id': user_id,
                        'expiry_date': subscriptionPlanExpiryDate,
                        'createdAt': new Date(),
                        'subscription_plan': subscriptionPlanData,
                        'payment_type': subscriptionPlanData.type
                    });
                }else{
                    const lastSubscriptionHistory = await database.collection('subscription_history').where('user_id','==',user_id).orderBy('createdAt','desc').get();
                    if(lastSubscriptionHistory && lastSubscriptionHistory.docs && lastSubscriptionHistory.docs.length > 0){
                        const subscriptionData = lastSubscriptionHistory.docs[0].data();
                        database.collection('subscription_history').doc(subscriptionData.id).update({
                            'expiry_date': subscriptionPlanExpiryDate,
                        });
                    }
                }
            } catch (error) {
                console.error("Error updating subscription history:", error);
            }
        }

        function showError(message) {
            $(".error_top").show().html("<p>" + message + "</p>");
            window.scrollTo(0, 0);
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

        function formatState(state) {
            if (!state.id) {
                return state.text;
            }
            var baseUrl = "<?php echo URL::to('/'); ?>/scss/icons/flag-icon-css/flags";
            var $state = $(
                '<span><img src="' + baseUrl + '/' + newcountriesjs[state.element.value].toLowerCase() +
                '.svg" class="img-flag" /> ' + state.text + '</span>'
            );
            return $state;
        }

        function formatState2(state) {
            if (!state.id) {
                return state.text;
            }
            var baseUrl = "<?php echo URL::to('/'); ?>/scss/icons/flag-icon-css/flags";
            var $state = $('<span><img class="img-flag" /> <span></span></span>');
            $state.find("span").text(state.text);
            $state.find("img").attr("src", baseUrl + "/" + newcountriesjs[state.element.value].toLowerCase() + ".svg");
            return $state;
        }
        var newcountriesjs = '<?php echo json_encode($newcountriesjs); ?>';
        var newcountriesjs = JSON.parse(newcountriesjs);
        async function storeImageData() {
            var newPhoto = [];
            newPhoto['ownerImage'] = ownerphoto;
            try {
                if (ownerphoto != '' && ownerphoto != null) {
                    if (ownerOldImageFile != "" && ownerOldImageFile != null && ownerphoto != ownerOldImageFile) {
                        var ownerOldImageUrlRef = await storage.refFromURL(ownerOldImageFile);
                        imageBucket = ownerOldImageUrlRef.bucket;
                        var envBucket = "<?php echo env('FIREBASE_STORAGE_BUCKET'); ?>";
                        if (imageBucket == envBucket) {
                            await ownerOldImageUrlRef.delete().then(() => {
                                console.log("Old file deleted!")
                            }).catch((error) => {
                                console.log("ERR File delete ===", error);
                            });
                        } else {
                            console.log('Bucket not matched');
                        }
                    }
                    if (ownerphoto != ownerOldImageFile) {
                        ownerphoto = ownerphoto.replace(/^data:image\/[a-z]+;base64,/, "")
                        var uploadTask = await storageRef.child(ownerFileName).putString(ownerphoto, 'base64', {
                            contentType: 'image/jpg'
                        });
                        var downloadURL = await uploadTask.ref.getDownloadURL();
                        newPhoto['ownerImage'] = downloadURL;
                        ownerphoto = downloadURL;
                    }
                }
            } catch (error) {
                console.log("ERR ===", error);
            }
            return newPhoto;
        }

        function handleFileSelectowner(evt) {
            var f = evt.target.files[0];
            var reader = new FileReader();
            new Compressor(f, {
                quality: <?php echo env('IMAGE_COMPRESSOR_QUALITY', 0.8); ?>,
                success(result) {
                    f = result;
                    reader.onload = (function(theFile) {
                        return function(e) {
                            var filePayload = e.target.result;
                            var val = f.name;
                            var ext = val.split('.')[1];
                            var docName = val.split('fakepath')[1];
                            var filename = (f.name).replace(/C:\\fakepath\\/i, '');
                            var timestamp = Number(new Date());
                            var filename = filename.split('.')[0] + "_" + timestamp + '.' + ext;
                            ownerphoto = filePayload;
                            ownerFileName = filename;
                            $("#uploaded_image_owner").attr('src', ownerphoto);
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
    </script>
@endsection
