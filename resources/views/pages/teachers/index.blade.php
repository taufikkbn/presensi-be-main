@pushOnce('custom-style')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.css') }}">
@endPushOnce

@extends('layouts.default', ['title' => 'Data Guru', 'cardTitle' => ''])
@section('card-title')
    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">Guru Terdaftar</h4>
        <div>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">Tambah
                Data</button>
        </div>
    </div>
@endsection
@php
    $sessionMsg = \Session::get('successMsg') ?? \Session::get('errorMsg');
@endphp

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
    <x-modal title="Tambah Data Guru" idModal="addModal">
        <form action="{{ route('teacher.store') }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <x-input-field label="Nama Lengkap" type="text" name="name"></x-input-field>
            <x-input-field label="Email" type="email" name="email"></x-input-field>
            <x-select-field label="Kelas" name="class_id" :options="$classrooms"></x-select-field>
            <x-input-field label="Photo" type="file" name="photo"></x-input-field>
            <input type="submit" value="Simpan Data" class="btn btn-primary w-100">
        </form>
    </x-modal>


    <table class="table" id="table1">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Lengkap</th>
                <th>Kelas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->classroom->class_name }} - {{ $item->classroom->type }}</td>
                    <td>
                        <button data-id="{{ $item->id }}"
                            class="deleteButton btn btn-sm btn-outline-danger">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@pushOnce('custom-script')
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
    <script src="{{ asset('assets/js/pages/datatables.js') }}"></script>
    <script src="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/js/components/delete-dialog.js') }}"></script>
    <script>
        $(document).ready(function() {
            let csrf = "{{ csrf_token() }}";

            $(".deleteButton").on("click", function(e) {
                e.preventDefault();
                let teacherId = $(this).data("id");
                let url = `{{ route('teacher.destroy', ['teacher' => ':teacher']) }}`;
                url = url.replace(":teacher", teacherId);
                deleteDialog(teacherId, url, csrf);
            });
        });
    </script>
@endPushOnce
