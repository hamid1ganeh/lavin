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
                            {{ Breadcrumbs::render('receptions') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="ti-pencil-alt page-icon"></i>
                             پذیرش
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                                <div class="row">
                                    <div class="form-group justify-content-center col-12 col-md-4">
                                        <form>
                                        <div class="form-group justify-content-center col-12">
                                            <label for="mobile-filter" class="control-label IRANYekanRegular">موبایل</label>
                                            <input type="text"  class="form-control input text-right" id="mobile-filter" name="mobile" placeholder="موبایل را وارد کنید" value="{{ request('mobile') }}">
                                        </div>

                                        <div class="form-group justify-content-center col-12">
                                            <label for="nation_code-filter" class="control-label IRANYekanRegular">شماره ملی</label>
                                            <input type="text"  class="form-control input text-right" id="nation_code-filter" name="nation_code" placeholder=" شماره ملی  را وارد کنید" value="{{ request('nation_code') }}">
                                        </div>


                                        <div class="form-group justify-content-center col-12">
                                            <label for="code-filter" class="control-label IRANYekanRegular">کد مراجعه</label>
                                            <input type="text"  class="form-control input text-right" id="code-filter" name="code" placeholder=" کد مراجعه  را وارد کنید" value="{{ request('code') }}">
                                        </div>


                                        <div class="form-group justify-content-center col-12">
                                            <button type="submit" class="btn btn-info  cursor-pointer">
                                                <i class="fa fa-search fa-sm"></i>
                                                <span class="pr-2">جستجو</span>
                                            </button>
                                            <a onclick="reset()" class="btn btn-light border border-secondary cursor-pointer">
                                                <i class="fas fa-undo fa-sm"></i>
                                                <span class="pr-2">پاک کردن</span>
                                            </a>
                                        </div>

                                        <script>
                                            function reset()
                                            {
                                                document.getElementById("mobile-filter").value = "";
                                                document.getElementById("nation_code-filter").value = "";
                                                document.getElementById("code-filter").value = "";
                                            }
                                        </script>
                                        </form>
                                    </div>


                                    <div class="form-group justify-content-center col-12 col-md-8">

                                        <div class="table-responsive">
                                            <table id="tech-companies-1" class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th><b class="IRANYekanRegular">ردیف</b></th>
                                                    <th><b class="IRANYekanRegular">نام نام خانوادگی</b></th>
                                                    <th><b class="IRANYekanRegular">کدملی</b></th>
                                                    <th><b class="IRANYekanRegular">شماره موبایل</b></th>
                                                    <th><b class="IRANYekanRegular">کدمراجعه</b></th>
                                                    <th><b class="IRANYekanRegular">مسئول پذیرش</b></th>
                                                    <th><b class="IRANYekanRegular">وضعیت مراجعه</b></th>
                                                    <th style="width:200px;"><b class="IRANYekanRegular">اقدامات</b></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($receptions as $index=>$reception)
                                                    <tr>
                                                        <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                                        <td><strong class="IRANYekanRegular">
                                                                @if($reception->user)
                                                                    {{ $reception->user->firstname.' '.$reception->user->lastname }}
                                                                @endif
                                                            </strong>
                                                        </td>
                                                        <td><strong class="IRANYekanRegular">{{ $reception->user->nationcode }}</strong></td>
                                                        <td><strong class="IRANYekanRegular">{{ $reception->user->mobile }}</strong></td>
                                                        <td><strong class="IRANYekanRegular">{{ $reception->code }}</strong></td>
                                                        <td><strong class="IRANYekanRegular">{{ $reception->reception->fullname }}</strong></td>
                                                        <td>

                                                            @if($reception->end)
                                                                @if(is_null($reception->hasOpenReferCode()) &&  Auth::guard('admin')->user()->can('reception.start'))
                                                                <a class="dropdown-item IR cusrsor" href="#start{{ $reception->id }}" data-toggle="modal"  title="باز کردن مراجعه" style="display: contents">
                                                                    <i class="ti-arrow-circle-right text-warning"></i>
                                                                </a>
                                                                @endif
                                                                <strong class="IRANYekanRegular">پایان مراجعه</strong>
                                                                <div class="modal fade" id="start{{ $reception->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-xs">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header py-3">
                                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">پایان مراجعه</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body text-center">
                                                                                <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید این مراجعه را مجداد باز کنید؟</h5>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <form action="{{ route('admin.receptions.start',$reception) }}"  method="POST" class="d-inline">
                                                                                    @csrf
                                                                                    @method('PATCH')
                                                                                    <button type="submit" title="پایان" class="btn btn-info px-8">باز</button>
                                                                                </form>
                                                                                &nbsp;
                                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                @if(Auth::guard('admin')->user()->can('reception.end'))
                                                                <a class="dropdown-item IR cusrsor" href="#end{{ $reception->id }}" data-toggle="modal"  title="پایان مراجعه" style="display: contents">
                                                                    <i class="mdi mdi-logout text-dark"></i>
                                                                </a>
                                                                @endif
                                                                <strong class="IRANYekanRegular">باز</strong>

                                                                <div class="modal fade" id="end{{ $reception->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-xs">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header py-3">
                                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">پایان مراجعه</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body text-center">
                                                                                <h5 class="IRANYekanRegular">آیا مطمئن هستید که این مراجعه پایان یافته است؟</h5>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <form action="{{ route('admin.receptions.end',$reception) }}"  method="POST" class="d-inline">
                                                                                    @csrf
                                                                                    @method('PATCH')
                                                                                    <button type="submit" title="پایان" class="btn btn-info px-8">پایان</button>
                                                                                </form>
                                                                                &nbsp;
                                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif


                                                        </td>
                                                        <td>
                                                            <a href="{{ route('admin.reserves.index',['code'=>$reception->code]) }}"  target="_blank" class="btn btn-icon" title="نمایش روزرها">
                                                                <i class="fa fa-eye text-success font-16"></i>
                                                            </a>

                                                        </td>
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

        </div>
    </div>
@endsection
