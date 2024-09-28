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
                                {{ Breadcrumbs::render('admins.staff.documents.personal',$admin) }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <img   width="30px" src="{{ url('images/front/personal-info.png') }}" alt="مشخصات فردی">
                            مشخصات فردی {{ $admin->fullname }}
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
                        <div class="card-body">

                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label for="fullname" class="IRANYekanRegular">نام نام خانوادگی:</label>
                                        <input id="fullname" type="text" class="form-control" name="fullname"  value="{{ $admin->fullname }}" placeholder="نام و نام خانوادگی" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="mobile" class="IRANYekanRegular">موبایل:</label>
                                        <input id="mobile" type="text" class="form-control ltr" name="mobile"  value="{{$admin->mobile }}" placeholder="موبایل" readonly>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label for="nationalcode" class="IRANYekanRegular">کدملی:</label>
                                        <input id="nationalcode" type="text" class="form-control ltr" name="nationalcode"  value="{{  $admin->nationalcode }}" placeholder="کدملی" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="IRANYekanRegular">آدرس ایمیل:</label>
                                        <input id="email" type="text" class="form-control ltr ltr" name="email"  value="{{ $admin->email }}" placeholder="آدرس ایمیل" readonly>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label for="province" class="IRANYekanRegular">استان:</label>
                                        <input id="province" type="text" class="form-control ltr" name="province"  value="{{  $address->province->name ?? '' }}" placeholder="استان" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nationalcode" class="IRANYekanRegular">شهر:</label>
                                        <input id="city" type="text" class="form-control ltr" name="city"  value="{{  $address->city->name ?? '' }}" placeholder="شهر" readonly>
                                    </div>
                                </div>

                                <div class="row mt-2">

                                    <div class="col-md-6">
                                        <label for="postalCode" class="IRANYekanRegular">کدپستی:</label>
                                        <input id="postalCode" type="text" class="form-control ltr" name="postalCode"  value="{{ $address->postalCode }}" placeholder="کدپستی" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="address" class="IRANYekanRegular">آدرس:</label>
                                        <input id="address" type="text" class="form-control " name="address"  value="{{  $address->address }}" placeholder="آدرس" readonly>
                                    </div>

                                </div>

                                <div class="row mt-2">

                                    <div class="col-md-6">
                                        <label for="longitude" class="IRANYekanRegular">طول جغرافیایی:</label>
                                        <input id="longitude" type="text" class="form-control text-latitude" name="longitude"  value="{{ old('longitude') ?? $address->longitude }}" placeholder="طول جغرافیایی" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="latitude" class="IRANYekanRegular">عرض جغرافیایی:</label>
                                        <input id="latitude" type="text" class="form-control text-right" name="latitude"  value="{{ old('latitude') ?? $address->latitude }}" placeholder="عرض جغرافیایی" readonly>
                                    </div>

                                </div>

                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        @if(Auth::guard('admin')->user()->can('admins.documents.confirm'))
                                        <!-- Confirm Modal -->
                                        <div class="modal fade" id="confirm" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xs">
                                                <div class="modal-content">
                                                    <div class="modal-header py-3">
                                                        <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">تایید مشخصات شخصی</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید مشخصات شخصی تایید نمایید؟</h5>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form action="{{ route('admin.admins.staff.personal.confirm',$admin)  }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('patch')
                                                            <button type="submit" title="تایید" class="btn btn-primary px-8">تایید</button>
                                                        </form>
                                                        &nbsp;
                                                        <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <a class="font18" href="#confirm" data-toggle="modal" title="تایید">
                                            <i class="fas fa-thumbs-up text-primary"></i>
                                        </a>
                                        &nbsp;
                                        @endif

                                       @if(Auth::guard('admin')->user()->can('admins.documents.reject'))
                                        <a class="font18" href="#reject" data-toggle="modal" title="رد">
                                            <i class="fas fa-thumbs-down text-danger"></i>
                                        </a>
                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="reject" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xs">
                                                <div class="modal-content">
                                                    <div class="modal-header py-3">
                                                        <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">رد مشخصات شخصی</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <h5 class="IRANYekanRegular">لطفا علت رد کردن مشخصات شخصی را بنویسید.</h5>
                                                        <form action="{{ route('admin.admins.staff.personal.reject',$admin)  }}" method="POST" class="d-inline" id="reject-form">
                                                            <input class="w-100 p-1" name="desc" value="{{ $admin->document->personal_desc ?? '' }}" required maxlength="256">
                                                            @csrf
                                                            @method('PATCH')

                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" title="رد" class="btn btn-danger px-8" form="reject-form">رد</button>
                                                        &nbsp;
                                                        <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>


                        </div>

                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div>
    </div>
</div>
@endsection

