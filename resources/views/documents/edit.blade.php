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
                <li class="breadcrumb-item"><a href="{!! route('documents') !!}">{{trans('lang.document_plural')}}</a>
                </li>
                <li class="breadcrumb-item active">{{trans('lang.document_edit')}}</li>
            </ol>
        </div>
    </div>
    <div class="card-body">
        <div class="error_top" style="display:none"></div>
        <div class="row restaurant_payout_create">
            <div class="restaurant_payout_create-inner">
                <fieldset>
                    <legend>{{trans('lang.document_edit')}}</legend>
                    <div class="form-group row width-50">
                        <label class="col-3 control-label">{{trans('lang.title')}}</label>
                        <div class="col-7">
                            <input type="text" type="text" class="form-control title">
                            <div class="form-text text-muted">{{ trans("lang.document_title_help") }}</div>
                        </div>
                    </div>
                    <div class="form-group row width-50">
                        <label class="col-3 control-label">{{trans('lang.document_for')}}</label>
                        <div class="col-7">
                            <select id="document_for" class="form-control">
                                <option value="restaurant">{{trans('lang.restaurant')}}</option>
                                <option value="driver">{{trans('lang.driver')}}</option>
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.select_document_for") }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row width-50">
                        <div class="form-check">
                            <input type="checkbox" class="frontside" id="frontside">
                            <label class="col-3 control-label" for="frontside">{{trans('lang.frontside')}}</label>
                        </div>
                    </div>
                    <div class="form-group row width-50">
                        <div class="form-check">
                            <input type="checkbox" class="backside" id="backside">
                            <label class="col-3 control-label" for="backside">{{trans('lang.backside')}}</label>
                        </div>
                    </div>
                    <div class="form-group row width-50">
                        <div class="form-check">
                            <input type="checkbox" class="enable" id="enable">
                            <label class="col-3 control-label" for="enable">{{trans('lang.enable')}}</label>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="form-group col-12 text-center btm-btn">
        <button type="button" class="btn btn-primary edit-setting-btn"><i class="fa fa-save"></i> {{
            trans('lang.save')}}
        </button>
        <a href="{!! route('documents') !!}" class="btn btn-default"><i
                class="fa fa-undo"></i>{{trans('lang.cancel')}}</a>
    </div>
