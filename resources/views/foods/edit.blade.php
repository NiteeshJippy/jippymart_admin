@extends('layouts.app')
@section('content')
    <div class="page-wrapper">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{trans('lang.food_plural')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">{{trans('lang.dashboard')}}</a></li>
                    <?php if(isset($_GET['eid']) && $_GET['eid'] != ''){?>
                    <li class="breadcrumb-item"><a
                                href="{{route('restaurants.foods',$_GET['eid'])}}">{{trans('lang.food_plural')}}</a>
                    </li>
                    <?php }else{ ?>
                    <li class="breadcrumb-item"><a href="{!! route('foods') !!}">{{trans('lang.food_plural')}}</a></li>
                    <?php } ?>
                    <li class="breadcrumb-item active">{{trans('lang.food_edit')}}</li>
                </ol>
            </div>
        </div>
        <div>
            <div class="card-body">
                <div class="error_top" style="display:none"></div>
                <div class="row restaurant_payout_create">
                    <div class="restaurant_payout_create-inner">
                        <fieldset>
                            <legend>{{trans('lang.food_information')}}</legend>
                            <div class="form-group row width-100" id="admin_commision_info" style="display:none">
                                <div class="m-3">
                                    <div class="form-text font-weight-bold text-danger h6">{{trans('lang.price_instruction')}}</div>
                                    <div class="form-text font-weight-bold text-danger h6" id="admin_commision"></div>
                                </div>
                            </div>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.food_name')}}</label>
                                <div class="col-7">
                                    <input type="text" class="form-control food_name" required>
                                    <div class="form-text text-muted">
                                        {{ trans("lang.food_name_help") }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.food_price')}}</label>
                                <div class="col-7">
                                    <input type="text" class="form-control food_price" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" required>
                                </div>
                            </div>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.food_discount')}}</label>
                                <div class="col-7">
                                    <input type="text" class="form-control food_discount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                    <div class="form-text text-muted">
                                        {{ trans("lang.food_discount_help") }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row width-50 food_restaurant_div">
                                <label class="col-3 control-label">{{trans('lang.food_restaurant_id')}}</label>
                                <div class="col-7">
                                    <select id="food_restaurant" class="form-control" required>
                                        <option value="">{{trans('lang.select_restaurant')}}</option>
                                    </select>
                                    <div class="form-text text-muted">
                                        {{ trans("lang.food_restaurant_id_help") }}
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.food_category_id')}}</label>
                                <div class="col-7">
                                    <select id='food_category' class="form-control" required>
                                        <option value="">{{trans('lang.select_category')}}</option>
                                    </select>
                                    <div class="form-text text-muted">
                                        {{ trans("lang.food_category_id_help") }}
                                    </div>
                                </div>
                            </div> -->
                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.food_category_id')}}</label>
                                <div class="col-7">
                                    <div id="selected_categories" class="mb-2"></div>
                                    <input type="text" id="food_category_search" class="form-control mb-2" placeholder="Search categories...">
                                    <select id='food_category' class="form-control" multiple required>
                                        <option value="">Select categories</option>
                                        <!-- options populated dynamically -->
                                    </select>
                                    <div class="form-text text-muted">
                                     {{ trans('lang.food_category_id_help') }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.item_quantity')}}</label>
                                <div class="col-7">
                                    <input type="number" class="form-control item_quantity" value="-1">
                                    <div class="form-text text-muted">
                                        {{ trans("lang.item_quantity_help") }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row width-100" id="attributes_div">
                                <label class="col-3 control-label">{{trans('lang.item_attribute_id')}}</label>
                                <div class="col-7">
                                    <select id='item_attribute' class="form-control chosen-select" required
                                            multiple="multiple"
                                            onchange="selectAttribute();"></select>
                                </div>
                            </div>
                            <div class="form-group row width-100">
                                <div class="item_attributes" id="item_attributes"></div>
                                <div class="item_variants" id="item_variants"></div>
                                <input type="hidden" id="attributes" value=""/>
                                <input type="hidden" id="variants" value=""/>
                            </div>
                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.food_image')}}</label>
                                <div class="col-7">
                                    <input type="file" id="product_image">
                                    <div class="placeholder_img_thumb product_image"></div>
                                    <div id="uploding_image"></div>
                                    <div class="form-text text-muted">
                                        {{ trans("lang.food_image_help") }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.food_description')}}</label>
                                <div class="col-7">
                                    <textarea rows="8" class="form-control food_description"
                                              id="food_description"></textarea>
                                </div>
                            </div>
                            <div class="form-check width-100">
                                <input type="checkbox" class="food_publish" id="food_publish">
                                <label class="col-3 control-label"
                                       for="food_publish">{{trans('lang.food_publish')}}</label>
                            </div>
                            <div class="form-check width-100">
                                <input type="checkbox" class="food_nonveg" id="food_nonveg">
                                <label class="col-3 control-label" for="food_nonveg">{{ trans('lang.non_veg')}}</label>
                            </div>
                            <div class="form-check width-100" style="display: none">
                                <input type="checkbox" class="food_take_away_option" id="food_take_away_option">
                                <label class="col-3 control-label"
                                       for="food_take_away_option">{{trans('lang.food_take_away')}}</label>
                            </div>
                            <div class="form-check width-100">
                                <input type="checkbox" class="food_is_available" id="food_is_available">
                                <label class="col-3 control-label" for="food_is_available">Available</label>
                            </div>
                        </fieldset>
                        <fieldset style="display: none">
                            <legend>{{trans('lang.ingredients')}}</legend>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.calories')}}</label>
                                <div class="col-7">
                                    <input type="number" class="form-control food_calories">
                                </div>
                            </div>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.grams')}}</label>
                                <div class="col-7">
                                    <input type="number" class="form-control food_grams">
                                </div>
                            </div>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.fats')}}</label>
                                <div class="col-7">
                                    <input type="number" class="form-control food_fats">
                                </div>
                            </div>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.proteins')}}</label>
                                <div class="col-7">
                                    <input type="number" class="form-control food_proteins">
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>{{trans('lang.food_add_one')}}</legend>
                            <div class="form-group add_ons_list extra-row">
                            </div>
                            <div class="form-group row width-100">
                                <div class="col-7">
                                    <button type="button" onclick="addOneFunction()" class="btn btn-primary"
                                            id="add_one_btn">{{trans('lang.food_add_one')}}</button>
                                </div>
                            </div>
                            <div class="form-group row width-100" id="add_ones_div" style="display:none">
                                <div class="row">
                                    <div class="col-6">
                                        <label class="col-3 control-label">{{trans('lang.food_title')}}</label>
                                        <div class="col-7">
                                            <input type="text" class="form-control add_ons_title">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="col-3 control-label">{{trans('lang.food_price')}}</label>
                                        <div class="col-7">
                                            <input type="number" class="form-control add_ons_price">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row save_add_one_btn width-100" style="display:none">
                                <div class="col-7">
                                    <button type="button" onclick="saveAddOneFunction()"
                                            class="btn btn-primary">{{trans('lang.save_add_ones')}}</button>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset style="display: none">
                            <legend>{{trans('lang.product_specification')}}</legend>
                            <div class="form-group product_specification extra-row">
                                <div class="row" id="product_specification_heading" style="display: none;">
                                    <div class="col-6">
                                        <label class="col-2 control-label">{{trans('lang.lable')}}</label>
                                    </div>
                                    <div class="col-6">
                                        <label class="col-3 control-label">{{trans('lang.value')}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row width-100">
                                <div class="col-7">
                                    <button type="button" onclick="addProductSpecificationFunction()"
                                            class="btn btn-primary"
                                            id="add_one_btn"> {{trans('lang.add_product_specification')}}</button>
                                </div>
                            </div>
                            <div class="form-group row width-100" id="add_product_specification_div"
                                 style="display:none">
                                <div class="row">
                                    <div class="col-6">
                                        <label class="col-2 control-label">{{trans('lang.lable')}}</label>
                                        <div class="col-7">
                                            <input type="text" class="form-control add_label">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="col-3 control-label">{{trans('lang.value')}}</label>
                                        <div class="col-7">
                                            <input type="text" class="form-control add_value">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row save_product_specification_btn width-100" style="display:none">
                                <div class="col-7">
                                    <button type="button" onclick="saveProductSpecificationFunction()"
                                            class="btn btn-primary">{{trans('lang.save_product_specification')}}</button>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="form-group col-12 text-center btm-btn">
                        <button type="button" class="btn btn-primary  edit-form-btn"><i class="fa fa-save"></i> {{trans('lang.save')}}</button>
                    <?php if(isset($_GET['eid']) && $_GET['eid'] != ''){?>
                        <a href="{{route('restaurants.foods',$_GET['eid'])}}" class="btn btn-default"><i class="fa fa-undo"></i>{{trans('lang.cancel')}}</a>
                    <?php }else{ ?>
                        <a href="{!! route('foods') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{trans('lang.cancel')}}</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
.image-item {
    position: relative;
    display: inline-block;
    margin: 5px;
}

.remove-btn, .set-main-btn {
    position: absolute;
    top: 5px;
    background: rgba(0,0,0,0.7);
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    text-align: center;
    line-height: 20px;
    cursor: pointer;
    font-size: 10px;
}

.remove-btn {
    right: 5px;
}

.set-main-btn {
    left: 5px;
}

.set-main-btn:hover {
    background: rgba(255,193,7,0.8);
    color: #333;
}
</style>

@section('scripts')
    <script>
        var id = "<?php echo $id;?>";
        var database = firebase.firestore();
        var ref = database.collection('vendor_products').doc(id);
        var storageRef = firebase.storage().ref('images');
        var storage = firebase.storage();
        var photo = "";
        var addOnesTitle = [];
        var addOnesPrice = [];
        var sizeTitle = [];
        var sizePrice = [];
        var attributes_list = [];
        var categories_list = [];
        var restaurant_list = [];
        var photos = [];
        var new_added_photos = [];
        var new_added_photos_filename = [];
        var photosToDelete = [];
        var product_specification = {};
        var placeholderImage = '';
        var productImagesCount = 0;
        var variant_photos=[];
        var variant_filename=[];
        var variantImageToDelete=[];
        var variant_vIds=[];
        var placeholder = database.collection('settings').doc('placeHolderImage');
        placeholder.get().then(async function (snapshotsimage) {
            var placeholderImageData = snapshotsimage.data();
            placeholderImage = placeholderImageData.image;
        })
        var refCurrency = database.collection('currencies').where('isActive', '==', true);
        refCurrency.get().then(async function (snapshots) {
            var currencyData = snapshots.docs[0].data();
            currentCurrency = currencyData.symbol;
            currencyAtRight = currencyData.symbolAtRight;
            if (currencyData.decimal_degits) {
                decimal_degits = currencyData.decimal_degits;
            }
        });
        var refAdminCommission = database.collection('settings').doc("AdminCommission");
        refAdminCommission.get().then(async function (snapshots) {
            var adminCommissionSettings = snapshots.data();
            if(adminCommissionSettings){
                var commission_type = adminCommissionSettings.commissionType;
                var commission_value = adminCommissionSettings.fix_commission;
                if(commission_type == "Percent"){
                    var commission_text = commission_value+'%';
                }else{
                    if(currencyAtRight){
                        commission_text = parseFloat(commission_value).toFixed(decimal_degits) + "" + currentCurrency;
                    }else{
                        commission_text = currentCurrency + "" + parseFloat(commission_value).toFixed(decimal_degits);
                    }
                }
                if(adminCommissionSettings.isEnabled){
                    $("#admin_commision_info").show();
                    $("#admin_commision").html('Admin Commission: '+commission_text);
                }
            }
        });
        $(document).ready(function () {
            <?php if(isset($_GET['eid']) && $_GET['eid'] != ''){?>
                $(".food_restaurant_div").hide();
            <?php } else{?>
                $(".food_restaurant_div").show();
            <?php } ?>
            $("#attributes_div").show(); 
            jQuery(document).on("click", ".mdi-cloud-upload", function () {
                var variant = jQuery(this).data('variant');
                var fileurl = $('[id="variant_' + variant + '_url"]').val();
                if (fileurl) {
                    variantImageToDelete.push(fileurl);
                }
                var photo_remove = $(this).attr('data-img');
                index = variant_photos.indexOf(photo_remove);
                if (index > -1) {
                    variant_photos.splice(index, 1); // 2nd parameter means remove one item only
                }
                var file_remove = $(this).attr('data-file');
                fileindex = variant_filename.indexOf(file_remove);
                if (fileindex > -1) {
                    variant_filename.splice(fileindex, 1); // 2nd parameter means remove one item only
                }
                variantindex = variant_vIds.indexOf(variant);
                if (variantindex > -1) {
                    variant_vIds.splice(variantindex, 1); // 2nd parameter means remove one item only
                }
                $('[id="variant_' + variant + '_url"]').val('');
                $('[id="file_' + variant + '"]').click();
            });
            jQuery(document).on("click", ".mdi-delete", function () {
                var variant = jQuery(this).data('variant');
                var fileurl = $('[id="variant_' + variant + '_url"]').val();
                if (fileurl) {
                    variantImageToDelete.push(fileurl);
                }
                var photo_remove = $(this).attr('data-img');
                index = variant_photos.indexOf(photo_remove);
                if (index > -1) {
                    variant_photos.splice(index, 1); // 2nd parameter means remove one item only
                }
                var file_remove = $(this).attr('data-file');
                fileindex = variant_filename.indexOf(file_remove);
                if (fileindex > -1) {
                    variant_filename.splice(fileindex, 1); // 2nd parameter means remove one item only
                }
                variantindex = variant_vIds.indexOf(variant);
                if (variantindex > -1) {
                    variant_vIds.splice(variantindex, 1); // 2nd parameter means remove one item only
                }
                $('[id="variant_' + variant + '_image"]').empty();
                $('[id="variant_' + variant + '_url"]').val('');
            });
            jQuery("#data-table_processing").show();
            ref.get().then(async function (snapshots) {
                var product = snapshots.data();
                await database.collection('vendors').orderBy('title', 'asc').get().then(async function (snapshots) {
                    snapshots.docs.forEach((listval) => {
                        var data = listval.data();
                        restaurant_list.push(data);
                        if (data.id == product.vendorID) {
                            $('#food_restaurant').append($("<option selected></option>")
                                .attr("value", data.id)
                                .text(data.title));
                        } else {
                            $('#food_restaurant').append($("<option></option>")
                                .attr("value", data.id)
                                .text(data.title));
                        }
                    })
                });
                await database.collection('vendor_categories').where('publish', '==', true).get().then(async function (snapshots) {
                    snapshots.docs.forEach((listval) => {
                        var data = listval.data();
                        categories_list.push(data);
                        if (data.id == product.categoryID) {
                            $('#food_category').append($("<option selected></option>")
                                .attr("value", data.id)
                                .text(data.title));
                        } else {
                            $('#food_category').append($("<option></option>")
                                .attr("value", data.id)
                                .text(data.title));
                        }
                        updateSelectedFoodCategoryTags();
                    })
                });
                var selected_attributes = [];
                if (product.item_attribute != null) {
                    $("#attributes_div").show();
                    $.each(product.item_attribute.attributes, function (index, attribute) {
                        selected_attributes.push(attribute.attribute_id);
                    });
                    $('#attributes').val(JSON.stringify(product.item_attribute.attributes));
                    $('#variants').val(JSON.stringify(product.item_attribute.variants));
                }
                var attributes = database.collection('vendor_attributes');
                attributes.get().then(async function (snapshots) {
                    snapshots.docs.forEach((listval) => {
                        var data = listval.data();
                        attributes_list.push(data);
                        var selected = '';
                        if ($.inArray(data.id, selected_attributes) !== -1) {
                            var selected = 'selected="selected"';
                        }
                        var option = '<option value="' + data.id + '" ' + selected + '>' + data.title + '</option>';
                        $('#item_attribute').append(option);
                    });
                    $("#item_attribute").show().chosen({"placeholder_text": "{{trans('lang.select_attribute')}}"});
                    if (product.item_attribute) {
                        $("#item_attribute").attr("onChange", "selectAttribute('" + btoa(JSON.stringify(product.item_attribute)) + "')");
                        selectAttribute(btoa(JSON.stringify(product.item_attribute)));
                    } else {
                        $("#item_attribute").attr("onChange", "selectAttribute()");
                        selectAttribute();
                    }
                });
                if (product.hasOwnProperty('product_specification')) {
                    product_specification = product.product_specification;
                    if (product_specification != null && product_specification != "") {
                        product_specification = {};
                        $.each(product.product_specification, function (key, value) {
                            product_specification[key] = value;
                        });
                    }
                    for (var key in product.product_specification) {
                        $('#product_specification_heading').show();
                        $(".product_specification").append('<div class="row" style="margin-top:5px;" id="add_product_specification_iteam_' + key + '">' +
                            '<div class="col-5"><input class="form-control" type="text" value="' + key + '" disabled ></div>' +
                            '<div class="col-5"><input class="form-control" type="text" value="' + product.product_specification[key] + '" disabled ></div>' +
                            '<div class="col-2"><button class="btn" type="button" onclick=deleteProductSpecificationSingle("' + key + '")><span class="mdi mdi-delete"></span></button></div></div>');
                    }
                }
                if (product.hasOwnProperty('photo')) {
                    photo = product.photo;
                    if (product.photos != undefined && product.photos != '') {
                        photos = product.photos;
                    } else {
                        if (photo != '' && photo != null) {
                            photos.push(photo);
                        }
                    }
                    if (photos != '' && photos != null) {  
                        photos.forEach((element, index) => {
                            var isMainPhoto = (photos[index] === photo);
                            var mainIconClass = isMainPhoto ? 'fa-star' : 'fa-star-o';
                            var mainIconStyle = isMainPhoto ? 'color: #ffc107;' : '';
                            $(".product_image").append('<span class="image-item" id="photo_' + index + '"><span class="remove-btn" data-id="' + index + '" data-img="' + photos[index] + '" data-status="old"><i class="fa fa-remove"></i></span><span class="set-main-btn" data-id="' + index + '" data-img="' + photos[index] + '" data-status="old" style="' + mainIconStyle + '"><i class="fa ' + mainIconClass + '"></i></span><img onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" class="rounded" width="50px" id="" height="auto" src="' + photos[index] + '"></span>');
                        })
                    } else if (photo != '' && photo != null) {
                        $(".product_image").append('<span class="image-item" id="photo_1"><span class="set-main-btn" data-id="1" data-img="' + photo + '" data-status="old" style="color: #ffc107;"><i class="fa fa-star"></i></span><img onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" class="rounded" width="50px" id="" height="auto" src="' + photo + '"></span>');
                    } else {
                        $(".product_image").append('<span class="image-item" id="photo_1"><img class="rounded" style="width:50px" src="' + placeholderImage + '" alt="image">');
                    }
                }
                $(".food_name").val(product.name);
                $(".food_price").val(product.price);
                $(".item_quantity").val(product.quantity);
                $(".food_discount").val(product.disPrice);
                if (product.hasOwnProperty("calories")) {
                    $(".food_calories").val(product.calories)
                }
                if (product.hasOwnProperty("grams")) {
                    $(".food_grams").val(product.grams);
                }
                if (product.hasOwnProperty("proteins")) {
                    $(".food_proteins").val(product.proteins)
                }
                if (product.hasOwnProperty("fats")) {
                    $(".food_fats").val(product.fats);
                }
                $("#food_description").val(product.description);
                if (product.publish) {
                    $(".food_publish").prop('checked', true);
                }
                if (product.nonveg) {
                    $(".food_nonveg").prop('checked', true);
                }
                if (product.takeawayOption) {
                    $(".food_take_away_option").prop('checked', true);
                }
                if (product.hasOwnProperty('addOnsTitle')) {
                    product.addOnsTitle.forEach((element, index) => {
                        $(".add_ons_list").append('<div class="row" style="margin-top:5px;" id="add_ones_list_iteam_' + index + '"><div class="col-5"><input class="form-control" type="text" value="' + element + '" disabled ></div><div class="col-5"><input class="form-control" type="text" value="' + product.addOnsPrice[index] + '" disabled ></div><div class="col-2"><button class="btn" type="button" onclick="deleteAddOnesSingle(' + index + ')"><span class="mdi mdi-delete"></span></button></div></div>');
                    })
                    addOnesTitle = product.addOnsTitle;
                    addOnesPrice = product.addOnsPrice;
                }
                if (product.hasOwnProperty('isAvailable') && product.isAvailable) {
                    $(".food_is_available").prop('checked', true);
                }
                jQuery("#data-table_processing").hide();
            })
            $(".edit-form-btn").click( async function () {
                var name = $(".food_name").val();
                var price = $(".food_price").val();
                var quantity = $(".item_quantity").val();
                var restaurant = $("#food_restaurant option:selected").val();
                var category = $("#food_category option:selected").val();
                var foodCalories = parseInt($(".food_calories").val());
                var foodGrams = parseInt($(".food_grams").val());
                var foodProteins = parseInt($(".food_proteins").val());
                var foodFats = parseInt($(".food_fats").val());
                var description = $("#food_description").val();
                var foodPublish = $(".food_publish").is(":checked");
                var nonveg = $(".food_nonveg").is(":checked");
                var veg = !nonveg;
                var foodTakeaway = $(".food_take_away_option").is(":checked");
                var discount = $(".food_discount").val();
                if (discount == '') {
                    discount = "0";
                }
                if (!foodCalories) {
                    foodCalories = 0;
                }
                if (!foodGrams) {
                    foodGrams = 0;
                }
                if (!foodFats) {
                    foodFats = 0;
                }
                if (!foodProteins) {
                    foodProteins = 0;
                }
                // Only set photo if it's not already set manually
                if (!photo || photo === '') {
                    if (photos.length > 0) {
                        photo = photos[0];
                    } else {
                        photo = '';
                    }
                }
                if (name == '') {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{trans('lang.enter_food_name_error')}}</p>");
                    window.scrollTo(0, 0);
                } else if (price == '') {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{trans('lang.enter_food_price_error')}}</p>");
                    window.scrollTo(0, 0);
                } else if (restaurant == '') {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{trans('lang.select_restaurant_error')}}</p>");
                    window.scrollTo(0, 0);
                } else if (category == '') {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{trans('lang.select_food_category_error')}}</p>");
                    window.scrollTo(0, 0);
                } else if (parseInt(price) < parseInt(discount)) {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{trans('lang.price_should_not_less_then_discount_error')}}</p>");
                    window.scrollTo(0, 0);
                } else if (quantity == '' || quantity < -1) {
                    $(".error_top").show();
                    $(".error_top").html("");
                    if (quantity == '') {
                        $(".error_top").append("<p>{{trans('lang.enter_item_quantity_error')}}</p>");
                    } else {
                        $(".error_top").append("<p>{{trans('lang.invalid_item_quantity_error')}}</p>");
                    }
                    window.scrollTo(0, 0);
                } else if (description == '') {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{trans('lang.enter_food_description_error')}}</p>");
                    window.scrollTo(0, 0);
                } else {
                    $(".error_top").hide();
                    var item_attribute = null;
                    var quantityerror = 0;
                    var priceerror = 0;
                    var attributes = [];
                    var variants = [];
                    if ($("#item_attribute").val().length > 0) {
                        if ($('#attributes').val().length > 0) {
                            var attributes = $.parseJSON($('#attributes').val());
                        }else{
                            alert('Please add your attribute value');
                            return false;
                        }
                        if($("#item_attribute").val().length !== attributes.length){
                            alert('Please add your attribute value');
                            return false;
                        }
                        console.log($("#item_attribute").val());
                        console.log($('#attributes').val());
                    }
                  
                    if ($('#variants').val().length > 0) {
                        var variantsSet = $.parseJSON($('#variants').val());
                        await storeVariantImageData().then(async (vIMG) => {
                            $.each(variantsSet, function (key, variant) {
                                var variant_id = uniqid();
                                var variant_sku = variant;
                                var variant_price = $('[id="price_' + variant + '"]').val();
                                var variant_quantity = $('[id="qty_' + variant + '"]').val();
                                var variant_image = $('[id="variant_' + variant + '_url"]').val();
                                if (variant_image) {
                                    variants.push({
                                        'variant_id': variant_id,
                                        'variant_sku': variant_sku,
                                        'variant_price': variant_price,
                                        'variant_quantity': variant_quantity,
                                        'variant_image': variant_image
                                    });
                                } else {
                                    variants.push({
                                        'variant_id': variant_id,
                                        'variant_sku': variant_sku,
                                        'variant_price': variant_price,
                                        'variant_quantity': variant_quantity
                                    });
                                }
                                if (variant_quantity = '' || variant_quantity < -1 || variant_quantity == 0) {
                                    quantityerror++;
                                }
                                if (variant_price == "" || variant_price <= 0) {
                                    priceerror++;
                                }
                            });
                        }).catch(err => {
                            jQuery("#data-table_processing").hide();
                            $(".error_top").show();
                            $(".error_top").html("");
                            $(".error_top").append("<p>" + err + "</p>");
                            window.scrollTo(0, 0);
                        });
                    }
                    if (attributes.length > 0 && variants.length > 0) {
                        if (quantityerror > 0) {
                            alert('Please add your variants quantity it should be -1 or greater than -1');
                            return false;
                        }
                        if (priceerror > 0) {
                            alert('Please add your variants  Price');
                            return false;
                        }
                        var item_attribute = {'attributes': attributes, 'variants': variants};
                    }
                   
                    if ($.isEmptyObject(product_specification)) {
                        product_specification = null;
                    }
                    jQuery("#data-table_processing").show();
                await storeImageData().then(async (IMG) => { 
                    // Store the manually selected photo before any processing
                    var selectedMainPhoto = photo;
                    
                    // Ensure we have a valid main photo
                    if (selectedMainPhoto && selectedMainPhoto !== '') {
                        // Check if the selected photo exists in the final image array
                        if (IMG.includes(selectedMainPhoto)) {
                            photo = selectedMainPhoto;
                            console.log('Using manually selected main photo:', photo);
                        } else {
                            // If selected photo is not in final array, use first available
                            photo = IMG.length > 0 ? IMG[0] : '';
                            console.log('Selected photo not found in final array, using first image:', photo);
                        }
                    } else if (IMG.length > 0) {
                        photo = IMG[0];
                        console.log('No manual selection, using first image as main photo:', photo);
                    } else {
                        photo = '';
                        console.log('No images available');
                    }
                    var foodIsAvailable = $(".food_is_available").is(":checked");
                    database.collection('vendor_products').doc(id).update({
                        'name': name,
                        'price': price.toString(),
                        'quantity': parseInt(quantity),
                        'disPrice': discount,
                        'vendorID': restaurant,
                        'categoryID': category,
                        'photo': photo,
                        'calories': foodCalories,
                        "grams": foodGrams,
                        'proteins': foodProteins,
                        'fats': foodFats,
                        'description': description,
                        'publish': foodPublish,
                        'nonveg': nonveg,
                        'veg': veg,
                        'addOnsTitle': addOnesTitle,
                        'addOnsPrice': addOnesPrice,
                        'takeawayOption': foodTakeaway,
                        'product_specification': product_specification,
                        'item_attribute': item_attribute,
                        'photos': IMG,
                        'isAvailable': foodIsAvailable
                    }).then(function (result) {
                        <?php if(isset($_GET['eid']) && $_GET['eid'] != ''){?>
                            window.location.href = "{{ route('restaurants.foods',$_GET['eid']) }}";
                        <?php }else{ ?>
                        jQuery("#data-table_processing").hide();
                            window.location.href = '{{ route("foods")}}';
                        <?php } ?>
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
        function handleFileSelect(evt) {
            var f = evt.target.files[0];
            var reader = new FileReader();
            new Compressor(f, {
                quality: <?php echo env('IMAGE_COMPRESSOR_QUALITY', 0.8); ?>,
                success(result) {
                    f = result;
                    reader.onload = (function (theFile) {
                        return function (e) {
                            var filePayload = e.target.result;
                            var val = f.name;
                            var ext = val.split('.')[1];
                            var docName = val.split('fakepath')[1];
                            var filename = (f.name).replace(/C:\\fakepath\\/i, '')
                            var timestamp = Number(new Date());
                            var filename = filename.split('.')[0] + "_" + timestamp + '.' + ext;
                            var uploadTask = storageRef.child(filename).put(theFile);
                            uploadTask.on('state_changed', function (snapshot) {
                                var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
                                console.log('Upload is ' + progress + '% done');
                                jQuery("#uploding_image").text("Image is uploading...");
                            }, function (error) {
                            }, function () {
                                uploadTask.snapshot.ref.getDownloadURL().then(function (downloadURL) {
                                    jQuery("#uploding_image").text("Upload is completed");
                                    photo = downloadURL;
                                    $(".item_image").empty()
                                    $(".item_image").append('<img class="rounded" style="width:50px" src="' + photo + '" alt="image">');
                                });
                            });
                        };
                    })(f);
                    reader.readAsDataURL(f);
                },
                error(err) {
                    console.log(err.message);
                },
            });
        }
        function addOneFunction() {
            $("#add_ones_div").show();
            $(".save_add_one_btn").show();
        }
        function saveAddOneFunction() {
            var optiontitle = $(".add_ons_title").val();
            var optionPricevalue = $(".add_ons_price").val();
            var optionPrice = $(".add_ons_price").val();
            $(".add_ons_price").val('');
            $(".add_ons_title").val('');
            if (optiontitle != '' && optionPricevalue != '') {
                addOnesPrice.push(optionPrice.toString());
                addOnesTitle.push(optiontitle);
                var index = addOnesTitle.length - 1;
                $(".add_ons_list").append('<div class="row" style="margin-top:5px;" id="add_ones_list_iteam_' + index + '"><div class="col-5"><input class="form-control" type="text" value="' + optiontitle + '" disabled ></div><div class="col-5"><input class="form-control" type="text" value="' + optionPrice + '" disabled ></div><div class="col-2"><button class="btn" type="button" onclick="deleteAddOnesSingle(' + index + ')"><span class="mdi mdi-delete"></span></button></div></div>');
            } else {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.enter_title_and_price_error')}}</p>");
                window.scrollTo(0, 0);
            }
        }
        function deleteAddOnesSingle(index) {
            addOnesTitle.splice(index, 1);
            addOnesPrice.splice(index, 1);
            $("#add_ones_list_iteam_" + index).hide();
        }
        function handleFileSelectProduct(evt) {
            var f = evt.target.files[0];
            var reader = new FileReader();
            reader.onload = (function (theFile) {
                return function (e) {
                    var filePayload = e.target.result;
                    var val = f.name;
                    var ext = val.split('.')[1];
                    var docName = val.split('fakepath')[1];
                    var filename = (f.name).replace(/C:\\fakepath\\/i, '')
                    var timestamp = Number(new Date());
                    var filename = filename.split('.')[0] + "_" + timestamp + '.' + ext;
                    var uploadTask = storageRef.child(filename).put(theFile);
                    uploadTask.on('state_changed', function (snapshot) {
                        var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
                        console.log('Upload is ' + progress + '% done');
                        $('.product_image').find(".uploding_image_photos").text("Image is uploading...");
                    }, function (error) {
                    }, function () {
                        uploadTask.snapshot.ref.getDownloadURL().then(function (downloadURL) {
                            jQuery("#uploding_image").text("Upload is completed");
                            if (downloadURL) {
                                productImagesCount++;
                                photos_html = '<span class="image-item" id="photo_' + productImagesCount + '"><span class="remove-btn" data-id="' + productImagesCount + '" data-img="' + downloadURL + '"><i class="fa fa-remove"></i></span><img class="rounded" width="50px" id="" height="auto" src="' + downloadURL + '"></span>'
                                $(".product_image").append(photos_html);
                                photos.push(downloadURL);
                            }
                        });
                    });
                };
            })(f);
            reader.readAsDataURL(f);
        }
        async function storeImageData() {
            var newPhoto = [];
            // Store the current main photo to preserve it
            var currentMainPhoto = photo;
            
            if (photos.length > 0) {
                newPhoto = photos; 
                console.log('Existing photos:', photos);
            }
            if (new_added_photos.length > 0) {
                console.log('New photos to upload:', new_added_photos.length);
                await Promise.all(new_added_photos.map(async (foodPhoto, index) => {
                    foodPhoto = foodPhoto.replace(/^data:image\/[a-z]+;base64,/, "");
                    var uploadTask = await storageRef.child(new_added_photos_filename[index]).putString(foodPhoto, 'base64', { contentType: 'image/jpg' });
                    var downloadURL = await uploadTask.ref.getDownloadURL();
                    newPhoto.push(downloadURL);
                    console.log('Uploaded new photo:', downloadURL);
                }));
            }
            if (photosToDelete.length > 0) {
                await Promise.all(photosToDelete.map(async (delImage) => {
                    imageBucket = delImage.bucket;
                    var envBucket = "<?php echo env('FIREBASE_STORAGE_BUCKET'); ?>";
                    if (imageBucket == envBucket) {
                        await delImage.delete().then(() => {
                            console.log("Old file deleted!")
                        }).catch((error) => {
                            console.log("ERR File delete ===", error);
                        });
                    } else {
                        console.log('Bucket not matched');
                    }
                }));
            }
            console.log('Final photo array:', newPhoto);
            console.log('Current main photo variable:', photo);
            return newPhoto;
        }
        $("#product_image").resizeImg({
            callback: function (base64str) {
                var val = $('#product_image').val().toLowerCase();
                var ext = val.split('.')[1];
                var docName = val.split('fakepath')[1];
                var filename = $('#product_image').val().replace(/C:\\fakepath\\/i, '')
                var timestamp = Number(new Date());
                var filename = filename.split('.')[0] + "_" + timestamp + '.' + ext;
                productImagesCount++;
                photos_html = '<span class="image-item" id="photo_' + productImagesCount + '"><span class="remove-btn" data-id="' + productImagesCount + '" data-img="' + base64str + '" data-status="new"><i class="fa fa-remove"></i></span><span class="set-main-btn" data-id="' + productImagesCount + '" data-img="' + base64str + '" data-status="new"><i class="fa fa-star-o"></i></span><img class="rounded" width="50px" id="" height="auto" src="' + base64str + '"></span>'
                $(".product_image").append(photos_html);
                new_added_photos.push(base64str);
                new_added_photos_filename.push(filename);
                $("#product_image").val('');
            }
        });
        $(document).on("click", ".remove-btn", function () {
            var id = $(this).attr('data-id');
            var photo_remove = $(this).attr('data-img');
            var status=$(this).attr('data-status');
            
            // If we're removing the main photo, we need to set a new main photo
            if (photo_remove === photo) {
                // Find the first available image to set as main
                var remainingPhotos = photos.filter(p => p !== photo_remove);
                var remainingNewPhotos = new_added_photos.filter(p => p !== photo_remove);
                
                if (remainingPhotos.length > 0) {
                    photo = remainingPhotos[0];
                } else if (remainingNewPhotos.length > 0) {
                    photo = remainingNewPhotos[0];
                } else {
                    photo = '';
                }
            }
            
            if(status=="old"){
                 photosToDelete.push(firebase.storage().refFromURL(photo_remove));
            }
            $("#photo_" + id).remove();
            index = photos.indexOf(photo_remove);
            if (index > -1) {
                photos.splice(index, 1); // 2nd parameter means remove one item only
            }
            index = new_added_photos.indexOf(photo_remove);
            if (index > -1) {
                new_added_photos.splice(index, 1); // 2nd parameter means remove one item only
                new_added_photos_filename.splice(index, 1);
            }
        });
        
        $(document).on("click", ".set-main-btn", function () {
            var id = $(this).attr('data-id');
            var photo_url = $(this).attr('data-img');
            var status = $(this).attr('data-status');
            
            // Set this image as the main photo
            photo = photo_url;
            
            // Update all star icons to show which one is main
            $(".set-main-btn i").removeClass('fa-star').addClass('fa-star-o');
            $(".set-main-btn").css('color', '');
            $(this).find('i').removeClass('fa-star-o').addClass('fa-star');
            $(this).css('color', '#ffc107');
            
            console.log('Set main photo to:', photo);
        });
        $("#food_restaurant").change(function () {
            $("#attributes_div").show();
            $("#item_attribute_chosen").css({'width': '100%'});
            var selected_vendor = this.value;
        });
        function change_categories(selected_vendor) {
            restaurant_list.forEach((vendor) => {
                if (vendor.id == selected_vendor) {
                    $('#item_category').html('');
                    $('#item_category').append($('<option value="">{{trans("lang.select_category")}}</option>'));
                    categories_list.forEach((data) => {
                        if (vendor.categoryID == data.id) {
                            $('#food_category').html($("<option></option>")
                                .attr("value", data.id)
                                .text(data.title));
                        }
                    })
                }
            });
        }
        function handleVariantFileSelect(evt, vid) {
            var f = evt.target.files[0];
            var reader = new FileReader();
            reader.onload = (function (theFile) {
                return function (e) {
                    var filePayload = e.target.result;
                    var val = f.name;
                    var ext = val.split('.')[1];
                    var docName = val.split('fakepath')[1];
                    var timestamp = Number(new Date());
                    var filename = (f.name).replace(/C:\\fakepath\\/i, '')
                    var filename = 'variant_' + vid + '_' + timestamp + '.' + ext;
                    variant_filename.push(filename);
                    variant_photos.push(filePayload);
                    variant_vIds.push(vid);
                    $('[id="variant_'+ vid+'_image"]').empty();
                    $('[id="variant_'+ vid+'_image"]').html('<img class="rounded" style="width:50px" src="' + filePayload + '" alt="image"><i class="mdi mdi-delete" data-variant="' + vid + '" data-img="' +filePayload + '" data-file="'+filename +'" data-status="new"></i>');
                    $('#upload_'+vid).attr('data-img',filePayload);
                    $('#upload_'+vid).attr('data-file',filename);
                };
            })(f);
            reader.readAsDataURL(f);
        }
        async function storeVariantImageData() {
            var newPhoto = [];
            if (variant_photos.length > 0) {
                await Promise.all(variant_photos.map(async (variantPhoto, index) => {
                    variantPhoto = variantPhoto.replace(/^data:image\/[a-z]+;base64,/, "");
                    var uploadTask = await storageRef.child(variant_filename[index]).putString(variantPhoto, 'base64', {contentType: 'image/jpg'});
                    var downloadURL = await uploadTask.ref.getDownloadURL();
                    $('[id="variant_'+ variant_vIds[index]+'_url"]').val(downloadURL);
                    newPhoto.push(downloadURL);
                }));
            }
            if (variantImageToDelete.length > 0) {
            }
            return newPhoto;
        }
    function selectAttribute(item_attribute = '') {
        if (item_attribute) {
            var item_attribute = $.parseJSON(atob(item_attribute));
        }
        var html = '';
        $("#item_attribute").find('option:selected').each(function () {
            var $this = $(this);
            var selected_options = [];
            if (item_attribute) {
                $.each(item_attribute.attributes, function (index, attribute) {
                    if ($this.val() == attribute.attribute_id) {
                        selected_options.push(attribute.attribute_options);
                    }
                });
            }
            html += '<div class="row" id="attr_' + $this.val() + '">';
            html += '<div class="col-md-3">';
            html += '<label>' + $this.text() + '</label>';
            html += '</div>';
            html += '<div class="col-lg-9">';
            html += '<input type="text" class="form-control" id="attribute_options_' + $this.val() + '" value="' + selected_options + '" placeholder="Add attribute values" data-role="tagsinput" onchange="variants_update(\'' + btoa(JSON.stringify(item_attribute)) + '\')">';
            html += '</div>';
            html += '</div>';
        });
        $("#item_attributes").html(html);
        $("#item_attributes input[data-role=tagsinput]").tagsinput();
        if ($("#item_attribute").val().length == 0) {
            $("#attributes").val('');
            $("#variants").val('');
            $("#item_variants").html('');
        }
    }
    function variants_update(item_attributeX = '') {
        if (item_attributeX) {
            var item_attributeX = $.parseJSON(atob(item_attributeX));
        }
        var html = '';
        var item_attribute = $("#item_attribute").map(function (idx, ele) {
                return $(ele).val();
            }).get();
        if (item_attribute.length > 0) {
            var attributes = [];
            var attributeSet = [];
            $.each(item_attribute, function (index, attribute) {
                var attribute_options = $("#attribute_options_" + attribute).val();
                if (attribute_options) {
                    var attribute_options = attribute_options.split(',');
                    attribute_options = $.map(attribute_options, function (value) {
                        return value.replace(/[^0-9a-zA-Z a]/g, '');
                    });
                    attributeSet.push(attribute_options);
                    attributes.push({'attribute_id': attribute, 'attribute_options': attribute_options});
                }
            });
            $('#attributes').val(JSON.stringify(attributes));
            var variants = getCombinations(attributeSet);
            $('#variants').val(JSON.stringify(variants));
              
            if (attributeSet.length > 0) {  
                html += '<table class="table table-bordered">';
                html += '<thead class="thead-light">';
                html += '<tr>';
                html += '<th class="text-center"><span class="control-label">Variant</span></th>';
                html += '<th class="text-center"><span class="control-label">Variant Price</span></th>';
                html += '<th class="text-center"><span class="control-label">Variant Quantity</span></th>';
                html += '<th class="text-center"><span class="control-label">Variant Image</span></th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';
                $.each(variants, function (index, variant) {
                    var variant_price = 1;
                    var variant_qty = -1;
                    var variant_image = variant_image_url = '';
                    if (item_attributeX) {
                        var variant_info = $.map(item_attributeX.variants, function (v, i) {
                            if (v.variant_sku == variant) {
                                return v;
                            }
                        });
                        if (variant_info[0]) {
                            variant_price = variant_info[0].variant_price;
                            variant_qty = variant_info[0].variant_quantity;
                            if (variant_info[0].variant_image) {
                                variant_image = '<img class="rounded" style="width:50px" src="' + variant_info[0].variant_image + '" alt="image"><i class="mdi mdi-delete" data-variant="' + variant + '" data-status="old"></i>';
                                variant_image_url = variant_info[0].variant_image;
                            }
                        }
                    }
                    html += '<tr>';
                    html += '<td><label for="" class="control-label">' + variant + '</label></td>';
                    html += '<td>';
                    html += '<input type="number" id="price_' + variant + '" value="' + variant_price + '" min="0" class="form-control">';
                    html += '</td>';
                    html += '<td>';
                    html += '<input type="number" id="qty_' + variant + '" value="' + variant_qty + '" min="-1" class="form-control">';
                    html += '</td>';
                    html += '<td>';
                    html += '<div class="variant-image">';
                    html += '<div class="upload">';
                    html += '<div class="image" id="variant_' + variant + '_image">' + variant_image + '</div>';
                    html += '<div class="icon"><i class="mdi mdi-cloud-upload" data-variant="' + variant + '" id="upload_'+variant+'"></i></div>';
                    html += '</div>';
                    html += '<div id="variant_' + variant + '_process"></div>';
                    html += '<div class="input-file">';
                    html += '<input type="file" id="file_' + variant + '" onChange="handleVariantFileSelect(event,\'' + variant + '\')" class="form-control" style="display:none;">';
                    html += '<input type="hidden" id="variant_' + variant + '_url" value="' + variant_image_url + '">';
                    html += '</div>';
                    html += '</div>';
                    html += '</td>';
                    html += '</tr>';
                });
                html += '</tbody>';
                html += '</table>';
            }
        }
        $("#item_variants").html(html);
    }
    function getCombinations(arr) {
        if (arr.length) {
            if (arr.length == 1) {
                return arr[0];
            } else {
                var result = [];
                var allCasesOfRest = getCombinations(arr.slice(1));
                for (var i = 0; i < allCasesOfRest.length; i++) {
                    for (var j = 0; j < arr[0].length; j++) {
                        result.push(arr[0][j] + '-' + allCasesOfRest[i]);
                    }
                }
                return result;
            }
        }
    }
    function uniqid(prefix = "", random = false) {
        const sec = Date.now() * 1000 + Math.random() * 1000;
        const id = sec.toString(16).replace(/\./g, "").padEnd(14, "0");
        return `${prefix}${id}${random ? `.${Math.trunc(Math.random() * 100000000)}` : ""}`;
    }
    function addProductSpecificationFunction() {
        $("#add_product_specification_div").show();
        $(".save_product_specification_btn").show();
    }
    function saveProductSpecificationFunction() {
        var optionlabel = $(".add_label").val();
        var optionvalue = $(".add_value").val();
        $(".add_label").val('');
        $(".add_value").val('');
        if (optionlabel != '' && optionvalue != '') {
            if (product_specification == null) {
                product_specification = {};
            }
            product_specification[optionlabel] = optionvalue;
            $(".product_specification").append('<div class="row add_product_specification_iteam_' + optionlabel + '" style="margin-top:5px;" id="add_product_specification_iteam_' + optionlabel + '"><div class="col-5"><input class="form-control" type="text" value="' + optionlabel + '" disabled ></div><div class="col-5"><input class="form-control" type="text" value="' + optionvalue + '" disabled ></div><div class="col-2"><button class="btn" type="button" onclick=deleteProductSpecificationSingle("' + optionlabel + '")><span class="mdi mdi-delete"></span></button></div></div>');
        } else {
            alert("Please enter Label and Value");
        }
    }
    function deleteProductSpecificationSingle(index) {
        delete product_specification[index];
        $(".add_product_specification_iteam_" + index).addClass('hide');
        delete product_specification[index];
        $("#add_product_specification_iteam_" + index).hide();
    }
    // $(function() {
    //     // Insert search input and tag container if not present
    //     if ($('#food_category_search').length === 0) {
    //         $('#food_category').before('<div id="selected_food_categories" class="mb-2"></div><input type="text" id="food_category_search" class="form-control mb-2" placeholder="Search categories...">');
    //     }
    $(document).ready(function() {
        // 1. Filter dropdown options based on search
        $('#food_category_search').on('keyup', function() {
            var search = $(this).val().toLowerCase();
            $('#food_category option').each(function() {
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
        $('#food_category').on('change', function() {
            updateSelectedFoodCategoryTags();
        });

        // 3. Remove tag and unselect in dropdown
        $('#selected_food_categories').on('click', '.remove-tag', function() {
            var value = $(this).parent().data('value');
            $('#food_category option[value="' + value + '"]').prop('selected', false);
            updateSelectedFoodCategoryTags();
        });
    });
    
        // 4. Update tags display
        function updateSelectedFoodCategoryTags() {
        var selected = $('#food_category').val() || [];
        var html = '';
        $('#food_category option:selected').each(function() {
            if ($(this).val() !== "") {
                html += '<span class="selected-category-tag" data-value="' + $(this).val() + '">' +
                    $(this).text() +
                    '<span class="remove-tag">&times;</span></span>';
            }
        });
        $('#selected_categories').html(html);
    }
</script>
@endsection
