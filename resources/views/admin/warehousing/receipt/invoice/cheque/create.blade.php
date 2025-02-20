@extends('admin.master')
@section('script')
    <script type="text/javascript">
        $("#date_of_issue").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#date_of_issue",
            textFormat: "yyyy/MM/dd",
            isGregorian: false,
            modalMode: false,
            englishNumber: false,
            enableTimePicker: true,
            selectedDateToShow: new Date(),
            calendarViewOnChange: function(param1){
                console.log(param1);
            }
        });

        $("#due_date").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#due_date",
            textFormat: "yyyy/MM/dd",
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
{{--                                {{ Breadcrumbs::render('warehousing.receipts.invoice.cheque.create',$receipt,$invoice) }}--}}
                            </ol>
                        </div>
                        <h4 class="page-title">
                                <i class="fas fa-dollar-sign page-icon"></i>
                             ایجاد پرداختی جدید چک
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
                                <form class="form-horizontal" action="{{ route('admin.warehousing.receipts.invoice.cheque.store',[$receipt,$invoice]) }}" method="post">
                                    {{ csrf_field() }}

                                    <div class="row">
                                        <div class="form-group col-12 col-md-4">
                                            <label for="sender_full_name" class="control-label IRANYekanRegular">نام و نام خانوادگی صادر کننده چک</label>
                                            <input type="text" class="form-control input" name="sender_full_name" id="sender_full_name" placeholder=" نام و نام خانوادگی صادر کننده چک را وارد کنید" value="{{ old('sender_full_name') }}" required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('sender_full_name') }} </span>
                                        </div>
                                        <div class="form-group col-12 col-md-4">
                                            <label for="sender_nation_code" class="control-label IRANYekanRegular">کدملی صادر کننده چک</label>
                                            <input type="text" class="form-control input text-right" name="sender_nation_code" id="sender_nation_code" placeholder="کدملی صادر کننده چک را وارد کنید" value="{{ old('sender_nation_code') }}" minlength="10" maxlength="10" required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('sender_nation_code') }} </span>
                                        </div>
                                        <div class="form-group col-12 col-md-4">
                                            <label for="sender_account_number" class="control-label IRANYekanRegular">شماره حساب صادر کننده چک</label>
                                            <input type="text" class="form-control input text-right" name="sender_account_number" id="sender_account_number" placeholder="شماره حساب صادر کننده چک را وارد کنید" value="{{ old('sender_account_number') }}"   required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('sender_account_number') }} </span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-12 col-md-6">
                                            <label for="serial_number" class="control-label IRANYekanRegular">شماره سریال چک</label>
                                            <input type="text" class="form-control input text-right" name="serial_number" id="serial_number" placeholder="شماره سریال چک وارد کنید" value="{{ old('serial_number') }}" required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('serial_number') }} </span>
                                        </div>

                                        <div class="form-group col-12 col-md-6">
                                            <label for="price" class="control-label IRANYekanRegular">مبلغ (تومان)</label>
                                            <input type="number" class="form-control input text-center" name="price" id="price" placeholder="مبلغ (تومان) وارد کنید" value="{{ old('price') }}" min="1000"  required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('price') }} </span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-12 col-md-6">
                                            <label for="date_of_issue" class="control-label IRANYekanRegular">تاریخ صدور چک</label>
                                            <input type="text" class="form-control input text-center" name="date_of_issue" id="date_of_issue" placeholder="تاریخ  صدور وارد کنید" value="{{ old('date_of_issue') }}" readonly required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('date_of_issue') }} </span>
                                        </div>
                                        <div class="form-group col-12 col-md-6">
                                            <label for="due_date" class="control-label IRANYekanRegular">تاریخ سررسید چک</label>
                                            <input type="text" class="form-control input text-center" name="due_date" id="due_date" placeholder="تاریخ  سررسید وارد کنید" value="{{ old('due_date') }}" readonly required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('due_date') }} </span>
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
