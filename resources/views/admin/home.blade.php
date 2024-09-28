@extends('admin.master')

@section('content')

<div class="content-page">

    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

        <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0 IR">
                                {{ Breadcrumbs::render('home') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-home page-icon"></i>
                              صفحه اصلی
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                    @foreach ($errors->all() as $error)
                        <li class="IR">{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="{{url('/') }}/images/front/logo.png" alt="درمانگاه لاوین" class="w-50">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
