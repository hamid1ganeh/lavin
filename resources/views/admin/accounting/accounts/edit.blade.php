@extends('admin.master')
@section('script')
    <script type="text/javascript">
        $("#opened_at").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#opened_at",
            textFormat: "yyyy/MM/dd",
            isGregorian: false,
            modalMode: false,
            englishNumber: false,
            enableTimePicker: false,
            selectedDateToShow: new Date(),
            calendarViewOnChange: function(param1){
                console.log(param1);
            }
        });
    </script>
@endsection
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
                                {{ Breadcrumbs::render('accounting.accounts.edit',$account) }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="mdi mdi-file-document-box page-icon"></i>
                             ویرایش حساب مالی
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

                            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main" style="margin:auto">
                                <form class="form-horizontal" action="{{ route('admin.accounting.accounts.update',$account) }}" method="post">
                                    {{ csrf_field() }}
                                    @method('PATCH')
                                    <div class="row">
                                        <div class="form-group col-12 col-md-6">
                                            <label for="full_name" class="control-label IRANYekanRegular">نام و نام خانوادگی</label>
                                            <input type="text" class="form-control input" name="full_name" id="full_name" placeholder="نام و نام خانوادگی را وارد کنید" value="{{ old('full_name') ?? $account->full_name }}" required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('full_name') }} </span>
                                        </div>
                                        <div class="form-group col-12 col-md-6">
                                            <label for="bank_name" class="control-label IRANYekanRegular">نام بانک</label>
                                            <input type="text" class="form-control input text-left" name="bank_name" id="bank_name" placeholder="نام بانک را وارد کنید" value="{{ old('bank_name') ?? $account->bank_name }}" required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('bank_name') }} </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-12 col-md-6">
                                            <label for="account_type" class="control-label IRANYekanRegular">نوع حساب</label>
                                            <input type="text" class="form-control input" name="account_type" id="account_type" placeholder="نوع حساب را وارد کنید" value="{{ old('account_type') ?? $account->account_type }}" required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('account_type') }} </span>
                                        </div>
                                        <div class="form-group col-12 col-md-6">
                                            <label for="opened_at" class="control-label IRANYekanRegular">تاریخ افتتاح حساب</label>
                                            <input type="text" class="form-control input text-center" name="opened_at" id="opened_at" placeholder="تاریخ افتتاح حساب را وارد کنید" value="{{ old('opened_at') ?? $account->openedAt() }}" readonly required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('opened_at') }} </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-12 col-md-6">
                                            <label for="cart_number" class="control-label IRANYekanRegular">شماره کارت</label>
                                            <input type="text" class="form-control input text-right" name="cart_number" id="cart_number" placeholder="شماره کارت را وارد کنید" value="{{ old('cart_number') ?? $account->cart_number }}" required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('cart_number') }} </span>
                                        </div>
                                        <div class="form-group col-12 col-md-6">
                                            <label for="account_number" class="control-label IRANYekanRegular">شماره حساب</label>
                                            <input type="text" class="form-control input text-right" name="account_number" id="account_number" placeholder="شماره حساب را وارد کنید" value="{{ old('account_number') ?? $account->account_number }}" required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('account_number') }} </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-12 col-md-6">
                                            <label for="shaba_number" class="control-label IRANYekanRegular">شماره شبا</label>
                                            <input type="text" class="form-control input text-right" name="shaba_number" id="shaba_number" placeholder="شماره شبا را وارد کنید" value="{{ old('shaba_number') ?? $account->shaba_number }}" required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('shaba_number') }} </span>
                                        </div>
                                        <div class="form-group justify-content-center col-6">
                                            <div class="form-check mt-4 pr-5 mr-2">
                                                <input class="form-check-input cursor-pointer" type="checkbox" value="on" name="pos" id="pos" {{ old('pos')=='on' || $account->pos?'checked':'' }}>
                                                <label class="form-check-label IRANYekanRegular" style="margin-right: 19px !important;" for="pos">
                                                    دستگاه پوز
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2 p-2">
                                        <div class="col-12" style="display:inherit;">
                                            <input type="radio" id="active" name="status" class="cursor-pointer" value="{{ App\Enums\Status::Active }}" @if($account->status!=App\Enums\Status::Deactive) checked @endif>
                                            &nbsp;
                                            <label for="active" class="IRANYekanRegular">فعال</label><br>
                                            &nbsp;&nbsp; &nbsp;
                                            <input type="radio" id="deactive" name="status" class="cursor-pointer" value="{{ App\Enums\Status::Deactive }}" @if($account->status==App\Enums\Status::Deactive) checked @endif>
                                            &nbsp;
                                            <label for="deactive" class="IRANYekanRegular">غیرفعال</label><br>
                                        </div>

                                    </div>
                                    <div class="form-group mt-2">
                                        <div class="col-12">
                                            <button type="submit" title="بروزرسانی" class="btn btn-success">بروزرسانی</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
