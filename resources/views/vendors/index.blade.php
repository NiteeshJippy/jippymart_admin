@extends('layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">
                @if(request()->is('vendors/approved'))
                @php $type = 'approved'; @endphp
                {{trans('lang.approved_vendors')}}
                @elseif(request()->is('vendors/pending'))
                @php $type = 'pending'; @endphp
                {{trans('lang.approval_pending_vendors')}}
                @else
                @php $type = 'all'; @endphp
                {{trans('lang.all_vendors')}}
                @endif
            </h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.vendor_list')}}</li>
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
                            <span class="icon mr-3"><img src="{{ asset('images/vendor.png') }}"></span>
                            <h3 class="mb-0">{{trans('lang.vendor_list')}}</h3>
                            <span class="counter ml-3 vendor_count"></span>
                        </div>
                        <div class="d-flex top-title-right align-self-center">
                            <div class="select-box pl-3">
                                <select class="form-control status_selector filteredRecords">
                                    <option value="" selected>{{trans("lang.status")}}</option>
                                    <option value="active">{{trans("lang.active")}}</option>
                                    <option value="inactive">{{trans("lang.in_active")}}</option>
                                </select>
                            </div>
                            <div class="select-box pl-3" style="display:block !important;">
                                <select class="form-control zone_selector filteredRecords" style="display:block !important;">
                                    <option value="" selected>{{trans("lang.select_zone")}}</option>
                                </select>
                            </div>
                            <div class="select-box pl-3" style="display:none !important;">
                                <select class="form-control zone_sort_selector filteredRecords" style="display:block !important;">
                                    <option value="" selected>{{trans("lang.sort_by_zone")}}</option>
                                    <option value="asc">{{trans("lang.zone_asc")}}</option>
                                    <option value="desc">{{trans("lang.zone_desc")}}</option>
                                </select>
                            </div>
                            <div class="select-box pl-3">
                            <div id="daterange"><i class="fa fa-calendar"></i>&nbsp;
                                <span></span>&nbsp; <i class="fa fa-caret-down"></i>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row mb-4">
    <div class="col-12">
        <div class="card border">
            <div class="card-header d-flex justify-content-between align-items-center border-0">
                <div class="card-header-title">
                    <h3 class="text-dark-2 mb-2 h4">Bulk Import Vendors</h3>
                    <p class="mb-0 text-dark-2">Upload Excel file to import multiple vendors at once</p>
                </div>
                <div class="card-header-right d-flex align-items-center">
                    <div class="card-header-btn mr-3">
                        <a href="{{ route('vendors.download-template') }}" class="btn btn-outline-primary rounded-full">
                            <i class="mdi mdi-download mr-2"></i>Download Template
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('vendors.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="importFile" class="control-label">Select Excel File (.xls/.xlsx)</label>
                                <input type="file" name="file" id="importFile" accept=".xls,.xlsx" class="form-control" required>
                                <div class="form-text text-muted">
                                    <i class="mdi mdi-information-outline mr-1"></i>
                                    File should contain: firstName, lastName, email, password, active, profilePictureURL, zoneId, phoneNumber, createdAt
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary rounded-full">
                                <i class="mdi mdi-upload mr-2"></i>Import Vendors
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
       <div class="table-list">
            <div class="row">
                <div class="col-12">
                    <div class="card border">
                        <div class="card-header d-flex justify-content-between align-items-center border-0">
                            <div class="card-header-title">
                                <h3 class="text-dark-2 mb-2 h4">{{trans('lang.vendor_list')}}</h3>
                                <p class="mb-0 text-dark-2">{{trans('lang.vendors_table_text')}}</p>
                            </div>
                            <div class="card-header-right d-flex align-items-center">
                                <div class="card-header-btn mr-3">
                                    <a class="btn-primary btn rounded-full" href="{!! route('vendors.create') !!}"><i class="mdi mdi-plus mr-2"></i>{{trans('lang.create_vendor')}}</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive m-t-10">
                                <table id="userTable"
                                    class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                    cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <?php if (
                                                ($type == "approved" && in_array('approve.vendors.delete', json_decode(@session('user_permissions'), true))) ||
                                                ($type == "pending" && in_array('pending.vendors.delete', json_decode(@session('user_permissions'), true))) ||
                                                ($type == "all" && in_array('vendors.delete', json_decode(@session('user_permissions'), true)))
                                            ) { ?>
                                                <th class="delete-all"><input type="checkbox" id="is_active">
                                                    <label class="col-3 control-label" for="is_active">
                                                        <a id="deleteAll" class="do_not_delete" href="javascript:void(0)"><i
                                                                class="mdi mdi-delete"></i> {{trans('lang.all')}}</a>
                                                    </label>
                                                </th>
                                            <?php } ?>
                                            <th>{{trans('lang.vendor_info')}}</th>
                                            <th>{{trans('lang.email')}}</th>
                                            <th>{{trans('lang.zone')}}</th>
                                            <th>{{trans('lang.current_plan')}}</th>
                                            <th>{{trans('lang.expiry_date')}}</th>
                                            <th>{{trans('lang.date')}}</th>
                                            <th>{{trans('lang.document_plural')}}</th>
                                            <th>{{trans('lang.active')}}</th>
                                            <th>{{trans('lang.actions')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="append_list1">
                                    </tbody>
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
    var database=firebase.firestore();
    var type="{{$type}}";
    var user_permissions='<?php echo @session("user_permissions") ?>';
    user_permissions=Object.values(JSON.parse(user_permissions));
    var checkDeletePermission=false;
    if(
        (type=='pending'&&$.inArray('pending.vendors.delete',user_permissions)>=0)||
        (type=='approved'&&$.inArray('approve.vendors.delete',user_permissions)>=0)||
        (type=='all'&&$.inArray('vendors.delete',user_permissions)>=0)
    ) {
        checkDeletePermission=true;
    }
    var ref=database.collection('users').where("role","==","vendor");
    if(type=='pending') {
        ref=database.collection('users').where("role","==","vendor").where("active","==",false);
    } else if(type=='approved') {
        ref=database.collection('users').where("role","==","vendor").where("active","==",true);
    }
    var placeholderImage='';
    var append_list='';
    var initialRef=ref;
    var zones = [];
    var zoneIdToName = {};
    
    // Load zones
    database.collection('zone').where('publish','==',true).orderBy('name','asc').get().then(async function(snapshots) {
        console.log('Loading zones, found:', snapshots.docs.length);
        snapshots.docs.forEach((listval) => {
            var data = listval.data();
            zones.push(data);
            $('.zone_selector').append($("<option></option>")
                .attr("value", listval.id)
                .text(data.name));
        });
        // Build zoneId to name map
        zoneIdToName = {};
        snapshots.docs.forEach(function(doc) {
            var data = doc.data();
            zoneIdToName[doc.id] = data.name;  // Use document ID as key
        });
        window.zoneIdToName = zoneIdToName;
        console.log('Zones loaded:', zones);
        console.log('zoneIdToName map:', zoneIdToName);
        // ✅ Initialize DataTable only after zones are loaded
        initializeVendorDataTable();
    }).catch(function(error) {
        console.error('Error fetching zones:', error);
    });

    // Move DataTable initialization into a function
    function initializeVendorDataTable() {
        $(document.body).on('click','.redirecttopage',function() {
            var url=$(this).attr('data-url');
            window.location.href=url;
        });
        jQuery("#data-table_processing").show();
        var placeholder=database.collection('settings').doc('placeHolderImage');
        placeholder.get().then(async function(snapshotsimage) {
            var placeholderImageData=snapshotsimage.data();
            placeholderImage=placeholderImageData.image;
        })
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
                { key: 'fullName', header: "{{trans('lang.vendor_info')}}" },
                { key: 'email', header: "{{trans('lang.email')}}" },
                { key: 'activePlanName', header: "{{trans('lang.active_subscription_plan')}}" },
                { key: 'subscriptionExpiryDate', header: "{{trans('lang.plan_expire_at')}}" },
                { key: 'createdAt', header: "{{trans('lang.created_at')}}" },
            ],
            fileName: "{{trans('lang.vendor_list')}}",
        };
        const table=$('#userTable').DataTable({
            pageLength: 10, // Number of rows per page
            processing: false, // Show processing indicator
            serverSide: true, // Enable server-side processing
            responsive: true,
            ajax: function(data,callback,settings) {
                const start=data.start;
                const length=data.length;
                const searchValue=data.search.value.toLowerCase();
                const orderColumnIndex=data.order[0].column;
                const orderDirection=data.order[0].dir;
                const orderableColumns=(checkDeletePermission)? ['','fullName','email','zoneName','subscription_plan.name','subscriptionExpiryDate','createdAt','','','','']:['fullName','email','zoneName','subscription_plan.name','subscriptionExpiryDate','createdAt','','','','']; // Ensure this matches the actual column names
                const orderByField=orderableColumns[orderColumnIndex]; // Adjust the index to match your table
                if(searchValue.length>=3||searchValue.length===0) {
                    $('#data-table_processing').show();
                }
                // Fetch all vendors (users)
                ref.orderBy('createdAt','desc').get().then(async function(querySnapshot) {
                    if(querySnapshot.empty) {
                        $('.vendor_count').text(0);
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
                    let records = [];
                    let filteredRecords = [];
                    let vendorIds = [];
                    let vendorsData = [];
                    querySnapshot.forEach(function(doc) {
                        let childData = doc.data();
                        childData.id = doc.id;
                        childData.fullName = childData.firstName + ' ' + childData.lastName || " ";
                        vendorsData.push(childData);
                        vendorIds.push(doc.id);
                    });
                    // After collecting vendorIds
                    console.log('Vendor IDs:', vendorIds);
                    // Fetch all restaurants (vendors collection) in batches of 10
                    let vendorRestaurants = {};
                    if (vendorIds.length > 0) {
                        try {
                            // Split vendorIds into chunks of 10
                            function chunkArray(array, size) {
                                const result = [];
                                for (let i = 0; i < array.length; i += size) {
                                    result.push(array.slice(i, i + size));
                                }
                                return result;
                            }
                            const vendorIdChunks = chunkArray(vendorIds, 10);
                            for (const chunk of vendorIdChunks) {
                                // Use 'author' field instead of 'vendorId'
                                const restaurantSnapshot = await database.collection('vendors').where('author', 'in', chunk).get();
                                restaurantSnapshot.forEach(function(doc) {
                                    const restaurantData = doc.data();
                                    vendorRestaurants[restaurantData.author] = restaurantData;
                                });
                            }
                            // Debug: log fetched restaurants
                            console.log('Fetched vendorRestaurants:', vendorRestaurants);
                        } catch (error) {
                            console.error('Error fetching vendor restaurants:', error);
                        }
                    }
                    // Attach zoneId to each vendor
                    vendorsData.forEach(function(childData) {
                        if (vendorRestaurants[childData.id]) {
                            childData.zoneId = vendorRestaurants[childData.id].zoneId || '';
                        } else {
                            childData.zoneId = '';
                        }
                        // Debug: log zoneId mapping
                        console.log('Vendor:', childData.fullName, 'ID:', childData.id, 'zoneId:', childData.zoneId);
                    });
                    // Filtering and search
                    vendorsData.forEach(function(childData) {
                        // Apply zone filter
                        let includeInResults = true;
                        if (window.selectedZone && window.selectedZone !== '') {
                            includeInResults = childData.zoneId === window.selectedZone;
                        }
                        if (!includeInResults) {
                            return;
                        }
                        // Search logic
                        var date = '';
                        var time = '';
                        if(childData.hasOwnProperty("createdAt")) {
                            try {
                                date = childData.createdAt.toDate().toDateString();
                                time = childData.createdAt.toDate().toLocaleTimeString('en-US');
                            } catch(err) {}
                        }
                        var createdAt = date + ' ' + time;
                        if(searchValue) {
                            if(
                                (childData.fullName && childData.fullName.toLowerCase().toString().includes(searchValue)) ||
                                (createdAt && createdAt.toString().toLowerCase().indexOf(searchValue) > -1) ||
                                (childData.expiryDate && childData.expiryDate.toString().toLowerCase().indexOf(searchValue) > -1) ||
                                (childData.hasOwnProperty('activePlanName') && childData.activePlanName.toLowerCase().toString().includes(searchValue)) ||
                                (childData.email && childData.email.toLowerCase().toString().includes(searchValue)) ||
                                (childData.phoneNumber && childData.phoneNumber.toString().includes(searchValue)) ||
                                (window.zoneIdToName && childData.zoneId && window.zoneIdToName[childData.zoneId] && window.zoneIdToName[childData.zoneId].toLowerCase().toString().includes(searchValue))
                            ) {
                                filteredRecords.push(childData);
                            }
                        } else {
                            filteredRecords.push(childData);
                        }
                    });
                    // Sort by zone if requested
                    if (window.selectedZoneSort === 'asc') {
                        filteredRecords.sort((a, b) => {
                            let aZone = (window.zoneIdToName && a.zoneId && window.zoneIdToName[a.zoneId]) ? window.zoneIdToName[a.zoneId] : '';
                            let bZone = (window.zoneIdToName && b.zoneId && window.zoneIdToName[b.zoneId]) ? window.zoneIdToName[b.zoneId] : '';
                            return aZone.localeCompare(bZone);
                        });
                    } else if (window.selectedZoneSort === 'desc') {
                        filteredRecords.sort((a, b) => {
                            let aZone = (window.zoneIdToName && a.zoneId && window.zoneIdToName[a.zoneId]) ? window.zoneIdToName[a.zoneId] : '';
                            let bZone = (window.zoneIdToName && b.zoneId && window.zoneIdToName[b.zoneId]) ? window.zoneIdToName[b.zoneId] : '';
                            return bZone.localeCompare(aZone);
                        });
                    }
                    const totalRecords=filteredRecords.length;
                    $('.vendor_count').text(totalRecords);
                    const paginatedRecords=filteredRecords.slice(start,start+length);
                    await Promise.all(paginatedRecords.map(async (childData) => {
                        var getData=await buildHTML(childData);
                        records.push(getData);
                    }));
                    $('#data-table_processing').hide(); // Hide loader
                    callback({
                        draw: data.draw,
                        recordsTotal: totalRecords, // Total number of records in Firestore
                        recordsFiltered: totalRecords, // Number of records after filtering (if any)
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
            order: (checkDeletePermission)? [[6,'desc']]:[[5,'desc']],
            columnDefs: [
                {
                    targets: (checkDeletePermission)? 6:5,
                    type: 'date',
                    render: function(data) {
                        return data;
                    }
                },
                {orderable: false,targets: (checkDeletePermission)? [0,7,8,9]:[6,7,8]},
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
        function debounce(func,wait) {
            let timeout;
            const context=this;
            return function(...args) {
                clearTimeout(timeout);
                timeout=setTimeout(() => func.apply(context,args),wait);
            };
        }
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
    }
    $('.status_selector').select2({
        placeholder: '{{trans("lang.status")}}',  
        minimumResultsForSearch: Infinity,
        allowClear: true 
    });
    
    $('.zone_selector').select2({
        placeholder: '{{trans("lang.select_zone")}}',  
        minimumResultsForSearch: Infinity,
        allowClear: true 
    });
    $('.zone_sort_selector').select2({
        placeholder: '{{trans("lang.sort_by_zone")}}',  
        minimumResultsForSearch: Infinity,
        allowClear: true 
    });
    $('select').on("select2:unselecting", function(e) {
        var self = $(this);
        setTimeout(function() {
            self.select2('close');
        }, 0);
    });
    function setDate() {
        $('#daterange span').html('{{trans("lang.select_range")}}');
        $('#daterange').daterangepicker({
            autoUpdateInput: false, 
        }, function (start, end) {
            $('#daterange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $('.filteredRecords').trigger('change'); 
        });
        $('#daterange').on('apply.daterangepicker', function (ev, picker) {
            $('#daterange span').html(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
            $('.filteredRecords').trigger('change');
        });
        $('#daterange').on('cancel.daterangepicker', function (ev, picker) {
            $('#daterange span').html('{{trans("lang.select_range")}}');
            $('.filteredRecords').trigger('change'); 
        });
    }
    setDate();
    $('.filteredRecords').change(async function() {
        var status = $('.status_selector').val();
        var zone = $('.zone_selector').val();
        var zoneSort = $('.zone_sort_selector').val();
        var daterangepicker = $('#daterange').data('daterangepicker');
        var refData = initialRef;
        
        if(status) {
            refData = (status === "active")
                ? refData.where('active','==',true)
                : refData.where('active','==',false);
        }
        
        if ($('#daterange span').html() != '{{trans("lang.select_range")}}' && daterangepicker) {
            var from = moment(daterangepicker.startDate).toDate();
            var to = moment(daterangepicker.endDate).toDate();
            if (from && to) { 
                var fromDate = firebase.firestore.Timestamp.fromDate(new Date(from));
                refData = refData.where('createdAt', '>=', fromDate);
                var toDate = firebase.firestore.Timestamp.fromDate(new Date(to));
                refData = refData.where('createdAt', '<=', toDate);
            }
        }
        
        // Store zone filter for use in ajax function
        window.selectedZone = zone;
        window.selectedZoneSort = zoneSort;
        ref = refData;
        $('#userTable').DataTable().ajax.reload();
    });
    async function buildHTML(listval) {
        var html=[];
        var val=listval;
        newdate='';
        var id=val.id;
        var route1='';
        var route1='{{route("vendor.edit", ":id")}}';
        route1=route1.replace(':id',id);
        var checkIsRestaurant=getUserRestaurantInfo(id);
        var trroute1='{{route("users.walletstransaction", ":id")}}';
        trroute1=trroute1.replace(':id',id);
        if(checkDeletePermission) {
            html.push('<input type="checkbox" id="is_open_'+id+'" class="is_open" dataId="'+id+'" data-vendorid="'+val.vendorID+'"><label class="col-3 control-label" for="is_open_'+id+'"></label>');
        }
        if(val.profilePictureURL==''&&val.profilePictureURL==null) {
            imageHtml='<img width="100%" style="width:70px;height:70px;" src="'+placeholderImage+'" alt="image">';
        } else {
           imageHtml='<img onerror="this.onerror=null;this.src=\''+placeholderImage+'\'" class="rounded" width="100%" style="width:70px;height:70px;" src="'+val.profilePictureURL+'" alt="image">';
        }
        if((val.firstName!=""&&val.firstName!=null)||(val.lastName!=""&&val.lastName!=null)) {
            html.push(imageHtml+'<a  href="'+route1+'">'+val.firstName+' '+val.lastName+'</a>');
        }
        else {
            html.push('');
        }
        if(val.email!=""&&val.email!=null) {
            html.push(val.email);
        }
        else {
            html.push("");
        }
        // Use zoneIdToName map to display zone name
        if(val.hasOwnProperty('zoneId') && val.zoneId && window.zoneIdToName && window.zoneIdToName[val.zoneId]) {
            html.push(window.zoneIdToName[val.zoneId]);
        } else {
            html.push('<span class="text-muted">No Zone</span>');
        }
        if(val.hasOwnProperty('subscription_plan') && val.subscription_plan && val.subscription_plan.name) {
            html.push(val.subscription_plan.name);
        } else {
            html.push('');
        }
        if(val.hasOwnProperty('subscriptionExpiryDate')) {
            html.push(val.expiryDate);
        } else {
            html.push('');
        }
        var date='';
        var time='';
        if(val.hasOwnProperty("createdAt")) {
            try {
                date=val.createdAt.toDate().toDateString();
                time=val.createdAt.toDate().toLocaleTimeString('en-US');
            } catch(err) {
            }
            html.push('<span class="dt-time">'+date+' '+time+'</span>');
        } else {
            html.push('');
        }
        document_list_view="{{route('vendors.document', ':id')}}";
        document_list_view=document_list_view.replace(':id',val.id);
        html.push('<a href="'+document_list_view+'"><i class="fa fa-file"></i></a>');
        if(val.active) {
            html.push('<label class="switch"><input type="checkbox" checked id="'+val.id+'" name="isActive"><span class="slider round"></span></label>');
        } else {
            html.push('<label class="switch"><input type="checkbox" id="'+val.id+'" name="isActive"><span class="slider round"></span></label>');
        }
        var action='<span class="action-btn">';
        var planRoute="{{route('vendor.subscriptionPlanHistory',':id')}}";
        planRoute=planRoute.replace(':id',val.id);
        if(val.hasOwnProperty('subscription_plan')) {
            action+='<a id="'+val.id+'"  href="'+planRoute+'"><i class="mdi mdi-crown"></i></a>';
        }
        action+='<a id="'+val.id+'"  href="'+route1+'"><i class="mdi mdi-lead-pencil"></i></a>';
        if(checkDeletePermission) {
            action=action+'<a id="'+val.id+'" data-vendorid="'+val.vendorID+'" class="delete-btn" name="vendor-delete" href="javascript:void(0)"><i class="mdi mdi-delete"></i></a>';
        }
        action=action+'</span>';
        html.push(action);
        // Debug: log the number of columns for each row
        console.log('buildHTML columns:', html.length, html);
        return html;
    }
    async function getUserRestaurantInfo(userId) {
        await database.collection('vendors').where('author','==',userId).get().then(async function(restaurantSnapshots) {
            if(restaurantSnapshots.docs.length>0) {
                var restaurantId=restaurantSnapshots.docs[0].data();
                restaurantId=restaurantId.id;
                var restaurantView='{{route("restaurants.edit", ":id")}}';
                restaurantView=restaurantView.replace(':id',restaurantId);
                $('#userName_'+userId).attr('data-url',restaurantView);
            }
        });
    }
    $("#is_active").click(function() {
        $("#userTable .is_open").prop('checked',$(this).prop('checked'));
    });
    $("#deleteAll").click(function() {
        if($('#userTable .is_open:checked').length) {
            if(confirm("{{trans('lang.selected_delete_alert')}}")) {
                jQuery("#data-table_processing").show();
                $('#userTable .is_open:checked').each(function() {
                    var dataId=$(this).attr('dataId');
                    var VendorId=$(this).attr('data-vendorid');
                    deleteDocumentWithImage('users',dataId,'profilePictureURL')
                        .then(() => {
                            return deleteUserData(dataId,VendorId);
                        })
                        .then(() => {
                            setTimeout(function() {
                                window.location.reload();
                            },7000);
                        })
                        .catch((error) => {
                            console.error('Error deleting document or store data:',error);
                        });
                });
            }
        } else {
            alert("{{trans('lang.select_delete_alert')}}");
        }
    });
    async function deleteStoreData(VendorId) {
        await database.collection('vendor_products').where('vendorID','==',VendorId).get().then(async function(snapshots) {
            if(snapshots.docs.length>0) {
                for(const temData of snapshots.docs) {
                    var item_data=temData.data();
                    await deleteDocumentWithImage('vendor_products',item_data.id,'photo','photos');
                }
            }
        });
        await database.collection('foods_review').where('VendorId','==',VendorId).get().then(async function(snapshotsItem) {
            if(snapshotsItem.docs.length>0) {
                for(const temData of snapshotsItem.docs) {
                    var item_data=temData.data();
                    await deleteDocumentWithImage('foods_review',item_data.id,'profile','photos');
                }
            }
        });
        await database.collection('coupons').where('resturant_id','in',[VendorId,'ALL']).get().then(async function(snapshotsItem) {
            if(snapshotsItem.docs.length>0) {
                for(const temData of snapshotsItem.docs) {
                    var item_data=temData.data();
                    await deleteDocumentWithImage('coupons',item_data.id,'image');
                }
            }
        });
        await database.collection('favorite_restaurant').where('restaurant_id','==',VendorId).get().then(async function(snapshotsItem) {
            if(snapshotsItem.docs.length>0) {
                snapshotsItem.docs.forEach((temData) => {
                    var item_data=temData.data();
                    database.collection('favorite_restaurant').doc(item_data.id).delete().then(function() {
                    });
                });
            }
        })
        await database.collection('favorite_item').where('store_id','==',VendorId).get().then(async function(snapshotsItem) {
            if(snapshotsItem.docs.length>0) {
                snapshotsItem.docs.forEach((temData) => {
                    var item_data=temData.data();
                    database.collection('favorite_item').doc(item_data.id).delete().then(function() {
                    });
                });
            }
        })
        await database.collection('payouts').where('vendorID','==',VendorId).get().then(async function(snapshotsItem) {
            if(snapshotsItem.docs.length>0) {
                snapshotsItem.docs.forEach((temData) => {
                    var item_data=temData.data();
                    database.collection('payouts').doc(item_data.id).delete().then(function() {
                    });
                });
            }
        });
        await database.collection('booked_table').where('vendorID','==',VendorId).get().then(async function(snapshotsItem) {
            if(snapshotsItem.docs.length>0) {
                snapshotsItem.docs.forEach((temData) => {
                    var item_data=temData.data();
                    database.collection('booked_table').doc(item_data.id).delete().then(function() {
                    });
                });
            }
        });
        await database.collection('story').where('vendorID','==',VendorId).get().then(async function(snapshotsItem) {
            if(snapshotsItem.docs.length>0) {
                for(const temData of snapshotsItem.docs) {
                    var item_data=temData.data();
                    await deleteDocumentWithImage('story',item_data.vendorID,'videoThumbnail','videoUrl');
                }
            }
        });
    }
    async function deleteUserData(userId,VendorId) {
        await database.collection('wallet').where('user_id','==',userId).get().then(async function(snapshotsItem) {
            if(snapshotsItem.docs.length>0) {
                snapshotsItem.docs.forEach((temData) => {
                    var item_data=temData.data();
                    database.collection('wallet').doc(item_data.id).delete().then(function() {
                    });
                });
            }
        });
        //delete user from mysql
        database.collection('settings').doc("Version").get().then(function(snapshot) {
            var settingData=snapshot.data();
            if(settingData&&settingData.storeUrl) {
                var siteurl=settingData.storeUrl+"/api/delete-user";
                var dataObject={"uuid": userId};
                jQuery.ajax({
                    url: siteurl,
                    method: 'POST',
                    contentType: "application/json; charset=utf-8",
                    data: JSON.stringify(dataObject),
                    success: function(data) {
                        console.log('Delete user from sql success:',data);
                    },
                    error: function(error) {
                        console.log('Delete user from sql error:',error.responseJSON.message);
                    }
                });
            }
        });
        //delete user from authentication
        var dataObject={"data": {"uid": userId}};
        var projectId='<?php echo env('FIREBASE_PROJECT_ID') ?>';
        jQuery.ajax({
            url: 'https://us-central1-'+projectId+'.cloudfunctions.net/deleteUser',
            method: 'POST',
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(dataObject),
            success: function(data) {
                console.log('Delete user success:',data.result);
                database.collection('users').doc(userId).delete().then(function() {
                });
            },
            error: function(xhr,status,error) {
                var responseText=JSON.parse(xhr.responseText);
                console.log('Delete user error:',responseText.error);
            }
        });
        await deleteDocumentWithImage('vendors',VendorId,['authorProfilePic','photo'],['photos','menuPhotos','restaurantMenuPhotos']);
        const getStoreName=deleteStoreData(VendorId);
        setTimeout(function() {
            window.location.reload();
        },7000);
    }
    $(document).on("click","a[name='vendor-delete']",function(e) {
        var id=this.id;
        var VendorId=$(this).attr('data-vendorid');
        deleteDocumentWithImage('users',VendorId,'profilePictureURL')
            .then(() => {
                return deleteUserData(id,VendorId);
            })
            .then(() => {
                setTimeout(function() {
                    window.location.reload();
                },7000);
            })
            .catch((error) => {
                console.error('Error deleting document or store data:',error);
            });
    });
    $(document).on("click","input[name='isActive']",function(e) {
        var ischeck=$(this).is(':checked');
        var id=this.id;
        if(ischeck) {
            database.collection('users').doc(id).update({'active': true}).then(function(result) {
            });
        } else {
            database.collection('users').doc(id).update({'active': false}).then(function(result) {
            });
        }
    });
</script>
@endsection