@extends('layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor restaurantTitle">{{trans('lang.book_table')}}</h3> 
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.book_table')}}</li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
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
                        <li>
                            <a href="{{route('restaurants.payout',$id)}}">{{trans('lang.tab_payouts')}}</a>
                        </li>
                        <li>
                            <a href="{{route('payoutRequests.restaurants.view',$id)}}">{{trans('lang.tab_payout_request')}}</a>
                        </li>
                        <li class="active">
                            <a href="{{route('restaurants.booktable',$id)}}">{{trans('lang.dine_in_future')}}</a>
                        </li>
                        <li id="restaurant_wallet"></li>
                        <li id="subscription_plan"></li>
                    </ul>
                </div>
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ url()->current() }}"><i class="fa fa-list mr-2"></i>{{trans('lang.book_table_table')}}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive m-t-10">
                            <table id="bookTable"
                                   class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                   cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>{{trans('lang.date')}}</th>
                                    <th>{{trans('lang.guestNumber')}}</th>
                                    <th>{{trans('lang.guestName')}}</th>
                                    <th>{{trans('lang.guestPhone')}}</th>
                                    <th>{{trans('lang.status')}}</th>
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
@endsection
@section('scripts')
<script type="text/javascript">
    var database = firebase.firestore();
    var offest = 1;
    var pagesize = 10;
    var end = null;
    var endarray = [];
    var start = null;
    var user_number = [];
    var vendorUserId = "<?php echo $id; ?>";
    var vendorId;
    var ref;
    var append_list = '';
    var placeholderImage = '';
    var dineInOrderAcceptedSubject = '';
    var dineInOrderAcceptedMsg = '';
    var dineInOrderRejectedSubject = '';
    var dineInOrderRejectedMsg = '';
    database.collection('dynamic_notification').where('type', 'in', ['dinein_accepted', 'dinein_canceled']).get().then(async function (snapshot) {
        if (snapshot.docs.length > 0) {
            snapshot.docs.map(async (listval) => {
                val = listval.data();
                if (val.type == "dinein_accepted") {
                    dineInOrderAcceptedSubject = val.subject;
                    dineInOrderAcceptedMsg = val.message;
                } else if (val.type == "dinein_canceled") {
                    var dineInOrderRejectedSubject = val.subject;
                    var dineInOrderRejectedMsg = val.message;
                }
            });
        }
    });
    ref = database.collection('booked_table').orderBy('createdAt', 'desc').where('vendorID', "==", vendorUserId);
    const getStoreName = getStoreNameFunction('<?php echo $id; ?>');
    $(document).ready(function () {
        database.collection('vendors').where("id", "==", '<?php echo $id; ?>').get().then(async function(snapshots) {
            var vendorData = snapshots.docs[0].data();
            walletRoute = "{{route('users.walletstransaction',':id')}}";
            walletRoute = walletRoute.replace(":id", vendorData.author);
            $('#restaurant_wallet').append('<a href="' + walletRoute + '">{{trans("lang.wallet_transaction")}}</a>');
            $('#subscription_plan').append('<a href="' + "{{route('vendor.subscriptionPlanHistory', ':id')}}".replace(':id', vendorData.author) + '">' + '{{trans('lang.subscription_history')}}' + '</a>');
        });
        $(document.body).on('click', '.redirecttopage', function () {
            var url = $(this).attr('data-url');
            window.location.href = url;
        });
        jQuery("#data-table_processing").show();
        var placeholder = database.collection('settings').doc('placeHolderImage');
        placeholder.get().then(async function (snapshotsimage) {
            var placeholderImageData = snapshotsimage.data();
            placeholderImage = placeholderImageData.image;
        })
        const table = $('#bookTable').DataTable({
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
                const orderableColumns = ['date','totalGuest','guestName', 'guestPhone','status','']; // Ensure this matches the actual column names
                const orderByField = orderableColumns[orderColumnIndex]; // Adjust the index to match your table
                if (searchValue.length >= 3 || searchValue.length === 0) {
                    $('#data-table_processing').show();
                }
                await ref.get().then(async function (querySnapshot) {
                    if (querySnapshot.empty) {
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
                        childData.guestName = childData.guestFirstName + " " + childData.guestLastName || " "
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
                            var statustext = "";
                            if (childData.status == "Order Rejected") {
                                statustext = '<span class="badge badge-danger py-2 px-3">Request Rejected</span>';
                            } else if (childData.status == "Order Placed") {
                                statustext = '<span class="badge badge-warning py-2 px-3">Requested</span>';
                            } else if (childData.status == "Order Accepted") {
                                statustext = '<span class="badge badge-success py-2 px-3">Request Accepted</span>';
                            }
                            if (
                                (createdAt && createdAt.toString().toLowerCase().indexOf(searchValue) > -1) || 
                                (childData.totalGuest && childData.totalGuest.toString().toLowerCase().includes(searchValue)) ||
                                (childData.guestName && childData.guestName.toString().toLowerCase().includes(searchValue)) ||
                                (childData.guestPhone && childData.guestPhone.toString().toLowerCase().includes(searchValue)) || 
                                (statustext && statustext.toString().toLowerCase().includes(searchValue))
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
                            aValue = a[orderByField] ? new Date(a[orderByField].toDate()).getTime() : 0;
                            bValue = b[orderByField] ? new Date(b[orderByField].toDate()).getTime() : 0;
                        }   
                        if (orderByField === 'totalGuest') {
                            aValue = a[orderByField] ? parseInt(a[orderByField]) : 0;
                            bValue = b[orderByField] ? parseInt(b[orderByField]) : 0;
                        }                        
                        if (orderDirection === 'asc') {
                            return (aValue > bValue) ? 1 : -1;
                        } else {
                            return (aValue < bValue) ? 1 : -1;
                        }
                    });
                    const totalRecords = filteredRecords.length;
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
            columnDefs: [
            {
                targets: 0,
                type: 'date',
                render: function (data) {
                    return data;
                }
            },
            {orderable: false, targets: [5]},
            ],
            order: [['0', 'desc']],
            "language": {
                "zeroRecords": "{{trans("lang.no_record_found")}}",
                "emptyTable": "{{trans("lang.no_record_found")}}",
                "processing": "" // Remove default loader
            },
        });
        table.columns.adjust().draw();
        function debounce(func, wait) {
            let timeout;
            const context = this;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }
    });
    async function buildHTML(val) {
        var html = [];
        var id = val.id;
        var route1 = '{{route("booktable.edit",":id")}}?id=<?php echo $id; ?>';
        route1 = route1.replace(':id', id);
        var date = '';
        var time = '';
        if (val.hasOwnProperty("date")) {
            try {
                date = val.date.toDate().toDateString();
                time = val.date.toDate().toLocaleTimeString('en-US');
            } catch (err) {
            }
            html.push('<td class="dt-time">' + date + ' ' + time + '</td>');
        } else {
            html.push('<td></td>');
        }
        html.push('<td>' + val.totalGuest + '</td>');
        html.push('<td>' + val.guestName  + '</td>');
        if(val.guestPhone != "" && val.guestPhone != null){
            html.push('<td>' + shortEditNumber(val.guestPhone) + '</td>');    
        }
        else
        {
            html.push('<td></td>');
        }
        var statustext = "";
        if (val.status == "Order Rejected") {
            statustext = '<span class="badge badge-danger py-2 px-3">Request Rejected</span>';
        } else if (val.status == "Order Placed") {
            statustext = '<span class="badge badge-warning py-2 px-3">Requested</span>';
        } else if (val.status == "Order Accepted") {
            statustext = '<span class="badge badge-success py-2 px-3">Request Accepted</span>';
        }
        html.push('<td>' + statustext + '</td>');
        html.push('<span class="action-btn"><a id="' + val.id + '" name="accept-request" data-name="' + val.vendor.title + '" data-auth="' + val.author.id + '" href="javascript:void(0)"><i class="fa fa-check" ></i></a><a id="' + val.id + '"  name="reject-request" data-auth="' + val.author.id + '" data-name="' + val.vendor.title + '" href="javascript:void(0)"><i class="fa fa-close" ></i></a><a href="' + route1 + '"><i class="mdi mdi-lead-pencil" title="Edit"></i></a><a id="' + val.id + '" name="book-table-delete" class="delete-btn" href="javascript:void(0)"><i class="mdi mdi-delete"></i></a></span>');
        return html;
    }
    async function getStoreNameFunction(vendorId) {
        var vendorName = '';
        await database.collection('vendors').where('id', '==', vendorId).get().then(async function (snapshots) {
            if (!snapshots.empty) {
                var vendorData = snapshots.docs[0].data();
                vendorName = vendorData.title;
                $('.restaurantTitle').html('{{trans("lang.book_table")}} - ' + vendorName);
                if (vendorData.dine_in_active == true) {
                    $(".dine_in_future").show();
                }
            }
        });
        return vendorName;
    }
    $(document).on("click", "a[name='book-table-delete']", function (e) {
        var id = this.id;
        database.collection('booked_table').doc(id).delete().then(function (result) {
            window.location.href = '{{ url()->current() }}';
        });
    });
    $(document).on("click", "a[name='accept-request']", function (e) {
        var id = this.id;
        var fullname = $(this).attr('data-name');
        var auth = $(this).attr('data-auth');
        database.collection('booked_table').doc(id).update({'status': 'Order Accepted'}).then(function (result) {
            database.collection('users').where('id', '==', auth).get().then(function (snapshots) {
                if (snapshots.docs.length) {
                    snapshots.forEach((doc) => {
                        user = doc.data();
                        if (user.fcmToken) {
                            $.ajax({
                                method: 'POST',
                                url: '<?php echo route('sendnotification'); ?>',
                                data: {
                                    'fcm': user.fcmToken,
                                    'type': 'booktable_request_accepted',
                                    'authorName': fullname,
                                    '_token': '<?php echo csrf_token() ?>',
                                    'subject': dineInOrderAcceptedSubject,
                                    'message': dineInOrderAcceptedMsg
                                }
                            }).done(function (data) {
                                window.location.href = '{{ url()->current() }}';
                            }).fail(function (xhr, textStatus, errorThrown) {
                                window.location.href = '{{ url()->current() }}';
                            });
                        } else {
                            window.location.href = '{{ url()->current() }}';
                        }
                    });
                } else {
                }
            });
        });
    });
    $(document).on("click", "a[name='reject-request']", function (e) {
        var id = this.id;
        var fullname = $(this).attr('data-name');
        var auth = $(this).attr('data-auth');
        database.collection('booked_table').doc(id).update({'status': 'Order Rejected'}).then(function (result) {
            database.collection('users').where('id', '==', auth).get().then(function (snapshots) {
                if (snapshots.length) {
                    snapshots.forEach((doc) => {
                        if (doc.fcmToken) {
                            $.ajax({
                                method: 'POST',
                                url: '<?php echo route('sendnotification'); ?>',
                                data: {
                                    'fcm': doc.fcmToken,
                                    'type': 'booktable_request_reject',
                                    'authorName': fullname,
                                    '_token': '<?php echo csrf_token() ?>',
                                    'subject': dineInOrderRejectedSubject,
                                    'message': dineInOrderRejectedMsg
                                }
                            }).done(function (data) {
                                window.location.href = '{{ url()->current() }}';
                            }).fail(function (xhr, textStatus, errorThrown) {
                                window.location.href = '{{ url()->current() }}';
                            });
                        } else {
                            window.location.href = '{{ url()->current() }}';
                        }
                    });
                } else {
                    window.location.href = '{{ url()->current() }}';
                }
            });
        });
    });
</script>
@endsection
