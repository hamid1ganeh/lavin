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
                                    {{ Breadcrumbs::render('accounting.discounts') }}
                                </ol>
                            </div>
                            <h4 class="page-title">
                                <i class="fas fa-percent page-icon"></i>
                                کدهای تخفیف
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
                                    <div class="col-6">
                                        <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="collapseExample" title="فیلتر">
                                            <i class="fas fa-filter"></i>
                                        </button>
                                    </div>
                                    <div class="col-6 text-right">
                                        @if(Auth::guard('admin')->user()->can('accounting.discounts.create'))
                                            <div class="btn-group" >
                                                <a href="{{ route('admin.accounting.discounts.create') }}" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-plus plusiconfont"></i>
                                                    <b class="IRANYekanRegular">ایجاد تخفیف جدید</b>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="collapse" id="filter">
                                    <div class="card card-body filter">
                                        <form id="filter-form">
                                            <div class="row">

                                                <div class="form-group justify-content-center col-6">
                                                    <label for="code" class="control-label IRANYekanRegular">کد تخیف</label>
                                                    <input type="text"  class="form-control input" id="code-filter" name="code" placeholder="کد تخفیف را وارد کنید" value="{{ request('code') }}">
                                                </div>

                                            </diV>

                                            <div class="form-group col-12 d-flex justify-content-center mt-3">

                                                <button type="submit" class="btn btn-info col-lg-2 offset-lg-4 cursor-pointer">
                                                    <i class="fa fa-filter fa-sm"></i>
                                                    <span class="pr-2">فیلتر</span>
                                                </button>

                                                <div class="col-lg-2">
                                                    <a onclick="reset()" class="btn btn-light border border-secondary cursor-pointer">
                                                        <i class="fas fa-undo fa-sm"></i>
                                                        <span class="pr-2">پاک کردن</span>
                                                    </a>
                                                </div>


                                                <script>
                                                    function reset()
                                                    {
                                                        document.getElementById("code-filter").value = "";
                                                    }
                                                </script>

                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table id="tech-companies-1" class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th><b class="IRANYekanRegular">ردیف</b></th>
                                            <th><b class="IRANYekanRegular">کد</b></th>
                                            <th><b class="IRANYekanRegular">واحد</b></th>
                                            <th><b class="IRANYekanRegular">مقدار</b></th>
                                            <th><b class="IRANYekanRegular">انقضاء</b></th>
                                            <th><b class="IRANYekanRegular">وضعیت</b></th>
                                            <th><b class="IRANYekanRegular">اقدامات</b></th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @php $row = 0;  @endphp
                                        @foreach($discounts as $discount)
                                            <tr>
                                                <td><strong class="IRANYekanRegular">{{ ++$row }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $discount->code }}</strong></td>
                                                <td>
                                                    <strong class="IRANYekanRegular">
                                                        @if($discount->unit==App\Enums\DiscountType::percet)
                                                            درصد(%)
                                                        @elseif($discount->unit==App\Enums\DiscountType::toman)
                                                            مبلغ(تومان)
                                                    @endif
                                                </td>
                                                <td><strong class="IRANYekanRegular">{{ $discount->value }}</strong></td>
                                                <td>
                                                    @if($discount->expire!=null)
                                                        <strong class="IRANYekanRegular">
                                                            {{ \Morilog\Jalali\CalendarUtils::convertNumbers(\Morilog\Jalali\CalendarUtils::strftime('H:i:s - Y/m/d',strtotime($discount->expire))) }}
                                                        </strong>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong class="IRANYekanRegular">
                                                        @if($discount->status==App\Enums\Status::Active)
                                                            <span class="badge badge-primary IR  p-1">فعال</span>
                                                        @elseif($discount->status==App\Enums\Status::Deactive)
                                                            <span class="badge badge-danger IR  p-1">غیرفعال</span>
                                                    @endif
                                                </td>
                                                <td>

                                                    <!-- Recycle Modal -->
                                                    <div class="modal fade" id="recycle{{ $discount->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">بازیابی کد تخفیف</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h5 class="IRANYekanRegular">آیا مطمئن هستید که کد تخفیف {{ $discount->code }} را بازیابی نمایید؟</h5>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form action="{{ route('admin.accounting.discounts.recycle', $discount) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('patch')
                                                                        <button type="submit"  title="بازیابی" class="btn btn-info px-8">بازیابی</button>
                                                                    </form>
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Remove Modal -->
                                                    <div class="modal fade" id="remove{{ $discount->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف کد تحفیف</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h5 class="IRANYekanRegular">آیا مطمئن هستید که می‌خواهدی کد تخفیف  {{ $discount->code }} را حذف کنید؟</h5>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form action="{{ route('admin.accounting.discounts.destroy', $discount) }}"  method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" title="حذف" class="btn btn-danger px-8">حذف</button>
                                                                    </form>
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Festival Modal -->
                                                    <div class="modal fade" id="festival{{ $discount->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">اختصاص به جشنواره ها</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body  text-left">
                                                                    <form action="{{ route('admin.accounting.discounts.festival.update', $discount) }}"  method="POST" class="d-inline" id="festival-form{{ $discount->id  }}">
                                                                        @csrf
                                                                        @method('patch')
                                                                        <label for="festivals{{ $discount->id  }}" class="col-form-label IRANYekanRegular">جشنواره ها:</label>
                                                                        <select name="festivals[]" id="festivals{{ $discount->id  }}" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple>
                                                                            @foreach($festivals as $festival)
                                                                                <option value="{{ $festival->id }}" {{ $festival->id==old('festivals')|| in_array(trim($festival->id),$discount->festivals->pluck('id')->toArray())?'selected':'' }}>{{ $festival->title }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" title="ثبت" class="btn btn-primary px-8" form="festival-form{{ $discount->id }}">ثبت</button>
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
                                                                @if($discount->trashed())
                                                                    @if(Auth::guard('admin')->user()->can('accounting.discounts.destroy'))
                                                                        <a href="#recycle{{ $discount->id }}" data-toggle="modal" title="بازیابی" class="dropdown-item IR cusrsor">
                                                                            <i class="fa fa-recycle text-danger"></i>
                                                                            <span class="p-1">بازیابی</span>
                                                                        </a>
                                                                    @endif
                                                                @else
                                                                    @if(Auth::guard('admin')->user()->can('accounting.discounts.user.update'))
                                                                        <a  href="{{ route('admin.accounting.discounts.users.show', $discount) }}" title="کابران" class="dropdown-item IR cusrsor">
                                                                            <i class="fa fa-users text-secondary"></i>
                                                                            <span class="p-1">کابران</span>
                                                                        </a>
                                                                    @endif

                                                                    @if(Auth::guard('admin')->user()->can('accounting.discounts.services.update'))
                                                                        <a   href="{{ route('admin.accounting.discounts.services.show', $discount) }}" title="سرویس‌ها و محصولات" class="dropdown-item IR cusrsor">
                                                                            <i class="fab fa-servicestack text-warning"></i>
                                                                            <span class="p-1">سرویس‌ها و محصولات</span>
                                                                        </a>
                                                                    @endif

                                                                    @if(Auth::guard('admin')->user()->can('accounting.discounts.edit'))
                                                                        <a   href="{{ route('admin.accounting.discounts.edit', $discount) }}" title="ویرایش" class="dropdown-item IR cusrsor">
                                                                            <i class="fa fa-edit text-info"></i>
                                                                            <span class="p-1">ویرایش</span>
                                                                        </a>
                                                                    @endif

                                                                    @if(Auth::guard('admin')->user()->can('accounting.discounts.destroy'))
                                                                        <a href="#remove{{ $discount->id }}" data-toggle="modal"   title="حذف" class="dropdown-item IR cusrsor">
                                                                            <i class="fa fa-trash text-danger"></i>
                                                                            <span class="p-1">حذف</span>
                                                                        </a>
                                                                    @endif

                                                                    @if(Auth::guard('admin')->user()->can('accounting.discounts.festival.update'))
                                                                        <a href="#festival{{ $discount->id }}" data-toggle="modal"   title="جشنواره ها" class="dropdown-item IR cusrsor">
                                                                            <i class="ti-wand text-success"></i>
                                                                            <span class="p-1">جشنواره ها</span>
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
                                    {{ $discounts->render() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

@endsection
