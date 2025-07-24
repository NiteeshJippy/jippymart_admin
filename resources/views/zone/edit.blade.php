@extends('layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.zone_plural')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{!! route('zone') !!}">{{trans('lang.zone_plural')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.zone_edit')}}</li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="error_top" style="display:none"></div>
                <div class="row restaurant_payout_create">
                    <div class="restaurant_payout_create-inner">
                        <fieldset>
                            <legend>{{trans('lang.zone_edit')}}</legend>
                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.zone_name')}}<span
                                            class="required-field"></span></label>
                                <div class="col-7">
                                    <input type="text" class="form-control" id="name">
                                    <div class="form-text text-muted">{{ trans("lang.zone_name_help") }}</div>
                                </div>
                            </div>
                            <div class="form-group row width-100">
                                <div class="form-check">
                                    <input type="checkbox" class="publish" id="publish">
                                    <label class="col-3 control-label" for="publish">{{trans('lang.status')}}</label>
                                </div>
                            </div>
                            <div class="form-hidden">
                                <input type="hidden" id="coordinates" name="coordinates" value="">
                                <input type="hidden" id="area" name="area" value="">
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-sm-5">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4>{{trans('lang.instructions')}}</h4>
                                <p>{{trans('lang.instructions_help')}}</p>
                                <p><i class="fa fa-hand-pointer-o map_icons"></i>{{trans('lang.instructions_hand_tool')}}</p>
                                <p><i class="fa fa-plus-circle map_icons"></i>{{trans('lang.instructions_shape_tool')}}</p>
                                <p><i class="mdi mdi-delete map_icons"></i>{{trans('lang.instructions_trash_tool')}}</p>
                            </div>
                            <div class="col-sm-12">
                                <img src="{{asset('images/zone_info.gif')}}" alt="GIF" width="100%">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <input type="text" placeholder="{{ trans('lang.search_location') }}" id="search-box"
                            class="form-control controls" />
                        <div id="autocomplete-list"></div>
                        <div id="map"></div>
                    </div>
                    <div class="col-sm-2 mapType">
                        <ul style="list-style: none;padding:0">
                            <li>
                                <a id="select-button" href="javascript:void(0)"
                                    class="btn-floating zone-add-btn btn-large waves-effect waves-light tooltipped"
                                    title="Use this tool to drag the map and select your desired location">
                                    <i class="fa fa-hand-pointer-o map_icons"></i>
                                </a>
                            </li>
                            <li>
                                <a id="add-button" href="javascript:void(0)"
                                    class="btn-floating zone-add-btn btn-large waves-effect waves-light tooltipped"
                                    title="Use this tool to highlight areas and connect the dots">
                                    <i class="fa fa-plus-circle map_icons"></i>
                                </a>
                            </li>
                            <li>
                                <a id="delete-all-button" href="javascript:void(0)" 
                                    class="btn-floating zone-delete-all-btn btn-large waves-effect waves-light tooltipped"
                                    title="Use this tool to delete all selected areas">
                                    <i class="mdi mdi-delete map_icons"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="form-group col-12 text-center btm-btn">
                    <button type="button" class="btn btn-primary edit-setting-btn">
                        <i class="fa fa-save"></i> {{trans('lang.save')}}
                    </button>
                    <a href="{!! route('zone') !!}" class="btn btn-default">
                        <i class="fa fa-undo"></i>{{trans('lang.cancel')}}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<style>
    #map {
        height: 500px;
        width: 100%;
    }
    #panel {
        width: 200px;
        font-family: Arial, sans-serif;
        font-size: 13px;
        float: right;
        margin: 10px;
        margin-top: 100px;
    }
    #delete-button,
    #add-button,
    #delete-all-button,
    #save-button {
        margin-top: 5px;
    }
    #search-box {
        background-color: #f7f7f7;
        font-size: 15px;
        font-weight: 300;
        margin-top: 10px;
        margin-bottom: 10px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        height: 25px;
        border: 1px solid #c7c7c7;
    }
    .map_icons {
        font-size: 24px;
        color: white;
        padding: 10px;
        margin: 5px;
        background-color: {{ isset($_COOKIE['admin_panel_color']) ? $_COOKIE['admin_panel_color'] : '#072750' }};
    }
    #autocomplete-list {
        border: 1px solid #d4d4d4;
        z-index: 9999;
        position: absolute;
        background-color: white;
        cursor: pointer;
    }
    .autocomplete-item {
        padding: 10px;
        border-bottom: 1px solid #d4d4d4;
    }
    .autocomplete-item:hover {
        background-color: #e9e9e9;
    }
    .leaflet-control-custom {
        background-color: #f44336;
        border: none;
        color: white;
        padding: 10px;
        cursor: pointer;
        font-size: 16px;
        border-radius: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    }
    /* Hover effect for the button */
    .leaflet-control-custom:hover {
        background-color: #d32f2f;
    }
    .leaflet-control-custom i {
        font-size: 18px;
    }
