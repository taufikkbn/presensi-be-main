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
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">Edit
                Radius</button>
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
    <x-modal title="Tambah Data Lokasi" idModal="addModal">
        <form id="formEditRadius" action="{{ route('perimeter.store') }}" method="POST">
            {{ csrf_field() }}
            <x-input-field label="Radius (Km)" type="number" name="radius"></x-input-field>
            <input type="submit" value="Simpan Data" class="btn btn-primary w-100">
        </form>
    </x-modal>

    <div class="row">
        <div class="col-lg-12">
            <table class="table">
                <tr>
                    <td>Alamat</td>
                    <td>'$perimeter->address'</td>
                </tr>
                <tr>
                    <td>Lat</td>
                    <td>$perimeter->lat</td>
                </tr>
                <tr>
                    <td>Long</td>
                    <td>$perimeter->long</td>
                </tr>
                <tr>
                    <td>Radius</td>
                    <td>$perimeter->radius Km</td>
                </tr>
            </table>
        </div>
        <div class="col-lg-12">
            <div id="map" style="width: 100%; height: 700px"></div>
        </div>
    </div>
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
