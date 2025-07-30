@extends('layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.restaurant_plural')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item">{{trans('lang.restaurant_plural')}}</li>
                <li class="breadcrumb-item active">{{trans('lang.restaurant_table')}}</li>
            </ol>
        </div>
        <div>
        </div>
    </div>
    <div class="container-fluid">
       <div class="admin-top-section"> 
        <div class="row">
            <div class="col-12">
                <div class="d-flex top-title-section pb-4 justify-content-between">
                    <div class="d-flex top-title-left align-self-center">
                        <span class="icon mr-3"><img src="{{ asset('images/restaurant.png') }}"></span>
                        <h3 class="mb-0">{{trans('lang.restaurant_plural')}}</h3>
                        <span class="counter ml-3 rest_count"></span>
                    </div>
                    <div class="d-flex top-title-right align-self-center">
                        <div class="select-box pl-3">
                            <select class="form-control restaurant_type_selector">
                                <option value="">{{trans('lang.restaurant_type')}}</option>
                                <option value="true">{{trans('lang.dine_in')}}</option>
                            </select>
                        </div>
                        <div class="select-box pl-3">
                            <select class="form-control business_model_selector">
                                <option value="" disabled selected>{{trans('lang.business_model')}}</option>
                            </select>
                        </div>
                        <div class="select-box pl-3">
                            <select class="form-control cuisine_selector">
                                <option value="" disabled selected>{{trans('lang.select_cuisines')}}</option>
                            </select>
                        </div>
                        <div class="select-box pl-3">
                            <select class="form-control zone_selector">
                            <option value="" disabled selected>{{trans('lang.select_zone')}}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <div class="row">
            <div class="col-12">
                <div class="card border">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card card-box-with-icon bg--1">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                       <div class="card-box-with-content">
                                        <h4 class="text-dark-2 mb-1 h4 rest_count">00</h4>
                                        <p class="mb-0 small text-dark-2">{{trans('lang.dashboard_total_restaurants')}}</p>
                                       </div>
                                        <span class="box-icon ab"><img src="{{ asset('images/restaurant_icon.png') }}"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-box-with-icon bg--5">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                       <div class="card-box-with-content">
                                        <h4 class="text-dark-2 mb-1 h4 rest_active_count">00</h4>
                                        <p class="mb-0 small text-dark-2">{{trans('lang.active_restaurants')}}</p>
                                       </div>
                                        <span class="box-icon ab"><img src="{{ asset('images/active_restaurant.png') }}"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-box-with-icon bg--8">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                       <div class="card-box-with-content">
                                        <h4 class="text-dark-2 mb-1 h4 rest_inactive_count">00</h4>
                                        <p class="mb-0 small text-dark-2">{{trans('lang.inactive_restaurants')}}</p>
                                       </div>
                                        <span class="box-icon ab"><img src="{{ asset('images/inactive_restaurant.png') }}"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-box-with-icon bg--6">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                       <div class="card-box-with-content">
                                        <h4 class="text-dark-2 mb-1 h4 new_joined_rest">00</h4>
                                        <p class="mb-0 small text-dark-2">{{trans('lang.new_joined_restaurants')}}</p>
                                       </div>
                                        <span class="box-icon ab"><img src="{{ asset('images/new_restaurant.png') }}"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       </div>
       <div class="row mb-4">
        <div class="col-12">
            <div class="card border">
                <div class="card-header d-flex justify-content-between align-items-center border-0">
                    <div class="card-header-title">
                        <h3 class="text-dark-2 mb-2 h4">Bulk Import/Update Restaurants</h3>
                        <p class="mb-0 text-dark-2">Upload Excel file to import or update multiple restaurants at once</p>
                    </div>
                    <div class="card-header-right d-flex align-items-center">
                        <div class="card-header-btn mr-3">
                            <a href="{{ route('restaurants.download-template') }}" class="btn btn-outline-primary rounded-full">
                                <i class="mdi mdi-download mr-2"></i>Download Template
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('restaurants.bulk-import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="bulkUpdateFile" class="control-label">Select Excel File (.xls/.xlsx)</label>
                                    <input type="file" name="file" id="bulkUpdateFile" accept=".xls,.xlsx" class="form-control" required>
                                    <div class="form-text text-muted">
                                        <i class="mdi mdi-information-outline mr-1"></i>
                                        File should contain: id, title, authorName, phonenumber, adminCommission, etc. (id is required)
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary rounded-full">
                                    <i class="mdi mdi-upload mr-2"></i>Bulk Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{!! session('success') !!}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{!! $error !!}</li>
                @endforeach
            </ul>
        </div>
    @endif
       <div class="table-list">
        <div class="row">
            <div class="col-12">
                <div class="card border">
                    <div class="card-header d-flex justify-content-between align-items-center border-0">
                    <div class="card-header-title">
                        <h3 class="text-dark-2 mb-2 h4">{{trans('lang.restaurant_table')}}</h3>
                        <p class="mb-0 text-dark-2">{{trans('lang.restaurants_table_text')}}</p>
                    </div>
                    <div class="card-header-right d-flex align-items-center">
                        <div class="card-header-btn mr-3"> 
                        <a href="{!! route('restaurants.create') !!}" class="btn-primary btn rounded-full"><i class="mdi mdi-plus mr-2"></i>{{trans('lang.create_restaurant')}}</a>
                        </div>
                    </div>                
                    </div>
                    <div class="card-body">
                            <div class="table-responsive m-t-10">
                                <table id="storeTable"
                                    class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                    cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <?php if (in_array('restaurant.delete', json_decode(@session('user_permissions'), true))) { ?>
                                                <th class="delete-all"><input type="checkbox" id="is_active"><label
                                                        class="col-3 control-label" for="is_active"><a id="deleteAll"
                                                            class="do_not_delete" href="javascript:void(0)"><i
                                                                class="mdi mdi-delete"></i> {{trans('lang.all')}}</a></label>
                                                </th>
                                            <?php } ?>
                                            <th>{{trans('lang.restaurant_info')}}</th>
                                            <th>{{trans('lang.owner_info')}}</th>
                                            <th>Admin Commission</th>
                                            <th>{{trans('lang.date')}}</th>
                                            <th>{{trans('lang.wallet_history')}}</th>
                                            <th>{{trans('lang.actions')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="append_restaurants"></tbody>
                                </table>
                            </div>
                            <!-- Popup -->
                            <div class="modal fade" id="create_vendor" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered notification-main" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">{{trans('lang.copy_vendor')}}
                                                <span id="vendor_title_lable"></span>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div id="data-table_processing"
                                                class="dataTables_processing panel panel-default" style="display: none;">
                                                {{trans('lang.processing')}}
                                            </div>
                                            <div class="error_top"></div>
                                            <!-- Form -->
                                            <div class="form-row">
                                                <div class="col-md-12 form-group">
                                                    <label class="form-label">{{trans('lang.first_name')}}</label>
                                                    <div class="input-group">
                                                        <input placeholder="Name" type="text" id="user_name"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label class="form-label">{{trans('lang.last_name')}}</label>
                                                    <div class="input-group">
                                                        <input placeholder="Name" type="text" id="user_last_name"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label class="form-label">{{trans('lang.vendor_title')}}</label>
                                                    <div class="input-group">
                                                        <input placeholder="Vendor Title" type="text" id="vendor_title"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 form-group"><label
                                                        class="form-label">{{trans('lang.email')}}</label><input
                                                        placeholder="Email" value="" id="user_email" type="text"
                                                        class="form-control"></div>
                                                <div class="col-md-12 form-group"><label
                                                        class="form-label">{{trans('lang.password')}}</label><input
                                                        placeholder="Password" id="user_password" type="password"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <!-- Form -->
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary save-form-btn"
                                                >{{trans('lang.create')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Popup -->
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
    var database = firebase.firestore();
    var refData = database.collection('vendors');
    var currentCurrency = '';
    var currencyAtRight = false;
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    var decimal_degits = 0;
    refCurrency.get().then(async function (snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });
    $('select').change(async function() {
        var zoneValue = $('.zone_selector').val();
        var restaurantTypeValue = $('.restaurant_type_selector').val();
        var businessModelValue = $('.business_model_selector').val();
        var cuisineValue = $('.cuisine_selector').val();
        refData = database.collection('vendors'); 
        if (zoneValue) {
            refData = refData.where('zoneId', '==', zoneValue);
        }
        if (restaurantTypeValue == "true") {
            refData = refData.where('enabledDiveInFuture', '==', true);
        }
        if (businessModelValue) {
            var vendorSelectedIds = await subscriptionPlanVendorIds(businessModelValue);
            if (vendorSelectedIds.length > 0) {
                refData = refData.where('id', 'in', vendorSelectedIds);
            } else{
                refData = refData.where('id', '==', null);
            }
        }
        if (cuisineValue) {
            refData = refData.where('categoryID', '==', cuisineValue);
        }
        $('#storeTable').DataTable().ajax.reload();
    });
    async function subscriptionPlanVendorIds(businessModelValue){
        var vendorIds = []
        try {
            const querySnapshot = await database.collection('users').where('subscriptionPlanId', '==', businessModelValue).get();  
            vendorIds = querySnapshot.docs.map(doc => doc.data().vendorID).filter(vendorID => vendorID !== undefined && vendorID !== null && vendorID !== '');
        } catch (error) {
            console.error("Error fetching users:", error);
        }
        return vendorIds;
    }
    var append_list = '';
    var placeholderImage = '';
    var user_permissions = '<?php echo @session("user_permissions") ?>';
    user_permissions = Object.values(JSON.parse(user_permissions));
    var checkDeletePermission = false;
    if ($.inArray('restaurant.delete', user_permissions) >= 0) {
        checkDeletePermission = true;
    }
    var userData = [];
    var vendorData = [];
    var vendorProducts = [];
    database.collection('zone').where('publish', '==', true).orderBy('name','asc').get().then(async function (snapshots) {
        snapshots.docs.forEach((listval) => {
            var data = listval.data();
            $('.zone_selector').append($("<option></option>")
                .attr("value", data.id)
                .text(data.name));
        })
    });
    database.collection('vendor_categories').where('publish', '==', true).get().then(async function (snapshots) {
        snapshots.docs.forEach((listval) => {
            var data = listval.data();
            $('.cuisine_selector').append($("<option></option>")
                .attr("value", data.id)
                .text(data.title));
        })
    });
    database.collection('subscription_plans').where('isEnable', '==', true).orderBy('name', 'asc').get().then(snapshots => {
        snapshots.docs.forEach(doc => {
            const { expiryDay, createdAt, id, name, type } = doc.data();
            if (expiryDay && createdAt) {
                const expiryDate = new Date(createdAt.toDate());
                expiryDate.setDate(expiryDate.getDate() + parseInt(expiryDay, 10));
                if (type !== "free" && expiryDate > new Date()) {
                    $('.business_model_selector').append($("<option>").attr("value", id).text(name));
                } else {
                    $('.business_model_selector').append($("<option>").attr("value", id).text(name));
                }
            }
        });
    });
    $('.zone_selector').select2({
        placeholder: "{{trans('lang.select_zone')}}",  
        minimumResultsForSearch: Infinity,
        allowClear: true 
    });
    $('.restaurant_type_selector').select2({
        placeholder: "{{trans('lang.restaurant_type')}}",  
        minimumResultsForSearch: Infinity,
        allowClear: true  
    });
    $('.business_model_selector').select2({
        placeholder: "{{trans('lang.business_model')}}",  
        minimumResultsForSearch: Infinity,
        allowClear: true  
    });
    $('.cuisine_selector').select2({
        placeholder: "{{trans('lang.select_cuisines')}}",  
        minimumResultsForSearch: Infinity,
        allowClear: true  
    });
    $('select').on("select2:unselecting", function(e) {
        var self = $(this);
        setTimeout(function() {
            self.select2('close');
        }, 0);
    });
    var placeholder = database.collection('settings').doc('placeHolderImage');
    placeholder.get().then(async function (snapshotsimage) {
        var placeholderImageData = snapshotsimage.data();
        placeholderImage = placeholderImageData.image;
    })
    $(document).ready(function () {
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
                { key: 'id', header: "{{trans('lang.id')}}" },
                { key: 'title', header: "{{trans('lang.restaurant')}}" },
                { key: 'authorName', header: "{{trans('lang.owner_name')}}" },
                { key: 'phonenumber', header: "{{trans('lang.phone')}}" },
                { key: 'createdAt', header: "{{trans('lang.created_at')}}" },
                { key: 'location', header: "{{trans('lang.location')}}" },
            ],
            fileName: "{{trans('lang.restaurant_table')}}",
        };
        const table = $('#storeTable').DataTable({
            pageLength: 10,
            processing: false,
            serverSide: true,
            responsive: true,
            ajax: async function(data, callback, settings) {
                const start = data.start;
                const length = data.length;
                const searchValue = data.search.value.toLowerCase();
                const orderColumnIndex = data.order[0].column;
                const orderDirection = data.order[0].dir;
                const orderableColumns = (checkDeletePermission) ? ['','title', 'authorName', 'createdAt', '', ''] : ['title', 'authorName', 'createdAt', '', ''];
                const orderByField = orderableColumns[orderColumnIndex];
                if (searchValue.length >= 3 || searchValue.length === 0) {
                    $('#data-table_processing').show();
                }
                await refData.orderBy('createdAt', 'asc').get().then(async function(querySnapshot) {
                    if (querySnapshot.empty) {
                        $('.rest_count').text(0);
                        console.error("No data found in Firestore.");
                        $('#data-table_processing').hide();
                        callback({
                            draw: data.draw,
                            recordsTotal: 0,
                            recordsFiltered: 0,
                            filteredData:[],
                            data: []
                        });
                        $('.rest_count').text('00');
                        $('.rest_active_count').text('00');
                        $('.rest_inactive_count').text('00');
                        $('.new_joined_rest').text('00');
                        return;
                    }
                    let records = [];
                    let filteredRecords = [];
                    await Promise.all(querySnapshot.docs.map(async (doc) => {
                        let childData = doc.data();
                        childData.phone = (childData.phonenumber != '' && childData.phonenumber != null && childData.phonenumber.slice(0, 1) == '+') ? childData.phonenumber.slice(1) : childData.phonenumber;
                        childData.id = doc.id;
                        childData.phonenumber = shortEditNumber(childData.phonenumber);
                        if (searchValue) {
                            var date = '';
                            var time = '';
                            if (childData.hasOwnProperty("createdAt")) {
                                try {
                                    date = childData.createdAt.toDate().toDateString();
                                    time = childData.createdAt.toDate().toLocaleTimeString('en-US');
                                } catch (err) {}
                            }
                            var createdAt = date + ' ' + time;
                            if (
                                (childData.title && childData.title.toLowerCase().toString().includes(searchValue)) ||
                                (childData.authorName && childData.authorName.toLowerCase().toString().includes(searchValue)) ||
                                (createdAt && createdAt.toString().toLowerCase().indexOf(searchValue) > -1) ||
                                (childData.email && childData.email.toLowerCase().toString().includes(searchValue)) ||
                                (childData.phoneNumber && childData.phoneNumber.toString().includes(searchValue))
                            ) {
                                filteredRecords.push(childData);
                            }
                        } else {
                            filteredRecords.push(childData);
                        }
                    }));
                    filteredRecords.sort((a, b) => {
                        let aValue = a[orderByField] ? a[orderByField].toString().toLowerCase() : '';
                        let bValue = b[orderByField] ? b[orderByField].toString().toLowerCase() : '';
                        if (orderByField === 'createdAt') {
                            try {
                                aValue = a[orderByField] ? new Date(a[orderByField].toDate()).getTime() : 0;
                                bValue = b[orderByField] ? new Date(b[orderByField].toDate()).getTime() : 0;
                            } catch (err) {}
                        }
                        if (orderDirection === 'asc') {
                            return (aValue > bValue) ? 1 : -1;
                        } else {
                            return (aValue < bValue) ? 1 : -1;
                        }
                    });
                    const totalRecords = filteredRecords.length;
                    let active_rest = 0;
                    let inactive_rest = 0;
                    let new_joined_rest = 0;
                    const today = new Date().setHours(0, 0, 0, 0); 
                    await Promise.all(filteredRecords.map(async (childData) => {
                        var isActive = false;
                        if (childData.author) {
                            const user_id = childData.author;
                            isActive = await vendorStatus(user_id); 
                        }
                        if (isActive) {
                            active_rest += 1;
                        } else {
                            inactive_rest += 1;
                        }
                        if (childData.createdAt && new Date(childData.createdAt.seconds * 1000).setHours(0, 0, 0, 0) === today) {
                            new_joined_rest += 1;
                        }
                    }));
                    $('.rest_count').text(totalRecords);
                    $('.rest_active_count').text(active_rest);
                    $('.rest_inactive_count').text(inactive_rest);
                    $('.new_joined_rest').text(new_joined_rest);
                    const paginatedRecords = filteredRecords.slice(start, start + length);
                    await Promise.all(paginatedRecords.map(async (childData) => {
                        var getData = await buildHTML(childData);
                        records.push(getData);
                    }));
                    $('#data-table_processing').hide();
                    callback({
                        draw: data.draw,
                        recordsTotal: totalRecords,
                        recordsFiltered: totalRecords,
                        filteredData: filteredRecords,
                        data: records
                    });
                }).catch(function(error) {
                    console.error("Error fetching data from Firestore:", error);
                    $('#data-table_processing').hide();
                    callback({
                        draw: data.draw,
                        recordsTotal: 0,
                        recordsFiltered: 0,
                        filteredData: [],
                        data: []
                    });
                });
            },
            order: (checkDeletePermission) ? [[4, 'desc']] : [[3, 'desc']],
            columnDefs: [
                {
                    targets: (checkDeletePermission) ? 4 : 3,
                    type: 'date',
                    render: function(data) {
                        return data;
                    }
                },
                { orderable: false, targets: (checkDeletePermission) ? [0, 4, 5] : [3, 4] },
            ],
            "language": {
                "zeroRecords": "{{trans('lang.no_record_found')}}",
                "emptyTable": "{{trans('lang.no_record_found')}}",
                "processing": ""
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
        function debounce(func, wait) {
            let timeout;
            const context = this;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }
        $('#search-input').on('input', debounce(function () {
            const searchValue = $(this).val();
            alert(searchValue);
            if (searchValue.length >= 3) {
                $('#data-table_processing').show();
                table.search(searchValue).draw();
            } else if (searchValue.length === 0) {
                $('#data-table_processing').show();
                table.search('').draw();
            }
        }, 300));
    });
    async function buildHTML(val) {
        var html = [];
        var id = val.id;
        var vendorUserId = val.author;
        var route1 = '{{route("restaurants.edit",":id")}}';
        route1 = route1.replace(':id', id);
        var route_view = '{{route("restaurants.view",":id")}}';
        route_view = route_view.replace(':id', id);
        if (checkDeletePermission) {
            html.push('<td class="delete-all"><input type="checkbox" id="is_open_' + id + '" class="is_open" dataId="' + id + '" author="' + val.author + '"><label class="col-3 control-label"\n' +
                'for="is_open_' + id + '" ></label></td>');
        }
        var restaurantInfo = '';
        if (val.photo != '' && val.photo != null) {
            restaurantInfo += '<img onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" alt="" width="100%" style="width:70px;height:70px;" src="' + val.photo + '" alt="image">';
        } else {
            restaurantInfo += '<img alt="" width="100%" style="width:70px;height:70px;" src="' + placeholderImage + '" alt="image">';
        }
        if(val.title != " " && val.title != "null" && val.title != null && val.title != ""){
            restaurantInfo += '<a href="' + route_view + '">' + val.title + '</a>';
        }else{
            restaurantInfo += 'UNKNOWN';
        }
        html.push(restaurantInfo);
        var ownerInfo = '';
        if (val.authorName) {
            ownerInfo +=  '<a href="' + route_view + '">' + val.authorName + '</a><br>';
        }
        if (val.hasOwnProperty('phonenumber') && val.phonenumber != null && val.phonenumber != "") {
            ownerInfo +=  '<a>' + val.phonenumber + '</a>';
        } else {
            ownerInfo += '';
        }
        html.push(ownerInfo);
        // Admin Commission column
        var adminCommission = '';
        if (val.adminCommission && val.adminCommission.fix_commission !== undefined) {
            adminCommission = val.adminCommission.fix_commission;
        } else if (val.admin_commission !== undefined) {
            adminCommission = val.admin_commission;
        } else {
            adminCommission = '-';
        }
        html.push(adminCommission);
        var date = '';
        var time = '';
        if (val.hasOwnProperty("createdAt")) {
            try {
                date = val.createdAt.toDate().toDateString();
                time = val.createdAt.toDate().toLocaleTimeString('en-US');
            } catch (err) {
            }
            html.push('<span class="dt-time">' + date + ' ' + time + '</span>');
        } else {
            html.push('');
        }
        var payoutRequests = '{{route("users.walletstransaction",":id")}}';
        payoutRequests = payoutRequests.replace(':id', val.author);
        html.push('<a href="' + payoutRequests + '">{{trans("lang.wallet_history")}}</a>');
        var active = val.isActive;
        var vendorId = val.id;
        var food_url = '{{route("restaurants.foods",":id")}}';
        food_url = food_url.replace(":id", vendorId);
        var vendor_url = '{{route("restaurants.orders",":id")}}';
        vendor_url = vendor_url.replace(":id", vendorId);
        var actionHtml = '';
        actionHtml += `<span class="action-btn">
            <a href="${food_url}"><i class="mdi mdi-food" title="Foods"></i></a>
            <a href="${vendor_url}"><i class="mdi mdi-view-list" title="Orders"></i></a>
            <a href="javascript:void(0)" vendor_id="${val.id}" author="${val.author}" name="vendor-clone" title="Copy"><i class="mdi mdi-content-copy"></i></a>
            <a href="${route_view}"><i class="mdi mdi-eye" title="View"></i></a>
            <a href="${route1}"><i class="mdi mdi-lead-pencil" title="Edit"></i></a>`;
        if (checkDeletePermission) {
            actionHtml += `<a id="${val.id}" author="${val.author}" name="vendor-delete" class="delete-btn" href="javascript:void(0)" title="Delete"><i class="mdi mdi-delete"></i></a>`;
        }
        actionHtml += `</span>`;
        html.push(actionHtml);
        return html;
    }
    async function vendorStatus(id) {
        let status = true;
        await database.collection('users').doc(id).get().then((snapshots) => {
            let data = snapshots.data();
            if (data) {
                status = data.active; 
            }
        });
        return status;
    }
    /*async function getTotalProduct(id) {
        let productSnapshots = await database.collection('vendor_products').where('vendorID', '==', id).get();
        return productSnapshots.docs.length;
    }
    async function getTotalOrders(id) {
        let productSnapshots = await database.collection('restaurant_orders').where('vendorID', '==', id).get();
        return productSnapshots.docs.length;
    }*/
    async function getOrdersWithdrawAmount(id) {
        var total_withdraws = 0;
        await  database.collection('payouts').where('vendorID', '==', id).where('paymentStatus', '==', 'Success').get().then(async function (productSnapshots) {
            if(productSnapshots && productSnapshots.docs && productSnapshots.docs.length > 0){
                productSnapshots.docs.forEach(function (doc) {
                    var order = doc.data();
                    withdraws = parseFloat(order.amount).toFixed(decimal_degits);
                    total_withdraws += parseFloat(withdraws);
                });
            }
        });
        return total_withdraws;
    }
    async function getOrdersTotalData(id) {
        var order_total = 0;
        var commission_total = 0;
        await database.collection('restaurant_orders').where('status','==','Order Completed').where('vendorID', '==', id).get().then(async function (productSnapshots) {
            if(productSnapshots && productSnapshots.docs && productSnapshots.docs.length > 0){
                productSnapshots.docs.forEach(function (doc) {
                    var order = doc.data();
                    var buildOrderTotalData = buildOrderTotal(order);
                    total = parseFloat(buildOrderTotalData.totalPrice).toFixed(decimal_degits);
                    order_total += parseFloat(total);
                    commission = parseFloat(buildOrderTotalData.adminCommission).toFixed(decimal_degits);
                    commission_total += parseFloat(commission);
                });
            }
        });
        return {
            adminCommission: commission_total,
            totalPrice: order_total
        };
    }
    function buildOrderTotal(snapshotsProducts) {
        var total_price = 0;
        var final_price = 0;
        var adminCommission = snapshotsProducts.adminCommission;
        var adminCommissionType = snapshotsProducts.adminCommissionType;
        var discount = snapshotsProducts.discount;
        var couponCode = snapshotsProducts.couponCode;
        var extras = snapshotsProducts.extras;
        var extras_price = snapshotsProducts.extras_price;
        var rejectedByDrivers = snapshotsProducts.rejectedByDrivers;
        var takeAway = snapshotsProducts.takeAway;
        var tip_amount = snapshotsProducts.tip_amount;
        var notes = snapshotsProducts.notes;
        var tax_amount = snapshotsProducts.vendor.tax_amount;
        var status = snapshotsProducts.status;
        var products = snapshotsProducts.products;
        deliveryCharge = snapshotsProducts.deliveryCharge;
        var specialDiscount = snapshotsProducts.specialDiscount;
        var intRegex = /^\d+$/;
        var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
        if (products) {
            products.forEach((product) => {
                var val = product;
                if(val.discountPrice!=0 && val.discountPrice!="" && val.discountPrice!=null && !isNaN(val.discountPrice)){
                    final_price = parseFloat(val.discountPrice);    
                }
                else{
                    final_price = parseFloat(val.price);
                }
                price_item = final_price.toFixed(decimal_degits);
                totalProductPrice = parseFloat(price_item) * parseInt(val.quantity);
                var extras_price = 0;
                if (product.extras != undefined && product.extras != '' && product.extras.length > 0) {
                    extras_price_item = (parseFloat(val.extras_price) * parseInt(val.quantity)).toFixed(decimal_degits);
                    if (parseFloat(extras_price_item) != NaN && val.extras_price != undefined) {
                        extras_price = extras_price_item;
                    }
                    totalProductPrice = parseFloat(extras_price) + parseFloat(totalProductPrice);
                }
                totalProductPrice = parseFloat(totalProductPrice).toFixed(decimal_degits);
                total_price += parseFloat(totalProductPrice);
            });
        }
        if (currencyAtRight) {
            var sub_total = parseFloat(total_price).toFixed(decimal_degits) + "" + currentCurrency;
        } else {
            var sub_total = currentCurrency + "" + parseFloat(total_price).toFixed(decimal_degits);
        }
        if (intRegex.test(discount) || floatRegex.test(discount)) {
            discount = parseFloat(discount).toFixed(decimal_degits);
            total_price -= parseFloat(discount);
        }
        if (specialDiscount != undefined) {
            special_discount = parseFloat(specialDiscount.special_discount).toFixed(2);
            total_price -= parseFloat(special_discount);
        }
        var total_item_price = total_price;
        var tax = 0;
        taxlabel = '';
        taxlabeltype = '';
        if (snapshotsProducts.hasOwnProperty('taxSetting') && snapshotsProducts.taxSetting.length > 0) {
            var total_tax_amount = 0;
            for (var i = 0; i < snapshotsProducts.taxSetting.length; i++) {
                var data = snapshotsProducts.taxSetting[i];
                if (data.type && data.tax) {
                    if (data.type == "percentage") {
                        tax = (data.tax * total_price) / 100;
                        var taxvalue = data.tax;
                        taxlabeltype = "%";
                    } else {
                        tax = data.tax;
                        taxlabeltype = "";
                        if (currencyAtRight) {
                            var taxvalue = parseFloat(data.tax).toFixed(decimal_degits) + "" + currentCurrency;
                        } else {
                            var taxvalue = currentCurrency + "" + parseFloat(data.tax).toFixed(decimal_degits);
                        }
                    }
                    taxlabel = data.title;
                }
                total_tax_amount += parseFloat(tax);
            }
            total_price = parseFloat(total_price) + parseFloat(total_tax_amount);
        }
        if (intRegex.test(deliveryCharge) || floatRegex.test(deliveryCharge)) {
            deliveryCharge = parseFloat(deliveryCharge).toFixed(decimal_degits);
            total_price += parseFloat(deliveryCharge);
        }
        if (intRegex.test(tip_amount) || floatRegex.test(tip_amount)) {
            tip_amount = parseFloat(tip_amount).toFixed(decimal_degits);
            total_price += parseFloat(tip_amount);
            total_price = parseFloat(total_price).toFixed(decimal_degits);
        }
        if (intRegex.test(adminCommission) || floatRegex.test(adminCommission)) {
                if (adminCommissionType == "Percent") {
                     adminCommission = parseFloat(parseFloat(total_item_price * adminCommission) / 100).toFixed(decimal_degits);
                } else {
                     adminCommission = parseFloat(adminCommission).toFixed(decimal_degits);
                }
        }
        return {
            adminCommission: adminCommission,
            totalPrice: total_price
        };        
    }
    $("#is_active").click(function () {
        $("#storeTable .is_open").prop('checked', $(this).prop('checked'));
    });
    $("#deleteAll").click(function () {
        if ($('#storeTable .is_open:checked').length) {
            if (confirm("{{trans('lang.selected_delete_alert')}}")) {
                jQuery("#data-table_processing").show();
                $('#storeTable .is_open:checked').each(function () {
                    var dataId = $(this).attr('dataId');
                    var author = $(this).attr('author');
                    database.collection('users').doc(author).update({ 'vendorID': "" }).then(function (result) {
                        deleteDocumentWithImage('vendors', dataId, "photo", ['restaurantMenuPhotos', 'photos'])
                        .then(() => {
                            return deleteStoreData(dataId);
                        })
                        .then(() => {
                            window.location.reload();
                        })
                        .catch((error) => {
                            console.error('Error deleting document or store data:', error);
                        });
                    });
                });
            }
        } else {
            alert("{{trans('lang.select_delete_alert')}}");
        }
    });
    $(document.body).on('click', '.redirecttopage', function () {
        var url = $(this).attr('data-url');
        window.location.href = url;
    });
    $(document).on("click", "a[name='vendor-delete']", function (e) {
        var id = this.id;
        jQuery("#data-table_processing").show();
        var author = $(this).attr('author');
        if (confirm("{{trans('lang.selected_delete_alert')}}")) {
            deleteDocumentWithImage('vendors', id, "photo", ['restaurantMenuPhotos', 'photos'])
            .then(() => {
                return deleteStoreData(id);
            })
            .then(() => {
                window.location.reload();
            })
            .catch((error) => {
                console.error('Error deleting document with image or store data:', error);
            });
        }
    });
    async function deleteStoreData(storeId) {
        await database.collection('users').where('vendorID', '==', storeId).get().then(async function (userssanpshots) {
            if (userssanpshots.docs.length > 0) {
                var item_data = userssanpshots.docs[0].data();
                await database.collection('wallet').where('user_id', '==', item_data.id).get().then(async function (snapshotsItem) {
                    if (snapshotsItem.docs.length > 0) {
                        snapshotsItem.docs.forEach((temData) => {
                            var item_data = temData.data();
                            database.collection('wallet').doc(item_data.id).delete().then(function () { });
                        });
                    }
                });
                database.collection('settings').doc("Version").get().then(function (snapshot) {
                    var settingData = snapshot.data();
                    if (settingData && settingData.storeUrl){
                        var siteurl = settingData.storeUrl + "/api/delete-user"; 
                        var dataObject = { "uuid": item_data.id };         
                        jQuery.ajax({
                            url: siteurl, 
                            method: 'POST',
                            contentType: "application/json; charset=utf-8",
                            data: JSON.stringify(dataObject),
                            success: function (data) {
                                console.log('Delete user from sql success:', data);
                            },
                            error: function (error) {
                                console.log('Delete user from sql error:', error.responseJSON.message);
                            }
                        });
                    }
                });
                var projectId = '<?php echo env('FIREBASE_PROJECT_ID') ?>';
                var dataObject = { "data": { "uid": item_data.id } };
                jQuery.ajax({
                    url: 'https://us-central1-' + projectId + '.cloudfunctions.net/deleteUser',
                    method: 'POST',
                    contentType: "application/json; charset=utf-8",
                    data: JSON.stringify(dataObject),
                    success:async function (data) {
                        console.log('Delete user success:', data.result);
                        await deleteDocumentWithImage('users',item_data.id,'profilePictureURL');
                    },
                    error: function (xhr, status, error) {
                        var responseText = JSON.parse(xhr.responseText);
                        console.log('Delete user error:', responseText.error);
                    }
                });
            }
        });
        await database.collection('vendor_products').where('vendorID', '==', storeId).get().then(async function (snapshots) {
            if (snapshots.docs.length > 0) {
                for (const listval of snapshots.docs) {
                    await deleteDocumentWithImage('vendor_products', listval.id, 'photo', 'photos');
                }
            }
        });
        await database.collection('foods_review').where('VendorId', '==', storeId).get().then(async function (snapshotsItem) {
            if (snapshotsItem.docs.length > 0) {
                for (const temData of snapshotsItem.docs) {
                    await deleteDocumentWithImage('items_review', temData.id, '', 'photos');
                }
            }
        });
        await database.collection('coupons').where('vendorID', '==', storeId).get().then(async function (snapshotsItem) {
            if (snapshotsItem.docs.length > 0) {
                for (const temData of snapshotsItem.docs) {
                    await deleteDocumentWithImage('coupons', temData.id, 'image');
                }
            }
        });
        await database.collection('favorite_restaurant').where('restaurant_id', '==', storeId).get().then(async function (snapshotsItem) {
            if (snapshotsItem.docs.length > 0) {
                snapshotsItem.docs.forEach((temData) => {
                    var item_data = temData.data();
                    database.collection('favorite_restaurant').doc(item_data.restaurant_id).delete().then(function () {
                    });
                });
            }
        })
        await database.collection('payouts').where('vendorID', '==', storeId).get().then(async function (snapshotsItem) {
            if (snapshotsItem.docs.length > 0) {
                snapshotsItem.docs.forEach((temData) => {
                    var item_data = temData.data();
                    database.collection('payouts').doc(item_data.id).delete().then(function () {
                    });
                });
            }
        });
        await database.collection('booked_table').where('vendorID', '==', storeId).get().then(async function (snapshotsItem) {
            if (snapshotsItem.docs.length > 0) {
                snapshotsItem.docs.forEach((temData) => {
                    var item_data = temData.data();
                    database.collection('booked_table').doc(item_data.id).delete().then(function () {
                    });
                });
            }
        });
        await database.collection('story').where('vendorID', '==', storeId).get().then(async function (snapshotsItem) {
            if (snapshotsItem.docs.length > 0) {
                for (const temData of snapshotsItem.docs) {
                    await deleteDocumentWithImage('story', temData.id,'videoThumbnail','videoUrl');
                }
            }
        });
        await database.collection('favorite_item').where('store_id', '==', storeId).get().then(async function (snapshotsItem) {
            if (snapshotsItem.docs.length > 0) {
                snapshotsItem.docs.forEach((temData) => {
                    var item_data = temData.data();
                    database.collection('favorite_item').where('store_id', '==', storeId).delete().then(function () {
                    });
                });
            }
        })
    }
    $(document).on("click", "a[name='vendor-clone']", async function (e) {
        var id = $(this).attr('vendor_id');
        var author = $(this).attr('author');
        await database.collection('users').doc(author).get().then(async function (snapshotsusers) {
            userData = snapshotsusers.data();
        });
        await database.collection('vendors').doc(id).get().then(async function (snapshotsvendors) {
            vendorData = snapshotsvendors.data();
        });
        await database.collection('vendor_products').where('vendorID', '==', id).get().then(async function (snapshotsproducts) {
            vendorProducts = [];
            snapshotsproducts.docs.forEach(async (product) => {
                vendorProducts.push(product.data());
            });
        });
        if (userData && vendorData) {
            jQuery("#create_vendor").modal('show');
            jQuery("#vendor_title_lable").text(vendorData.title);
        }
    });
    $(document).on("click", ".save-form-btn", async function (e) {
        var vendor_id = database.collection("tmp").doc().id;
        if (userData && vendorData) {
            var vendor_title = jQuery("#vendor_title").val();
            var userFirstName = jQuery("#user_name").val();
            var userLastName = jQuery("#user_last_name").val();
            var email = jQuery("#user_email").val();
            var password = jQuery("#user_password").val();
            if (userFirstName == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.user_name_required')}}</p>");
                window.scrollTo(0, 0);
            } else if (userLastName == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.user_last_name_required')}}</p>");
                window.scrollTo(0, 0);
            } else if (vendor_title == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.vendor_title_required')}}</p>");
                window.scrollTo(0, 0);
            } else if (email == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.user_email_required')}}</p>");
                window.scrollTo(0, 0);
            } else if (password == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.enter_owners_password_error')}}</p>");
                window.scrollTo(0, 0);
            } else {
                jQuery("#data-table_processing2").show();
                firebase.auth().createUserWithEmailAndPassword(email, password).then(async function (firebaseUser) {
                    var user_id = firebaseUser.user.uid;
                    userData.email = email;
                    userData.firstName = userFirstName;
                    userData.lastName = userLastName;
                    userData.id = user_id;
                    userData.vendorID = vendor_id;
                    userData.createdAt = createdAt;
                    userData.wallet_amount = 0;
                    vendorData.author = user_id;
                    vendorData.authorName = userFirstName + ' ' + userLastName;
                    vendorData.title = vendor_title;
                    vendorData.id = vendor_id;
                    coordinates = new firebase.firestore.GeoPoint(vendorData.latitude, vendorData.longitude);
                    vendorData.coordinates = coordinates;
                    vendorData.createdAt = createdAt;
                    await database.collection('users').doc(user_id).set(userData).then(async function (result) {
                        await geoFirestore.collection('vendors').doc(vendor_id).set(vendorData).then(async function (result) {
                            var count = 0;
                            await vendorProducts.forEach(async (product) => {
                                var product_id = await database.collection("tmp").doc().id;
                                product.id = product_id;
                                product.vendorID = vendor_id;
                                await database.collection('vendor_products').doc(product_id).set(product).then(function (result) {
                                    count++;
                                    if (count == vendorProducts.length) {
                                        jQuery("#data-table_processing2").hide();
                                        alert('Successfully created.');
                                        jQuery("#create_vendor").modal('hide');
                                        location.reload();
                                    }
                                });
                            });
                        });
                    })
                }).catch(function (error) {
                    $(".error_top").show();
                    jQuery("#data-table_processing2").hide();
                    $(".error_top").html("");
                    $(".error_top").append("<p>" + error + "</p>");
                });
            }
        }
    });
</script>
@endsection
