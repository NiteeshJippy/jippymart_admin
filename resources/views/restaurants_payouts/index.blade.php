@extends('layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.restaurants_payout_plural')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.restaurants_payout_table')}}</li>
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
                        <span class="icon mr-3"><img src="{{ asset('images/payment.png') }}"></span>
                        <h3 class="mb-0">{{trans('lang.restaurants_payout_plural')}}</h3>
                        <span class="counter ml-3 total_count"></span>
                    </div>
                    <div class="d-flex top-title-right align-self-center">
                        <div class="select-box pl-3">
                         
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
                                <a href="{{route('restaurants.view',$id)}}">{{trans('lang.tab_basic')}}</a>
                            </li>
                            <li>
                                <a href="{{route('restaurants.foods',$id)}}">{{trans('lang.tab_foods')}}</a>
                            </li>
                            <li>
                                <a href="{{route('restaurants.orders',$id)}}">{{trans('lang.tab_orders')}}</a>
                            </li>
                            <li>
                                <a href="{{route('restaurants.coupons',$id)}}">{{trans('lang.tab_promos')}}</a>
                            <li class="active">
                                <a href="{{route('restaurants.payout',$id)}}">{{trans('lang.tab_payouts')}}</a>
                            </li>
                            <li>
                                <a
                                    href="{{route('payoutRequests.restaurants.view',$id)}}">{{trans('lang.tab_payout_request')}}</a>
                            </li>
                            <li>
                                <a href="{{route('restaurants.booktable',$id)}}">{{trans('lang.dine_in_future')}}</a>
                            </li>
                            <li id="restaurant_wallet"></li>
                            <li id="subscription_plan"></li>
                        </ul>
                    </div>
                <?php } ?>
               <div class="card border">
                 <div class="card-header d-flex justify-content-between align-items-center border-0">
                   <div class="card-header-title">
                    <h3 class="text-dark-2 mb-2 h4">{{trans('lang.restaurants_payout_table')}}</h3>
                    <p class="mb-0 text-dark-2">{{trans('lang.restaurant_payouts_table_text')}}</p>
                   </div>
                   <div class="card-header-right d-flex align-items-center">
                    <div class="card-header-btn mr-3"> 
                        <?php if ($id != '') { ?>  
                            <a class="btn-primary btn rounded-full" href="{!! route('restaurantsPayouts.create') !!}/{{$id}}"><i class="mdi mdi-plus mr-2"></i>{{trans('lang.restaurants_payout_create')}}</a>
                        <?php } else { ?>
                            <a class="btn-primary btn rounded-full" href="{!! route('restaurantsPayouts.create') !!}"><i class="mdi mdi-plus mr-2"></i>{{trans('lang.restaurants_payout_create')}}</a>
                        <?php } ?>
                    </div>
                   </div>                
                 </div>
                 <div class="card-body">
                         <div class="table-responsive m-t-10">
                         <table id="restaurantPayoutTable"
                                class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                    <th class="delete-all"><input type="checkbox" id="is_active"><label
                                                        class="col-3 control-label" for="is_active"><a id="deleteAll"
                                                            class="do_not_delete" href="javascript:void(0)"><i
                                                                class="mdi mdi-delete"></i> {{trans('lang.all')}}</a></label>
                                                </th>
                                        <?php if ($id == '') { ?>
                                            <th>{{ trans('lang.restaurant')}}</th>
                                        <?php } ?>
                                        <th>{{trans('lang.paid_amount')}}</th>
                                        <th>{{trans('lang.date')}}</th>
                                        <th>{{trans('lang.restaurants_payout_note')}}</th>
                                        <th>{{trans('lang.admin_note')}}</th>
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
<script>
    var database = firebase.firestore();
    var intRegex = /^\d+$/;
    var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
    var getId = '{{$id}}';
    <?php if ($id != '') { ?>
        database.collection('vendors').where("id", "==", '<?php echo $id; ?>').get().then(async function (snapshots) {
            var vendorData = snapshots.docs[0].data();
            walletRoute = "{{route('users.walletstransaction',':id')}}";
            walletRoute = walletRoute.replace(":id", vendorData.author);
            $('#restaurant_wallet').append('<a href="' + walletRoute + '">{{trans("lang.wallet_transaction")}}</a>');
            $('#subscription_plan').append('<a href="' + "{{route('vendor.subscriptionPlanHistory', ':id')}}".replace(':id', vendorData.author) + '">' + '{{trans('lang.subscription_history')}}' + '</a>');
        });
        var refData = database.collection('payouts').where('vendorID', '==', '<?php echo $id; ?>').where('paymentStatus', '==', 'Success');
        var ref = refData.orderBy('paidDate', 'desc');
        const getStoreName = getStoreNameFunction('<?php echo $id; ?>');
    <?php } else { ?>
        var refData = database.collection('payouts').where('paymentStatus', '==', 'Success');
        var ref = refData.orderBy('paidDate', 'desc');
    <?php } ?>
    var currentCurrency = '';
    var currencyAtRight = false;
    var decimal_degits = 0;
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    refCurrency.get().then(async function (snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });
    var append_list = '';
    $(document).ready(function () {
        $(document.body).on('click', '.redirecttopage', function () {
            var url = $(this).attr('data-url');
            window.location.href = url;
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
                { key: 'restaurant', header: "{{ trans('lang.restaurant')}}" },
                { key: 'paidamount', header: "{{trans('lang.paid_amount')}}" },
                { key: 'paidDate', header: "{{trans('lang.date')}}" },
                { key: 'adminNote', header: "{{trans('lang.admin_note')}}" },
                { key: 'note', header: "{{trans('lang.restaurants_payout_note')}}" },
            ],
            fileName: "{{trans('lang.restaurants_payout_table')}}",
        };
        const table = $('#restaurantPayoutTable').DataTable({
            pageLength: 10, // Number of rows per page
            processing: false, // Show processing indicator
            serverSide: true, // Enable server-side processing
            responsive: true,
            ajax: async function (data, callback, settings) {
                const start = data.start;
                const length = data.length;
                const searchValue = data.search.value.toLowerCase();
                const orderColumnIndex = data.order[0].column;
                const orderDirection = data.order[0].dir;
                const orderableColumns = (getId != '') ? ['','amount', 'paidDate', 'note', 'adminNote'] : ['','restaurant', 'amount', 'paidDate', 'note', 'adminNote']; // Ensure this matches the actual column names
                const orderByField = orderableColumns[orderColumnIndex]; // Adjust the index to match your table
                if (searchValue.length >= 3 || searchValue.length === 0) {
                    $('#data-table_processing').show();
                }
                await ref.get().then(async function (querySnapshot) {
                    if (querySnapshot.empty) {
                        $('.total_count').text(0); 
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
                    await Promise.all(querySnapshot.docs.map(async (doc) => {
                        let childData = doc.data();
                        childData.restaurant = await payoutRestaurant(childData.vendorID);
                        childData.id = doc.id; // Ensure the document ID is included in the data    
                        if (currencyAtRight) {
                            childData.paidamount = parseFloat(childData.amount).toFixed(decimal_degits) + "" + currentCurrency;
                        } else {
                            childData.paidamount = currentCurrency + "" + parseFloat(childData.amount).toFixed(decimal_degits);
                        }          
                        if (searchValue) {
                            var date = '';
                            var time = '';
                            if (childData.hasOwnProperty("paidDate")) {
                                try {
                                    date = childData.paidDate.toDate().toDateString();
                                    time = childData.paidDate.toDate().toLocaleTimeString('en-US');
                                } catch (err) {
                                }
                            }
                            var paidDate = date + ' ' + time;
                            if (
                                (childData.restaurant && childData.restaurant.toString().toLowerCase().includes(searchValue)) ||
                                (childData.amount && childData.amount.toString().includes(searchValue)) ||
                                (paidDate && paidDate.toString().toLowerCase().indexOf(searchValue) > -1) ||
                                (childData.note && (childData.note).toLowerCase().includes(searchValue))||
                                (childData.adminNote && (childData.adminNote).toString().includes(searchValue))
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
                            } catch (err) {
                            }
                        }
                         if (orderByField === 'amount') {
                                aValue = a[orderByField] ? parseFloat(a[orderByField]) : 0;
                                bValue = b[orderByField] ? parseFloat(b[orderByField]) : 0;
                         }
                        if (orderDirection === 'asc') {
                            return (aValue > bValue) ? 1 : -1;
                        } else {
                            return (aValue < bValue) ? 1 : -1;
                        }
                    });
                    const totalRecords = filteredRecords.length;
                    $('.total_count').text(totalRecords); 
                    const paginatedRecords = filteredRecords.slice(start, start + length);
                    await Promise.all(paginatedRecords.map(async (childData) => {
                        var getData = await buildHTML(childData);
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
                }).catch(function (error) {
                    console.error("Error fetching data from Firestore:", error);
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
            order: [[3, 'desc']],
            columnDefs: [
                {
                    targets:  3,
                    type: 'date',
                    render: function (data) {
                        return data;
                    }
                },
                { orderable: false, targets: 0 },

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
            if (searchValue.length >= 3) {
                $('#data-table_processing').show();
                table.search(searchValue).draw();
            } else if (searchValue.length === 0) {
                $('#data-table_processing').show();
                table.search('').draw();
            }
        }, 300));
    });
    async function payoutRestaurant(restaurant) {
        var payoutRestaurant = '';
        await database.collection('vendors').where("id", "==", restaurant).get().then(async function (snapshotss) {
            if (snapshotss.docs[0]) {
                var restaurant_data = snapshotss.docs[0].data();
                payoutRestaurant = restaurant_data.title;
            }
        });
        return payoutRestaurant;
    }
    async function getStoreNameFunction(vendorId) {
        var vendorName = '';
        await database.collection('vendors').where('id', '==', vendorId).get().then(async function (snapshots) {
            if (!snapshots.empty) {
                var vendorData = snapshots.docs[0].data();
                vendorName = vendorData.title;
                $('.restaurantTitle').html('{{trans("lang.restaurants_payout_plural")}} - ' + vendorName);
                if (vendorData.dine_in_active == true) {
                    $(".dine_in_future").show();
                }
            }
        });
        return vendorName;
    }
    async function buildHTML(val) {
        var html = [];
        var id = val.id;
        html.push('<td class="delete-all"><input type="checkbox" id="is_open_' + id + '" class="is_open" dataId="' + id + '"><label class="col-3 control-label"\n' +
        'for="is_open_' + id + '" ></label></td>');
        var price_val = '';
        var price = val.amount;
        if (intRegex.test(price) || floatRegex.test(price)) {
            price = parseFloat(price).toFixed(2);
        } else {
            price = 0;
        }
        if (currencyAtRight) {
            price_val = parseFloat(price).toFixed(decimal_degits) + "" + currentCurrency;
        } else {
            price_val = currentCurrency + "" + parseFloat(price).toFixed(decimal_degits);
        }
        <?php if ($id == '') { ?>
            var route = '{{route("restaurants.view",":id")}}';
            route = route.replace(':id', val.vendorID);
            html.push('<a href="' + route + '" class="redirecttopage" >' + val.restaurant + '</a>');
        <?php } ?>
        html.push('<span class="text-red">(' + price_val + ')</span>');
        var date = val.paidDate.toDate().toDateString();
        var time = val.paidDate.toDate().toLocaleTimeString('en-US');
        html.push('<span class="dt-time">' + date + ' ' + time + '</span>');
        if (val.note != undefined && val.note != '') {
           html.push(val.note);
        } else {
            html.push('');
        }
        if (val.adminNote != undefined && val.adminNote != '') {
            html.push(val.adminNote);
        } else {
            html.push('');
        }
        return html;
    }
    $("#is_active").click(function () {
        $("#restaurantPayoutTable .is_open").prop('checked', $(this).prop('checked'));
    });
    $("#deleteAll").click(function () {
        if ($('#restaurantPayoutTable .is_open:checked').length) {
            if (confirm("{{trans('lang.selected_delete_alert')}}")) {
                jQuery("#data-table_processing").show();
                $('#restaurantPayoutTable .is_open:checked').each(function () {
                    var dataId = $(this).attr('dataId');
                    deleteDocumentWithImage('payouts', dataId)
                    .then(() => {
                        window.location.reload();
                    })
                    .catch((error) => {
                        console.error('Error deleting document or store data:', error);
                    });
                });
            }
        } else {
            alert("{{trans('lang.select_delete_alert')}}");
        }
    });
</script>
@endsection