</div>
</div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
<link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<script>
    var database = firebase.firestore();
    var id = "{{$id}}";
    var ref = database.collection('documents').where('id', '==', id);
    var alldriver = database.collection('users').where('role', '==', 'driver');
    var allvendor = database.collection('users').where('role', '==', 'vendor');
    var enableFront = false;
    var enableBack = false;
    var enableOneDoc = true;
    $(document).ready(function () {
        jQuery("#data-table_processing").show();
        ref.get().then(async function (snapshot) {
            var data = snapshot.docs[0].data();
            $(".title").val(data.title);
            $("#document_for").val(data.type);
            if (data.enable) {
                $(".enable").prop("checked", true);
            }
            if (data.frontSide) {
                enableFront = true;
                $(".frontside").prop("checked", true);
            }
            if (data.backSide) {
                enableBack = true;
                $(".backside").prop("checked", true);
            }
            jQuery("#data-table_processing").hide();
        })
        $(".edit-setting-btn").click(async function () {
            var title = $(".title").val();
            var document_for = $("#document_for").val();
            var isEnabled = $(".enable").is(":checked");
            await database.collection('documents').where('type', '==', document_for).where('enable', '==', true).get().then(async function (snapshot) {
                if (snapshot.docs.length == 1 && isEnabled == false) {
                    enableOneDoc = false;
                }
            });
            var forntend = $(".frontside").is(":checked");
            if (forntend == true && enableFront == false) {
                await updateDocumentStatus('frontImage');
            }
            var backend = $(".backside").is(":checked");
            if (backend == true && enableBack == false) {
                await updateDocumentStatus('backImage');
            }
            if (title == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.document_title_help')}}</p>");
                window.scrollTo(0, 0);
                return;
            } else if (enableOneDoc == false) {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans("lang.atleast_one_document_should_enable")}}</p>");
                window.scrollTo(0, 0);
                return;
            } else if (forntend == false && backend == false) {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.check_atleast_one_side_of_document_from_front_or_back')}}</p>");
                window.scrollTo(0, 0);
                return;
            }
            else {
                jQuery("#data-table_processing").show();
                database.collection('documents').doc(id).update({
                    'title': title,
                    'type': document_for,
                    'frontSide': forntend,
                    'backSide': backend,
                    'enable': isEnabled,
                    'id': id,
                }).then(async function (result) {
                    if (document_for == 'driver') {
                        var enableDocIds = await getDocId('driver');
                        await alldriver.get().then(async function (snapshotsdriver) {
                            if (snapshotsdriver.docs.length > 0) {
                                var verification = await userDocVerification(enableDocIds, snapshotsdriver);
                                if (verification) {
                                    jQuery("#data-table_processing").hide();
                                    window.location.href = '{{ route("documents")}}';
                                }
                            }
                        })
                    } else {
                        var enableDocIds = await getDocId('restaurant');
                        await allvendor.get().then(async function (snapshotsvendor) {
                            if (snapshotsvendor.docs.length > 0) {
                                var verification = await userDocVerification(enableDocIds, snapshotsvendor);
                                if (verification) {
                                    jQuery("#data-table_processing").hide();
                                    window.location.href = '{{ route("documents")}}';
                                }
                            }
                        })
                    }
                });
            }
        });
    });
    async function updateDocumentStatus(documentSide) {
        var document_for = $("#document_for").val();
        await database.collection('documents_verify').where('type','==',document_for).get().then(async function (snapshot) {
            const updatePromises = snapshot.docs.map(async listval => {
                var data = listval.data();
                var docArray = data.documents;
                if (Array.isArray(docArray)) {
                    var updatedArray = data.documents.map(doc => {
                        if (doc.hasOwnProperty(documentSide) && ((documentSide === 'frontImage') ? doc.frontImage !== '' : doc.backImage !== '')) {
                            return doc; // Return the doc unchanged if the condition is met
                        } else {
                            return (doc.documentId === id) ? { ...doc, status: 'pending' } : doc; // Update status if documentId matches
                        }
                    });
                    await database.collection('documents_verify').doc(data.id).update({ 'documents': updatedArray });
                } else {
                    console.log('data.documents is not an array for document ID: ' + listval.id);
                }
            });
            await Promise.all(updatePromises);
        })
    }
    async function getDocId(type) {
        var enableDocIds = [];
        await database.collection('documents').where('type', '==', type).where('enable', "==", true).get().then(async function (snapshots) {
            await snapshots.forEach((doc) => {
                enableDocIds.push(doc.data().id);
            });
        });
        return enableDocIds;
    }
    async function userDocVerification(enableDocIds, snapshots) {
        var isCompleted = false;
        var document_for = $("#document_for").val()
        await Promise.all(snapshots.docs.map(async (driver) => {
            await database.collection('documents_verify').doc(driver.id).get().then(async function (docrefSnapshot) {
                if (docrefSnapshot.data() && docrefSnapshot.data().documents.length > 0) {
                    var driverDocId = await docrefSnapshot.data().documents.filter((doc) => doc.status == 'approved').map((docData) => docData.documentId);
                    if (driverDocId.length >= enableDocIds.length) {
                        if (document_for == 'driver') {
                            await database.collection('users').doc(driver.id).update({ 'isDocumentVerify': true, isActive: true });
                        } else {
                            await database.collection('users').doc(driver.id).update({ 'isDocumentVerify': true });
                        }
                    } else {
                        await enableDocIds.forEach(async (docId) => {
                            if (!driverDocId.includes(docId)) {
                                if (document_for == 'driver') {
                                    await database.collection('users').doc(driver.id).update({ 'isDocumentVerify': false, isActive: false });
                                } else {
                                    await database.collection('users').doc(driver.id).update({ 'isDocumentVerify': false });
                                }
                            }
                        });
                    }
                } else {
                    if (document_for == 'driver') {
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
