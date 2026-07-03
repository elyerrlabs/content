@extends('layouts.app')

@push('head')
    <title>Test Admin Manager</title>
@endpush

@push('css')
    @module_vite(['resources/css/app.css', 'resources/js/app.js'])
@endpush

@section('content')
    <x-inertia::app />
@endsection
