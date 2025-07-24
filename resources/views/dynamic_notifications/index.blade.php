@extends('layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.dynamic_notification')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.notificaions_table')}}</li>
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
                        <span class="icon mr-3"><img src="{{ asset('images/notification.png') }}"></span>
                        <h3 class="mb-0">{{trans('lang.dynamic_notification')}}</h3>
                        <span class="counter ml-3 notification_count"></span>
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
               <div class="card border">
                 <div class="card-header d-flex justify-content-between align-items-center border-0">
                   <div class="card-header-title">
                    <h3 class="text-dark-2 mb-2 h4">{{trans('lang.notificaions_table')}}</h3>
                    <p class="mb-0 text-dark-2">{{trans('lang.notifications_table_text')}}</p>
                   </div>
                   <div class="card-header-right d-flex align-items-center">
                    <div class="card-header-btn mr-3"> 
                        <!-- <a class="btn-primary btn rounded-full" href="{!! route('users.create') !!}"><i class="mdi mdi-plus mr-2"></i>{{trans('lang.user_create')}}</a> -->
                     </div>
                   </div>                
                 </div>
                 <div class="card-body">
                         <div class="table-responsive m-t-10">
                         <table id="notificationTable" class="display nowrap table table-hover table-striped table-bordered table table-striped" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{trans('lang.type')}}</th>
                                    <th>{{trans('lang.subject')}}</th>
                                    <th>{{trans('lang.message')}}</th>
                                    <th>{{trans('lang.date_created')}}</th>
                                    <th>{{trans('lang.actions')}}</th>
                                </tr>
                            </thead>
                            <tbody id="append_restaurants">
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
    var database = firebase.firestore();
    var refData = database.collection('dynamic_notification');
    var ref = refData.orderBy('createdAt', 'desc');
    var append_list = '';
    $(document).ready(function () {
        jQuery("#data-table_processing").show();
        const table = $('#notificationTable').DataTable({
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
                const orderableColumns = ['type', 'subject', 'message', 'createdAt', '']; // Ensure this matches the actual column names
                const orderByField = orderableColumns[orderColumnIndex]; // Adjust the index to match your table
                if (searchValue.length >= 3 || searchValue.length === 0) {
                    $('#data-table_processing').show();
                }
                await ref.get().then(async function (querySnapshot) {
                    if (querySnapshot.empty) {
                        $('.notification_count').text(0); 
                        console.error("No data found in Firestore.");
                        $('#data-table_processing').hide(); // Hide loader
                        callback({
                            draw: data.draw,
                            recordsTotal: 0,
                            recordsFiltered: 0,
                            data: [] // No data
                        });
                        return;
                    }
                    let records = [];
                    let filteredRecords = [];
                    await Promise.all(querySnapshot.docs.map(async (doc) => {
                        let childData = doc.data();
                        childData.id = doc.id; // Ensure the document ID is included in the data              
                        if (searchValue) {
                            var date = '';
                            var time = '';
                            if (childData.hasOwnProperty("createdAt")) {
                                try {
                                    date = childData.createdAt.toDate().toDateString();
                                    time = childData.createdAt.toDate().toLocaleTimeString('en-US');
                                } catch (err) {
                                }
                            }
                            var createdAt = date + ' ' + time;
                            if (
                                (childData.type && childData.type.toLowerCase().toString().includes(searchValue)) ||
                                (childData.subject && childData.subject.toLowerCase().toString().includes(searchValue)) ||
                                (createdAt && createdAt.toString().toLowerCase().indexOf(searchValue) > -1) ||
                                (childData.message && childData.message.toLowerCase().toString().includes(searchValue))
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
                        if (orderDirection === 'asc') {
                            return (aValue > bValue) ? 1 : -1;
                        } else {
                            return (aValue < bValue) ? 1 : -1;
                        }
                    });
                    const totalRecords = filteredRecords.length;
                    $('.notification_count').text(totalRecords); 
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
                        data: records // The actual data to display in the table
                    });
                }).catch(function (error) {
                    console.error("Error fetching data from Firestore:", error);
                    $('#data-table_processing').hide(); // Hide loader
                    callback({
                        draw: data.draw,
                        recordsTotal: 0,
                        recordsFiltered: 0,
                        data: [] // No data due to error
                    });
                });
            },
            order: [[3, 'desc']],
            columnDefs: [
                {
                    targets: 3,
                    type: 'date',
                    render: function (data) {
                        return data;
                    }
                },
                { orderable: false, targets: [4] },
            ],
            "language": {
                "zeroRecords": "{{trans("lang.no_record_found")}}",
                "emptyTable": "{{trans("lang.no_record_found")}}",
                "processing": "" // Remove default loader
            },
        });
    })
    $("#is_active").click(function () {
        $("#notificationTable .is_open").prop('checked', $(this).prop('checked'));
    });
    $("#deleteAll").click(function () {
        if ($('#notificationTable .is_open:checked').length) {
            if (confirm("{{trans('lang.selected_delete_alert')}}")) {
                jQuery("#data-table_processing").show();
                $('#notificationTable .is_open:checked').each(function () {
                    var dataId = $(this).attr('dataId');
                    database.collection('dynamic_notification').doc(dataId).delete().then(function () {
                        window.location.reload();
                    });
                });
            }
        } else {
            alert("{{trans('lang.select_delete_alert')}}");
        }
    });
    function buildHTML(val) {
        var html = [];
        newdate = '';
        var id = val.id;
        route1 = '{{route("dynamic-notification.save",":id")}}'
        route1 = route1.replace(":id", id);
        var type='';
        var title='';
        if (val.type == "restaurant_rejected") {
            type = "{{trans('lang.order_rejected_by_restaurant')}}";
            title = "{{trans('lang.order_reject_notification')}}";
        } else if (val.type == "restaurant_accepted") {
            type = "{{trans('lang.order_accepted_by_restaurant')}}";
            title = "{{trans('lang.order_accept_notification')}}";
        } else if (val.type == "takeaway_completed") {
            type = "{{trans('lang.takeaway_order_completed')}}";
            title = "{{trans('lang.takeaway_order_complete_notification')}}";
        } else if (val.type == "driver_completed") {
            type = "{{trans('lang.driver_completed_order')}}";
            title = "{{trans('lang.order_complete_notification')}}";
        } else if (val.type == "driver_accepted") {
            type = "{{trans('lang.driver_accepted_order')}}";
            title = "{{trans('lang.driver_accept_order_notification')}}";
        } else if (val.type == "dinein_canceled") {
            type = "{{trans('lang.dine_order_book_canceled')}}";
            title = "{{trans('lang.dinein_cancel_notification')}}";
        } else if (val.type == "dinein_accepted") {
            type = "{{trans('lang.dine_order_book_accepted')}}";
            title = "{{trans('lang.dinein_accept_notification')}}";
        } else if (val.type == "order_placed") {
            type = "{{trans('lang.new_order_place')}}";
            title = "{{trans('lang.order_placed_notification')}}";
        } else if (val.type == "dinein_placed") {
            type = "{{trans('lang.new_dine_booking')}}";
            title = "{{trans('lang.dinein_order_place_notification')}}";
        } else if (val.type == "schedule_order") {
            type = "{{trans('lang.shedule_order')}}";
            title = "{{trans('lang.schedule_order_notification')}}";
        } else if (val.type == "payment_received") {
            type = "{{trans('lang.pament_received')}}";
            title = "{{trans('lang.payment_receive_notification')}}";
        }
        else if (val.type == "driver_reached_doorstep") {
            type = "Driver reached your Doorstep";
            title = "Driver reached your Doorstep notification";
        }
        html.push(type);
        html.push(val.subject);
        html.push(val.message);
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
        html.push('<span class="action-btn"><i class="text-dark fs-12 fa-solid fa fa-info" data-toggle="tooltip" title="' + title + '" aria-describedby="tippy-3"></i><a href="' + route1 + '"><i class="mdi mdi-lead-pencil" title="Edit"></i></a></span>');
        return html;
    }
    $(document).on("click", "a[name='notifications-delete']", function (e) {
        var id = this.id;
        database.collection('dynamic_notification').doc(id).delete().then(function () {
            window.location.reload();
        });
    });
</script>
@endsection