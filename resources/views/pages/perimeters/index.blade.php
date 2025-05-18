@pushOnce('custom-style')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.css') }}">
@endPushOnce

@php
    $sessionMsg = \Session::get('successMsg') ?? \Session::get('errorMsg');
@endphp

@extends('layouts.default', ['title' => 'Setting - Lokasi Presensi', 'cardTitle' => ''])

@section('card-title')
    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">Lokasi Presensi</h4>
        <div>
            <button
                type="button"
                class="btn btn-primary btn-sm"
                data-address="{{ $perimeter->address }}"
                data-lat="{{ $perimeter->lat }}"
                data-long="{{ $perimeter->long }}"
                data-radius="{{ $perimeter->radius }}"
                data-bs-toggle="modal"
                data-bs-target="#editModal">
                Edit Radius
            </button>
        </div>
    </div>
@endsection

@if ($sessionMsg)
    @section('alert-section')
        <div class="alert alert-{{ \Session::has('successMsg') ? 'success' : 'danger' }} alert-dismissible fade show"
             role="alert">
            {{ $sessionMsg }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endsection
@endif

@section('content')
    <x-modal title="Tambah Data Lokasi" idModal="editModal">
        <form id="formEditRadius" action="{{ route('perimeter.store') }}" method="POST">
            {{ csrf_field() }}
            <x-textarea-field label="Address" name="address" rows="4"></x-textarea-field>
            <x-input-field label="Latitude" type="number" name="lat" step="any" min="-90" max="90"/>
            <x-input-field label="Longitude" type="number" name="long" step="any" min="-180" max="180"/>
            <x-input-field label="Radius (Km)" type="number" name="radius" step="any" min="0"/>
            <input type="submit" value="Simpan Data" class="btn btn-primary w-100">
        </form>
    </x-modal>

    <div class="row">
        <div class="col-lg-12">
            <table class="table">
                <tr>
                    <td>Alamat</td>
                    <td>{{ $perimeter->address }}</td>
                </tr>
                <tr>
                    <td>Lat</td>
                    <td>{{ $perimeter->lat }}</td>
                </tr>
                <tr>
                    <td>Long</td>
                    <td>{{ $perimeter->long }}</td>
                </tr>
                <tr>
                    <td>Radius</td>
                    <td>{{ $perimeter->radius }} KM</td>
                </tr>
            </table>
        </div>
        <div class="col-lg-12">
            <div id="map" style="width: 100%; height: 700px"></div>
        </div>
    </div>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Draggable Map with Radius</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            #map {
                height: 600px;
                width: 100%;
            }

            .controls {
                margin-top: 10px;
                border: 1px solid transparent;
                border-radius: 2px 0 0 2px;
                box-sizing: border-box;
                -moz-box-sizing: border-box;
                height: 32px;
                outline: none;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            }

            #radius-control {
                background-color: #fff;
                padding: 10px;
                margin: 10px;
                max-width: 300px;
            }
        </style>
    </head>
    <body>
    {{--    <div id="radius-control">--}}
    {{--        <label for="radius">Radius (meters):</label>--}}
    {{--        <input type="range" id="radius" min="100" max="5000" step="100" value="{{ $defaultRadius }}">--}}
    {{--        <span id="radius-value">{{ $defaultRadius }}</span> meters--}}
    {{--    </div>--}}
    <div id="map"></div>

    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAuRgkpiJkMEGRX2ohxIOkwn8SZ-9wrr_M&libraries=places"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    {{-- Edit Modal Script --}}
    <script>
        $('#editModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget); // Button yang diklik

            const address = button.data('address');
            const lat = button.data('lat');
            const long = button.data('long');
            const radius = button.data('radius');

            console.log(address, lat, long, radius);

            var modal = $(this);

            modal.find('textarea[name="address"]').val(address);
            modal.find('input[name="lat"]').val(lat);
            modal.find('input[name="long"]').val(long);
            modal.find('input[name="radius"]').val(radius);
        });
    </script>


    {{--  Google Maps Script  --}}
    <script>
        let map;
        let marker;
        let circle;
        let defaultLat = {{ $perimeter->lat }};
        let defaultLng = {{ $perimeter->long }};
        let defaultRadius = {{ $perimeter->radius }};

        function initMap() {
            const initialPosition = {lat: defaultLat, lng: defaultLng};

            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 20,
                center: initialPosition,
            });

            // Create the initial marker
            marker = new google.maps.Marker({
                position: initialPosition,
                map: map,
                draggable: false, // Make the marker draggable
                title: "Drag me!"
            });

            // Create the initial circle
            circle = new google.maps.Circle({
                strokeColor: "#FF0000",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "#FF0000",
                fillOpacity: 0.35,
                map: map,
                center: initialPosition,
                radius: defaultRadius
            });

            // Update circle when marker is dragged
            marker.addListener("drag", function (event) {
                updateCircle(event.latLng);
            });

            // Update circle when marker drag ends
            marker.addListener("dragend", function (event) {
                updateCircle(event.latLng);
                saveLocation(event.latLng.lat(), event.latLng.lng(), circle.getRadius());
            });

            // Update circle when radius changes
            document.getElementById("radius").addEventListener("input", function () {
                const radius = parseInt(this.value);
                document.getElementById("radius-value").textContent = radius;
                circle.setRadius(radius);
                saveLocation(marker.getPosition().lat(), marker.getPosition().lng(), radius);
            });
        }

        function updateCircle(position) {
            circle.setCenter(position);
        }

        function saveLocation(lat, lng, radius) {
            $.ajax({
                url: "{{ route('perimeter.store') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    latitude: lat,
                    longitude: lng,
                    radius: radius
                },
                success: function (response) {
                    console.log("Location saved:", response);
                },
                error: function (xhr) {
                    console.error("Error saving location:", xhr.responseText);
                }
            });
        }

        // Initialize the map
        initMap();
    </script>
    </body>
    </html>
