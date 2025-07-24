@extends('layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.order_plural')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.order_table')}}</li>
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
                            <span class="icon mr-3"><img src="{{ asset('images/order.png') }}"></span>
                            <h3 class="mb-0">{{trans('lang.order_plural')}}</h3>
                            <span class="counter ml-3 order_count"></span>
                        </div>
                        <div class="d-flex top-title-right align-self-center">
                            <div class="d-flex top-title-right align-self-center">
                                <div class="select-box pl-3">
                                    <select class="form-control zone_selector filteredRecords">
                                        <option value="" selected>{{trans("lang.select_zone")}}</option>
                                    </select>
                                </div>
                                <div class="select-box pl-3">
                                    <select class="form-control status_selector filteredRecords">
                                        <option value="" selected>{{trans("lang.status")}}</option>
                                        <option value="Order Placed">{{trans("lang.order_placed")}}</option>
                                        <option value="Order Accepted">{{trans("lang.order_accepted")}}</option>
                                        <option value="Order Rejected">{{trans("lang.order_rejected")}}</option>
                                        <option value="Driver Pending">{{trans("lang.driver_pending")}}</option>
                                        <option value="Driver Rejected">{{trans("lang.driver_rejected")}}</option>
                                        <option value="Order Shipped">{{trans("lang.order_shipped")}}</option>
                                        <option value="In Transit">{{trans("lang.in_transit")}}</option>
                                        <option value="Order Completed">{{trans("lang.order_completed")}}</option>
                                    </select>
                                </div>
                                <div class="select-box pl-3">
                                    <select class="form-control order_type_selector filteredRecords">
                                        <option value="" selected>{{trans("lang.order_type")}}</option>
                                        <option value="takeaway">{{trans("lang.order_takeaway")}}</option>
                                        <option value="delivery">{{trans("lang.delivery")}}</option>
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
        </div>
        <div class="table-list">
            <div class="row">
                <div class="col-12">
                    <div class="menu-tab d-none vendorMenuTab">
                        <ul>
                            <li>
                                <a href="{{route('restaurants.view', $id)}}">{{trans('lang.tab_basic')}}</a>
                            </li>
                            <li>
                                <a href="{{route('restaurants.foods', $id)}}">{{trans('lang.tab_foods')}}</a>
                            </li>
                            <li class="active">
                                <a href="{{route('restaurants.orders', $id)}}">{{trans('lang.tab_orders')}}</a>
                            </li>
                            <li>
                                <a href="{{route('restaurants.coupons', $id)}}">{{trans('lang.tab_promos')}}</a>
                            </li>
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
                    @if(request()->has('driverId'))
                        <div class="menu-tab d-none driverMenuTab">
                            <ul>
                                <li>
                                    <a
                                        href="{{route('drivers.view', request()->query('driverId'))}}">{{trans('lang.tab_basic')}}</a>
                                </li>
                                <li class="active">
                                    <a
                                        href="{{route('orders')}}?driverId={{request()->query('driverId')}}">{{trans('lang.tab_orders')}}</a>
                                </li>
                                <li>
                                    <a
                                        href="{{route('driver.payout', request()->query('driverId'))}}">{{trans('lang.tab_payouts')}}</a>
                                </li>
                                <li>
                                    <a
                                        href="{{route('payoutRequests.drivers.view', request()->query('driverId'))}}">{{trans('lang.tab_payout_request')}}</a>
                                </li>
                                <li>
                                    <a
                                        href="{{route('users.walletstransaction', request()->query('driverId'))}}">{{trans('lang.wallet_transaction')}}</a>
                                </li>
                            </ul>
                        </div>
                    @endif
                    @if(request()->has('userId'))
                        <div class="menu-tab d-none userMenuTab">
                            <ul>
                                <li>
                                    <a
                                        href="{{ route('users.view', request()->query('userId')) }}">{{ trans('lang.tab_basic') }}</a>
                                </li>
                                <li class="active">
                                    <a
                                        href="{{route('orders', 'userId='.request()->query('userId'))}}">{{trans('lang.tab_orders')}}</a>
                                </li>
                                <li>
                                    <a
                                        href="{{route('users.walletstransaction', request()->query('userId'))}}">{{trans('lang.wallet_transaction')}}</a>
                                </li>
                            </ul>
                        </div>
                    @endif
                    <div class="card border">
                        <div class="card-header d-flex justify-content-between align-items-center border-0">
                            <div class="card-header-title">
                                <h3 class="text-dark-2 mb-2 h4">{{trans('lang.order_table')}}</h3>
                                <p class="mb-0 text-dark-2">{{trans('lang.order_table_text')}}</p>
                            </div>
                            <div class="card-header-right d-flex align-items-center">
                                <div class="card-header-btn mr-3">
                                    <!-- <a class="btn-primary btn rounded-full" href="{!! route('users.create') !!}"><i class="mdi mdi-plus mr-2"></i>{{trans('lang.user_create')}}</a> -->
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive m-t-10">
                                <table id="orderTable"
                                    class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                    cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <?php if (in_array('orders.delete', json_decode(@session('user_permissions'), true))) { ?>
                                            <th class="delete-all"><input type="checkbox" id="is_active"><label
                                                    class="col-3 control-label" for="is_active">
                                                    <a id="deleteAll" class="do_not_delete" href="javascript:void(0)">
                                                        <i class="mdi mdi-delete"></i> {{trans('lang.all')}}</a></label>
                                            </th>
                                            <?php } ?>
                                            <th>{{trans('lang.order_id')}}</th>
                                            @if ($id == '')
                                                <th>{{trans('lang.restaurant')}}</th>
                                            @endif
                                            @if (isset($_GET['userId']))
                                                <th class="driverClass">{{trans('lang.driver_plural')}}</th>
                                            @elseif (isset($_GET['driverId']))
                                                <th>{{trans('lang.order_user_id')}}</th>
                                            @else
                                                <th class="driverClass">{{trans('lang.driver_plural')}}</th>
                                                <th>{{trans('lang.order_user_id')}}</th>
                                            @endif
                                            <th>{{trans('lang.date')}}</th>
                                            <th>{{trans('lang.restaurants_payout_amount')}}</th>
                                            <th>{{trans('lang.order_type')}}</th>
                                            <th>{{trans('lang.order_order_status_id')}}</th>
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
    var vendor_id='<?php echo $id; ?>';
    var append_list='';
    var redData=ref;
    var currentCurrency='';
    var currencyAtRight=false;
    var decimal_degits=0;
    var refCurrency=database.collection('currencies').where('isActive','==',true);
    refCurrency.get().then(async function(snapshots) {
        var currencyData=snapshots.docs[0].data();
        currentCurrency=currencyData.symbol;
        currencyAtRight=currencyData.symbolAtRight;
        if(currencyData.decimal_degits) {
            decimal_degits=currencyData.decimal_degits;
        }
    });
    var user_permissions='<?php echo @session("user_permissions") ?>';
    user_permissions=Object.values(JSON.parse(user_permissions));
    var checkDeletePermission=false;
    if($.inArray('orders.delete',user_permissions)>=0) {
        checkDeletePermission=true;
    }
    var order_status=jQuery('#order_status').val();
    var search=jQuery("#search").val();
    var refData=database.collection('restaurant_orders');
    var ref='';
    $(document.body).on('change','#order_status',function() {
        order_status=jQuery(this).val();
    });
    $(document.body).on('keyup','#search',function() {
        search=jQuery(this).val();
    });
    var getId='<?php echo $id; ?>';
    var userID='<?php if (isset($_GET['userId'])) {
    echo $_GET['userId'];
} else {
    echo '';
} ?>';
    var driverID='<?php if (isset($_GET['driverId'])) {
    echo $_GET['driverId'];
} else {
    echo '';
} ?>';
    var orderStatus='<?php if (isset($_GET['status'])) {
    echo $_GET['status'];
} else {
    echo '';
} ?>';
    if(userID) {
        const getUserName=getUserNameFunction(userID);
        $('.userMenuTab').removeClass('d-none');
        if((order_status=='All'||order_status!='')&&search!='') {
            ref=refData.where('authorID','==',userID);
        } else {
            ref=refData.orderBy('createdAt','desc').where('authorID','==',userID);
        }
    } else if(driverID) {
        const getUserName=getUserNameFunction(driverID);
        $('.driverMenuTab').removeClass('d-none');
        if((order_status=='All'||order_status!='')&&search!='') {
            ref=refData.where('driverID','==',driverID);
        } else {
            ref=refData.orderBy('createdAt','desc').where('driverID','==',driverID);
        }
    } else if(orderStatus) {
        if(orderStatus=='order-placed') {
            ref=refData.orderBy('createdAt','desc').where('status','==','Order Placed');
        }
        else if(orderStatus=='order-confirmed') {
            ref=refData.orderBy('createdAt','desc').where('status','in',['Order Accepted','Driver Accepted']);
        }
        else if(orderStatus=='order-shipped') {
            ref=refData.orderBy('createdAt','desc').where('status','in',['Order Shipped','In Transit']);
        }
        else if(orderStatus=='order-completed') {
            ref=refData.orderBy('createdAt','desc').where('status','==','Order Completed');
        }
        else if(orderStatus=='order-canceled') {
            ref=refData.orderBy('createdAt','desc').where('status','==','Order Rejected');
        }
        else if(orderStatus=='order-failed') {
            ref=refData.orderBy('createdAt','desc').where('status','==','Driver Rejected');
        }
        else if(orderStatus=='order-pending') {
            ref=refData.orderBy('createdAt','desc').where('status','==','Driver Pending');
        } else {
            ref=refData.orderBy('createdAt','desc');
        }
    }
    else if(getId!='') {
        $('.vendorMenuTab').removeClass('d-none');
        database.collection('vendors').where("id","==",getId).get().then(async function(snapshots) {
            var vendorData=snapshots.docs[0].data();
            walletRoute="{{route('users.walletstransaction', ':id')}}";
            walletRoute=walletRoute.replace(":id",vendorData.author);
            $('#restaurant_wallet').append('<a href="'+walletRoute+'">{{trans("lang.wallet_transaction")}}</a>');
            $('#subscription_plan').append('<a href="'+"{{route('vendor.subscriptionPlanHistory', ':id')}}".replace(':id',vendorData.author)+'">'+'{{trans('lang.subscription_history')}}'+'</a>');
        });
        const getStoreName=getStoreNameFunction(getId);
        if((order_status=='All'||order_status!='')&&search!='') {
            ref=refData.where('vendorID','==',getId);
        } else {
            ref=refData.orderBy('createdAt','desc').where('vendorID','==',getId);
        }
    } else {
        if((order_status=='All'||order_status!='')&&search!='') {
            ref=refData;
        } else {
            ref=refData.orderBy('createdAt','desc');
        }
    }
    database.collection('zone').where('publish','==',true).orderBy('name','asc').get().then(async function(snapshots) {
        snapshots.docs.forEach((listval) => {
            var data=listval.data();
            $('.zone_selector').append($("<option></option>")
                .attr("value",data.id)
                .text(data.name));
        })
    });
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
    $('.order_type_selector').select2({
        placeholder: '{{trans("lang.order_type")}}',  
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
    var initialRef=ref;
    $('.filteredRecords').change(async function() {
        var status=$('.status_selector').val();
        var zoneValue=$('.zone_selector').val();
        var orderType=$('.order_type_selector').val();
        var daterangepicker = $('#daterange').data('daterangepicker');
        var refData=initialRef;
        if(zoneValue) {
            refData=refData.where('zoneId','==',zoneValue);
        }
        if(status) {
            refData=refData.where('status','==',status);
        }
        if(orderType) {
            refData=(orderType=='takeaway')? refData.where('takeAway','==',true):refData.where('takeAway','==',false);
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
        ref=refData;
        $('#orderTable').DataTable().ajax.reload();
    });
    $(document).ready(function() {
        jQuery('#search').hide();
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
                { key: 'id', header: "{{trans('lang.order_id')}}" },
                { key: 'driverName', header: "{{trans('lang.driver_plural')}}" },
                { key: 'client', header: "{{trans('lang.order_user_id')}}" },
                { key: 'status', header: "{{trans('lang.order_order_status_id')}}" },
                { key: 'orderType', header: "{{trans('lang.order_type')}}" },
                { key: 'amount', header: "{{trans('lang.amount')}}" },
                { key: 'createdAt', header: "{{trans('lang.created_at')}}" },
            ],
            fileName: "{{trans('lang.order_table')}}",
        };
        const table=$('#orderTable').DataTable({
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
                if(getId!='') {
                    var orderableColumns=(checkDeletePermission)? ['','id','driverName','client','createdAt','amount','orderType','status','']:['id','drivers','client','createdAt','amount','orderType','status','']; // Ensure this matches the actual column names
                } else if(driverID) {
                    var orderableColumns=(checkDeletePermission)? ['','id','restaurants','client','createdAt','amount','orderType','status','']:['id','restaurants','client','createdAt','amount','orderType','status','']; // Ensure this matches the actual column names
                } else if(userID) {
                    var orderableColumns=(checkDeletePermission)? ['','id','restaurants','driverName','createdAt','amount','orderType','status','']:['id','restaurants','driver','createdAt','amount','orderType','status','']; // Ensure this matches the actual column names
                } else {
                    var orderableColumns=(checkDeletePermission)? ['','id','restaurants','driverName','client','createdAt','amount','orderType','status','']:['id','restaurants','driver','client','createdAt','amount','orderType','status','']; // Ensure this matches the actual column names
                }
                const orderByField=orderableColumns[orderColumnIndex]; // Adjust the index to match your table
                if(searchValue.length>=3||searchValue.length===0) {
                    $('#data-table_processing').show();
                }
                await ref.get().then(async function(querySnapshot) {
                    if(querySnapshot.empty) {
                        $('.order_count').text(0);
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
                    let records=[];
                    let filteredRecords=[];
                    await Promise.all(querySnapshot.docs.map(async (doc) => {
                        let childData=doc.data();
                        childData.restaurants=childData.vendor.title;
                        childData.driverName='';
                        if(childData.hasOwnProperty('driver')&&childData.driver!=null&&childData.driver!='') {
                            childData.driverName=childData.driver.firstName+' '+childData.driver.lastName;
                        }
                        childData.client=childData.author.firstName+' '+childData.author.lastName
                        if(childData.hasOwnProperty('takeAway')&&childData.takeAway) {
                            childData.orderType="{{trans('lang.order_takeaway')}}"
                        } else {
                            childData.orderType="{{trans('lang.order_delivery')}}";
                        }
                        childData.amount=await buildHTMLProductstotal(childData);
                        childData.id=doc.id; // Ensure the document ID is included in the data              
                        if(searchValue) {
                            var date='';
                            var time='';
                            if(childData.hasOwnProperty("createdAt")) {
                                try {
                                    date=childData.createdAt.toDate().toDateString();
                                    time=childData.createdAt.toDate().toLocaleTimeString('en-US');
                                } catch(err) {
                                }
                            }
                            var createdAt=date+' '+time;
                            if(
                                (childData.id&&childData.id.toLowerCase().toString().includes(searchValue))||
                                (childData.restaurants&&childData.restaurants.toLowerCase().toString().includes(searchValue))||
                                (createdAt&&createdAt.toString().toLowerCase().indexOf(searchValue)>-1)||
                                (childData.driverName&&childData.driverName.toLowerCase().toString().includes(searchValue))||
                                (childData.client&&childData.client.toLowerCase().toString().includes(searchValue))||
                                (childData.orderType&&childData.orderType.toLowerCase().toString().includes(searchValue))||
                                (childData.status&&childData.status.toLowerCase().toString().includes(searchValue))||
                                (childData.amount&&childData.amount.toString().includes(searchValue))
                            ) {
                                filteredRecords.push(childData);
                            }
                        } else {
                            filteredRecords.push(childData);
                        }
                    }));
                    filteredRecords.sort((a,b) => {
                        let aValue=a[orderByField]? a[orderByField].toString().toLowerCase():'';
                        let bValue=b[orderByField]? b[orderByField].toString().toLowerCase():'';
                        if(orderByField==='createdAt') {
                            try {
                                aValue=a[orderByField]? new Date(a[orderByField].toDate()).getTime():0;
                                bValue=b[orderByField]? new Date(b[orderByField].toDate()).getTime():0;
                            } catch(err) {
                            }
                        }
                        if(orderByField==='amount') {
                            aValue=a[orderByField].slice(1)? parseInt(a[orderByField].slice(1)):0;
                            bValue=b[orderByField].slice(1)? parseInt(b[orderByField].slice(1)):0;
                        }
                        if(orderDirection==='asc') {
                            return (aValue>bValue)? 1:-1;
                        } else {
                            return (aValue<bValue)? 1:-1;
                        }
                    });
                    const totalRecords=filteredRecords.length;
                    $('.order_count').text(totalRecords);
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
            order: (getId!=''||driverID||userID&&checkDeletePermission)? [[4,'desc']]:(getId!=''||driverID||userID)? ((checkDeletePermission)? [[4,'desc']]:[[3,'desc']]):((checkDeletePermission)? [[5,'desc']]:[[4,'desc']]),
            columnDefs: [
                {
                    targets: (getId!=''||driverID||userID&&checkDeletePermission)? 4:(getId!=''||driverID||userID)? ((checkDeletePermission)? 4:3):((checkDeletePermission)? 5:4),
                    type: 'date',
                    render: function(data) {
                        return data;
                    }
                },
                {orderable: false,targets: (getId!=''||driverID||userID&&checkDeletePermission)? [0,8]:(getId!=''||driverID||userID)? ((checkDeletePermission)? [0,8]:[7]):(checkDeletePermission)? [0,9]:[8]},
            ],
            "language": {
                "zeroRecords": "{{trans("lang.no_record_found")}}",
                "emptyTable": "{{trans("lang.no_record_found")}}",
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
    });
    async function buildHTML(val) {
        var html=[];
        newdate='';
        var id=val.id;
        var vendorID=val.vendorID;
        var user_id=val.authorID;
        var route1='{{route("orders.edit", ":id")}}';
        route1=route1.replace(':id',id);
        var printRoute='{{route("vendors.orderprint", ":id")}}';
        printRoute=printRoute.replace(':id',id);
        <?php if ($id != '') { ?>
        route1=route1+'?eid={{$id}}';
        printRoute=printRoute+'?eid={{$id}}';
        <?php } ?>
        var route_view='{{route("restaurants.view", ":id")}}';
        route_view=route_view.replace(':id',vendorID);
        var customer_view='{{route("users.view", ":id")}}';
        customer_view=customer_view.replace(':id',user_id);
        if(checkDeletePermission) {
            html.push('<td class="delete-all"><input type="checkbox" id="is_open_'+id+'" class="is_open" dataId="'+id+'"><label class="col-3 control-label"\n'+
                'for="is_open_'+id+'" ></label></td>');
        }
        html.push('<a href="'+route1+'" class="redirecttopage">'+val.id+'</a>');
        if(userID) {
            var title='';
            if(val.hasOwnProperty('vendor')&&val.vendor.title!=undefined) {
                title=val.vendor.title;
            }
            html.push('<a  href="'+route_view+'" >'+title+'</a>');
            if(val.hasOwnProperty("driver")&&val.driver!=null&&val.driver!='') {
                var driverId=val.driver.id;
                var diverRoute='{{route("drivers.view", ":id")}}';
                diverRoute=diverRoute.replace(':id',driverId);
                html.push('<a href="'+diverRoute+'" >'+val.driver.firstName+' '+val.driver.lastName+'</a>');
            } else {
                html.push('');
            }
        } else if(driverID) {
            if(val.hasOwnProperty("author")&&val.author!=null&&val.author!='') {
                var driverId=val.author.id;
                html.push('<a  href="'+customer_view+'" >'+val.author.firstName+' '+val.author.lastName+'</a>');
            } else {
                html.push('');
            }
            var title='';
            if(val.hasOwnProperty('vendor')&&val.vendor.title!=undefined) {
                title=val.vendor.title;
            }
            html.push('<a  href="'+route_view+'" >'+title+'</a>');
        } else if(getId!='') {
            if(val.hasOwnProperty("driver")&&val.driver!=null&&val.driver!='') {
                var driverId=val.driver.id;
                var diverRoute='{{route("drivers.view", ":id")}}';
                diverRoute=diverRoute.replace(':id',driverId);
                html.push('<a  href="'+diverRoute+'" >'+val.driver.firstName+' '+val.driver.lastName+'</a>');
            } else {
                html.push('');
            }
            if(val.hasOwnProperty("author")&&val.author!=null&&val.author!='') {
                var driverId=val.author.id;
                html.push('<a  href="'+customer_view+'">'+val.author.firstName+' '+val.author.lastName+'</a>');
            } else {
                html.push('');
            }
        } else {
            var title='';
            if(val.hasOwnProperty('vendor')&&val.vendor.title!=undefined&&val.vendor.title!='') {
                title=val.vendor.title;
            }
            html.push('<a  href="'+route_view+'" >'+title+'</a>');
            if(val.hasOwnProperty("driver")&&val.driver!=null&&val.driver!='') {
                var driverId=val.driver.id;
                var diverRoute='{{route("drivers.view", ":id")}}';
                diverRoute=diverRoute.replace(':id',driverId);
                html.push('<a  href="'+diverRoute+'">'+val.driver.firstName+' '+val.driver.lastName+'</a>');
            } else {
                html.push('');
            }
            if(val.hasOwnProperty("author")&&val.author!=null) {
                var driverId=val.author.id;
                html.push('<a  href="'+customer_view+'" class="redirecttopage">'+val.author.firstName+' '+val.author.lastName+'</a>');
            } else {
                html.push('');
            }
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
        html.push('<span class="text-green">'+val.amount+'</span>');
        if(val.hasOwnProperty('takeAway')&&val.takeAway) {
            html.push('{{trans("lang.order_takeaway")}}');
        } else {
            html.push('{{trans("lang.order_delivery")}}');
        }
        if(val.status=='Order Placed') {
            html.push('<span class="order_placed"><span>'+val.status+'</span></span>');
        } else if(val.status=='Order Accepted') {
            html.push('<span class="order_accepted"><span>'+val.status+'</span></span>');
        } else if(val.status=='Order Rejected') {
            html.push('<span class="order_rejected"><span>'+val.status+'</span></span>');
        } else if(val.status=='Driver Pending') {
            html.push('<span class="driver_pending"><span>'+val.status+'</span></span>');
        } else if(val.status=='Driver Rejected') {
            html.push('<span class="driver_rejected"><span>'+val.status+'</span></span>');
        } else if(val.status=='Order Shipped') {
            html.push('<span class="order_shipped"><span>'+val.status+'</span></span>');
        } else if(val.status=='In Transit') {
            html.push('<span class="in_transit"><span>'+val.status+'</span></span>');
        }
        else {
            html.push('<span class="order_completed"><span>'+val.status+'</span></span>');
        }
        var actionHtml='';
        actionHtml+='<span class="action-btn"><a href="'+printRoute+'"><i class="fa fa-print" style="font-size:20px;"></i></a><a href="'+route1+'"><i class="mdi mdi-lead-pencil" title="Edit"></i></a>';
        if(checkDeletePermission) {
            actionHtml+='<a id="'+val.id+'" class="delete-btn" name="order-delete" href="javascript:void(0)"><i class="mdi mdi-delete"></i></a>';
        }
        actionHtml+='</span>';
        html.push(actionHtml);
        return html;
    }
    $("#is_active").click(function() {
        $("#orderTable .is_open").prop('checked',$(this).prop('checked'));
    });
    $("#deleteAll").click(function() {
        if($('#orderTable .is_open:checked').length) {
            if(confirm("{{trans('lang.selected_delete_alert')}}")) {
                jQuery("#data-table_processing").show();
                $('#orderTable .is_open:checked').each(function() {
                    var dataId=$(this).attr('dataId');
                    database.collection('restaurant_orders').doc(dataId).delete().then(function() {
                        setTimeout(function() {
                            window.location.reload();
                        },7000);
                    });
                });
            }
        } else {
            alert("{{trans('lang.select_delete_alert')}}");
        }
    });
    $(document).on("click","a[name='order-delete']",function(e) {
        var id=this.id;
        database.collection('restaurant_orders').doc(id).delete().then(function(result) {
            window.location.href='{{ url()->current() }}';
        });
    });
    async function getStoreNameFunction(vendorId) {
        var vendorName='';
        await database.collection('vendors').where('id','==',vendorId).get().then(async function(snapshots) {
            if(!snapshots.empty) {
                var vendorData=snapshots.docs[0].data();
                vendorName=vendorData.title;
                $('.orderTitle').html('{{trans("lang.order_plural")}} - '+vendorName);
                if(vendorData.dine_in_active==true) {
                    $(".dine_in_future").show();
                }
            }
        });
        return vendorName;
    }
    async function getUserNameFunction(userId) {
        var userName='';
        await database.collection('users').where('id','==',userId).get().then(async function(snapshots) {
            var user=snapshots.docs[0].data();
            userName=user.firstName+' '+user.lastName;
            $('.orderTitle').html('{{trans("lang.order_plural")}} - '+userName);
        });
        return userName;
    }
    function buildHTMLProductstotal(snapshotsProducts) {
        var adminCommission=snapshotsProducts.adminCommission;
        var adminCommissionType=snapshotsProducts.adminCommissionType;
        var discount=snapshotsProducts.discount;
        var couponCode=snapshotsProducts.couponCode;
        var extras=snapshotsProducts.extras;
        var extras_price=snapshotsProducts.extras_price;
        var rejectedByDrivers=snapshotsProducts.rejectedByDrivers;
        var takeAway=snapshotsProducts.takeAway;
        var tip_amount=snapshotsProducts.tip_amount;
        var status=snapshotsProducts.status;
        var products=snapshotsProducts.products;
        var deliveryCharge=snapshotsProducts.deliveryCharge;
        var totalProductPrice=0;
        var total_price=0;
        var specialDiscount=snapshotsProducts.specialDiscount;
        var intRegex=/^\d+$/;
        var floatRegex=/^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
        if(products) {
            products.forEach((product) => {
                var val=product;
                var final_price='';
                if(val.discountPrice!=0&&val.discountPrice!=""&&val.discountPrice!=null&&!isNaN(val.discountPrice)) {
                    final_price=parseFloat(val.discountPrice);
                }
                else {
                    final_price=parseFloat(val.price);
                }
                if(final_price) {
                    price_item=parseFloat(final_price).toFixed(decimal_degits);
                    extras_price_item=0;
                    if(val.extras_price&&!isNaN(extras_price_item)&&!isNaN(val.quantity)) {
                        extras_price_item=(parseFloat(val.extras_price)*parseInt(val.quantity)).toFixed(decimal_degits);
                    }
                    if(!isNaN(price_item)&&!isNaN(val.quantity)) {
                        totalProductPrice=parseFloat(price_item)*parseInt(val.quantity);
                    }
                    var extras_price=0;
                    if(parseFloat(extras_price_item)!=NaN&&val.extras_price!=undefined) {
                        extras_price=extras_price_item;
                    }
                    totalProductPrice=parseFloat(extras_price)+parseFloat(totalProductPrice);
                    totalProductPrice=parseFloat(totalProductPrice).toFixed(decimal_degits);
                    if(!isNaN(totalProductPrice)) {
                        total_price+=parseFloat(totalProductPrice);
                    }
                }
            });
        }
        if(intRegex.test(discount)||floatRegex.test(discount)) {
            discount=parseFloat(discount).toFixed(decimal_degits);
            total_price-=parseFloat(discount);
            if(currencyAtRight) {
                discount_val=discount+""+currentCurrency;
            } else {
                discount_val=currentCurrency+""+discount;
            }
        }
        var special_discount=0;
        if(specialDiscount!=undefined) {
            special_discount=parseFloat(specialDiscount.special_discount).toFixed(decimal_degits);
            total_price=total_price-special_discount;
        }
        var total_item_price=total_price;
        var tax=0;
        taxlabel='';
        taxlabeltype='';
        if(snapshotsProducts.hasOwnProperty('taxSetting')&&snapshotsProducts.taxSetting.length>0) {
            var total_tax_amount=0;
            for(var i=0;i<snapshotsProducts.taxSetting.length;i++) {
                var data=snapshotsProducts.taxSetting[i];
                if(data.type&&data.tax) {
                    if(data.type=="percentage") {
                        tax=(data.tax*total_price)/100;
                        taxlabeltype="%";
                    } else {
                        tax=data.tax;
                        taxlabeltype="fix";
                    }
                    taxlabel=data.title;
                }
                total_tax_amount+=parseFloat(tax);
            }
            total_price=parseFloat(total_price)+parseFloat(total_tax_amount);
        }
        if((intRegex.test(deliveryCharge)||floatRegex.test(deliveryCharge))&&!isNaN(deliveryCharge)) {
            deliveryCharge=parseFloat(deliveryCharge).toFixed(decimal_degits);
            total_price+=parseFloat(deliveryCharge);
            if(currencyAtRight) {
                deliveryCharge_val=deliveryCharge+""+currentCurrency;
            } else {
                deliveryCharge_val=currentCurrency+""+deliveryCharge;
            }
        }
        if(intRegex.test(tip_amount)||floatRegex.test(tip_amount)&&!isNaN(tip_amount)) {
            tip_amount=parseFloat(tip_amount).toFixed(decimal_degits);
            total_price+=parseFloat(tip_amount);
            total_price=parseFloat(total_price).toFixed(decimal_degits);
        }
        if(currencyAtRight) {
            var total_price_val=parseFloat(total_price).toFixed(decimal_degits)+""+currentCurrency;
        } else {
            var total_price_val=currentCurrency+""+parseFloat(total_price).toFixed(decimal_degits);
        }
        return total_price_val;
    }
</script>
@endsection