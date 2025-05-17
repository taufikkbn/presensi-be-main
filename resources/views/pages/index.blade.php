@extends('layouts.default', ['title' => 'Dashboard', 'cardTitle' => 'Data'])
@section('content')
    {{ auth()->user() }}
@endsection
