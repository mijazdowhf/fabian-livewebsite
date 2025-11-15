@extends('admin.layouts.master')
@section('content')
@php
    $sidenav = file_get_contents(resource_path('views/agent/partials/sidenav.json'));
@endphp
    <div class="page-wrapper default-version">
        @include('agent.partials.sidenav')
        @include('agent.partials.topnav')

        <div class="container-fluid px-3 px-sm-0">
            <div class="body-wrapper">
                <div class="bodywrapper__inner">
                    @yield('panel')
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <style>
        .btn--base {
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
        }
        .btn--base:hover {
            filter: brightness(0.95);
        }
    </style>
@endpush


