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
{{--                                {{ Breadcrumbs::render('reserves.payment.invoice.card.edit',$reserve,$invoice,$card) }}--}}
                            </ol>
                        </div>
                        <h4 class="page-title">
                                <i class="fas fa-dollar-sign page-icon"></i>
                             ویرایش پرداختی جدید کارت به کارت
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
                                <form class="form-horizontal" action="{{ route('admin.accounting.reception.invoices.card.update',[$reception,$receptionInvoice,$card]) }}" method="post">
                                    {{ csrf_field() }}
                                    @method('PUT')

                                    <div class="row">
                                        <div class="form-group col-12 col-md-6">
                                            <label for="sender_full_name" class="control-label IRANYekanRegular">نام و نام خانوادگی کارت واریز کننده</label>
                                            <input type="text" class="form-control input" name="sender_full_name" id="sender_full_name" placeholder=" شماره تراکنش را وارد کنید" value="{{ old('transaction_number') ?? $card->transaction_number  }}" required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('transaction_number') }} </span>
                                        </div>
                                        <div class="form-group col-12 col-md-6">
                                            <label for="sender_cart_number" class="control-label IRANYekanRegular">شماره کارت واریز کننده</label>
                                            <input type="text" class="form-control input text-right" name="sender_cart_number" id="sender_cart_number" placeholder=" شماره کارت واریز کننده" value="{{ old('sender_cart_number') ?? $card->sender_cart_number }}" required maxlength="16" minlength="16">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('sender_cart_number') }} </span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-12 col-md-6">
                                            <label for="receiver_account_id" class="control-label IRANYekanRegular">حساب بانکی دریافت کننده</label>
                                            <select name="receiver_account_id" id="receiver_account_id" class="form-control dropdown IR" required>
                                                <option value="">حساب بانکی دریافت کننده را انتخاب کنید.</option>
                                                 @foreach($accounts as $account)
                                                    <option value="{{ $account->id }}" @if($account->id==old('receiver_account_id') ||  $card->receiver_account_id) selected @endif>{{ $account->bank_name.' ('.$account->full_name.')'.' ('.$account->cart_number.')' }}</option>
                                                  @endforeach
                                            </select>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('receiver_account_id') }} </span>
                                        </div>
                                        <div class="form-group col-12 col-md-6">
                                            <label for="price" class="control-label IRANYekanRegular">مبلغ (تومان)</label>
                                            <input type="number" class="form-control input text-center" name="price" id="price" placeholder="مبلغ (تومان) وارد کنید" value="{{ old('price') ?? $card->price }}" min="1000"  required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('price') }} </span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-12 col-md-6">
                                            <label for="transaction_number" class="control-label IRANYekanRegular">شماره تراکنش</label>
                                            <input type="text" class="form-control input text-right" name="transaction_number" id="transaction_number" placeholder=" شماره تراکنش را وارد کنید" value="{{ old('transaction_number') ?? $card->transaction_number }}" required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('transaction_number') }} </span>
                                        </div>
                                        <div class="form-group col-12 col-md-6">
                                            <label for="paid_at" class="control-label IRANYekanRegular">تاریخ پرداخت</label>
                                            <input type="text" class="form-control input text-center" name="paid_at" id="paid_at" placeholder="تاریخ  پرداخت وارد کنید" value="{{ old('paid_at') ?? $card->paidAt() }}" readonly required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('paid_at') }} </span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label for="description" class="control-label IRANYekanRegular">توضیحات</label>
                                            <input type="text" class="form-control input" name="description" id="description" placeholder=" توضیحات را وارد کنید" value="{{ old('description') ?? $card->description }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('description') }} </span>
                                        </div>
                                    </div>

                                    <div class="form-group mt-2">
                                        <div class="col-sm-12">
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
