@php
    $gender = [
        'L' => 'Laki-Laki',
        'P' => 'perempuan',
    ];
    $religion = [
        'islam' => 'Islam',
        'katolik' => 'Katolik',
        'protestan' => 'Protestan',
        'hindu' => 'Hindu',
        'budha' => 'Budha',
        'konghucu' => 'Konghucu',
    ];
    $sessionMsg = \Session::get('successMsg') ?? \Session::get('errorMsg');
@endphp
@pushOnce('custom-style')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.css') }}">
@endPushOnce

@extends('layouts.default', ['title' => 'Data Siswa', 'cardTitle' => ''])

@section('card-title')
    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">Siswa Terdaftar</h4>
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
    <x-modal title="Tambah Data Siswa" idModal="addModal" modalType="modal-lg">
        <form id="studentForm" action="{{ route('student.store') }}" method="POST" enctype="multipart/form-data">

            {{ csrf_field() }}
            <x-input-field label="NIPD" type="number" name="nipd"></x-input-field>
            <x-input-field label="Email Siswa" type="email" name="email"></x-input-field>
            <x-input-field label="Nama Siswa" type="text" name="name"></x-input-field>
            <x-select-field label="Kelas" name="class_id" :options="$classrooms"></x-select-field>
            <x-select-field label="Jenis Kelamin" name="gender" :options="$gender"></x-select-field>
            <div class="row">
                <div class="col-6">
                    <x-input-field label="Tempat Lahir" type="text" name="pob"></x-input-field>
                </div>
                <div class="col-6">
                    <x-input-field label="Tanggal Lahir" type="date" name="dob"></x-input-field>
                </div>
            </div>
            <x-select-field label="Agama" name="religion" :options="$religion"></x-select-field>
            <x-text-area-field label="Alamat Lengkap" name="address"></x-text-area-field>
            <x-input-field label="Jenis Tinggal" type="text" name="residence_type"></x-input-field>
            <x-input-field label="Photo" type="file" name="photo"></x-input-field>
            <div class="mb-3">
                <label class="form-label">Preview</label>
                <img src="https://puprpkpp.riau.go.id/asset/img/default-image.png" id="imgPreview" class="img-fluid">
            </div>
            <input type="submit" value="Simpan Data" class="btn btn-primary w-100">
        </form>
    </x-modal>

    <x-modal title="Detail Siswa" idModal="detailModal" modalType="">
        <div>
            <img src="" alt="" id="photoDetail" class="img-fluid">
        </div>
        <table class="table">
            <tr>
                <td>NIPD</td>
                <td class="font-bold"><span id="nipdDetail"></span></td>
            </tr>
            <tr>
                <td>Email</td>
                <td class="font-bold"><span id="emailDetail"></span></td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td class="font-bold text-capitalize"><span id="classDetail"></span></td>
            </tr>
            <tr>
                <td>Nama Lengkap</td>
                <td class="font-bold text-capitalize"><span id="nameDetail"></span></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td class="font-bold text-capitalize"><span id="genderDetail"></span></td>
            </tr>
            <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td class="font-bold text-capitalize"><span id="dobDetail"></span></td>
            </tr>
            <tr>
                <td>Agama</td>
                <td class="font-bold text-capitalize"><span id="religionDetail"></span></td>
            </tr>
            <tr>
                <td>Alamat Lengkap</td>
                <td class="font-bold text-capitalize"><span id="addressDetail"></span></td>
            </tr>
            <tr>
                <td>Jenis Tinggal</td>
                <td class="font-bold text-capitalize"><span id="residence_typeDetail"></span></td>
            </tr>
        </table>
    </x-modal>

    <x-modal title="Edit Data Siswa" idModal="editModal" modalType="modal-lg">
        <form id="editStudentForm" action="{{ route('student.store') }}" method="POST" enctype="multipart/form-data">

            {{ csrf_field() }}
            @method('PATCH')
            <x-input-field label="NIPD" type="number" name="nipdEdit"></x-input-field>
            <input type="hidden" id="nipdDefault">
            <x-input-field label="Email Siswa" type="email" name="emailEdit"></x-input-field>
            <x-input-field label="Nama Siswa" type="text" name="nameEdit"></x-input-field>
            <x-select-field label="Kelas" name="class_idEdit" :options="$classrooms"></x-select-field>
            <x-select-field label="Jenis Kelamin" name="genderEdit" :options="$gender"></x-select-field>
            <div class="row">
                <div class="col-6">
                    <x-input-field label="Tempat Lahir" type="text" name="pobEdit"></x-input-field>
                </div>
                <div class="col-6">
                    <x-input-field label="Tanggal Lahir" type="date" name="dobEdit"></x-input-field>
                </div>
            </div>
            <x-select-field label="Agama" name="religionEdit" :options="$religion"></x-select-field>
            <x-text-area-field label="Alamat Lengkap" name="addressEdit"></x-text-area-field>
            <x-input-field label="Jenis Tinggal" type="text" name="residence_typeEdit"></x-input-field>
            <x-input-field label="Photo" type="file" name="photoEdit"></x-input-field>
            <div class="mb-3 text-center">
                <label class="form-label">Preview</label>
                <img src="https://puprpkpp.riau.go.id/asset/img/default-image.png" id="imgPreviewEdit" class="img-fluid">
                <button class="text-danger btn d-none" id="cancelChangeImage">Klik Untuk Batal Mengganti Foto</button>
            </div>

            <input type="submit" value="Simpan Data" class="btn btn-primary w-100">
        </form>
    </x-modal>

    <table class="table" id="table1">
        <thead>
            <tr>
                <th>#</th>
                <th>NIPD</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->nipd }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->classroom->class_name }} - {{ $student->classroom->type }}</td>
                    <td>
                        <button data-id="{{ $student->nipd }}" class="detailButton btn btn-sm btn-outline-primary"
                            data-bs-toggle="modal" data-bs-target="#detailModal">Detail</button>
                        <button data-id="{{ $student->nipd }}" class="editButton btn btn-sm btn-outline-success"
                            data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                        <button data-id="{{ $student->nipd }}"
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
            let imageUrlEdit;
            $("#photo").on('change', function() {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $('#imgPreview').attr('src', event.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            })

            $('#studentForm').submit(function(e) {
                e.preventDefault(); // Mencegah form submit biasa

                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert('Data berhasil disimpan');
                        $('#studentForm')[0].reset();
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Try to get the response text or JSON
                        let errorMessage = 'Terjadi kesalahan';

                        if (xhr.responseJSON) {
                            // If server returns JSON response
                            errorMessage += ': ' + xhr.responseJSON.message;
                        } else if (xhr.responseText) {
                            // If server returns text response
                            errorMessage += ': ' + xhr.responseText;
                        } else {
                            errorMessage += ': ' + error;
                        }

                        alert(errorMessage);
                    }
                });
            });

            $('.detailButton').on('click', function(e) {
                let student_id = $(this).data("id");
                let url = `{{ route('student.show', ['student' => ':student']) }}`;
                url = url.replace(":student", student_id);
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {
                        $("#nipdDetail").text(response.nipd)
                        $("#emailDetail").text(response.email)
                        $("#classDetail").text(response.classroom)
                        $("#nameDetail").text(response.name)
                        $("#genderDetail").text(response.gender)
                        $("#dobDetail").text(response.pob_dob)
                        $("#religionDetail").text(response.religion)
                        $("#addressDetail").text(response.address)
                        $("#residence_typeDetail").text(response.residence_type)
                        $("#photoDetail").attr('src', response.photo)
                    }
                });
            })

            $(".deleteButton").on("click", function(e) {
                e.preventDefault();
                let studentId = $(this).data("id");
                let url = `{{ route('student.destroy', ['student' => ':student']) }}`;
                url = url.replace(":student", studentId);
                deleteDialog(studentId, url, csrf);
            });

            $(".editButton").on("click", function(e) {
                e.preventDefault();
                let studentId = $(this).data("id");
                let url = `{{ route('student.show', ['student' => ':student']) }}`;
                url = url.replace(":student", studentId);



                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {
                        console.log(response)
                        $("#nipdEdit").val(response.nipd);
                        $("#nipdDefault").val(response.nipd);
                        $("#emailEdit").val(response.email);
                        $("#class_idEdit").empty();
                        $("#class_idEdit").append(
                            $("<option>", {
                                value: response.classroom_detail
                                    .classroom_id,
                                text: response.classroom_detail
                                    .classroom_name + '-' + response
                                    .classroom_detail.classroom_type,
                                selected: true,
                            })
                        );
                        $.each(response.classroom_detail.other_classroom, function(index,
                            value) {
                            $("#class_idEdit").append(
                                $("<option>", {
                                    value: value.id,
                                    text: value.class_name + '-' + value.type,
                                    selected: false,
                                })
                            );
                        });
                        $("#nameEdit").val(response.name);
                        $("#genderEdit").empty()
                        $("#genderEdit").append(
                            $("<option>", {
                                value: response.gender_value,
                                text: response.gender,
                                selected: true
                            })
                        )
                        $.each(response.gender_data, function(index, value) {
                            $("#genderEdit").append(
                                $("<option>", {
                                    value: index,
                                    text: value,
                                    selected: false
                                })
                            )
                        });

                        $("#dobEdit").val(response.dob);
                        $("#pobEdit").val(response.pob)
                        $("#religionEdit").empty();
                        $("#religionEdit").append(
                            $("<option>", {
                                value: response.religion,
                                text: response.religion,
                                selected: true
                            })
                        )
                        $.each(response.religion_detail, function(index, value) {
                            $("#religionEdit").append(
                                $("<option>", {
                                    value: index,
                                    text: value,
                                    selected: false
                                })
                            )
                        });
                        $("#addressEdit").val(response.address);
                        $("#residence_typeEdit").val(response.residence_type);
                        $("#imgPreviewEdit").attr('src', response.photo);
                        imageUrlEdit = response.photo
                    }
                });
            })

            $("#photoEdit").on('change', function() {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $('#imgPreviewEdit').attr('src', event.target.result);
                        $("#cancelChangeImage").removeClass('d-none')
                    }
                    reader.readAsDataURL(file);
                }
            })

            $("#cancelChangeImage").on('click', function(e) {
                e.preventDefault();
                $("#photoEdit").val('')
                $('#imgPreviewEdit').attr('src', imageUrlEdit);
                $("#cancelChangeImage").addClass('d-none')
            })

            $('#editStudentForm').submit(function(e) {
                e.preventDefault(); // Mencegah form submit biasa
                let student_id = $("#nipdDefault").val();
                console.log($("#nipdDefault").val())
                let url = `{{ route('student.update', ['student' => ':student']) }}`;
                url = url.replace(":student", student_id);
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert('Data berhasil diupdate');
                        location.reload();
                        console.log(response)
                    },
                    error: function(response) {
                        // Tampilkan alert error
                        alert('Terjadi kesalahan: ' + response.responseJSON.message);
                        console.log(response)
                    }
                });
            });
        });
    </script>
@endPushOnce
