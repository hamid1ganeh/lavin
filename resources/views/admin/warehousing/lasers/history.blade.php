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
                                {{ Breadcrumbs::render('warehousing.lasers.tube.history',$laser) }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <i class="fas fa-deaf page-icon"></i>
                             تاریخچه تعویض تیوب دستگاه لیزر {{ $laser->name }}
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tech-companies-1" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th><b class="IRANYekanRegular">ردیف</b></th>
                                        <th><b class="IRANYekanRegular">نام تیوب</b></th>
                                        <th><b class="IRANYekanRegular">برند تیوب</b></th>
                                        <th><b class="IRANYekanRegular">شات</b></th>
                                        <th><b class="IRANYekanRegular">ضایعات تیوب قبلی</b></th>
                                        <th><b class="IRANYekanRegular">توضیحات</b></th>
                                        <th><b class="IRANYekanRegular">زمان تعویض</b></th>
                                        <th><b class="IRANYekanRegular">تعویض کننده</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($histories as $index=>$history)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $history->good_title }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $history->good_brand }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $history->shot }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $history->waste }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $history->description }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $history->createdAt()}}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $history->changedBy->fullname }}</strong></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
