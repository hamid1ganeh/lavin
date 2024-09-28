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
                                {{ Breadcrumbs::render('reserves.upgrade',$reserve) }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-level-up-alt page-icon"></i>
                            ارتقاء
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

                                <div class="col-12  text-right">
                                    @if(Auth::guard('admin')->user()->can('reserves.upgrade.create') && !is_null($reserve->reception) && !$reserve->reception->end)
                                    <div class="btn-group" >
                                        <a href="{{ route('admin.reserves.upgrade.create',$reserve) }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-plus plusiconfont"></i>
                                            <b class="IRANYekanRegular">ایجاد ارتقاء جدید</b>
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
                                            <th><b class="IRANYekanRegular">سرگروه سرویس</b></th>
                                            <th><b class="IRANYekanRegular">سرویس</b></th>
                                            <th><b class="IRANYekanRegular">قیمت</b></th>
                                            <th><b class="IRANYekanRegular">دستیار اول</b></th>
                                            <th><b class="IRANYekanRegular">دستیار دوم</b></th>
                                            <th><b class="IRANYekanRegular">توضیحات</b></th>
                                            <th><b class="IRANYekanRegular">زمان انجام</b></th>
                                            <th><b class="IRANYekanRegular">مدت زمان(ساعت)</b></th>
                                            <th><b class="IRANYekanRegular">شماره قبض</b></th>
                                            <th><b class="IRANYekanRegular">وضعیت</b></th>
                                            <th><b class="IRANYekanRegular">اقدامات</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($upgrades as $index=>$upgrade)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $upgrade->service_name }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $upgrade->detail_name }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ number_format($upgrade->price) }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $upgrade->asistant1->fullname }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $upgrade->asistant2->fullname ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $upgrade->desc ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $upgrade->done_time()  }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $upgrade->duration()  }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $upgrade->payment_code ?? '' }}</strong></td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    @switch($upgrade->status)
                                                        @case(App\Enums\ReserveStatus::waiting)
                                                        <span class="badge badge-warning IR p-1">درانتظار</span>
                                                        @break
                                                        @case(App\Enums\ReserveStatus::confirm)
                                                        <span class="badge badge-primary IR p-1">تایید شده</span>
                                                        @break
                                                        @case(App\Enums\ReserveStatus::cancel)
                                                            <span class="badge badge-danger IR p-1">لغو شده</span>
                                                            @break
                                                    @endswitch
                                                </strong>
                                            </td>

                                            <td>
                                                <!-- Remove Modal -->
                                                <div class="modal fade" id="remove{{ $upgrade->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف ارتقاء</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید این ارتقاء را حذف کنید؟</h5>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ route('admin.reserves.upgrade.delete', [$reserve,$upgrade] ) }}"  method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" title="حذف" class="btn btn-danger px-8">حذف</button>
                                                                </form>
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Confirm Modal -->
                                                <div class="modal fade" id="confirm{{ $upgrade->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">تایید ارتقاء</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                <h5 class="IRANYekanRegular">شماره قبض پرداخت را وارد کنید</h5>
                                                                <input name="payment" type="text" value="{{ $upgrade->payment_code  }}"  placeholder="شماره قبض پرداخت" class="text-center p-1" required form="confirm-form-id{{ $upgrade->id }}">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ route('admin.reserves.upgrade.confirm',[$reserve,$upgrade]) }}"  method="POST" class="d-inline" id="confirm-form-id{{ $upgrade->id }}">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <input name="status" type="hidden" value="{{ App\Enums\ReserveStatus::confirm }}">

                                                                    <button type="submit" title="تایید" class="btn btn-primary px-8">تایید</button>
                                                                </form>
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Cancel Modal -->
                                                <div class="modal fade" id="cancel{{ $upgrade->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">لغو ارتقاء</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید این ارتقاء را لغو کنید؟</h5>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ route('admin.reserves.upgrade.confirm',[$reserve,$upgrade]) }}"  method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <input name="status" type="hidden" value="{{ App\Enums\ReserveStatus::cancel }}">
                                                                    <button type="submit" title="لغو" class="btn btn-danger px-8">لغو</button>
                                                                </form>
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <i class=" ti-align-justify" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                                        <div class="dropdown-menu">
                                                            @if(App\Enums\reserveStatus::waiting == $upgrade->status)
                                                            @if(Auth::guard('admin')->user()->can('reserves.upgrade.edit'))
                                                                <a class="dropdown-item IR cusrsor" href="{{ route('admin.reserves.upgrade.edit', [$reserve,$upgrade]) }}" title="ویرایش">
                                                                    <i class="fa fa-edit text-success"></i>
                                                                    ویرایش
                                                                </a>
                                                            @endif
                                                            @if(Auth::guard('admin')->user()->can('reserves.upgrade.delete'))

                                                                <a class="dropdown-item IR cusrsor" href="#remove{{ $upgrade->id }}" data-toggle="modal"  title="حذف">
                                                                    <i class="fa fa-trash text-danger"></i>
                                                                    حذف
                                                                </a>
                                                            @endif
                                                            @endif

                                                            @if(Auth::guard('admin')->user()->can('reserves.upgrade.confirm'))
                                                            @if($upgrade->status==App\Enums\ReserveStatus::waiting || $upgrade->status==App\Enums\ReserveStatus::cancel)
                                                            <a class="dropdown-item IR cusrsor" href="#confirm{{ $upgrade->id }}" data-toggle="modal"  title="تایید">
                                                                <i class="fas fa-thumbs-up text-primary"></i>
                                                                 تایید
                                                            </a>
                                                            @endif

                                                            @if($upgrade->status==App\Enums\ReserveStatus::waiting || $upgrade->status==App\Enums\ReserveStatus::confirm)
                                                            <a class="dropdown-item IR cusrsor" href="#cancel{{ $upgrade->id }}" data-toggle="modal"  title="لغو">
                                                                <i class="fas fa-thumbs-down text-danger"></i>
                                                                لغو
                                                            </a>
                                                            @endif
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