@endsection

{{--@pushOnce('custom-script')--}}
{{--    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>--}}
{{--    <script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>--}}
{{--    <script src="{{ asset('assets/js/pages/datatables.js') }}"></script>--}}
{{--    <script src="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>--}}
{{--    <script src="{{ asset('assets/js/components/delete-dialog.js') }}"></script>--}}
{{--    <link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />--}}
{{--    <script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>--}}
{{--    <script src='https://unpkg.com/@turf/turf@6/turf.min.js'></script>--}}

{{--    <script>--}}
{{--        mapboxgl.accessToken =--}}
{{--            'pk.eyJ1Ijoic2FrYXJhZ3VuYSIsImEiOiJjbGpscDk4cm4wNm52M29yMTB2b2hvOTFsIn0.3kK1ddejis-F5FEE7RQtJQ';--}}
{{--        const map = new mapboxgl.Map({--}}
{{--            container: 'map',--}}
{{--            style: 'mapbox://styles/mapbox/streets-v12',--}}
{{--            center: [{{ $perimeter->long }}, {{ $perimeter->lat }}],--}}
{{--            zoom: 12--}}
{{--        });--}}

{{--        const marker1 = new mapboxgl.Marker()--}}
{{--            .setLngLat([{{ $perimeter->long }}, {{ $perimeter->lat }}])--}}
{{--            .addTo(map);--}}

{{--        // Menghitung radius dalam meter--}}
{{--        const radiusInKilometers = {{ $perimeter->radius }};--}}
{{--        const radiusInMeters = radiusInKilometers * 1000;--}}

{{--        // Menggambar lingkaran dengan radius di peta--}}
{{--        const center = turf.point([{{ $perimeter->long }}, {{ $perimeter->lat }}]);--}}
{{--        const options = {--}}
{{--            steps: 64,--}}
{{--            units: 'meters',--}}
{{--            properties: {}--}}
{{--        };--}}
{{--        const circle = turf.circle(center, radiusInMeters, options);--}}
{{--        const circleLayerId = 'circle-layer';--}}

{{--        map.on('load', function() {--}}
{{--            map.addSource('circle-source', {--}}
{{--                type: 'geojson',--}}
{{--                data: circle--}}
{{--            });--}}

{{--            map.addLayer({--}}
{{--                id: circleLayerId,--}}
{{--                type: 'fill',--}}
{{--                source: 'circle-source',--}}
{{--                paint: {--}}
{{--                    'fill-color': '#0080ff',--}}
{{--                    'fill-opacity': 0.4--}}
{{--                }--}}
{{--            });--}}
{{--        });--}}

{{--        // Menghapus lingkaran saat peta diklik ulang--}}
{{--        map.on('click', function(e) {--}}
{{--            if (map.getLayer(circleLayerId)) {--}}
{{--                map.removeLayer(circleLayerId);--}}
{{--            }--}}
{{--            if (map.getSource('circle-source')) {--}}
{{--                map.removeSource('circle-source');--}}
{{--            }--}}
{{--        });--}}
{{--    </script>--}}

{{--    <script>--}}
{{--        $(document).ready(function() {--}}
{{--            $("#formEditRadius").submit(function(e) {--}}
{{--                e.preventDefault(); // Mencegah form submit biasa--}}
{{--                $.ajax({--}}
{{--                    type: 'POST',--}}
{{--                    url: $(this).attr('action'),--}}
{{--                    data: new FormData(this),--}}
{{--                    processData: false,--}}
{{--                    contentType: false,--}}
{{--                    success: function(response) {--}}
{{--                        alert('Data berhasil disimpan');--}}
{{--                        location.reload();--}}
{{--                    },--}}
{{--                    error: function(response) {--}}
{{--                        // Tampilkan alert error--}}
{{--                        alert('Terjadi kesalahan: ' + response.responseJSON);--}}
{{--                        console.log(response.responseJSON.message)--}}
{{--                    }--}}
{{--                });--}}
{{--            })--}}
{{--        });--}}
{{--    </script>--}}
{{--@endpushOnce--}}
