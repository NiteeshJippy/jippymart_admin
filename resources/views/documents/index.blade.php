@extends('layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.document_plural')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.document_table')}}</li>
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
                        <span class="icon mr-3"><img src="{{ asset('images/document.png') }}"></span>
                        <h3 class="mb-0">{{trans('lang.document_plural')}}</h3>
                        <span class="counter ml-3 doc_count"></span>
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
                    <h3 class="text-dark-2 mb-2 h4">{{trans('lang.document_table')}}</h3>
                    <p class="mb-0 text-dark-2">{{trans('lang.documents_table_text')}}</p>
                   </div>
                   <div class="card-header-right d-flex align-items-center">
                    <div class="card-header-btn mr-3"> 
                        <a class="btn-primary btn rounded-full" href="{!! route('documents.create') !!}"><i class="mdi mdi-plus mr-2"></i>{{trans('lang.document_create')}}</a>
                     </div>
                   </div>                
                 </div>
                 <div class="card-body">
                        <div class="table-responsive m-t-10">
                            <table id="documentTable" class="display nowrap table table-hover table-striped table-bordered table table-striped" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <?php if (in_array('documents.delete', json_decode(@session('user_permissions'), true))) { ?>
                                            <th class="delete-all"><input type="checkbox" id="is_active"><label
                                                    class="col-3 control-label" for="is_active"><a id="deleteAll"
                                                        class="do_not_delete" href="javascript:void(0)"><i
                                                            class="mdi mdi-delete"></i> {{trans('lang.all')}}</a></label>
                                            <?php } ?>
                                        </th>
                                        <th>{{trans('lang.title')}}</th>
                                        <th>{{trans('lang.document_for')}}</th>
                                        <th>{{trans('lang.coupon_enabled')}}</th>
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
    var database = firebase.firestore();
    var offest = 1;
    var pagesize = 10;
    var ref = database.collection('documents');
    var append_list = '';
    var alldriver = database.collection('users').where('role', '==', 'driver');
    var allvendor = database.collection('users').where('role', '==', 'vendor');
    var user_permissions = '<?php echo @session("user_permissions") ?>';
    user_permissions = Object.values(JSON.parse(user_permissions));
    var checkDeletePermission = false;
    if ($.inArray('documents.delete', user_permissions) >= 0) {
        checkDeletePermission = true;
    }
    $(document).ready(function () {
        $(document.body).on('click', '.redirecttopage', function () {
            var url = $(this).attr('data-url');
            window.location.href = url;
        });
        jQuery("#data-table_processing").show();
        const table = $('#documentTable').DataTable({
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
                var orderableColumns = (checkDeletePermission) ? ['', 'title', 'type', '', ''] : ['title', 'type', '', ''] // Ensure this matches the actual column names
                const orderByField = orderableColumns[orderColumnIndex]; // Adjust the index to match your table
                if (searchValue.length >= 3 || searchValue.length === 0) {
                    $('#data-table_processing').show();
                }
                await ref.get().then(async function (querySnapshot) {
                    if (querySnapshot.empty) {
                        $('.doc_count').text(0);
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
                            if (
                                (childData.title && childData.title.toLowerCase().toString().includes(searchValue)) ||
                                (childData.type && childData.type.toLowerCase().toString().includes(searchValue))
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
                        if (orderDirection === 'asc') {
                            return (aValue > bValue) ? 1 : -1;
                        } else {
                            return (aValue < bValue) ? 1 : -1;
                        }
                    });
                    const totalRecords = filteredRecords.length;
                    $('.doc_count').text(totalRecords);
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
            order: (checkDeletePermission) ? [[1,'asc']] : [[0,'asc']],
            columnDefs: [
                { orderable: false, targets: (checkDeletePermission) ? [0,3,4] : [2,3] }
            ],
            "language": {
                "zeroRecords": "{{trans("lang.no_record_found")}}",
                "emptyTable": "{{trans("lang.no_record_found")}}",
                "processing": "" // Remove default loader
            },
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
    async function buildHTML(val) {
        var html = [];
        newdate = '';
        var id = val.id;
        var route1 = '{{route("documents.edit", ":id")}}';
        route1 = route1.replace(':id', id);
        if (checkDeletePermission) {
            html.push('<td class="delete-all"><input type="checkbox" id="is_open_' + id + '" class="is_open" dataId="' + id + '" dataUser="' + val.type + '"><label class="col-3 control-label"\n' +
                'for="is_open_' + id + '" ></label></td>');
        }
        html.push('<a href="' + route1 + '"  class="redirecttopage">' + val.title + '</a>');
        html.push(val.type);
        if (val.enable) {
            html.push('<label class="switch"><input type="checkbox" checked id="' + val.id + '" name="isSwitch" dataUser="' + val.type + '"><span class="slider round"></span></label>');
        } else {
            html.push('<label class="switch"><input type="checkbox" id="' + val.id + '" name="isSwitch" dataUser="' + val.type + '"><span class="slider round"></span></label>');
        }
        var actionHtml = '';
        actionHtml += '<span class="action-btn"><a href="' + route1 + '"><i class="mdi mdi-lead-pencil" title="Edit"></i></a>';
        if (checkDeletePermission) {
            actionHtml = actionHtml + '<a id="' + val.id + '" name="document_delete" dataUser="' + val.type + '" class="delete-btn" href="javascript:void(0)"><i class="mdi mdi-delete"></i></a>';
        }
        actionHtml += '</span>';
        html.push(actionHtml);
        return html;
    }
    $(document).on("click", "input[name='isSwitch']", function (e) {
        var ischeck = $(this).is(':checked');
        var id = this.id;
        var dataUser = $(this).attr('dataUser');
        var checkedVal = ischeck ? true : false;
        database.collection('documents').where('type', '==', dataUser).where('enable', '==', true).get().then(async function (snapshot) {
            if (snapshot.docs.length == 1 && checkedVal == false) {
                jQuery("#data-table_processing").hide();
                alert('{{trans("lang.atleast_one_document_should_enable")}}');
                window.location.reload();
            } else {
                database.collection('documents').doc(id).update({ 'enable': ischeck ? true : false }).then(async function (result) {
                    jQuery("#data-table_processing").show();
                    if (dataUser == 'driver') {
                        var enableDocIds = await getDocId('driver');
                        await alldriver.get().then(async function (snapshotsdriver) {
                            if (snapshotsdriver.docs.length > 0) {
                                var verification = await userDocVerification(enableDocIds, snapshotsdriver, "driver");
                                if (verification) {
                                    jQuery("#data-table_processing").hide();
                                }
                            }
                        })
                    } else {
                        var enableDocIds = await getDocId('restaurant');
                        await allvendor.get().then(async function (snapshotsvendor) {
                            if (snapshotsvendor.docs.length > 0) {
                                var verification = await userDocVerification(enableDocIds, snapshotsvendor, "restaurant");
                                if (verification) {
                                    jQuery("#data-table_processing").hide();
                                }
                            }
                        })
                    }
                });
            }
        })
    });
    $("#is_active").click(function () {
        $("#documentTable .is_open").prop('checked', $(this).prop('checked'));
    });
    $("#deleteAll").click(async function () {
        if ($('#documentTable .is_open:checked').length) {
            if (confirm("{{trans('lang.selected_delete_alert')}}")) {
                jQuery("#data-table_processing").show();
                // Get all selected documents to be deleted
                const selectedDocs = $('#documentTable .is_open:checked').map(function () {
                    return {
                        dataId: $(this).attr('dataId'),
                        dataUser: $(this).attr('dataUser')
                    };
                }).get();
                for (let doc of selectedDocs) {
                    var dataId = doc.dataId;
                    var dataUser = doc.dataUser;
                    let snapshots = await database.collection('documents').where('type', '==', dataUser).get();
                    if (snapshots.docs.length == 1) {
                        jQuery("#data-table_processing").hide();
                        alert('{{trans("lang.atleast_one_document_should_be_there_for")}} ' + dataUser);
                        return false;  // Stop further processing
                    }
                    await database.collection('documents').doc(dataId).delete();
                    let verifySnapshots = await database.collection('documents_verify').get();
                    for (let listval of verifySnapshots.docs) {
                        var data = listval.data();
                        var newDocArr = data.documents.filter(item => item.documentId !== dataId);
                        await database.collection('documents_verify').doc(data.id).update({ 'documents': newDocArr });
                    }
                    if (dataUser == 'driver') {
                        var enableDocIds = await getDocId('driver');
                        let driverSnapshots = await database.collection('users').where('role', '==', 'driver').where('isDocumentVerify', '==', false).get();
                        if (driverSnapshots.docs.length > 0) {
                            var verification = await userDocVerification(enableDocIds, driverSnapshots, "driver");
                            if (verification) {
                                window.location.reload();
                            }
                        } else {
                            window.location.reload();
                        }
                    } else {
                        var enableDocIds = await getDocId('restaurant');
                        let vendorSnapshots = await database.collection('users').where('role', '==', 'vendor').where('isDocumentVerify', '==', false).get();
                        if (vendorSnapshots.docs.length > 0) {
                            var verification = await userDocVerification(enableDocIds, vendorSnapshots, "restaurant");
                            if (verification) {
                                window.location.reload();
                            }
                        } else {
                            window.location.reload();
                        }
                    }
                }
                jQuery("#data-table_processing").hide();
            }
        } else {
            alert("{{trans('lang.select_delete_alert')}}");
        }
    });
    $(document).on("click", "a[name='document_delete']", async function (e) {
        var id = this.id;
        var dataUser = $(this).attr('dataUser');
        await database.collection('documents').where('type', '==', dataUser).get().then(async function (snapshots) {
            if (snapshots.docs.length == 1) {
                jQuery("#data-table_processing").hide();
                alert('{{trans("lang.atleast_one_document_should_be_there_for")}} ' + dataUser);
                return false;
            } else {
                database.collection('documents').doc(id).delete().then(async function () {
                    jQuery("#data-table_processing").show();
                    await database.collection('documents_verify').get().then(async function (snapshots) {
                        snapshots.docs.forEach(async listval => {
                            var data = listval.data();
                            var newDocArr = data.documents.filter(item => item.documentId !== id);
                            await database.collection('documents_verify').doc(data.id).update({ 'documents': newDocArr });
                        })
                    })
                    if (dataUser == 'driver') {
                        var enableDocIds = await getDocId('driver');
                        await database.collection('users').where('role', '==', 'driver').where('isDocumentVerify', '==', false).get().then(async function (snapshotsdriver) {
                            if (snapshotsdriver.docs.length > 0) {
                                var verification = await userDocVerification(enableDocIds, snapshotsdriver, "driver");
                                if (verification) {
                                    window.location.reload();
                                }
                            } else {
                                window.location.reload();
                            }
                        })
                    }
                    else {
                        var enableDocIds = await getDocId('restaurant');
                        await database.collection('users').where('role', '==', 'vendor').where('isDocumentVerify', '==', false).get().then(async function (snapshotsvendor) {
                            if (snapshotsvendor.docs.length > 0) {
                                var verification = await userDocVerification(enableDocIds, snapshotsvendor, "restaurant");
                                if (verification) {
                                    window.location = "{{!url()->current() }}";
                                }
                            } else {
                                window.location = "{{!url()->current() }}";
                            }
                        })
                    }
                });
            }
        });
    });
    async function getDocId(type) {
        var enableDocIds = [];
        await database.collection('documents').where('type', '==', type).where('enable', "==", true).get().then(async function (snapshots) {
            await snapshots.forEach((doc) => {
                enableDocIds.push(doc.data().id);
            });
        });
        return enableDocIds;
    }
    async function userDocVerification(enableDocIds, snapshots, documentFor) {
        var isCompleted = false;
        await Promise.all(snapshots.docs.map(async (driver) => {
            await database.collection('documents_verify').doc(driver.id).get().then(async function (docrefSnapshot) {
                if (docrefSnapshot.data() && docrefSnapshot.data().documents.length > 0) {
                    var driverDocId = await docrefSnapshot.data().documents.filter((doc) => doc.status == 'approved').map((docData) => docData.documentId);
                    if (driverDocId.length >= enableDocIds.length) {
                        if (documentFor == 'driver') {
                            await database.collection('users').doc(driver.id).update({ 'isDocumentVerify': true, isActive: true });
                        } else {
                            await database.collection('users').doc(driver.id).update({ 'isDocumentVerify': true });
                        }
                    } else {
                        await enableDocIds.forEach(async (docId) => {
                            if (!driverDocId.includes(docId)) {
                                if (documentFor == 'driver') {
                                    await database.collection('users').doc(driver.id).update({ 'isDocumentVerify': false, isActive: false });
                                } else {
                                    await database.collection('users').doc(driver.id).update({ 'isDocumentVerify': false });
                                }
                            }
                        });
                    }
                } else {
                    if (documentFor == 'driver') {
                        await database.collection('users').doc(driver.id).update({ 'isDocumentVerify': false, isActive: false });
                    } else {
                        await database.collection('users').doc(driver.id).update({ 'isDocumentVerify': false });
                    }
                }
            });
            isCompleted = true;
        }));
        return isCompleted;
    }
</script>
@endsection
