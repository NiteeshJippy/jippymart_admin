@extends('layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor restaurantTitle">{{trans('lang.food_plural')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.food_plural')}}</li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <div class="admin-top-section">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex top-title-section pb-4 justify-content-between">
                        <div class="d-flex top-title-left align-self-center">
                            <span class="icon mr-3"><img src="{{ asset('images/food.png') }}"></span>
                            <h3 class="mb-0">{{trans('lang.food_table')}}</h3>
                            <span class="counter ml-3 food_count"></span>
                        </div>
                        <div class="d-flex top-title-right align-self-center">
                            <div class="select-box pl-3">
                                <select class="form-control food_type_selector">
                                    <option value=""  selected>{{trans("lang.type")}}</option>
                                    <option value="veg">{{trans("lang.veg")}}</option>
                                    <option value="non-veg">{{trans("lang.non_veg")}}</option>
                                </select>
                            </div>
                            <div class="select-box pl-3">
                                <select class="form-control restaurant_selector">
                                    <option value=""  selected>{{trans("lang.restaurant")}}</option>
                                </select>
                            </div>
                            <div class="select-box pl-3">
                                <select class="form-control category_selector">
                                    <option value=""  selected>{{trans("lang.category_plural")}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-list">
            <div class="row">
                <div class="col-12">
                    <?php if ($id != '') { ?>
                        <div class="menu-tab">
                            <ul>
                                <li>
                                    <a href="{{route('restaurants.view', $id)}}">{{trans('lang.tab_basic')}}</a>
                                </li>
                                <li class="active">
                                    <a href="{{route('restaurants.foods', $id)}}">{{trans('lang.tab_foods')}}</a>
                                </li>
                                <li>
                                    <a href="{{route('restaurants.orders', $id)}}">{{trans('lang.tab_orders')}}</a>
                                </li>
                                <li>
                                    <a href="{{route('restaurants.coupons', $id)}}">{{trans('lang.tab_promos')}}</a>
                                <li>
                                    <a href="{{route('restaurants.payout', $id)}}">{{trans('lang.tab_payouts')}}</a>
                                </li>
                                <li>
                                    <a
                                        href="{{route('payoutRequests.restaurants.view', $id)}}">{{trans('lang.tab_payout_request')}}</a>
                                </li>
                                <li>
                                    <a href="{{route('restaurants.booktable', $id)}}">{{trans('lang.dine_in_future')}}</a>
                                </li>
                                <li id="restaurant_wallet"></li>
                                <li id="subscription_plan"></li>
                            </ul>
                        </div>
                    <?php } ?>
                    <div class="card border">
                        <div class="card-header d-flex justify-content-between align-items-center border-0">
                            <div class="card-header-title">
                                <h3 class="text-dark-2 mb-2 h4">{{trans('lang.food_table')}}</h3>
                                <p class="mb-0 text-dark-2">{{trans('lang.food_table_text')}}</p>
                            </div>
                            <div class="card-header-right d-flex align-items-center">
                                <div class="card-header-btn mr-3">
                                    <?php if ($id != '') { ?>
                                        <a class="btn-primary btn rounded-full"
                                            href="{!! route('foods.create') !!}/{{$id}}"><i
                                                class="mdi mdi-plus mr-2"></i>{{trans('lang.food_create')}}</a>
                                    <?php } else { ?>
                                        <a class="btn-primary btn rounded-full" href="{!! route('foods.create') !!}"><i
                                                class="mdi mdi-plus mr-2"></i>{{trans('lang.food_create')}}</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive m-t-10">
                                <table id="foodTable"
                                    class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                    cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <?php if (in_array('foods.delete', json_decode(@session('user_permissions'), true))) { ?>
                                                <th class="delete-all"><input type="checkbox" id="select-all">
                                                    <label class="col-3 control-label" for="select-all">
                                                        <a id="deleteAll" class="do_not_delete" href="javascript:void(0)"><i
                                                                class="mdi mdi-delete"></i> {{trans('lang.all')}}</a>
                                                    </label>
                                                </th>
                                            <?php } ?>
                                            <th>{{trans('lang.food_name')}}</th>
                                            <th>{{trans('lang.food_price')}}</th>
                                            <?php if ($id == '') { ?>
                                                <th>{{trans('lang.food_restaurant_id')}}</th>
                                            <?php } ?>
                                            <th>{{trans('lang.food_category_id')}}</th>
                                            <th>{{trans('lang.food_publish')}}</th>
                                            <th>Available</th>
                                            <th>{{trans('lang.actions')}}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    const urlParams=new URLSearchParams(location.search);
    for(const [key,value] of urlParams) {
        if(key=='categoryID') {
            var categoryID=value;
        } else {
            var categoryID='';
        }
    }
    var database=firebase.firestore();
    var currentCurrency='';
    var currencyAtRight=false;
    var decimal_degits=0;
    var storage=firebase.storage();
    var storageRef=firebase.storage().ref('images');
    var user_permissions='<?php echo @session("user_permissions") ?>';
    user_permissions=Object.values(JSON.parse(user_permissions));
    var checkDeletePermission=false;
    if($.inArray('foods.delete',user_permissions)>=0) {
        checkDeletePermission=true;
    }
    var restaurantID="{{$id}}";
    if(categoryID!=''&&categoryID!=undefined) {
        var ref=database.collection('vendor_products').where('categoryID','==',categoryID);
    } else {
        <?php if ($id != '') { ?>
            database.collection('vendors').where("id","==",'<?php echo $id; ?>').get().then(async function(snapshots) {
                var vendorData=snapshots.docs[0].data();
                walletRoute="{{route('users.walletstransaction', ':id')}}";
                walletRoute=walletRoute.replace(":id",vendorData.author);
                $('#restaurant_wallet').append('<a href="'+walletRoute+'">{{trans("lang.wallet_transaction")}}</a>');
                $('#subscription_plan').append('<a href="'+"{{route('vendor.subscriptionPlanHistory', ':id')}}".replace(':id',vendorData.author)+'">'+'{{trans('lang.subscription_history')}}'+'</a>');
            });
            var ref=database.collection('vendor_products').where('vendorID','==','<?php echo $id; ?>');
            const getStoreName=getStoreNameFunction('<?php echo $id; ?>');
        <?php } else { ?>
            var ref=database.collection('vendor_products');
        <?php } ?>
    }
    var refCurrency=database.collection('currencies').where('isActive','==',true);
    var append_list='';
    refCurrency.get().then(async function(snapshots) {
        var currencyData=snapshots.docs[0].data();
        currentCurrency=currencyData.symbol;
        currencyAtRight=currencyData.symbolAtRight;
        if(currencyData.decimal_degits) {
            decimal_degits=currencyData.decimal_degits;
        }
    });
    var placeholderImage='';
    var placeholder=database.collection('settings').doc('placeHolderImage');
    placeholder.get().then(async function(snapshotsimage) {
        var placeholderImageData=snapshotsimage.data();
        placeholderImage=placeholderImageData.image;
    })
    database.collection('vendor_categories').get().then(async function(snapshots) {
        snapshots.docs.forEach((listval) => {
            var data=listval.data();
            $('.category_selector').append($("<option></option>")
                .attr("value",data.id)
                .text(data.title));
        })
    });
    database.collection('vendors').get().then(async function(snapshots) {
        snapshots.docs.forEach((listval) => {
            var data=listval.data();
            if(data.title!='' && data.title!=null){
            $('.restaurant_selector').append($("<option></option>")
                .attr("value",data.id)
                .text(data.title));
            }
        })
    });
var initialRef=ref;
$('select').change(async function() {
        var restaurant = $('.restaurant_selector').val();
        var foodType = $('.food_type_selector').val();
        var category = $('.category_selector').val();
        refData = initialRef;
        if (restaurant) {
            refData = refData.where('vendorID', '==', restaurant);
        }
        if (foodType) {
           refData= (foodType=="veg") ? refData.where('nonveg', '==', false) : refData.where('nonveg', '==', true)          
        }
        if (category) {
            refData=refData.where('categoryID','==',category);
        }
         ref=refData;
        $('#foodTable').DataTable().ajax.reload();
    });
    $(document).ready(function() {
        $('.restaurant_selector').select2({
            placeholder: "{{trans('lang.restaurant')}}",  
            minimumResultsForSearch: Infinity,
            allowClear: true  
        });
        $('.food_type_selector').select2({
            placeholder: "{{trans('lang.type')}}",  
            minimumResultsForSearch: Infinity,
            allowClear: true  
        });
        $('.category_selector').select2({
            placeholder: "{{trans('lang.category')}}",  
            minimumResultsForSearch: Infinity,
            allowClear: true  
        });
        $('select').on("select2:unselecting", function(e) {
            var self = $(this);
            setTimeout(function() {
                self.select2('close');
            }, 0);
        });
        $('#category_search_dropdown').hide();
        $(document.body).on('click','.redirecttopage',function() {
            var url=$(this).attr('data-url');
            window.location.href=url;
        });
        jQuery("#data-table_processing").show();
        $(document).on('click', '.dt-button-collection .dt-button', function () {
            $('.dt-button-collection').hide();
            $('.dt-button-background').hide();
        });
        $(document).on('click', function (event) {
            if (!$(event.target).closest('.dt-button-collection, .dt-buttons').length) {
                $('.dt-button-collection').hide();
                $('.dt-button-background').hide();
            }
        });
        var fieldConfig = {
            columns: [
                { key: 'foodName', header: "{{trans('lang.food_name')}}" },
                { key: 'restaurant', header: "{{trans('lang.restaurant')}}" },
                { key: 'category', header: "{{trans('lang.category')}}" },
                { key: 'finalPrice', header: "{{trans('lang.food_price')}}" },
            ],
            fileName: "{{trans('lang.food_table')}}",
        };
        const table=$('#foodTable').DataTable({
            pageLength: 10, // Number of rows per page
            processing: false, // Show processing indicator
            serverSide: true, // Enable server-side processing
            responsive: true,
            ajax: async function(data,callback,settings) {
                const start=data.start;
                const length=data.length;
                const searchValue=data.search.value.toLowerCase();
                const orderColumnIndex=data.order[0].column;
                const orderDirection=data.order[0].dir;
                @if($id!='')
                    const orderableColumns=(checkDeletePermission)? ['','foodName','finalPrice','category','','']:['name','finalPrice','category','','']; // Ensure this matches the actual column names
                @else
                    const orderableColumns=(checkDeletePermission)? ['','foodName','finalPrice','restaurant','category','','']:['name','finalPrice','restaurant','category','','']; // Ensure this matches the actual column names
                @endif
                const orderByField=orderableColumns[orderColumnIndex]; // Adjust the index to match your table
                if(searchValue.length>=3||searchValue.length===0) {
                    $('#data-table_processing').show();
                }
                await ref.get().then(async function(querySnapshot) {
                    if(querySnapshot.empty) {
                        $('.food_count').text(0);
                        console.error("No data found in Firestore.");
                        $('#data-table_processing').hide(); // Hide loader
                        callback({
                            draw: data.draw,
                            recordsTotal: 0,
                            recordsFiltered: 0,
                            filteredData: [],
                            data: [] // No data
                        });
                        return;
                    }
                    var restaurantNames={};
                    // Fetch restaurants names
                    @if($id=='')
                        const vendorDocs=await database.collection('vendors').get();
                    vendorDocs.forEach(doc => {
                        restaurantNames[doc.id]=doc.data().title;
                    });
                    @endif
                    var categoryNames={};
                    const categoryDocs=await database.collection('vendor_categories').get();
                    categoryDocs.forEach(doc => {
                        categoryNames[doc.id]=doc.data().title;
                    });
                    let records=[];
                    let filteredRecords=[];
                    await Promise.all(querySnapshot.docs.map(async (doc) => {
                        let childData=doc.data();
                        childData.id=doc.id; // Ensure the document ID is included in the data
                        var finalPrice=0;
                        if(childData.hasOwnProperty('disPrice')&&childData.disPrice!=''&&childData.disPrice!='0') {
                            finalPrice=childData.disPrice;
                        } else {
                            finalPrice=childData.price;
                        }
                        childData.foodName=childData.name;
                        childData.finalPrice=parseInt(finalPrice);
                        childData.restaurant=restaurantNames[childData.vendorID]||'';
                        childData.category=categoryNames[childData.categoryID]||'';
                        if(searchValue) {
                            if(
                                (childData.name&&childData.name.toString().toLowerCase().includes(searchValue))||
                                (childData.finalPrice&&childData.finalPrice.toString().includes(searchValue))
                                ||(childData.restaurant&&childData.restaurant.toString().toLowerCase().includes(searchValue))||
                                (childData.category&&childData.category.toString().toLowerCase().includes(searchValue))
                            ) {
                                filteredRecords.push(childData);
                            }
                        } else {
                            filteredRecords.push(childData);
                        }
                    }));
                    filteredRecords.sort((a,b) => {
                        let aValue=a[orderByField];
                        let bValue=b[orderByField];
                        if(orderByField==='finalPrice') {
                            aValue=a[orderByField]? parseInt(a[orderByField]):0;
                            bValue=b[orderByField]? parseInt(b[orderByField]):0;
                        } else {
                            aValue=a[orderByField]? a[orderByField].toString().toLowerCase():'';
                            bValue=b[orderByField]? b[orderByField].toString().toLowerCase():''
                        }
                        if(orderDirection==='asc') {
                            return (aValue>bValue)? 1:-1;
                        } else {
                            return (aValue<bValue)? 1:-1;
                        }
                    });
                    const totalRecords=filteredRecords.length;
                    $('.food_count').text(totalRecords);
                    const paginatedRecords=filteredRecords.slice(start,start+length);
                    await Promise.all(paginatedRecords.map(async (childData) => {
                        var getData=await buildHTML(childData);
                        records.push(getData);
                    }));
                    $('#data-table_processing').hide(); // Hide loader
                    callback({
                        draw: data.draw,
                        recordsTotal: totalRecords, // Total number of records in Firestore
                        recordsFiltered: totalRecords, 
                        filteredData: filteredRecords,
                        data: records // The actual data to display in the table
                    });
                }).catch(function(error) {
                    console.error("Error fetching data from Firestore:",error);
                    $('#data-table_processing').hide(); // Hide loader
                    callback({
                        draw: data.draw,
                        recordsTotal: 0,
                        recordsFiltered: 0,
                        filteredData: [], 
                        data: [] // No data due to error
                    });
                });
            },
            order: (checkDeletePermission)? [1,'asc']:[0,'asc'],
            columnDefs: [
                {
                    orderable: false,
                    targets: (restaurantID=='')? ((checkDeletePermission)? [0,5,6]:[4,5]):((checkDeletePermission)? [0,4,5]:[3,5])
                },
                {
                    type: 'formatted-num',
                    targets: (checkDeletePermission)? [2]:[3]
                }
            ],
            "language": {
                "zeroRecords": "{{trans("lang.no_record_found")}}",
                "emptyTable": "{{trans("lang.no_record_found")}}",
                "processing": "" // Remove default loader
            },
            dom: 'lfrtipB',
            buttons: [
                {
                    extend: 'collection',
                    text: '<i class="mdi mdi-cloud-download"></i> Export as',
                    className: 'btn btn-info',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            text: 'Export Excel',
                            action: function (e, dt, button, config) {
                                exportData(dt, 'excel',fieldConfig);
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: 'Export PDF',
                            action: function (e, dt, button, config) {
                                exportData(dt, 'pdf',fieldConfig);
                            }
                        },   
                        {
                            extend: 'csvHtml5',
                            text: 'Export CSV',
                            action: function (e, dt, button, config) {
                                exportData(dt, 'csv',fieldConfig);
                            }
                        }
                    ]
                }
            ],
            initComplete: function() {
                $(".dataTables_filter").append($(".dt-buttons").detach());
                $('.dataTables_filter input').attr('placeholder', 'Search here...').attr('autocomplete','new-password').val('');
                $('.dataTables_filter label').contents().filter(function() {
                    return this.nodeType === 3; 
                }).remove();
            }
        });
        $('#search-input').on('input',debounce(function() {
            const searchValue=$(this).val();
            if(searchValue.length>=3) {
                $('#data-table_processing').show();
                table.search(searchValue).draw();
            } else if(searchValue.length===0) {
                $('#data-table_processing').show();
                table.search('').draw();
            }
        },300));
    });
    function debounce(func,wait) {
        let timeout;
        const context=this;
        return function(...args) {
            clearTimeout(timeout);
            timeout=setTimeout(() => func.apply(context,args),wait);
        };
    }
    async function buildHTML(val) {
        var html=[];
        newdate='';
        var imageHtml='';
        var id=val.id;
        var route1='{{route("foods.edit", ":id")}}';
        route1=route1.replace(':id',id);
        <?php if ($id != '') { ?>
            route1=route1+'?eid={{$id}}';
        <?php } ?>
        if(val.photos!=''&&val.photos!=null) {
           imageHtml='<img onerror="this.onerror=null;this.src=\''+placeholderImage+'\'" class="rounded" width="100%" style="width:70px;height:70px;" src="'+val.photo+'" alt="image">';
        } else if(val.photo!=''&&val.photos!=null) {
           imageHtml='<img onerror="this.onerror=null;this.src=\''+placeholderImage+'\'" class="rounded" width="100%" style="width:70px;height:70px;" src="'+val.photo+'" alt="image">';
        } else {
            imageHtml='<img width="100%" style="width:70px;height:70px;" src="'+placeholderImage+'" alt="image">';
        }
        if(checkDeletePermission) {
            html.push('<td class="delete-all"><input type="checkbox" id="is_open_'+id+'" name="record" class="is_open" dataId="'+id+'"><label class="col-3 control-label"\n'+
                'for="is_open_'+id+'" ></label></td>');
        }
        html.push(imageHtml+'<a href="'+route1+'" >'+val.name+'</a>');
        if(val.hasOwnProperty('disPrice')&&val.disPrice!=''&&val.disPrice!='0') {
            if(currencyAtRight) {
                html.push('<span class="text-green">'+parseFloat(val.disPrice).toFixed(decimal_degits)+''+currentCurrency+'  <s>'+parseFloat(val.price).toFixed(decimal_degits)+''+currentCurrency+'</s>');
            } else {
                html.push('<span class="text-green">'+''+currentCurrency+parseFloat(val.disPrice).toFixed(decimal_degits)+'  <s>'+currentCurrency+''+parseFloat(val.price).toFixed(decimal_degits)+'</s>');
            }
        } else {
            if(currencyAtRight) {
                html.push('<span class="text-green">'+parseFloat(val.price).toFixed(decimal_degits)+''+currentCurrency);
            } else {
                html.push('<span class="text-green">'+currentCurrency+''+parseFloat(val.price).toFixed(decimal_degits));
            }
        }
        <?php if ($id == '') { ?>
            var restaurantroute='{{route("restaurants.view", ":id")}}';
            restaurantroute=restaurantroute.replace(':id',val.vendorID);
            html.push('<a href="'+restaurantroute+'">'+val.restaurant+'</a>');
        <?php } ?>
        var caregoryroute='{{route("categories.edit", ":id")}}';
        caregoryroute=caregoryroute.replace(':id',val.categoryID);
        html.push('<a href="'+caregoryroute+'">'+val.category+'</a>');
        if(val.publish) {
            html.push('<label class="switch"><input type="checkbox" checked id="'+val.id+'" name="isActive"><span class="slider round"></span></label>');
        } else {
            html.push('<label class="switch"><input type="checkbox" id="'+val.id+'" name="isActive"><span class="slider round"></span></label>');
        }
        // Add isAvailable toggle
        if(val.isAvailable) {
            html.push('<label class="switch"><input type="checkbox" checked id="isAvailable_'+val.id+'" name="isAvailable"><span class="slider round"></span></label>');
        } else {
            html.push('<label class="switch"><input type="checkbox" id="isAvailable_'+val.id+'" name="isAvailable"><span class="slider round"></span></label>');
        }
        var actionHtml='';
        actionHtml+='<span class="action-btn"><a href="'+route1+'" class="link-td"><i class="mdi mdi-lead-pencil" title="Edit"></i></a>';
        if(checkDeletePermission) {
            actionHtml+='<a id="'+val.id+'" name="food-delete" href="javascript:void(0)" class="delete-btn"><i class="mdi mdi-delete"></i></a>';
        }
        actionHtml+='</span>';
        html.push(actionHtml);
        return html;
    }
    async function checkIfImageExists(url,callback) {
        const img=new Image();
        img.src=url;
        if(img.complete) {
            callback(true);
        } else {
            img.onload=() => {
                callback(true);
            };
            img.onerror=() => {
                callback(false);
            };
        }
    }
    $(document).on("click","input[name='isActive']",function(e) {
        var ischeck=$(this).is(':checked');
        var id=this.id;
        if(ischeck) {
            database.collection('vendor_products').doc(id).update({
                'publish': true
            }).then(function(result) {
            });
        } else {
            database.collection('vendor_products').doc(id).update({
                'publish': false
            }).then(function(result) {
            });
        }
    });
    // Add isAvailable toggle logic
    $(document).on("click","input[name='isAvailable']",function(e) {
        var ischeck=$(this).is(':checked');
        var id=this.id.replace('isAvailable_','');
        if(ischeck) {
            database.collection('vendor_products').doc(id).update({
                'isAvailable': true
            }).then(function(result) {
            });
        } else {
            database.collection('vendor_products').doc(id).update({
                'isAvailable': false
            }).then(function(result) {
            });
        }
    });
    async function getStoreNameFunction(vendorId) {
        var vendorName='';
        await database.collection('vendors').where('id','==',vendorId).get().then(async function(snapshots) {
            if(!snapshots.empty) {
                var vendorData=snapshots.docs[0].data();
                vendorName=vendorData.title;
                $('.restaurantTitle').html('{{trans("lang.food_plural")}} - '+vendorName);
                if(vendorData.dine_in_active==true) {
                    $(".dine_in_future").show();
                }
            }
        });
        return vendorName;
    }
    $(document).on("click","a[name='food-delete']",async function(e) {
        var id=this.id;
        await deleteDocumentWithImage('vendor_products',id,'photo','photos');
        window.location.reload();
    });
    $(document.body).on('change','#selected_search',function() {
        if(jQuery(this).val()=='category') {
            var ref_category=database.collection('vendor_categories');
            ref_category.get().then(async function(snapshots) {
                snapshots.docs.forEach((listval) => {
                    var data=listval.data();
                    $('#category_search_dropdown').append($("<option></option").attr("value",data.id).text(data.title));
                });
            });
            jQuery('#search').hide();
            jQuery('#category_search_dropdown').show();
        } else {
            jQuery('#search').show();
            jQuery('#category_search_dropdown').hide();
        }
    });
    $('#select-all').change(function() {
        var isChecked=$(this).prop('checked');
        $('input[type="checkbox"][name="record"]').prop('checked',isChecked);
    });
    $('#deleteAll').click(function() {
        if(confirm("{{trans('lang.selected_delete_alert')}}")) {
            jQuery("#data-table_processing").show();
            // Loop through all selected records and delete them
            $('input[type="checkbox"][name="record"]:checked').each(async function() {
                var id=$(this).attr('dataId');
                await deleteDocumentWithImage('vendor_products',id,'photo','photos');
                window.location.reload();
            });
        }
    });
</script>
@endsection