</style>
@section('scripts')
<script>
    var id = "<?php echo $id;?>";
    var database = firebase.firestore();
    var ref = database.collection('zone').where("id", "==", id);
    var default_lat = getCookie('default_latitude');
    var default_lng = getCookie('default_longitude');
    var geopoints = '';
    let drawnItems = new L.FeatureGroup();
    let deleteButton ,dragMap;
    let selectedPolygon = null;
    var mapType = 'ONLINE';
    database.collection('settings').doc('DriverNearBy').get().then(async function (snapshots) {
        var data = snapshots.data();
        if (data && data.selectedMapType && data.selectedMapType == "osm") {
            mapType = "OFFLINE"
        }
        var onclick='',polygon='',deletearea='';
        if(mapType == "OFFLINE"){
            onclick = function() {
                console.log("Offline mode, no drawing available.");
            };
            polygon = function() {
                enablePolygonDrawing(map) ;
            };
        }else
        {
            onclick = function() {
                drawingManager.setDrawingMode(null);
            };
            polygon = function() {
                drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
            };
            deletearea=function() {
                clearMap();
            };
        }
        document.getElementById("select-button").onclick = onclick;
        document.getElementById("add-button").onclick = polygon;
        document.getElementById("delete-all-button").onclick = deletearea;
    });
    $(document).ready(function () {
        ref.get().then(async function (snapshots) {
            if (snapshots.docs) {
                var zone = snapshots.docs[0].data();
                $("#name").val(zone.name);
                $("#coordinates").val(zone.area);
                let coordinates = zone.area.map(item => [item.c_, item.h_]);
                document.getElementById('area').value = coordinates;
                var AREA = document.getElementById('area').value;
                const values = AREA.split(',');
                const latLonArray = [];
                for (let i = 0; i < values.length; i += 2) {
                    const lat = parseFloat(values[i + 1]); // Latitude is the second value in the pair
                    const lon = parseFloat(values[i]);    // Longitude is the first value in the pair
                    latLonArray.push([lat, lon]);          // Add [lat, lon] pair to the array
                }
                if(mapType == "ONLINE"){
                    latLonArray.push(latLonArray[0]);
                    document.getElementById('coordinates').value =latLonArray;
                }
                else
                {
                    // latLonArray.push(latLonArray);
                    // Convert to desired format
                    var coordinatesUpdated = latLonArray.map(function(coord) {
                        return {
                            lat: coord[0],  // latitude from the first element of the array
                            lon: coord[1]   // longitude from the second element of the array
                        };
                    });
                    document.getElementById('coordinates').value =JSON.stringify(coordinatesUpdated);
                }
                if (zone.publish) {
                    $("#publish").prop('checked', true);
                }
                default_lat = zone.latitude;
                default_lng = zone.longitude;
                geopoints = zone.area;
            }
        });
        setTimeout(function(){
            initMap();
        },2500);
        $(".edit-setting-btn").click(function () {
            var name = $("#name").val();
            var publish = $("#publish").is(":checked");
            var coordinates_object = $('#coordinates').val(); 
            $(".error_top").empty();
            if (name == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.zone_name_error')}}</p>");
                window.scrollTo(0, 0);
            } else if (coordinates_object == "") {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.zone_coordinates_error')}}</p>");
                window.scrollTo(0, 0);
            } else {
                if(mapType == "ONLINE"){
                    var coordinates_parse = coordinates_object;
                    if (coordinates_parse.startsWith('[[')) {
                        coordinates_parse = coordinates_parse.slice(1);  // Remove the first '['
                    }
                    if (coordinates_parse.endsWith(']]')) {
                        coordinates_parse = coordinates_parse.slice(0, -1);  // Remove the last ']'
                    }
                    var coordinates = JSON.parse(coordinates_parse);
                    if (coordinates && coordinates.length > 0) {
                            var latitude = coordinates[0].lat;
                            var longitude = coordinates[0].lng;
                            var area = [];
                            for (let i = 0; i < coordinates.length; i++) {
                                var item = coordinates[i];
                                if (item && item.lat !== undefined && item.lng !== undefined) {
                                    area.push(new firebase.firestore.GeoPoint(item.lat, item.lng));
                                } else {
                                    console.error("Invalid coordinate at index " + i, item);
                                }
                            }
                            if (latitude && longitude) {
                                jQuery("#overlay").show();
                                database.collection('zone').doc(id).set({
                                    'id': id,
                                    'name': name,
                                    'latitude': latitude,
                                    'longitude': longitude,
                                    'area': area,
                                    'publish': publish,
                                }).then(function (result) {
                                    jQuery("#overlay").hide();
                                    window.location.href = '{{ route("zone")}}';
                                });
                            } 
                            else 
                            {
                                console.error("Invalid latitude or longitude");
                            }
                    } 
                    else
                    {
                        console.error("Coordinates array is empty or invalid.");
                    }
                }
                else
                {
                    try 
                    {
                        if (coordinates_object.startsWith('[[')) {
                            coordinates_object = coordinates_object.slice(1);  // Remove the first '['
                        }
                        if (coordinates_object.endsWith(']]')) {
                            coordinates_object = coordinates_object.slice(0, -1);  // Remove the last ']'
                        }
                        if (coordinates_object.trim().startsWith('[') && coordinates_object.trim().endsWith(']')) {
                                var coordinates_parse;
                                try {
                                    coordinates_parse = JSON.parse(coordinates_object); 
                                }
                                catch (error) {
                                    console.error("Error parsing JSON:", error);
                                    $(".error_top").show();
                                    $(".error_top").html("");
                                    $(".error_top").append("<p>{{trans('lang.zone_coordinates_error')}}</p>");
                                    window.scrollTo(0, 0);
                                    return; // Exit early if JSON parsing fails
                                }
                            if (!Array.isArray(coordinates_parse)) {
                                console.error("Coordinates object is not an array:", coordinates_parse);
                                throw new Error("Coordinates should be an array.");
                            }
                                var latitude, longitude;
                                var validCoordinates = true;
                                var area = [];
                                // Ensure each element in coordinates_parse has lat and lng
                                coordinates_parse.forEach((item, index) =>  {
                                    let updatedItem = '';
                                    if (item.lng !== undefined) {
                                        // Create a new object with 'lat' and 'lon'
                                        updatedItem = {
                                            lat: item.lat,  // Keep lat as is
                                            lon: item.lng   // Replace lng with lon
                                        };
                                    }
                                    else
                                    {
                                        updatedItem = {
                                            lat: item.lat,  // Keep lat as is
                                            lon: item.lon   // Replace lng with lon
                                        };
                                    }
                                    if (item && item.lat !== undefined && (item.lon !== undefined || item.lng !== undefined)) {
                                            const lat = updatedItem.lat;
                                            const lng = updatedItem.lon;
                                            if (typeof lat === 'number'  && !isNaN(lat) && !isNaN(lng) && typeof lng === 'number') {
                                                area.push(new firebase.firestore.GeoPoint(lat, lng));
                                                if (!latitude && !longitude) { 
                                                    latitude = lat;
                                                    longitude = lng;
                                                }
                                            } else {
                                                validCoordinates = false;
                                            }   
                                    } 
                                    else 
                                    {
                                            validCoordinates = false;
                                    }
                                });
                                // If valid coordinates, proceed with the logic
                                if (!validCoordinates) {
                                    throw new Error("Invalid coordinates.");
                                }
                                if (latitude === undefined || longitude === undefined) {
                                    console.error("Latitude or longitude is undefined.");
                                    $(".error_top").show();
                                    $(".error_top").html("<p>{{trans('lang.zone_coordinates_error')}}</p>");
                                    window.scrollTo(0, 0);
                                    return;
                                }
                            $("#area").val(area);
                        }else {
                            throw new Error("Invalid coordinates format: Should be an array of objects.");
                        }
                    } catch (e) {
                        console.error("Error parsing coordinates: ", e);
                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append("<p>{{trans('lang.zone_coordinates_error')}}</p>");
                        window.scrollTo(0, 0);
                    }
                    jQuery("#overlay").show();
                    database.collection('zone').doc(id).set({
                        'id': id,
                        'name': name,
                        'latitude': latitude,
                        'longitude': longitude,
                        'area': area,
                        'publish': publish,
                    }).then(function (result) {
                        jQuery("#overlay").hide();
                        window.location.href = '{{ route("zone")}}';
                    });
                } 
            }
        });
    });
    var map;
    var drawingManager;
    var selectedShape;
    var selectedKernel;
    var gmarkers = [];
    var coordinates = [];
    var allShapes = [];
    var sendable_coordinates = [];
    var shapeColor = "#007cff";
    var kernelColor = "#000";
    function addNewPolys(newPoly) {
        google.maps.event.addListener(newPoly, 'click', function() {
            setSelection(newPoly);
        });
    }
    function setMapOnAll(map) {
        for (var i = 0; i < gmarkers.length; i++) {
            gmarkers[i].setMap(map);
        }
    }
    function clearMarkers() {
        setMapOnAll(null);
    }
    function deleteMarkers() {
        clearMarkers();
        gmarkers = [];
    }
    function deleteSelectedShape() {
        if (selectedShape) {
            selectedShape.setMap(null);
            var index = allShapes.indexOf(selectedShape);
            if (index > -1) {
                allShapes.splice(index, 1);
            }
        }
        if (selectedKernel) {
            selectedKernel.setMap(null);
        }
        let lat_lng = [];
        allShapes.forEach(function(data, index) {
            lat_lng[index] = getCoordinates(data);
        });
        if (lat_lng.length == 0) {
            document.getElementById('coordinates').value = '';
        } else {
            document.getElementById('coordinates').value = JSON.stringify(lat_lng);
        }
    }
    function clearMap() {
        if (allShapes.length > 0) {
            for (var i = 0; i < allShapes.length; i++) {
                allShapes[i].setMap(null);
            }
            allShapes = [];
            deleteMarkers();
            document.getElementById('coordinates').value = null;
        }
    }
    function clearSelection() {
        if (selectedShape) {
            if (selectedShape.type !== 'marker') {
                selectedShape.setEditable(false);
            }
            selectedShape = null;
        }
        if (selectedKernel) {
            if (selectedKernel.type !== 'marker') {
                selectedKernel.setEditable(false);
            }
            selectedKernel = null;
        }
    }
    function setSelection(shape, check) {
        clearSelection();
        shape.setEditable(true);
        shape.setDraggable(true);
        if (check) {
            selectedKernel = shape;
        } else {
            selectedShape = shape;
        }
    }
    function getCoordinates(polygon) {
        var path = polygon.getPath();
        coordinates = [];
        for (var i = 0; i < path.length; i++) {
            coordinates.push({
                lat: path.getAt(i).lat(),
                lng: path.getAt(i).lng()
            });
        }
        document.getElementById('coordinates').value = JSON.stringify(coordinates);        
        return coordinates;
    }
    function createMarker(coord, nr, map) {
        var mesaj = "<h6>Vârf " + nr + "</h6><br>" + "Lat: " + coord.lat + "<br>" + "Lng: " + coord.lng;
        var marker = new google.maps.Marker({
            position: coord,
            map: map,
        });
        google.maps.event.addListener(marker, 'click', function() {
            infowindow.setContent(mesaj);
            infowindow.open(map, marker);
        });
        google.maps.event.addListener(marker, 'dblclick', function() {
            marker.setMap(null);
        });
        return marker;
    }
    function makePolygonDraggable(layer) {
        var latLngs = layer.getLatLngs()[0]; // Get the LatLngs of the polygon
        const  coordinates = layer.getLatLngs();  // Get the polygon's coordinates
        var coordinatesString = JSON.stringify(coordinates);
        if (coordinatesString.startsWith('[[')) {
            coordinatesString = coordinatesString.slice(1);  // Remove the first '['
        }
        if (coordinatesString.endsWith(']]')) {
            coordinatesString = coordinatesString.slice(0, -1);  // Remove the last ']'
        }
        document.getElementById('coordinates').value = coordinatesString;
        // To track mouse position and delta
        var isDragging = false;
        var startLatLng = null;
        var startLatLngs = [];
        // Mouse down event to start dragging
        layer.on('mousedown', function(e) {
            isDragging = true;
            startLatLng = e.latlng; // Store the initial mouse position in LatLng
            startLatLngs = latLngs.map(function(latlng) {
                return  L.latLng(latlng.lat, latlng.lng);  // Clone the LatLngs of the polygon for reference
            });
            map.on('mousemove', onMouseMove); // Track mouse movement
            map.on('mouseup', onMouseUp); // End dragging when mouse is released
        });
        // Mouse move event to drag the polygon
        function onMouseMove(e) {
            const  coordinates = layer.getLatLngs();  // Get the polygon's coordinates
            layer.setLatLngs(coordinates);
            var coordinatesString = JSON.stringify(coordinates);
            if (coordinatesString.startsWith('[[')) {
                coordinatesString = coordinatesString.slice(1);  // Remove the first '['
            }
            if (coordinatesString.endsWith(']]')) {
                coordinatesString = coordinatesString.slice(0, -1);  // Remove the last ']'
            }
            document.getElementById('coordinates').value = coordinatesString;
            if (isDragging) {
                var dx = e.latlng.lng - startLatLng.lng; // Calculate change in longitude
                var dy = e.latlng.lat - startLatLng.lat; // Calculate change in latitude
                // Create new LatLngs by applying the change to each point
                var newLatLngs = startLatLngs.map(function(latlng) {
                    return L.latLng(latlng.lat + dy, latlng.lng + dx); // Shift each point by dx, dy
                });
                // Update the polygon's LatLngs
                layer.setLatLngs([newLatLngs]);
                document.getElementById('coordinates').value = JSON.stringify(newLatLngs);
            }
        }
        // Mouse up event to stop dragging
        function onMouseUp() {
            isDragging = false;
            map.off('mousemove', onMouseMove); // Stop mousemove tracking
            map.off('mouseup', onMouseUp); // Stop mouseup tracking
        }
   }
    function createDragMapButton() {
        if(!dragMap){
            var dragMap = L.control({ position: 'topright' });
            dragMap.onAdd = function(map) {
                var button = L.DomUtil.create('button', 'leaflet-control-custom');
                button.innerHTML = '<i class="fa fa-hand-pointer-o"></i>'; // Using Font Awesome icon
                // Disable map dragging when clicking the button
                L.DomEvent.disableClickPropagation(button);
                // Button click functionality
                button.addEventListener('click', function() {
                    DragMap();
                });
                return button; // Return the button to the control
            };
            // Add the custom button to the map
            dragMap.addTo(map);
        }
    }
    // Create the delete button once and hide it initially
    function createDeleteButton() {
        if (!deleteButton) {
            var deleteButton = L.control({ position: 'topright' });
            deleteButton.onAdd = function(map) {
                var button = L.DomUtil.create('button', 'leaflet-control-custom');
                button.innerHTML = '<i class="mdi mdi-delete"></i>'; // Using Font Awesome icon
                // Disable map dragging when clicking the button
                L.DomEvent.disableClickPropagation(button);
                // Button click functionality
                button.addEventListener('click', function() {
                    deleteSelectedPolygon();
                });
                return button; // Return the button to the control
            };
            // Add the custom button to the map
            deleteButton.addTo(map);
        } 
    }
    function enablePolygonDrawing(map) { 
        map.dragging.disable();
        if (!drawnItems) {
            drawnItems = new L.FeatureGroup();
            map.addLayer(drawnItems);
        } 
        // Create the delete button before enabling drawing
        createDeleteButton();
        createDragMapButton();
        map.on('draw:created', function(event) {
            var layer = event.layer;  // The drawn polygon or shape
            // Add the drawn layer to the map (it is already added to the 'drawnItems' feature group)
            drawnItems.addLayer(layer);
            makePolygonDraggable(layer);
            layer.bindPopup("Drag me!").openPopup();
            // Optionally, log the coordinates of the drawn polygon to the console
            const  coordinates = layer.getLatLngs();  // Get the polygon's coordinates
            if(drawnItems.getLayers().length==1){
                document.getElementById('coordinates').value = JSON.stringify(coordinates);
            }
        });
        map.on('click', function(event) {
            map.dragging.disable();
            var latlng = event.latlng; 
            if (selectedPolygon) {
                // If there's already a selected polygon, deselect it
                selectedPolygon.setStyle({ color: '#3388ff' });
            }
            drawnItems.eachLayer(function(layer) {
                makePolygonDraggable(layer);
                if (layer instanceof L.Polygon && layer.getBounds().contains(event.latlng)) {
                    selectedPolygon = layer;
                    layer.setStyle({ color: 'red' });
                }
                // Optionally, log the coordinates of the drawn polygon to the console
                const  coordinates = layer.getLatLngs();  // Get the polygon's coordinates
                document.getElementById('coordinates').value = JSON.stringify(coordinates);
            });
        });
    }
    function DragMap() {
        map.dragging.enable();
    }
    // Allow deletion of selected polygon
    function deleteSelectedPolygon() {
        map.dragging.disable();
        if (!selectedPolygon) {
        alert("No polygon selected to delete.");
           return;
        }
            drawnItems.removeLayer(selectedPolygon);
            selectedPolygon = null; 
            if(selectedPolygon == null){
                document.getElementById('coordinates').value = '';
            }
    }
    function searchBox() {
        if (mapType == "OFFLINE"){
            var input = document.getElementById('search-box');
            let marker , newLat , newLon;
            var autocompleteList = document.getElementById('autocomplete-list');
            input.addEventListener('keydown', function() {
                if (event.key === 'Enter') {
                    var query = this.value.trim();
                    if (query) {
                        fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1`)
                            .then(response => response.json())
                            .then(data => {
                                autocompleteList.innerHTML = '';
                                data.forEach(place => {
                                    var item = document.createElement('div');
                                    item.classList.add('autocomplete-item');
                                    item.innerText = place.display_name;
                                    item.onclick = function() {
                                        input.value = place.display_name;
                                        input.setAttribute('data-latitude', place.lat);
                                        input.setAttribute('data-longitude', place.lon);
                                        marker = L.marker([place.lat, place.lon], { draggable: true }).addTo(map);
                                        marker.dragging.enable();
                                        map.setView([place.lat, place.lon], 13);
                                        if (marker) {
                                            map.removeLayer(marker);
                                        }
                                        newLat = place.lat;
                                        newLon = place.lon;
                                        // Initially update coordinates display
                                        updateCoordinatesDisplay(newLat, newLon);
                                        marker.on('dragend', function(e) {
                                            newLat = e.target.getLatLng().lat;
                                            newLon = e.target.getLatLng().lng;
                                            updateCoordinatesDisplay(newLat, newLon);
                                        });
                                        marker.on('drag', function(e) {
                                            newLat = e.target.getLatLng().lat;
                                            newLon = e.target.getLatLng().lng;
                                            updateCoordinatesDisplay(newLat, newLon);
                                        });
                                        marker.on('moveend', function() {
                                            updateCoordinatesDisplay(newLat, newLon);
                                        }); 
                                        if (place.address) {
                                            var city = place.address.city || place.address.town || place.address.village || 'N/A';
                                            var state = place.address.state || 'N/A';
                                            var country = place.address.country || 'N/A';
                                            input.setAttribute('data-city', city);
                                            input.setAttribute('data-state', state);
                                            input.setAttribute('data-country', country);
                                        }
                                        autocompleteList.innerHTML = ''; // Clear the autocomplete list
                                    };
                                    autocompleteList.appendChild(item);
                                });
                                if (data && data.length > 0) {
                                    const lat = parseFloat(data[0].lat);
                                    const lon = parseFloat(data[0].lon);
                                    // Set the map view to the new coordinates
                                    map.setView([lat, lon], 13);
                                        // If a marker already exists, remove it
                                    if (marker) {
                                        map.removeLayer(marker);
                                    }
                                    // Add a new marker at the new location
                                    marker = L.marker([lat, lon], { draggable: true }).addTo(map);
                                    marker.dragging.enable();
                                    marker.on('dragend', function(e) {
                                        newLat = e.target.getLatLng().lat;
                                        newLon = e.target.getLatLng().lng;
                                        updateCoordinatesDisplay(newLat, newLon);
                                    });
                                } else {
                                    alert("Location not found. Please try again.");
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    }
                }
            });
            document.addEventListener('click', function(e) {
                let latitude = input.dataset.latitude;
                let longitude = input.dataset.longitude;
                if (e.target !== input) {
                    autocompleteList.innerHTML = '';
                }
            });
            function updateCoordinatesDisplay(lat, lon) {
                var url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json&addressdetails=1`;
                // Fetch data from Nominatim API
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        // Display location details
                        if (data && data.address) {
                            var address = data.display_name; 
                            document.getElementById('search-box').value = address;
                        }
                    })
                    .catch(error => {
                        document.getElementById('search-box').innerHTML = "Error fetching data.";
                        console.error('Error:', error);
                    });
            }
        }
        else
        {
            var input = document.getElementById('search-box');
            var searchBox = new google.maps.places.SearchBox(input);
            map.addListener('bounds_changed', function() {
                searchBox.setBounds(map.getBounds());
            });
            searchBox.addListener('places_changed', function() {
                var places = searchBox.getPlaces();
                if (places.length == 0) {
                    return;
                }
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function(place) {
                    if (!place.geometry) {
                        return;
                    }
                    var icon = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25)
                    };
                    if (place.geometry.viewport) {
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                if (place && place.address_components) {
                    var placeaddress = autocomplete.getPlace().address_components;
                    var city = place.address_components.filter(f => JSON.stringify(f.types) === JSON.stringify(['locality', 'political']))[0].long_name;
                    var state = place.address_components.filter(f => JSON.stringify(f.types) === JSON.stringify(['administrative_area_level_1', 'political']))[0].long_name;
                    var country = place.address_components.filter(f => JSON.stringify(f.types) === JSON.stringify(['country', 'political']))[0].long_name;
                    $("#search-box").val(place.formatted_address).attr('data-latitude', place.geometry.location.lat()).attr('data-longitude', place.geometry.location.lng()).attr('data-city', city).attr('data-state', state).attr('data-country', country)
                }
            });
        }
    }
    function initMap() {
        if (mapType == "ONLINE"){
            var infowindow = new google.maps.InfoWindow({
                size: new google.maps.Size(150, 50)
            })
            $(".mapType").show();
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 8,
                center: new google.maps.LatLng(default_lat, default_lng),
                mapTypeControl: false,
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
                    position: google.maps.ControlPosition.LEFT_CENTER
                },
                zoomControl: true,
                zoomControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER
                },
                scaleControl: false,
                scaleControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER
                },
                streetViewControl: false,
                fullscreenControl: false
            });
            var zones = [];
            var zones_area = [];
            for (i = 0; i < geopoints.length; i++) {
                zones_area.push({'lat': geopoints[i].latitude, 'lng': geopoints[i].longitude})
            }
            zones.push(zones_area);
            var i;
            var polygon;
            for (i = 0; i < zones.length; i++) {
                polygon = new google.maps.Polygon({
                    paths: zones[i],
                    strokeWeight: 1,
                    strokeColor:'#007cf',
                    fillColor: '#007cff',
                    fillOpacity: 0.4,
                });
                polygon.setMap(map);
                addNewPolys(polygon);
                allShapes.push(polygon);
                    google.maps.event.addListener(polygon, 'click', function(e) { getCoordinates(polygon); });
                    google.maps.event.addListener(polygon, "dragend", function(e) {
                    for (i=0; i < allShapes.length; i++) {
                        if (polygon.getPath() == allShapes[i].getPath()) {
                            allShapes.splice(i, 1);
                        }
                    }
                    allShapes.push(polygon);
                    let lat_lng = [];
                    allShapes.forEach(function(data, index) {
                        lat_lng[index] = getCoordinates(data);
                    });
                    document.getElementById('coordinates').value = JSON.stringify(lat_lng);
                });
                google.maps.event.addListener(polygon.getPath(), "insert_at", function(e) {
                    for (i=0; i < allShapes.length; i++) {   // Clear out the old allShapes entry
                        if (polygon.getPath() == allShapes[i].getPath()) {
                            allShapes.splice(i, 1);
                        }
                    }
                    allShapes.push(polygon);
                    let lat_lng = [];
                    allShapes.forEach(function(data, index) {
                        lat_lng[index] = getCoordinates(data);
                    });
                    document.getElementById('coordinates').value = JSON.stringify(lat_lng);
                });
                google.maps.event.addListener(polygon.getPath(), "remove_at", function(e) { getCoordinates(polygon); });
                google.maps.event.addListener(polygon.getPath(), "set_at", function(e) { getCoordinates(polygon); });
            }
            let lat_lng = [];
            allShapes.forEach(function(data, index) {
                lat_lng[index] = getCoordinates(data);
            });
            document.getElementById('coordinates').value = JSON.stringify(lat_lng);
            searchBox();
            var shapeOptions = {
                strokeWeight: 1,
                fillOpacity: 0.4,
                editable: true,
                draggable: true
            };
            drawingManager = new google.maps.drawing.DrawingManager({
                // direct polygon drawing setting
                // drawingMode: google.maps.drawing.OverlayType.POLYGON,
                drawingMode: null,
                drawingControl: false,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER,
                    drawingModes: ['polygon']
                },
                polygonOptions: shapeOptions,
                map: map
            });
            google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
                var newShape = e.overlay;
                allShapes.push(newShape);
                let lat_lng = [];
                allShapes.forEach(function(data, index) {
                    lat_lng[index] = getCoordinates(data);
                });
                document.getElementById('coordinates').value = JSON.stringify(lat_lng);
                newShape.setOptions({
                    fillColor: shapeColor
                });
                getCoordinates(newShape);
                drawingManager.setDrawingMode(null);
                setSelection(newShape, 0);
                google.maps.event.addListener(newShape, 'click', function(e) {
                    if (e.vertex !== undefined) {
                        var path = newShape.getPaths().getAt(e.path);
                        path.removeAt(e.vertex);
                        getCoordinates(newShape);
                        if (path.length < 3) {
                            newShape.setMap(null);
                        }
                    }
                    setSelection(newShape, 0);
                });
                //update coordinates
                google.maps.event.addListener(newShape, 'click', function(e) {
                    getCoordinates(newShape);
                });
                google.maps.event.addListener(newShape, "dragend", function(e) {
                    getCoordinates(newShape);
                });
                google.maps.event.addListener(newShape.getPath(), "insert_at", function(e) {
                    getCoordinates(newShape);
                });
                google.maps.event.addListener(newShape.getPath(), "remove_at", function(e) {
                    getCoordinates(newShape);
                });
                google.maps.event.addListener(newShape.getPath(), "set_at", function(e) {
                    getCoordinates(newShape);
                });
            });
            google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
            google.maps.event.addListener(map, 'click', clearSelection);
        }
        else
        {
            $(".mapType").hide();
            searchBox();
            map = L.map('map').setView([default_lat, default_lng], 10);
            map.dragging.disable();
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);
            // Create a feature group to store drawn items (polygons, lines, etc.)
            drawnItems = new L.FeatureGroup();
            map.addLayer(drawnItems);
            const AREA = document.getElementById('area').value;
            const values = AREA.split(',');
            const latLonArray = [];
            for (let i = 0; i < values.length; i += 2) {
                const lat = parseFloat(values[i + 1]); // Latitude is the second value in the pair
                const lon = parseFloat(values[i]);    // Longitude is the first value in the pair
                latLonArray.push([lat, lon]);          // Add [lat, lon] pair to the array
            }
            const cooo = [
                latLonArray.map(coord => ({
                    lat: coord[0],
                    lon: coord[1]
                }))
            ];
            if(mapType == "ONLINE"){
                document.getElementById('coordinates').value = JSON.stringify(cooo);
            }
            else
            {
                var coordinatesString = JSON.stringify(cooo);
                // Check if the string starts with '[[' and remove the first '[' if true
                if (coordinatesString.startsWith('[[')) {
                    coordinatesString = coordinatesString.slice(1);  // Remove the first '['
                }
                if (coordinatesString.endsWith(']]')) {
                    coordinatesString = coordinatesString.slice(0, -1);  // Remove the last ']'
                }
                // Set the cleaned string as the value of the input field
                document.getElementById('coordinates').value = coordinatesString;
            }
            // Create a polygon and add it to the map
            var polygon = L.polygon(latLonArray, { color: 'blue' }).addTo(drawnItems);
            polygon.on('click', function () {
                if (selectedPolygon) {
                    selectedPolygon.setStyle({ color: 'blue', weight: 3 });
                }
                polygon.setStyle({ color: 'red', weight: 3 });
                selectedPolygon = polygon;
            });
            map.addControl(new L.Control.Draw({
                draw: {  // Disable drawing functionality
                    polygon: true,  // Enable drawing of polygons
                    rectangle: false, // Disable rectangle drawing
                    circle: false,    // Disable circle drawing
                    marker: false,    // Disable marker drawing
                    polyline: false,  // Disable polyline drawing
                    circlemarker: false,
                },
                edit: {
                    featureGroup: drawnItems,  // Allow editing of drawn items
                    remove: false  // Allow removal of items
                }
            }));
            map.on('draw:edited', function(event) {
                event.layers.eachLayer(function(layer) {
                    if (layer instanceof L.Polygon  || layer instanceof L.MultiPolygon) {
                        makePolygonDraggable(layer);
                        // Get the coordinates of the polygon (all vertices)
                        let latLngs = layer.getLatLngs();
                        // Flatten the array of coordinates in case of multi-polygon
                        let flatLatLngs = L.LineUtil.isFlat(latLngs) ? latLngs : latLngs.flat(Infinity);
                        // Convert to desired format (lat, lon)
                        let convertedArray = flatLatLngs.map(function(latLng) {
                            if (latLng && typeof latLng.lat === 'number' && typeof latLng.lng === 'number') {
                                if (latLng.lat >= -90 && latLng.lat <= 90 && latLng.lng >= -180 && latLng.lng <= 180) {
                                    return { lat: latLng.lat, lon: latLng.lng };
                                }
                                else
                                {
                                    console.error("Invalid latLng:", latLng); // Log invalid latLng for debugging
                                    return null; // Avoid undefined latLngs
                                }
                            } else {
                                console.error("Invalid latLng:", latLng); // Log invalid latLng for debugging
                                return null; // Avoid undefined latLngs
                            }
                        }).filter(item => item !== null); 
                         // Final array to be saved as JSON
                        let finalArray = convertedArray;
                        layer.setLatLngs(finalArray); 
                        document.getElementById('coordinates').value = JSON.stringify(finalArray);
                    }
                });
            }); 
            map.on('draw:resize', function (event) {
                var layer = event.layer;
                if (layer instanceof L.Polygon || layer instanceof L.MultiPolygon) {
                    let latLngs = layer.getLatLngs();
                    let flatLatLngs = L.LineUtil.isFlat(latLngs) ? latLngs : latLngs.flat(Infinity);
                    let convertedArray = flatLatLngs.map(function(latLng) {
                            if (latLng && typeof latLng.lat === 'number' && typeof latLng.lng === 'number') {
                                if (latLng.lat >= -90 && latLng.lat <= 90 && latLng.lng >= -180 && latLng.lng <= 180) {
                                    return { lat: latLng.lat, lon: latLng.lng };
                                }
                                else
                                {
                                    console.error("Invalid latLng:", latLng); // Log invalid latLng for debugging
                                    return null; // Avoid undefined latLngs
                                }
                            } else {
                                console.error("Invalid latLng:", latLng); // Log invalid latLng for debugging
                                return null; // Avoid undefined latLngs
                            }
                        }).filter(item => item !== null); 
                         // Final array to be saved as JSON
                        let finalArray = convertedArray;
                        layer.setLatLngs(finalArray); 
                        document.getElementById('coordinates').value = JSON.stringify(finalArray);            
                }
            });
            enablePolygonDrawing(map);   
        }  
    }
</script>
@endsection
