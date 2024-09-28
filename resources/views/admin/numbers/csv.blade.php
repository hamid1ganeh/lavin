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
                            {{ Breadcrumbs::render('numbers.csv') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-file-import page-icon"></i>
                            درون ریزی فایل CSV
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body" style="padding:70px;">

                            <form method="POST" action="{{ route('admin.numbers.import') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row mt-2">
                                    <div class="col-12 text-center">
                                        <div class="fileupload btn btn-success waves-effect waves-light m-4">
                                            <span><i class="mdi mdi-cloud-upload mr-1"></i>فایل CSV</span>
                                            <input type="file" class="csv" name="csv" value="" accept=".csv">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('csv') }} </span>
                                        </div>
                                    </div>
                                </div>
                        
                                <div class="form-group row">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-primary">درون ریزی</button>
                                    </div>
                                </div>

                            </form>
                        </div>

                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div>
    </div>
</div>
 
@endsection

