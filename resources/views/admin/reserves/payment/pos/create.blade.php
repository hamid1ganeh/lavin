@extends('admin.master')
@section('script')
    <script type="text/javascript">
        $("#paid_at").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#paid_at",
            textFormat: "yyyy/MM/dd HH:mm",
            isGregorian: false,
            modalMode: false,
            englishNumber: false,
            enableTimePicker: true,
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
{{--                                {{ Breadcrumbs::render('accounting.accounts.create') }}--}}
                            </ol>
                        </div>
                        <h4 class="page-title">
                                <i class="fas fa-dollar-sign page-icon"></i>
                             ایجاد پرداختی جدید دستگاه پوز
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
                                <form class="form-horizontal" action="{{ route('admin.reserves.payment.pos.store',[$reserve,$invoice]) }}" method="post">
                                    {{ csrf_field() }}

                                    <div class="row">
                                        <div class="form-group col-12 col-md-6">
                                            <label for="account" class="control-label IRANYekanRegular">حساب بانکی</label>
                                            <select name="account" id="account" class="form-control dropdown IR" required>
                                                <option value="">حساب بانکی را انتخاب کنید.</option>
                                                 @foreach($accounts as $account)
                                                    <option value="{{ $account->id }}" @if($account->id==old('account')) selected @endif>{{ $account->bank_name.' ('.$account->full_name.')' }}</option>
                                                  @endforeach
                                            </select>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('account') }} </span>
                                        </div>
                                        <div class="form-group col-12 col-md-6">
                                            <label for="price" class="control-label IRANYekanRegular">مبلغ (تومان)</label>
                                            <input type="number" class="form-control input text-center" name="price" id="price" placeholder="مبلغ (تومان) وارد کنید" value="{{ old('price') }}" min="1000"  required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('price') }} </span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-12 col-md-6">
                                            <label for="transaction_number" class="control-label IRANYekanRegular">شماره تراکنش</label>
                                            <input type="text" class="form-control input text-right" name="transaction_number" id="transaction_number" placeholder=" شماره تراکنش را وارد کنید" value="{{ old('transaction_number') }}" required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('transaction_number') }} </span>
                                        </div>
                                        <div class="form-group col-12 col-md-6">
                                            <label for="paid_at" class="control-label IRANYekanRegular">تاریخ پرداخت</label>
                                            <input type="text" class="form-control input text-center" name="paid_at" id="paid_at" placeholder="تاریخ  پرداخت وارد کنید" value="{{ old('paid_at') }}" readonly required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('paid_at') }} </span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label for="description" class="control-label IRANYekanRegular">توضیحات</label>
                                            <input type="text" class="form-control input" name="description" id="description" placeholder=" توضیحات را وارد کنید" value="{{ old('description') }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('description') }} </span>
                                        </div>
                                    </div>

                                    <div class="form-group mt-2">
                                        <div class="col-sm-12">
                                            <button type="submit" title="ثبت" class="btn btn-primary">ثبت</button>
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