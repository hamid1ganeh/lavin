@extends('crm.master')

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
{{--                            {{ Breadcrumbs::render('ask.analysis') }}--}}
                        </ol>
                        </div>
                        <h4 class="page-title">
                            <i class="fa fa-spinner page-icon"></i>
                             درخواست آنالیز
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                        @if ($errors->any())
                            <div class="row">
                                <div class="col-12 alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li class="IR">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif



                            <div class="table-responsive">
                                <table id="tech-companies-1" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th><b class="IRANYekanRegular">ردیف</b></th>
                                        <th><b class="IRANYekanRegular">نام و نام خانوادگی</b></th>
                                        <th><b class="IRANYekanRegular">شماره تماس</b></th>
                                        <th><b class="IRANYekanRegular">عنوان سرویس آنالیز</b></th>
                                        <th><b class="IRANYekanRegular">پزشک آنالیز</b></th>
                                        <th><b class="IRANYekanRegular">مبلغ مورد نظر(تومان)</b></th>
                                        <th><b class="IRANYekanRegular">زمان درخواست آنالیز</b></th>
                                        <th><b class="IRANYekanRegular">وضعیت</b></th>
                                        <th><b class="IRANYekanRegular">اقدامات</b></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($asks as $index=>$ask)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $ask->user->getFullName() ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $ask->user->mobile ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $ask->analyse->title ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $ask->doctor->fullname ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $ask->price ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $ask->ask_date_time() ?? '' }}</strong></td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    @if($ask->status == App\Enums\AnaliseStatus::pending)
                                                        <span class="badge badge-warning IR p-1">در انتظار</span>
                                                    @elseif($ask->status == App\Enums\AnaliseStatus::doctor)
                                                        <span class="badge badge-info IR p-1">ارجاع به پزشک</span>
                                                    @elseif($ask->status == App\Enums\AnaliseStatus::response)
                                                        <span class="badge badge-success IR p-1">پاسخ پزشک</span>
                                                    @elseif($ask->status == App\Enums\AnaliseStatus::reject)
                                                        <span class="badge badge-danger IR p-1">رد شده</span>
                                                    @elseif($ask->status == App\Enums\AnaliseStatus::accept)
                                                        <span class="badge badge-primary IR p-1">تایید شده</span>
                                                    @endif
                                                </strong>
                                            </td>
                                            <td>
                                                <a class="font18 m-1" href="{{ route('website.account.analysis.show',$ask) }}" title="پاسخ پزشک">
                                                    <i class="fa fa-eye text-success"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {{ $asks->render() }}
                            </div>

                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div>
    </div>
</div>
@endsection

