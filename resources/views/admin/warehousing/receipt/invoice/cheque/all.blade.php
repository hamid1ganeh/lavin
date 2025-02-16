@extends('admin.master')

@section('script')
    <script type="text/javascript">
        @foreach($payments as $index=>$cheque)
        $("#passed-date{{ $cheque->id  }}").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#passed-date{{ $cheque->id  }}",
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
        @endforeach
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
                                {{ Breadcrumbs::render('reserves.payment.invoice.cheque',$reserve,$invoice) }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <i class="fas fa-dollar-sign page-icon"></i>
                             پرداختی های چک
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
                            <div class="row">
                                <div class="col-12 text-right">
                                    @if(Auth::guard('admin')->user()->can('reserves.payment.invoice.cheque.create'))
                                    <div class="btn-group" >
                                            <a href="{{ route('admin.reserves.payment.cheque.create',[$reserve,$invoice]) }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-plus plusiconfont"></i>
                                            <b class="IRANYekanRegular">ایجاد پرداخت جدید</b>
                                        </a>
                                    </div>
                                   @endif
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="tech-companies-1" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th><b class="IRANYekanRegular">ردیف</b></th>
                                        <th><b class="IRANYekanRegular">نام صادرکننده</b></th>
                                        <th><b class="IRANYekanRegular">کدملی صادرکننده</b></th>
                                        <th><b class="IRANYekanRegular">شماره سریال</b></th>
                                        <th><b class="IRANYekanRegular">مبلغ(تومان)</b></th>
                                        <th><b class="IRANYekanRegular">تاریخ صدرو</b></th>
                                        <th><b class="IRANYekanRegular">تاریخ سررسید</b></th>
                                        <th><b class="IRANYekanRegular">تاریخ پاس شدن</b></th>
                                        <th><b class="IRANYekanRegular">پاس در حساب</b></th>
                                        <th><b class="IRANYekanRegular">صندوقدار</b></th>
                                        <th><b class="IRANYekanRegular">توضیحات</b></th>
                                        <th><b class="IRANYekanRegular">اقدامات</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($payments as $index=>$cheque)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $cheque->sender_full_name }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $cheque->sender_nation_code }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $cheque->serial_number }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ number_format($cheque->price) }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $cheque->dateOfIssue() }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $cheque->dueDate() }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $cheque->passedDate() }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $cheque->passedByAccountInfo()  }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $cheque->cashier->fullname??'' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $cheque->description }}</strong></td>
                                            <td>
                                                <!-- Remove Modal -->
                                                <div class="modal fade" id="remove{{ $cheque->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف پرداختی</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h5 class="IRANYekanRegular">آیا مطمئن هستید که مخواهید این  پرداختی را حذف کنید؟</h5>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ route('admin.reserves.payment.cheque.destroy', [$reserve,$invoice,$cheque]) }}"  method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger px-8" title="حذف" >حذف</button>
                                                                </form>
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Pass Modal -->
                                                <div class="modal fade" id="pass{{ $cheque->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">پاس کردن چک</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group col-12">
                                                                    <form action="{{ route('admin.reserves.payment.cheque.pass', [$reserve,$invoice,$cheque]) }}"  method="POST" class="d-inline" id="pass-form{{ $cheque->id  }}">
                                                                        @csrf
                                                                        @method('patch')
                                                                        <div class="modal-body text-left">
                                                                            <div class="form-group col-12">
                                                                                <label for="paid_at" class="control-label IRANYekanRegular">تاریخ پاس شدن چک</label>
                                                                                <input type="text" class="form-control input text-center" name="passed_date" id="passed-date{{ $cheque->id  }}" placeholder="تاریخ  پاس شدن چک وارد کنید" value="{{ old('passed_date') ?? jalaliNow('Y/m/d')  }}" readonly required>
                                                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('passed_date') }} </span>
                                                                            </div>
                                                                            <div class="form-group col-12">
                                                                                <label for="passed_by_account_id{{ $cheque->id }}" class="control-label IRANYekanRegular">پاس شدن در حساب</label>
                                                                                <select name="passed_by_account_id" id="passed_by_account_id{{ $cheque->id }}" class="form-control dropdown IR" required>
                                                                                    <option value="">حساب بانکی پاس شدن چک را انتخاب کنید.</option>
                                                                                    @foreach($accounts as $account)
                                                                                        <option value="{{ $account->id }}" @if($account->id==old('passed_by_account_id')) selected @endif>{{ $account->bank_name.' ('.$account->full_name.')'.' ('.$account->cart_number.')' }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('passed_by_account_id') }} </span>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary px-8" form="pass-form{{ $cheque->id }}" title="ثبت" >ثبت</button>
                                                                &nbsp;
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <i class=" ti-align-justify" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                                        <div class="dropdown-menu">
                                                            @if(Auth::guard('admin')->user()->can('reserves.payment.invoice.cheque.edit'))
                                                            <a class="dropdown-item IR cursor-pointer" href="{{ route('admin.reserves.payment.cheque.edit', [$reserve,$invoice,$cheque]) }}" title="ویرایش">
                                                                <i class="fa fa-edit text-success font-16 cursor-pointer"></i>
                                                                <span class="p-1">ویرایش</span>
                                                            </a>
                                                             @endif
                                                            @if(Auth::guard('admin')->user()->can('reserves.payment.invoice.cheque.pass'))
                                                                <a href="#pass{{ $cheque->id }}" data-toggle="modal" class="dropdown-item IR cursor-pointer" title="پاس کردن">
                                                                    <i class="fa fa-check text-primary font-16 cursor-pointer"></i>
                                                                    <span class="p-1">پاس کردن</span>
                                                                </a>
                                                            @endif

                                                            @if(Auth::guard('admin')->user()->can('reserves.payment.invoice.cheque.delete'))
                                                            <a href="#remove{{ $cheque->id }}" data-toggle="modal" class="dropdown-item IR cursor-pointer" title="حذف">
                                                                <i class="fa fa-edit text-danger font-16 cursor-pointer"></i>
                                                                <span class="p-1">حذف</span>
                                                            </a>
                                                           @endif
                                                        </div>
                                                    </div>
                                                </div>
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
@endsection
