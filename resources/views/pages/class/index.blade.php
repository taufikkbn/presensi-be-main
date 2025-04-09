@pushOnce('custom-style')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.css') }}">
@endPushOnce

@php
    $options = [
        'pagi' => 'Pagi',
        'siang' => 'Siang',
    ];
    $sessionMsg = \Session::get('successMsg') ?? \Session::get('errorMsg');
@endphp
@extends('layouts.default', ['title' => 'Data Kelas', 'cardTitle' => ''])


@section('card-title')
    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">Kelas Terdaftar</h4>
        <div>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">Tambah
                Data</button>
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
    <x-modal title="Tambah Data Kelas" idModal="addModal">
        <form action="{{ route('class.store') }}" method="POST">
            {{ csrf_field() }}
            <x-input-field label="Nama Kelas" type="text" name="class_name"></x-input-field>
            <x-select-field label="Tipe Kelas" name="type" :options="$options"></x-select-field>
            <input type="submit" value="Simpan Data" class="btn btn-primary w-100">
        </form>
    </x-modal>

    <x-modal title="Edit Data Kelas" idModal="editModal">
        <form action="" method="POST" id="editForm">
            {{ csrf_field() }}
            @method('PATCH')
            <x-input-field label="ID Kelas" type="text" name="class_id" isReadOnly="true"></x-input-field>
            <x-input-field label="Nama Kelas" type="text" name="class_name_edit"></x-input-field>
            <x-select-field label="Tipe Kelas" name="type_class_edit" :options="$options"></x-select-field>
            <input type="submit" value="Simpan Data" class="btn btn-primary w-100">
        </form>
    </x-modal>
    <table class="table" id="table1">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Kelas</th>
                <th>Tipe Kelas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->class_name }}</td>
                    <td class="text-capitalize">{{ $item->type }}</td>
                    <td>
                        <button data-id="{{ $item->id }}" class="editButton btn btn-sm btn-outline-success"
                            data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
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
            $(document).ready(function() {
                let csrf = "{{ csrf_token() }}";
                $(".editButton").on("click", function(e) {
                    e.preventDefault();
                    let class_id = $(this).data("id");
                    let url = `{{ route('class.edit', ['class' => ':class']) }}`;
                    url = url.replace(":class", class_id);
                    let options = [{
                            value: "pagi",
                            label: "Pagi",
                        },
                        {
                            value: "siang",
                            label: "Siang",
                        },
                    ];
                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function(response) {
                            $("#class_id").val(response.id);
                            $("#class_name_edit").val(response.class_name);
                            $("#type_class_edit").empty();
                            $("#type_class_edit").append(
                                $("<option>", {
                                    value: response.type,
                                    text: response.type,
                                    selected: true,
                                })
                            );
                            $.each(options, function(index, data) {
                                $("#type_class_edit").append(
                                    $("<option>", {
                                        value: data.value,
                                        text: data.label,
                                        selected: false,
                                    })
                                );
                            });
                            let updateUrl =
                                `{{ route('class.update', ['class' => ':classId']) }}`;
                            updateUrl = updateUrl.replace(":classId", response.id);
                            $("#editForm").attr("action", updateUrl);
                        },
                        error: function(err) {
                            console.log(err);
                        },
                    });
                });
                $(".deleteButton").on("click", function(e) {
                    e.preventDefault();
                    let class_id = $(this).data("id");
                    let url = `{{ route('class.destroy', ['class' => ':class']) }}`;
                    url = url.replace(":class", class_id);
                    deleteDialog(class_id, url, csrf);
                });
            });

        });
    </script>
@endPushOnce
