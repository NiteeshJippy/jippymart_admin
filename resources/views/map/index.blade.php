@extends('layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.live_tracking')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a>
                </li>
                <li class="breadcrumb-item active">
                    {{trans('lang.live_tracking')}}
                </li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <!-- start row -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="table-responsive ride-list">
                            <input type="text" id="searchInput" oninput="searchDriver()" placeholder="Search Driver...">
                            <div class="live-tracking-list">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div id="map" style="height:450px"></div>
                        <div id="legend">
                            <h3>{{trans('lang.legend')}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        #append_list12 tr {
            cursor: pointer;
        }
        #legend {
            font-family: Arial, sans-serif;
            background: #fff;
            padding: 10px;
            margin: 11px;
            border: 1px solid #000;
        }
        #legend h3 {
            margin-top: 0;
        }
        #legend img {
            vertical-align: middle;
        }
    </style>
    @endsection
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    @section('scripts')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script type="text/javascript">
        var database = firebase.firestore();
        var map;
        var marker;
        var markers = [];
        var map_data = [];
        var base_url = '{!! asset('/images/') !!}';
        $(document).ready(function () {
            var database = firebase.firestore();
            var orders = [];
            var orders_drivers = [];
            database.collection('restaurant_orders').where('status', '==', 'In Transit').get().then(async function (snapshots) {
                if (snapshots.docs.length > 0) {
                    snapshots.docs.forEach((doc) => {
                        var data = doc.data();
                        data.flag = 'in_transit';
                        orders.push(data);
                        if (data.hasOwnProperty('driver')) {
                            orders_drivers.push(data.driver.id);
                        }
                    });
                }
            });
            var drivers = [];
            database.collection('users').where('role', '==', 'driver').where('location', '!=', null).get().then(async function (snapshots) {
                if (snapshots.docs.length > 0) {
                    snapshots.docs.forEach((doc) => {
                        var data = doc.data();
                        data.flag = 'available';
                        if (isNaN(data.location.latitude) != true && isNaN(data.location.longitude) != true && data.location.latitude != null && data.location.latitude != null) {
                            if ($.inArray(data.id, orders_drivers) === -1) {
                                drivers.push(data);
                            }
                        }
                    });
                }
                let mapdata = $.merge(orders, drivers)
                loadData(mapdata);
                searchDriver();
            });
            setTimeout(function () {
                $(".sidebartoggler").click();
            }, 500);
            $(document).on("click", ".ride-list .track-from", function () {
                var lat = $(this).data('lat');
                var lng = $(this).data('lng');
                var index = $(this).data('index');
                if (mapType == "OFFLINE" ){
                    map.setView([lat, lng], map.getZoom());
                    if(markers[index]){
                       markers[index].openPopup();
                    } else {
                       console.log("Marker at index " + index + " is undefined.");
                    }
                } else{
                    map.panTo(new google.maps.LatLng(lat, lng));
                    google.maps.event.trigger(markers[index], 'click');
                }
            });
        });
          function InitializeGodsEyeMap() {
              var default_lat = getCookie('default_latitude');
              var default_lng = getCookie('default_longitude');
              var legend = document.getElementById('legend');
            if (mapType == "OFFLINE" ){
                map = L.map('map').setView([default_lat, default_lng], 10);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);
            } else{
                var myLatlng = new google.maps.LatLng(default_lat, default_lng);
                var infowindow = new google.maps.InfoWindow();
                var mapOptions = {
                    zoom: 10,
                    center: myLatlng,
                    streetViewControl: false,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                map = new google.maps.Map(document.getElementById("map"), mapOptions);
            }
              var fliter_icons = {
                  available: {
                      name: 'Available',
                      icon: base_url + '/available.png'
                  },
                  ontrip: {
                      name: 'In Transit',
                      icon: base_url + '/ontrip.png'
                  }
              };
              for (var key in fliter_icons) {
                  var type = fliter_icons[key];
                  var name = type.name;
                  var icon = type.icon;
                  var div = document.createElement('div');
                  div.innerHTML = '<img src="' + icon + '"> ' + name;
                  legend.appendChild(div);
              }
            if (mapType == "OFFLINE" ){
                var lmaplegend  = L.control({ position: 'bottomleft' });
                lmaplegend.onAdd = function (map) {
                    var div = L.DomUtil.create('div', 'legend');
                    div.innerHTML = "<h4>Map Legend</h4>";
                    div.appendChild(legend);
                    return div;
                };
                lmaplegend.addTo(map);
            } else{
                map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(legend);
            }
          }
        async function loadData(data) {
            for (let i = 0; i < data.length; i++) {
                val = data[i];
                var html = '';
                var driverId = '';
                var userId = '';
                if (val.flag == "in_transit") {
                    if (val.hasOwnProperty('driver')) {
                        driverId = val.driver.id;
                    }
                } else {
                    driverId = val.id;
                }
                let driver = await getDriverDetail(driverId);
                if (driver != '' && driver != undefined) {
                    if (mapType == "OFFLINE" ){
                        html += '<div class="live-tracking-box track-from" data-index="' + i + '" data-lat="' + driver.location.latitude + '" data-lng="' + driver.location.longitude + '">';
                    }
                    if (val.flag == "in_transit") {
                        if (val.hasOwnProperty('author')) {
                            userId = val.author.id;
                        }
                        let user = await getUserDetail(val.author.id);
                        if (user != undefined && user != '') {
                            if (mapType != "OFFLINE" ){
                                html += '<div class="live-tracking-box track-from" data-index="' + i + '" data-lat="' + driver.location.latitude + '" data-lng="' + driver.location.longitude + '">';
                            }
                            html += '<div class="live-tracking-inner">';
                            html += '<span class="listicon"></span>';
                            html += '<h3 class="drier-name">{{trans("lang.driver_name")}} :  <span class="dvrname">' + driver.firstName + ' ' + driver.lastName + '</span></h3>';
                            if (user.firstName || user.lastName) {
                                html += '<h4 class="user-name">{{trans("lang.user_name")}} : ' + user.firstName + ' ' + user.lastName + '</h4>';
                            }
                            if (val.author.shippingAddress && val.vendor.location) {
                                html += '<div class="location-ride">';
                                html += '<div class="from-ride"><span>' + val.vendor.location + '</span></div>';
                                destination = val.author.shippingAddress.line1;
                                if (val.author.shippingAddress.line2 != "null" && val.author.shippingAddress.line2 != '') {
                                    destination = destination + "," + val.author.shippingAddress.line2;
                                }
                                if (val.author.shippingAddress.city != "null" && val.author.shippingAddress.city != '') {
                                    destination = destination + "," + val.author.shippingAddress.city;
                                }
                                if (val.author.shippingAddress.country != "null" && val.author.shippingAddress.country != '') {
                                    destination = destination + "," + val.author.shippingAddress.country;
                                }
                                html += '<div class="to-ride"><span>' + destination + '</span></div>';
                                html += '</div>';
                            }
                            html += '<span class="badge badge-danger">In Tranist</span>';
                            html += '&nbsp;&nbsp;<a href="/orders/edit/' + val.id + '" class="badge badge-info" target="_blank">{{trans("lang.order_id")}} : ' + val.id.substring(0, 7) + '</a>';
                            html += '</div>';
                            html += '</div>';
                        }
                    } else {
                        if (driver.firstName || driver.lastName) {
                            if (mapType != "OFFLINE" ){
                               html += '<div class="live-tracking-box track-from" data-lat="' + driver.location.latitude + '" data-lng="' + driver.location.longitude + '">';
                            }
                            html += '<div class="live-tracking-inner">';
                            html += '<span class="listicon"></span>';
                            html += '<h3 class="drier-name">{{trans("lang.driver_name")}} : <span class="dvrname">' + driver.firstName + ' ' + driver.lastName + '</span></h3>';
                            html += '<span class="badge badge-success">Available<span>';
                            html += '</div>';
                            html += '</div>';
                        }
                    }
                }
                $(".live-tracking-list").append(html);
                if (driver != undefined && driver != '') {
                    if (typeof driver.location.latitude != 'undefined' && typeof driver.location.longitude != 'undefined') {
                        let iconImg = '';
                        let position = '';
                        if (val.flag == "available") {
                            iconImg = base_url + '/car_available.png';
                        } else {
                            iconImg = base_url + '/car_on_trip.png';
                        }
                        var content = `
                     <div class="p-2">
                         <h6>{{trans('lang.driver_name')}} : ${driver.firstName + " " + driver.lastName ?? '-'} </h6>
                         <h6>{{trans('lang.phone')}} : ${driver.phoneNumber ?? '-'} </h6>
                     </div>`;
                        if (mapType == "OFFLINE" ){
                            var customIcon = L.icon({
                                iconUrl: iconImg,
                                iconSize: [25, 25],
                            });
                            let marker = L.marker([driver.location.latitude, driver.location.longitude], { icon: customIcon }).addTo(map);
                            marker.bindPopup(content);
                            markers[i] = marker;
                            marker.on('click', function () {
                                marker.openPopup();
                            });
                            setInterval(function () {
                                locationUpdate(marker, driver);
                            }, 10000);
                        } else{
                            let marker = new google.maps.Marker({
                                position: new google.maps.LatLng(driver.location.latitude, driver.location.longitude),
                                icon: {
                                    url: iconImg,
                                    scaledSize: new google.maps.Size(25, 25)
                                },
                                map: map
                            });
                            let infowindow = new google.maps.InfoWindow({
                                content: content
                            });
                            marker.addListener('click', function () {
                                infowindow.open(map, marker);
                            });
                            markers[i] = marker;
                            marker.setMap(map);
                            setInterval(function () {
                                locationUpdate(marker, driver);
                            }, 10000);
                        }
                    }
                }
            }
            async function locationUpdate(marker, driver) {
                database.collection("users").doc(driver.id).get().then((doc) => {
                    let data = doc.data();
                    if(data && data.location && data.location.latitude && data.location.longitude ){
                        if (mapType == "OFFLINE" ){
                            marker.setLatLng([data.location.latitude, data.location.longitude]);
                        } else{
                            marker.setPosition(new google.maps.LatLng(data.location.latitude, data.location.longitude));
                        }
                    }
                });
            }
        }
        async function getUserDetail(userId) {
            if (userId != '') {
                return database.collection("users").doc(userId).get().then((doc) => {
                    return doc.data();
                });
            }
        }
        async function getDriverDetail(driverId) {
            if (driverId != '') {
                return database.collection("users").where('isActive', '==', true).where('id','==',driverId).get().then((querySnapshot) => {
                    if (!querySnapshot.empty) {
                        // Assuming there's only one matching document
                        return querySnapshot.docs[0].data();
                    } else {
                        return null; // No active user found with this driverId
                    }
                });
            }
        }
        function searchDriver() {
            let input = document.getElementById('searchInput').value.trim().toLowerCase(); 
            let driverElements = document.querySelectorAll('.live-tracking-box');
            if (input === "") {
                driverElements.forEach(function(driver) {
                    driver.style.display = 'block'; // Show all drivers
                });
                return; // Exit early if input is blank
            }
            driverElements.forEach(function(driver) {
                let driverNameElement = driver.querySelector('.drier-name .dvrname');
                if (driverNameElement) {
                    let driverName = driverNameElement.textContent.trim().toLowerCase();
                    let driverNames = driverName.split(" ");
                    let firstName = driverNames[0];
                    let lastName = driverNames.slice(1).join(" "); // In case there is a middle name
                    // Check if input matches first name, last name, or full name
                    if (firstName.toLowerCase().includes(input) || lastName.toLowerCase().includes(input) || driverName.includes(input)) {
                        driver.style.display = 'block'; // Show driver
                    } else {
                        driver.style.display = 'none'; // Hide driver
                    }
                }
            });
        }
    </script>
    @endsection
