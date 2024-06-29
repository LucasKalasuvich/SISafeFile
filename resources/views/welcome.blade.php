@extends('layouts.app') @section('content') <div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        {{-- <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Basic Card</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="index.html">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="#">Card</a>
                                </li>
                                <li class="breadcrumb-item active">Basic Card </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <h4>Selamat datang, <b>{{ Auth::user()->name }}</b></h4>
    </div>
</div> @endsection