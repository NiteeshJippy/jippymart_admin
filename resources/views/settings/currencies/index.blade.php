@extends('layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.currency_table')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.currency_table')}}</li>
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
                        <span class="icon mr-3"><img src="{{ asset('images/currency.png') }}"></span>
                        <h3 class="mb-0">{{trans('lang.currency_table')}}</h3>
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
               <div class="card border">
                 <div class="card-header d-flex justify-content-between align-items-center border-0">
                   <div class="card-header-title">
                    <h3 class="text-dark-2 mb-2 h4">{{trans('lang.currency_table')}}</h3>
                    <p class="mb-0 text-dark-2">{{trans('lang.currency_table_text')}}</p>
                   </div>
                   <div class="card-header-right d-flex align-items-center">
                    <div class="card-header-btn mr-3"> 
                        <a class="btn-primary btn rounded-full" href="{!! route('currencies.create') !!}"><i class="mdi mdi-plus mr-2"></i>{{trans('lang.currency_create')}}</a>
                     </div>
                   </div>                
                 </div>
                 <div class="card-body">
                         <div class="table-responsive m-t-10">
                         <table id="currenciesTable" class="display nowrap table table-hover table-striped table-bordered table table-striped" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <?php if (in_array('currency.delete', json_decode(@session('user_permissions'),true))) { ?>
                                    <th class="delete-all">
                                        <input type="checkbox" id="select-all">
                                        <label class="col-3 control-label" for="select-all">
                                            <a id="deleteAll" class="do_not_delete" href="javascript:void(0)"><i class="mdi mdi-delete"></i> {{trans('lang.all')}}</a>
                                        </label>
                                    </th>
                                    <?php } ?>
                                    <th>{{trans('lang.currency_name')}}</th>
                                    <th>{{trans('lang.currency_symbol')}}</th>
                                    <th>{{trans('lang.currency_code')}}</th>
                                    <th>{{trans('lang.symbole_at_right')}}</th>
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
    var database = firebase.firestore();
    var offest = 1;
    var pagesize = 10;
    var end = null;
    var endarray = [];
    var start = null;
    var ref = database.collection('currencies').orderBy('name');
    var user_permissions = '<?php echo @session("user_permissions")?>';
    user_permissions = Object.values(JSON.parse(user_permissions));
    var checkDeletePermission = false;
    if ($.inArray('currency.delete', user_permissions) >= 0) {
        checkDeletePermission = true;
    }
    var append_list = '';
    $(document).ready(function() {
        var inx = parseInt(offest) * parseInt(pagesize);
        jQuery("#data-table_processing").show();
        append_list = document.getElementById('append_list1');
        append_list.innerHTML = '';
        ref.get().then(async function(snapshots) {
            html = '';
            html = await buildHTML(snapshots);
            jQuery("#data-table_processing").hide();
            if (html != '') {
                append_list.innerHTML = html;
                start = snapshots.docs[snapshots.docs.length - 1];
                endarray.push(snapshots.docs[0]);
                if (snapshots.docs.length < pagesize) {
                    jQuery("#data-table_paginate").hide();
                }
            }
            if (checkDeletePermission) {
                $('#currenciesTable').DataTable({
                    order: [
                        ['1', 'asc']
                    ],
                    columnDefs: [
                        {
                            orderable: false,
                            targets: [0, 4, 5, 6]
                        },
                    ],
                    "language": {
                        "zeroRecords": "{{ trans('lang.no_record_found') }}",
                        "emptyTable": "{{ trans('lang.no_record_found') }}"
                    },
                    responsive: true
                });
            }
            else
            {
                  $('#currenciesTable').DataTable({
                    order: [
                        ['0', 'asc']
                    ],
                    columnDefs: [
                        {
                            orderable: false,
                            targets: [3, 4, 5]
                        },
                    ],
                    "language": {
                        "zeroRecords": "{{ trans('lang.no_record_found') }}",
                        "emptyTable": "{{ trans('lang.no_record_found') }}"
                    },
                    responsive: true
                });
            }
        });
    });
    function buildHTML(snapshots) {
        var html = '';
        var alldata = [];
        var number = [];
        $('.total_count').text(snapshots.docs.length); 
        snapshots.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            alldata.push(datas);
        });
        var count = 0;
        alldata.forEach((listval) => {
            var val = listval;
            html = html + '<tr>';
            var id = val.id;
            var route1 = '{{route("currencies.edit",":id")}}';
            route1 = route1.replace(':id', id);
            if (checkDeletePermission) {
            html = html + '<td class="delete-all"><input type="checkbox" id="is_open_' + id + '" name="record" class="is_open" dataId="' + id + '"><label class="col-3 control-label"\n' +
                'for="is_open_' + id + '" ></label></td>';
            }
            html = html + '<td><a href="' + route1 + '">' + val.name + '</a></td>';
            html = html + '<td>' + val.symbol + '</td>';
            html = html + '<td>' + val.code + '</td>';
            if (val.symbolAtRight) {
                html = html + '<td><span class="badge badge-success">Yes</span></td>';
            } else {
                html = html + '<td><span class="badge badge-danger">No</span></td>';
            }
            if (val.isActive) {
                html = html + '<td><label class="switch"><input type="checkbox" checked id="' + val.id + '" name="isSwitch"><span class="slider round"></span></label></td>';
            } else {
                html = html + '<td><label class="switch"><input type="checkbox" id="' + val.id + '" name="isSwitch"><span class="slider round"></span></label></td>';
            }
            html = html + '<td class="action-btn"><a href="' + route1 + '"><i class="mdi mdi-lead-pencil" title="Edit"></i></a>';
            if (checkDeletePermission) {
            html = html + '<a id="' + val.id + '" name="category-delete" class="delete-btn" href="javascript:void(0)"><i class="mdi mdi-delete"></i></a></td>';
            }
            html = html + '</tr>';
        });
        return html;
    }
    $('#select-all').change(function() {
        var isChecked = $(this).prop('checked');
        $('input[type="checkbox"][name="record"]').prop('checked', isChecked);
    });
    $('#deleteAll').click(function() {
        if (confirm("{{trans('lang.selected_delete_alert')}}")) {
            // Loop through all selected records and delete them
            $('input[type="checkbox"][name="record"]:checked').each(function() {
                var id = $(this).attr('dataId');
                // Perform deletion of record with id
                database.collection('currencies').doc(id).delete();
            });
            // Reload the page or refresh the data as needed
            setTimeout(function() {
                window.location.reload();
            }, 2000);
        }
    });
    /* toggal publish action code start*/
    $(document).on("click", "input[name='isSwitch']", function(e) {
        var ischeck = $(this).is(':checked');
        var id = this.id;
        if (ischeck) {
            database.collection('currencies').doc(id).update({
                'isActive': true
            }).then(function(result) {
            });
            //only 1 currency should active at a time
            database.collection('currencies').where('isActive', "==", true).get().then(function(snapshots) {
                var activeCurrency = snapshots.docs[0].data();
                var activeCurrencyId = activeCurrency.id;
                database.collection('currencies').doc(activeCurrencyId).update({
                    'isActive': false
                });
                $("#append_list1 tr").each(function() {
                    $(this).find(".switch #" + activeCurrencyId).prop('checked', false);
                });
            });
        } else {
            database.collection('currencies').where('isActive', "==", true).get().then(function(snapshots) {
                var activeCurrency = snapshots.docs[0].data();
                var activeCurrencyId = activeCurrency.id;
                if (snapshots.docs.length == 1 && activeCurrencyId == id) {
                    alert('Can not disable all currency');
                    $("#" + id).prop('checked', true);
                    return false;
                } else {
                    database.collection('currencies').doc(id).update({
                        'isActive': false
                    }).then(function(result) {});
                }
            });
        }
    });
    /*toggal publish action code end*/
    function prev() {
        if (endarray.length == 1) {
            return false;
        }
        end = endarray[endarray.length - 2];
        if (end != undefined || end != null) {
            jQuery("#data-table_processing").show();
            if (jQuery("#selected_search").val() == 'name' && jQuery("#search").val().trim() != '') {
                listener = ref.orderBy('name').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').startAt(end).get();
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
            if (jQuery("#selected_search").val() == 'name' && jQuery("#search").val().trim() != '') {
                listener = ref.orderBy('name').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').startAfter(start).get();
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
        if (jQuery("#selected_search").val() == 'name' && jQuery("#search").val().trim() != '') {
            wherequery = ref.orderBy('name').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').get();
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
    $(document).on("click", "a[name='category-delete']", function(e) {
        var id = this.id;
        if (confirm("{{trans('lang.selected_delete_alert')}}")) {
            database.collection('currencies').doc(id).delete().then(function() {
                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            });
        }
    });
</script>
@endsection
