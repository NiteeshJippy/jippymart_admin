@extends('layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.payout_request')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.payout_request')}}</li>
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
                        <h3 class="mb-0">{{trans('lang.payout_request')}}</h3>
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
                @if($id != '')
                    <div class="menu-tab">
                        <ul>
                            <li>
                                <a href="{{route('drivers.view',$id)}}">{{trans('lang.tab_basic')}}</a>
                            </li>
                            <li>
                                <a href="{{route('orders')}}?driverId={{$id}}">{{trans('lang.tab_orders')}}</a>
                            </li>
                            <li>
                                <a href="{{route('driver.payout',$id)}}">{{trans('lang.tab_payouts')}}</a>
                            </li>
                            <li class="active">
                                <a href="{{route('payoutRequests.drivers.view',$id)}}">{{trans('lang.tab_payout_request')}}</a>
                            </li>
                            <li>
                                <a href="{{route('users.walletstransaction',$id)}}">{{trans('lang.wallet_transaction')}}</a>
                            </li>
                        </ul>
                    </div>
                @endif
               <div class="card border">
                 <div class="card-header d-flex justify-content-between align-items-center border-0">
                   <div class="card-header-title">
                    <h3 class="text-dark-2 mb-2 h4">{{trans('lang.payout_request')}}</h3>
                    <p class="mb-0 text-dark-2">{{trans('lang.payout_request_table_text')}}</p>
                   </div>
                   <div class="card-header-right d-flex align-items-center">
                    <div class="card-header-btn mr-3"> 
                        @if($id == '' && request()->is('payoutRequests/drivers'))
                            <a class="btn-primary btn rounded-full" href="{!! url('payoutRequests/restaurants') !!}"><i class="mdi mdi-plus mr-2"></i>{{trans('lang.restaurant_payout_request')}}</a>
                        @endif
                        @if (request()->is('payoutRequests/restaurants'))
                        <a class="btn-primary btn rounded-full" href="{!! url('payoutRequests/drivers') !!}"><i class="mdi mdi-plus mr-2"></i>{{trans('lang.drivers_payout_request')}}</a>
                        @endif
                     </div>
                   </div>                
                 </div>
                 <div class="card-body">
                         <div class="table-responsive m-t-10">
                            <div class="error_top" style="display:none"></div>
                            <div class="success_top" style="display:none"></div>
                            <table id="payoutRequestTable" class="display nowrap table table-hover table-striped table-bordered table table-striped" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                    <th class="delete-all"><input type="checkbox" id="is_active"><label
                                                        class="col-3 control-label" for="is_active"><a id="deleteAll"
                                                            class="do_not_delete" href="javascript:void(0)"><i
                                                                class="mdi mdi-delete"></i> {{trans('lang.all')}}</a></label>
                                                </th>
                                        @if($id == '')
                                        <th>{{ trans('lang.driver')}}</th>
                                        @endif
                                        <th>{{trans('lang.paid_amount')}}</th>
                                        <th>{{trans('lang.drivers_payout_note')}}</th>
                                        <th>{{trans('lang.drivers_payout_paid_date')}}</th>
                                        <th>{{trans('lang.status')}}</th>
                                        <th>{{trans('lang.withdraw_method')}}</th>
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
<div class="modal fade" id="bankdetailsModal" tabindex="-1" role="dialog"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered location_modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title locationModalTitle">{{trans('lang.bankdetails')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="">
                    <div class="form-row">
                        <input type="hidden" name="driverId" id="driverId">
                        <div class="form-group row">
                            <div class="form-group row width-100">
                                <label class="col-12 control-label">{{
                                    trans('lang.bank_name')}}</label>
                                <div class="col-12">
                                    <input type="text" name="bank_name" class="form-control" id="bankName">
                                </div>
                            </div>
                            <div class="form-group row width-100">
                                <label class="col-12 control-label">{{
                                    trans('lang.branch_name')}}</label>
                                <div class="col-12">
                                    <input type="text" name="branch_name" class="form-control" id="branchName">
                                </div>
                            </div>
                            <div class="form-group row width-100">
                                <label class="col-4 control-label">{{
                                    trans('lang.holer_name')}}</label>
                                <div class="col-12">
                                    <input type="text" name="holer_name" class="form-control" id="holderName">
                                </div>
                            </div>
                            <div class="form-group row width-100">
                                <label class="col-12 control-label">{{
                                    trans('lang.account_number')}}</label>
                                <div class="col-12">
                                    <input type="text" name="account_number" class="form-control" id="accountNumber">
                                </div>
                            </div>
                            <div class="form-group row width-100">
                                <label class="col-12 control-label">{{
                                    trans('lang.other_information')}}</label>
                                <div class="col-12">
                                    <input type="text" name="other_information" class="form-control" id="otherDetails">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary save-form-btn" id="submit_accept">
                        {{trans('lang.accept')}}</a>
                    </button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">
                        {{trans('lang.close')}}</a>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cancelRequestModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title locationModalTitle">{{trans('lang.cancel_payout_request')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="data-table_processing_modal" class="dataTables_processing panel panel-default"
                        style="display: none;">{{trans('lang.processing')}}
                </div>
                <form class="">
                    <div class="form-row">
                        <div class="form-group row">
                            <div class="form-group row width-100">
                                <label class="col-12 control-label">{{trans('lang.notes')}}</label>
                                <div class="col-12">
                                    <textarea name="admin_note" class="form-control" id="admin_note" cols="5" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary save-form-btn" id="submit_cancel">
                        {{trans('lang.submit')}}</a>
                    </button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">
                        {{trans('lang.close')}}</a>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="payoutResponseModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{trans('lang.payout_response')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="payout-response"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">
                    {{trans('lang.close')}}</a>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    var database = firebase.firestore();
    var driverId = "{{$id}}";
    var intRegex = /^\d+$/;
    var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
    if (driverId != "") {
        var refData = database.collection('driver_payouts').where('driverID', '==', driverId);
        getDriverNameFunction(driverId);
    } else {
        var refData = database.collection('driver_payouts').where('paymentStatus', '==', 'Pending');
    }
    var email_templates = database.collection('email_templates').where('type', '==', 'payout_request_status');
    var emailTemplatesData = null;
    var ref = refData.orderBy('paidDate', 'desc');
    var append_list = '';
    var currentCurrency = '';
    var currencyAtRight = false;
    var decimal_degits = 0;
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    refCurrency.get().then(async function (snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });
    $(document).ready(function () {
        email_templates.get().then(async function (snapshots) {
            emailTemplatesData = snapshots.docs[0].data();
        });
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
                { key: 'driverName', header: "{{ trans('lang.driver')}}" },
                { key: 'payoutamount', header: "{{trans('lang.paid_amount')}}" },
                { key: 'paymentStatus', header: "{{trans('lang.status')}}" },
                { key: 'withdrawMethod', header: "{{trans('lang.withdraw_method')}}" },
                { key: 'paidDate', header: "{{trans('lang.drivers_payout_paid_date')}}" },
            ],
            fileName: "{{trans('lang.payout_request')}}",
        };
        const table = $('#payoutRequestTable').DataTable({
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
                @if($id == '')
                    const orderableColumns =  ['','driverName', 'amount', 'note', 'paidDate', 'paymentStatus','withdrawMethod','']; // Ensure this matches the actual column names
                @else
                    const orderableColumns =  ['','driverName', 'amount', 'note', 'paidDate', 'paymentStatus','withdrawMethod',''];
                @endif
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
                        childData.id = doc.id; // Ensure the document ID is included in the data
                        if (driverId == "") {
                            const payoutDriver = payoutDriverfunction(childData.driverID);
                            childData.driverName = payoutDriver;
                        }
                        childData.payoutamount = childData.amount;
                        if (intRegex.test(childData.payoutamount) || floatRegex.test(childData.payoutamount)) {
                            childData.payoutamount = parseFloat(childData.payoutamount).toFixed(decimal_degits);
                        } else {
                            childData.payoutamount = 0.00;
                        }
                        if (currencyAtRight) {
                            childData.payoutamount = parseFloat(childData.payoutamount).toFixed(decimal_degits) + "" + currentCurrency;
                        } else {
                            childData.payoutamount = currentCurrency + "" + parseFloat(childData.payoutamount).toFixed(decimal_degits);
                        }
                        if (searchValue) {
                            if (
                                (childData.driverName && childData.driverName.toString().toLowerCase().includes(searchValue)) ||
                                (childData.amount && childData.amount.toString().includes(searchValue))
                                || (childData.note && childData.note.toString().toLowerCase().includes(searchValue)) ||
                                (childData.paymentStatus && childData.paymentStatus.toString().toLowerCase().includes(searchValue)) || (childData.withdrawMethod && childData.withdrawMethod.toString().toLowerCase().includes(searchValue))
                            ) {
                                filteredRecords.push(childData);
                            }
                        } else {
                            filteredRecords.push(childData);
                        }
                    }));
                    filteredRecords.sort((a, b) => {
                        let aValue = a[orderByField] ;
                        let bValue = b[orderByField] ;
                        if (orderByField === 'amount') {
                            aValue = a[orderByField] ? parseFloat(a[orderByField]) : 0.0;
                            bValue = b[orderByField] ? parseFloat(b[orderByField]) : 0.0;
                        }else if (orderByField === 'paidDate') {
                            aValue = a[orderByField] ? new Date(a[orderByField].toDate()).getTime() : 0;
                            bValue = b[orderByField] ? new Date(b[orderByField].toDate()).getTime() : 0;
                        }
                        else{
                            aValue = a[orderByField] ? a[orderByField].toString().toLowerCase() : '';
                            bValue = b[orderByField] ? b[orderByField].toString().toLowerCase() : ''
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
            order: (driverId == '') ? [4, 'desc'] : [3, 'desc'],
            columnDefs: [
                {
                    orderable: false,
                    targets: (driverId == '') ? [0,5,7] : [0,4,6],
                },
                
                {
                    type: 'date',
                    render: function (data) {
                        return data;
                    },
                    targets: (driverId == '') ? [4] : [3],
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
    });
    async function getDriverNameFunction(driverId) {
        var snapshots = await database.collection('users').doc(driverId).get();
        if(snapshots.exists){
            var driverData = snapshots.data();
            var driverName = driverData.firstName+' '+driverData.lastName;
            $('.driverTitle').html('{{trans("lang.payout_request")}} - ' + driverName);
        }
    }
    function buildHTML(val) {
        var html = [];
        var alldata = [];
        var number = [];
        var route1 = '{{route("drivers.edit",":id")}}';
        var id = val.id;
        html.push('<td class="delete-all"><input type="checkbox" id="is_open_' + id + '" class="is_open" dataId="' + id + '"><label class="col-3 control-label"\n' +
        'for="is_open_' + id + '" ></label></td>');
        route1 = route1.replace(':id', val.id);
        if (driverId == "") {
            const payoutDriver = payoutDriverfunction(val.driverID);
            html.push('<a href="'+route1+'" class="driver_' + val.driverID + ' redirecttopage" ></a>');
        }
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
        html.push(price_val);
        var date = val.paidDate.toDate().toDateString();
        var time = val.paidDate.toDate().toLocaleTimeString('en-US');
        if (val.note) {
            html.push(val.note);
        } else {
            html.push('');
        }
        html.push(date + ' ' + time );
        if (val.paymentStatus == 'Pending' || val.paymentStatus == 'In Process') {
            html.push('<td class="order_placed"><span>' + val.paymentStatus + '</span></td>');
        }else if (val.paymentStatus == 'Reject' || val.paymentStatus == 'Failed') {
            html.push('<td class="order_rejected"><span>' + val.paymentStatus + '</span></td>');
        }else if (val.paymentStatus == 'Success') {
            html.push('<td class="order_completed"><span>' + val.paymentStatus + '</span></td>');
        } else{
            html.push('');
        }
        if (val.withdrawMethod) {
            var selectedwithdrawMethod = (val.withdrawMethod == "bank") ? "Bank Transfer" : val.withdrawMethod;
            html.push('<td style="text-transform:capitalize">' + selectedwithdrawMethod + '</td>');
        } else {
            html.push('');
        }
        var actionHtml = '';
        actionHtml = actionHtml + '<td class="action-btn">';
            if (val.paymentStatus != "Reject" && val.paymentStatus != "Success") {
                actionHtml = actionHtml + '<a id="' + val.id + '" name="driver_view" data-auth="' + val.driverID + '" data-amount = "' + price_val + '" href="javascript:void(0)" data-toggle="modal" data-target="#bankdetailsModal" class="btn btn-info mb-2">Manual Pay</a>';
            }
            if (val.withdrawMethod && val.withdrawMethod != "bank" && val.paymentStatus != "Reject" && val.paymentStatus != "Success") {
                actionHtml = actionHtml + '<br>';
                actionHtml = actionHtml + '<a id="' + val.id + '" name="driver_pay"  data-auth="' + val.driverID + '" data-amount="' + price + '" data-method="'+val.withdrawMethod+'" href="javascript:void(0)" class="btn btn-success mb-2 direct-click-btn">Pay Online</a>';
            }
            if (val.paymentStatus != "Reject" && val.paymentStatus != "Success") {
                actionHtml = actionHtml + '<br>';
                actionHtml = actionHtml + '<a id="' + val.id + '" name="driver_reject_request" data-toggle="modal" data-target="#cancelRequestModal" data-auth="' + val.driverID + '" data-amount = "' + price_val + '" data-price="' + price + '" href="javascript:void(0)" class="btn btn-primary mb-2">Cancel Request</a>';
            }
            if (val.paymentStatus == "In Process") {
                actionHtml = actionHtml + '<br>';
                actionHtml = actionHtml + '<a id="' + val.id + '" name="driver_check_status" data-auth="' + val.driverID + '" data-amount="' + price + '" data-method="'+val.withdrawMethod+'" href="javascript:void(0)" class="btn btn-dark mb-2">Check Payment Status</a>';
            }
        actionHtml = actionHtml + '</td>';
        html.push(actionHtml);
        return html;
    }
    async function getDriverBankDetails() {
        var driverId = $('#driverId').val();
        await database.collection('users').where("id", "==", driverId).get().then(async function (snapshotss) {
            if (snapshotss.docs[0]) {
                var user_data = snapshotss.docs[0].data();
                if (user_data.userBankDetails) {
                    $('#bankName').val(user_data.userBankDetails.bankName);
                    $('#branchName').val(user_data.userBankDetails.branchName);
                    $('#holderName').val(user_data.userBankDetails.holderName);
                    $('#accountNumber').val(user_data.userBankDetails.accountNumber);
                    $('#otherDetails').val(user_data.userBankDetails.otherDetails);
                }
            }
        });
    }
    $(document).on("click", "a[name='driver_view']", function (e) {
        $('#bankName').val("");
        $('#branchName').val("");
        $('#holderName').val("");
        $('#accountNumber').val("");
        $('#otherDetails').val("");
        var id = this.id;
        var auth = $(this).attr('data-auth');
        var amount = $(this).attr('data-amount');
        $('#driverId').val(auth);
        getDriverBankDetails();
        $('#submit_accept').attr('data-id',id).attr('data-amount',amount).attr('data-auth',auth);
    });
    $(document).on("click", "a[name='driver_pay']", async function (e) {
        $(this).prop('disabled',true).css({'cursor':'default','opacity':'0.5'});
        var data = {};
        data['payoutId'] = this.id;
        data['method'] = $(this).data('method');
        data['amount'] = $(this).data('amount');
        data['user'] =  await getUserData($(this).data('auth'));
        data['settings'] = await getPaymentSettings();
        if(data['method'] != "undefined"){
            $.ajax({
                type: 'POST',
                data: {
                    data: btoa(JSON.stringify(data)),
                },
                url: "{{url('pay-to-user')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if(response.success == true){
                        $(".success_top").show().html("");
                        $(".success_top").append("<p>"+response.message+"</p>");
                        window.scrollTo(0, 0);
                        database.collection('driver_payouts').doc(data['payoutId']).update({'paymentStatus': response.status,'payoutResponse' : response.result}).then(async function (result) {
                            if (data['user'] && data['user'] != undefined){
                                var emailData = await sendMailToRestaurant(data['user'], data['payoutId'], 'Approved', data['amount']);
                                if(emailData){
                                    window.location.reload();
                                }
                            }
                        });
                    }else{
                        $(".error_top").show().html("");
                        $(".error_top").append("<p>"+response.message+"</p>");
                        window.scrollTo(0, 0);
                        setTimeout(function(){
                            window.location.reload();
                        },5000);
                    }
                }
            });
        }
    });
    $(document).on("click", "a[name='driver_check_status']", async function (e) {
        $(this).prop('disabled',true).css({'cursor':'default','opacity':'0.5'});
        var data = {};
        data['payoutId'] = this.id;
        data['method'] = $(this).data('method');
        data['amount'] = $(this).data('amount');
        data['user'] =  await getUserData($(this).data('auth'));
        data['settings'] = await getPaymentSettings();
        data['payoutDetail'] = await getPayoutDetail(data['payoutId']);
        if(data['method'] != "undefined"){
            $.ajax({
                type: 'POST',
                data: {
                    data: btoa(JSON.stringify(data)),
                },
                url: "{{url('check-payout-status')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if(response.success == true){
                        $(".success_top").show().html("");
                        $(".success_top").append("<p>"+response.message+"</p>");
                        window.scrollTo(0, 0);
                    }else{
                        $(".error_top").show().html("");
                        $(".error_top").append("<p>"+response.message+"</p>");
                        window.scrollTo(0, 0);
                    }
                    $(this).prop('disabled',false).css({'cursor':'pointer','opacity':'1'});
                    if(response.result && response.status){
                        database.collection('driver_payouts').doc(data['payoutId']).update({'paymentStatus': response.status,'payoutResponse' : response.result});
                        $("#payoutResponseModal .payout-response").html(JSON.stringify(JSON.parse(JSON.stringify(response.result)),null,4));
                        $("#payoutResponseModal").modal('show');
                    }
                }
            });
        }
    });
    async function payoutDriverfunction(driver) {
        var payoutDriver = '';
        var routedriver = '{{route("users.edit",":id")}}';
        routedriver = routedriver.replace(':id', driver);
        await database.collection('users').where("id", "==", driver).get().then(async function (snapshotss) {
            if (snapshotss.docs[0]) {
                var driver_data = snapshotss.docs[0].data();
                payoutDriver = driver_data.firstName + " " + driver_data.lastName;
                jQuery(".driver_" + driver).attr("data-url", routedriver).html(payoutDriver);
            } else {
                jQuery(".driver_" + driver).attr("data-url", routedriver).html('');
            }
        });
        return payoutDriver;
    }
    async function sendMailToRestaurant(user, id, status, amount) {
        var formattedDate = new Date();
        var month = formattedDate.getMonth() + 1;
        var day = formattedDate.getDate();
        var year = formattedDate.getFullYear();
        month = month < 10 ? '0' + month : month;
        day = day < 10 ? '0' + day : day;
        formattedDate = day + '-' + month + '-' + year;
        var subject = emailTemplatesData.subject;
        subject = subject.replace(/{requestid}/g, id);
        emailTemplatesData.subject = subject;
        var message = emailTemplatesData.message;
        message = message.replace(/{username}/g, user.firstName + ' ' + user.lastName);
        message = message.replace(/{date}/g, formattedDate);
        message = message.replace(/{requestid}/g, id);
        message = message.replace(/{status}/g, status);
        message = message.replace(/{amount}/g, amount);
        message = message.replace(/{usercontactinfo}/g, user.phoneNumber);
        emailTemplatesData.message = message;
        var url = "{{url('send-email')}}";
        return await sendEmail(url, emailTemplatesData.subject, emailTemplatesData.message, [user.email]);
    }
    async function getUserData(driverId) {
        var data = '';
        await database.collection('users').where("id", "==", driverId).get().then(async function (snapshotss) {
            if (snapshotss.docs[0]) {
                data = snapshotss.docs[0].data();
            }
        });
        if(data.id){
            await database.collection('withdraw_method').where("userId", "==", data.id).get().then(async function (snapshotss) {
                if (snapshotss.docs.length) {
                    data['withdrawMethod'] = snapshotss.docs[0].data();
                }
            });
        }
        return data;
    }
    async function getPaymentSettings() {
        var settings = {};
        await database.collection('settings').get().then(async function (snapshots) {
            snapshots.forEach((doc) => {
                if(doc.id == "flutterWave"){
                    settings["flutterwave"] = doc.data();
                }
                if(doc.id == "paypalSettings"){
                    settings["paypal"] = doc.data();
                }
                if(doc.id == "razorpaySettings"){
                    settings["razorpay"] = doc.data();
                }
                if(doc.id == "stripeSettings"){
                    settings["stripe"] = doc.data();
                }
            });
        });
        return settings;
    }
    async function getPayoutDetail(payoutId) {
        var snapshot = await database.collection('driver_payouts').doc(payoutId).get();
        return snapshot.data();
    }
    $(document).on("click", "a[name='driver_reject_request']", function (e) {
        $('#admin_note').val("");
        var id = this.id;
        var auth = $(this).attr('data-auth');
        var amount = $(this).attr('data-amount');
        var price = $(this).attr('data-price');
        $('#submit_cancel').attr('data-id',id).attr('data-amount',amount).attr('data-price',price).attr('data-auth',auth);
    });
    $(document).on("click", "#submit_cancel", async function (e) {
        $(this).prop('disabled',true).css({'cursor':'default','opacity':'0.5'});
        var id = $(this).data('id');
        var auth = $(this).data('auth');
        var user = await getUserData(auth);
        var priceadd = $(this).data('price');
        var amount = $(this).data('amount');
        var admin_note = $("#admin_note").val();
        jQuery("#data-table_processing").show();
        database.collection('users').where("id", "==", auth).get().then(function (resultdriver) {
            if (resultdriver.docs.length) {
                var driver = resultdriver.docs[0].data();
                var wallet_amount = 0;
                if (isNaN(driver.wallet_amount) || driver.wallet_amount == undefined) {
                    wallet_amount = 0;
                } else {
                    wallet_amount = driver.wallet_amount;
                }
                price = parseFloat(wallet_amount) + parseFloat(priceadd);
                if (!isNaN(price)) {
                    database.collection('driver_payouts').doc(id).update({'paymentStatus': 'Reject','adminNote':admin_note}).then(function (result) {
                        database.collection('users').doc(driver.id).update({'wallet_amount': price}).then(async function (result) {
                            var wId = database.collection('temp').doc().id;
                            database.collection('wallet').doc(wId).set({
                                'amount': parseFloat(priceadd),
                                'date': firebase.firestore.FieldValue.serverTimestamp(),
                                'id': wId,
                                'isTopUp': false,
                                'order_id': id,
                                'payment_method': 'Wallet',
                                'payment_status': 'Refund success',
                                'transactionUser': 'driver',
                                'user_id': driver.id,
                                'note': 'Refund by admin'
                            });
                            if (user && user != undefined) {
                                var emailData = await sendMailToRestaurant(user, id, 'Disapproved', amount);
                                if (emailData) {
                                    window.location.reload();
                                }
                            } else {
                                window.location.reload();
                            }
                        });
                    });
                }
            } else {
                alert('Driver not found.');
            }
        });
    });
    $(document).on("click", "#submit_accept", async function (e) {
        $(this).prop('disabled',true).css({'cursor':'default','opacity':'0.5'});
        var id = $(this).data('id');
        var auth = $(this).data('auth');
        var user = await getUserData(auth);
        var amount = $(this).data('amount');
        jQuery("#data-table_processing").show();
        database.collection('driver_payouts').doc(id).update({'paymentStatus': 'Success'}).then(async function (result) {
            if (user && user != undefined) {
                var emailData = await sendMailToRestaurant(user, id, 'Approved', amount);
                if (emailData) {
                    window.location.reload();
                }
            } else {
                window.location.reload();
            }
        });
    });
    function prev() {
        if (endarray.length == 1) {
            return false;
        }
        end = endarray[endarray.length - 2];
        if (end != undefined || end != null) {
            jQuery("#data-table_processing").show();
            if (jQuery("#selected_search").val() == 'vendor' && jQuery("#search").val().trim() != '') {
                listener = refData.orderBy('firstName').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').startAt(end).get();
            } else {
                listener = ref.startAt(end).limit(pagesize).get();
            }
            listener.then((snapshots) => {
                html = '';
                html = buildHTML(snapshots);
                jQuery("#data-table_processing").hide();
                if (html != '') {
                    append_list.innerHTML = html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    endarray.splice(endarray.indexOf(endarray[endarray.length - 1]), 1);
                    if (snapshots.docs.length < pagesize) {
                        jQuery("#users_table_previous_btn").hide();
                    }
                }
            });
        }
    }
    function next() {
        if (start != undefined || start != null) {
            jQuery("#data-table_processing").hide();
            if (jQuery("#selected_search").val() == 'vendor' && jQuery("#search").val().trim() != '') {
                listener = refData.orderBy('firstName').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').startAfter(start).get();
            } else {
                listener = ref.startAfter(start).limit(pagesize).get();
            }
            listener.then((snapshots) => {
                html = '';
                html = buildHTML(snapshots);
                jQuery("#data-table_processing").hide();
                if (html != '') {
                    append_list.innerHTML = html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    if (endarray.indexOf(snapshots.docs[0]) != -1) {
                        endarray.splice(endarray.indexOf(snapshots.docs[0]), 1);
                    }
                    endarray.push(snapshots.docs[0]);
                }
            });
        }
    }
    function searchclear() {
        jQuery("#search").val('');
        searchtext();
    }
    function searchtext() {
        jQuery("#data-table_processing").show();
        append_list.innerHTML = '';
        if (jQuery("#selected_search").val() == 'note' && jQuery("#search").val().trim() != '') {
            wherequery = refData.orderBy('note').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').get();
        } else {
            wherequery = ref.limit(pagesize).get();
        }
        wherequery.then((snapshots) => {
            html = '';
            html = buildHTML(snapshots);
            jQuery("#data-table_processing").hide();
            if (html != '') {
                append_list.innerHTML = html;
                start = snapshots.docs[snapshots.docs.length - 1];
                endarray.push(snapshots.docs[0]);
                if (snapshots.docs.length < pagesize) {
                    jQuery("#data-table_paginate").hide();
                } else {
                    jQuery("#data-table_paginate").show();
                }
            }
        });
    }
    $("#is_active").click(function () {
        $("#payoutRequestTable .is_open").prop('checked', $(this).prop('checked'));
    });
    $("#deleteAll").click(function () {
        if ($('#payoutRequestTable .is_open:checked').length) {
            if (confirm("{{trans('lang.selected_delete_alert')}}")) {
                jQuery("#data-table_processing").show();
                $('#payoutRequestTable .is_open:checked').each(function () {
                    var dataId = $(this).attr('dataId');
                    deleteDocumentWithImage('driver_payouts', dataId)
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
