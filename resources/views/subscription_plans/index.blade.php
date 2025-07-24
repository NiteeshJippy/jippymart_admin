@extends('layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor restaurantTitle">{{ trans('lang.subscription_plans') }}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ trans('lang.dashboard') }}</a></li>
                <li class="breadcrumb-item active">{{ trans('lang.subscription_plans') }}</li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <div class="admin-top-section">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex top-title-section pb-4 justify-content-between">
                        <div class="d-flex top-title-left align-items-center">
                            <span class="icon mr-3"><img src="{{ asset('images/subscription.png') }}"></span>
                            <h3 class="mb-0">{{ trans('lang.subscription_plans') }}</h3>
                            <span class="counter ml-3 total_count"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="overview-sec">
            <div class="row">
                <div class="col-12">
                    <div class="card border">
                        <div class="card-header d-flex justify-content-between align-items-center border-0">
                            <div class="card-header-title">
                                <h3 class="text-dark-2 mb-2 h4">{{trans("lang.overview")}}</h3>
                                <p class="mb-0 text-dark-2">{{trans("lang.see_overview_of_package_earning")}}</p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row subscription-list">
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
                                <h3 class="text-dark-2 mb-2 h4">{{trans("lang.subscription_package_list")}}</h3>
                                <p class="mb-0 text-dark-2">{{trans("lang.manage_all_package_in_single_click")}}</p>
                            </div>
                            <div class="card-header-right d-flex align-items-center">
                                <div class="card-header-btn mr-3">
                                    <a href="{!! route('subscription-plans.save') !!}"
                                        class="btn-primary btn rounded-full"><i
                                            class="mdi mdi-plus mr-2"></i>{{ trans('lang.create_subscription_plan') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive m-t-10">
                                <table id="subscriptionPlansTable"
                                    class="display nowrap table table-hover table-striped table-bordered table table-striped dataTable no-footer dtr-inline collapsed"
                                    cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <?php if (in_array('subscription-plans.delete', json_decode(@session('user_permissions'), true))) { ?>
                                            <th class="delete-all"><input type="checkbox" id="is_active"><label
                                                    class="col-3 control-label" for="is_active">
                                                    <a id="deleteAll" class="do_not_delete" href="javascript:void(0)"><i
                                                            class="mdi mdi-delete"></i>
                                                        {{ trans('lang.all') }}</a></label>
                                            </th>
                                            <?php } ?>
                                            <th>{{ trans('lang.plan_name') }}</th>
                                            <th>{{ trans('lang.plan_price') }}</th>
                                            <th>{{ trans('lang.duration') }}</th>
                                            <th>{{ trans('lang.current_subscriber') }}</th>
                                            <th>{{ trans('lang.status') }}</th>
                                            <th>{{ trans('lang.actions') }}</th>
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
</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    var database=firebase.firestore();
    var ref=database.collection('subscription_plans');

    var user_permissions='<?php echo @session('user_permissions'); ?>';
    user_permissions=Object.values(JSON.parse(user_permissions));
    var checkDeletePermission=false;
    if($.inArray('subscription-plans.delete',user_permissions)>=0) {
        checkDeletePermission=true;
    }
    var currentCurrency='';
    var currencyAtRight=false;
    var decimal_degits=0;
    var refCurrency=database.collection('currencies').where('isActive','==',true);
    refCurrency.get().then(async function(snapshots) {
        var currencyData=snapshots.docs[0].data();
        currentCurrency=currencyData.symbol;
        currencyAtRight=currencyData.symbolAtRight;
        decimal_degits=currencyData.decimal_degits;
    });
    var placeholder=database.collection('settings').doc('placeHolderImage');
    placeholder.get().then(async function(snapshotsimage) {
        var placeholderImageData=snapshotsimage.data();
        placeholderImage=placeholderImageData.image;
    })
    $(document).ready(function() {
        $(document.body).on('click','.redirecttopage',function() {
            var url=$(this).attr('data-url');
            window.location.href=url;
        });
        jQuery("#data-table_processing").show();
        const table=$('#subscriptionPlansTable').DataTable({
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
                const orderableColumns=(checkDeletePermission)? ['','name','price',
                    'expiryDay','','',''
                ]:['name','price','expiryDay','','',
                    '']; // Ensure this matches the actual column names
                const orderByField=orderableColumns[
                    orderColumnIndex]; // Adjust the index to match your table
                if(searchValue.length>=3||searchValue.length===0) {
                    $('#data-table_processing').show();
                }
                ref.get().then(async function(querySnapshot) {
                    if(querySnapshot.empty) {
                        $(".total_count").text(0);
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
                    let records=[];
                    let filteredRecords=[];
                    await Promise.all(querySnapshot.docs.map(async (doc) => {
                        let childData=doc.data();
                        childData.id=doc.id; // Ensure the document ID is included in the data
                        if(searchValue) {
                            if(
                                (childData.name&&childData.name.toString()
                                    .toLowerCase().includes(searchValue))||
                                (childData.price&&childData.price.toString()
                                    .toLowerCase().includes(searchValue))||
                                (childData.expiryDay&&childData.expiryDay
                                    .toString().includes(searchValue))
                            ) {
                                filteredRecords.push(childData);
                            }
                        } else {
                            filteredRecords.push(childData);
                        }
                        childData.totalSubscriber=await getTotalSubscriber(childData.id);
                    }));
                    filteredRecords.sort((a,b) => {
                        let aValue=a[orderByField]? a[orderByField]
                            .toString().toLowerCase():'';
                        let bValue=b[orderByField]? b[orderByField]
                            .toString().toLowerCase():'';
                        if(orderByField==='expiryDay') {
                            aValue=a[orderByField]? parseInt(a[
                                orderByField]):0;
                            bValue=b[orderByField]? parseInt(b[
                                orderByField]):0;
                        }
                        if(orderDirection==='asc') {
                            return (aValue>bValue)? 1:-1;
                        } else {
                            return (aValue<bValue)? 1:-1;
                        }
                    });
                    const totalRecords=filteredRecords.length;
                    $(".total_count").text(totalRecords);
                    const paginatedRecords=filteredRecords.slice(start,start+
                        length);

                    await Promise.all(paginatedRecords.map(async (childData) => {
                        var getData=await buildHTML(childData);
                        records.push(getData);
                    }));


                    let specialRecordId='J0RwvxCWhZzQQD7Kc2Ll';
                    let specialIndex=records.findIndex(record => {
                        return record.some(cell => cell.includes(`id="${specialRecordId}"`));
                    });

                    if(specialIndex>-1) {
                        let [specialRecord]=records.splice(specialIndex,1);
                        records.unshift(specialRecord);
                    }
                    $('#data-table_processing').hide(); // Hide loader
                    callback({
                        draw: data.draw,
                        recordsTotal: totalRecords, // Total number of records in Firestore
                        recordsFiltered: totalRecords, // Number of records after filtering (if any)
                        data: records // The actual data to display in the table
                    });
                }).catch(function(error) {
                    console.error("Error fetching data from Firestore:",error);
                    $('#data-table_processing').hide(); // Hide loader
                    callback({
                        draw: data.draw,
                        recordsTotal: 0,
                        recordsFiltered: 0,
                        data: [] // No data due to error
                    });
                });
            },
            order: (checkDeletePermission)? [1,'asc']:[0,'asc'],
            columnDefs: [{
                orderable: false,
                targets: (checkDeletePermission)? [0,5,6]:[4,5]
            },
            ],
            "language": {
                "zeroRecords": "{{ trans('lang.no_record_found') }}",
                "emptyTable": "{{ trans('lang.no_record_found') }}",
                "processing": "" // Remove default loader
            },
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
    async function buildHTML(childData) {
        var row=[];
        var id=childData.id;
        var route1=
            '{{ route('subscription-plans.save', ':id') }}';
        route1=route1.replace(':id',id);
        var route2=
            '{{ route('current-subscriber.list', ':id') }}';
        route2=route2.replace(':id',id);
        if(childData.image!=''&&childData.image!=null) {
            var imageHtml='<img onerror="this.onerror=null;this.src=\''+placeholderImage+'\'" alt="" width="100%" style="width:70px;height:70px;" src="'+childData.image+'" alt="image">';
        } else {
            var imageHtml='<img alt="" width="100%" style="width:70px;height:70px;" src="'+placeholderImage+'" alt="image">';
        }

        if(checkDeletePermission) {
            if(childData.id!='J0RwvxCWhZzQQD7Kc2Ll') {
                row.push(`
        <td class="delete-all">
            <input type="checkbox" id="is_open_${id}" class="is_open" dataId="${id}">
            <label class="col-3 control-label" for="is_open_${id}"></label>
        </td>
    `);
            } else {
                row.push('');
            }
        }

        // Add the rest of the row data
        row.push(`
    <td>${imageHtml}<a href="${route2}" id="${childData.id}">${childData.name}</a></td>
`);

        row.push(
            childData.type!=="free"
                ? currencyAtRight
                    ? parseFloat(childData.price).toFixed(decimal_degits)+currentCurrency
                    :currentCurrency+parseFloat(childData.price).toFixed(decimal_degits)
                :"<span style='color:red;'>Free</span>"
        );

        row.push(
            childData.expiryDay==='-1'
                ? "{{trans('lang.unlimited')}}"
                :childData.expiryDay+' Days'
        );

        row.push(`
    <td><a href="${route2}">${childData.totalSubscriber}</a></td>
`);

        if(childData.id!='J0RwvxCWhZzQQD7Kc2Ll') {
            row.push(
                childData.isEnable
                    ? `<label class="switch">
                    <input type="checkbox" checked id="${childData.id}" name="isActive">
                    <span class="slider round"></span>
               </label>`
                    :`<label class="switch">
                    <input type="checkbox" id="${childData.id}" name="isActive">
                    <span class="slider round"></span>
               </label>`
            );
        } else {
            row.push('')
        }

        row.push(`
    <span class="action-btn">
        <a href="${route1}" class="link-td"><i class="mdi mdi-lead-pencil"></i></a>`+
            (childData.id!='J0RwvxCWhZzQQD7Kc2Ll'
                ? `<?php if (in_array('subscription-plans.delete', json_decode(@session('user_permissions'), true))) { ?>
            <a id="${childData.id}" class="link-td delete-btn direct-click-btn" name="plan-delete" href="javascript:void(0)">
                <i class="mdi mdi-delete"></i>
            </a>
           <?php } ?>`
                :'')+
            `</span>
`);


        return row;

    }

    $(document).on("click","input[name='isActive']",async function(e) {
        var ischeck=$(this).is(':checked');
        var id=this.id;
        if(ischeck) {
            database.collection('subscription_plans').doc(id).update({
                'isEnable': true
            }).then(function(result) {
            });
        } else {
            var refactiveSubscription=await database.collection('subscription_plans').where('isEnable','==',true).where('id','!=','J0RwvxCWhZzQQD7Kc2Ll').get();
            var EnabledSubscriptions=refactiveSubscription.size;
            if(EnabledSubscriptions==1) {
                Swal.fire({
                    title: 'Error!',
                    text: '{{trans("lang.atleast_one_subscription_plan_should_be_active")}}',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                $(this).prop('checked',true);

            } else {
                database.collection('subscription_plans').doc(id).update({
                    'isEnable': false
                }).then(function(result) {
                });
            }

        }
    });
    $(document).on("click","a[name='plan-delete']",async function(e) {
        var id=this.id;
        database.collection('subscription_plans').doc(id).delete().then(async function(result) {
            window.location.reload();
        })


    });
    $("#is_active").click(function() {
        $("#subscriptionPlansTable .is_open").prop('checked',$(this).prop('checked'));
    });
    $("#deleteAll").click(function() {
        if($('#subscriptionPlansTable .is_open:checked').length) {
            if(confirm("{{ trans('lang.selected_delete_alert') }}")) {
                jQuery("#data-table_processing").show();
                $('#subscriptionPlansTable .is_open:checked').each(async function() {
                    var dataId=$(this).attr('dataId');
                    database.collection('subscription_plans').doc(dataId).delete().then(async function(result) {
                        window.location.reload();
                    })

                });
            }
        } else {
            alert("{{ trans('lang.select_delete_alert') }}");
        }
    });
    async function getTotalSubscriber(id) {
        var total=0;
        await database.collection('users').where('role','==','vendor').where('subscriptionPlanId','==',id).get().then(async function(snapshots) {
            total=snapshots.docs.length;
        });
        return total;
    }
    ref.where('id','!=','J0RwvxCWhZzQQD7Kc2Ll').get().then(async function(snapshots) {
        var html='';
        if(snapshots.docs.length>0) {
            snapshots.docs.map(async (listval) => {
                var data=listval.data();
                getEarnings(data.id);
                html+=` <div class="col-md-4">
                                <div class="card card-box-with-icon">
                                    <div class="card-body">
                                      <span class="box-icon"><img src="${data.image}"></span>
                                       <div class="card-box-with-content mt-3">
                                        <h4 class="text-dark-2 mb-1 h4 earnings_${data.id}">$10,000</h4>
                                        <p class="mb-0 text-dark-2">${data.name}</p>
                                       </div>
                                       <span class="background-img"><img src="${data.image}"></span>
                                    </div>
                                </div>
                            </div>`;
            });
            $('.subscription-list').append(html);
        }
    })
    function getEarnings(planId) {
        var total=0;
        database.collection('subscription_history').where('subscription_plan.id','==',planId).get().then(async function(snapshots) {
            await snapshots.docs.map(async (listval) => {
                var data=listval.data();
                total+=parseFloat(data.subscription_plan.price);
            });
            if(currencyAtRight) {
                total=parseFloat(total).toFixed(decimal_degits)+currentCurrency;
            } else {
                total=currentCurrency+parseFloat(total).toFixed(decimal_degits);
            }
            $('.earnings_'+planId).html(total);
        });
    }
</script>
@endsection