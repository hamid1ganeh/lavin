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
{{--                                {{ Breadcrumbs::render('employments.categories.main.create',$main) }}--}}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-file page-icon"></i>
                             ایجاد رسید جدید
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
                                <form class="form-horizontal" action="{{ route('admin.warehousing.receipt.store') }}" method="post">
                                     @csrf
                                    <div id="goods-div">
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <label for="priority" class="control-label IRANYekanRegular">نوع رسید</label>
                                                <select class="form-control dropdown"  name="type" id="type" required>
                                                    <option value="{{ App\Enums\ReceiptType::received }}" {{ App\Enums\ReceiptType::received==old('type')?'selected':'' }}>دریافتی</option>
                                                    <option value="{{ App\Enums\ReceiptType::returned }}" {{ App\Enums\ReceiptType::returned==old('type')?'selected':'' }}>مرجوعی</option>
                                               </select>
                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('type') }} </span>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="number" class="control-label IRANYekanRegular">شماره رسید</label>
                                                <input type="text" class="form-control input text-right" name="number" id="number" placeholder="شماره رسید را وارد کنید" value="{{ old('number') }}" required>
                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('number') }} </span>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="seller" class="control-label IRANYekanRegular">طرف حساب</label>
                                                <input type="text" class="form-control input" name="seller" id="seller" placeholder="طرف حساب را وارد کنید" value="{{ old('seller') }}">
                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('seller') }} </span>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="seller_id" class="control-label IRANYekanRegular">فروشنده</label>
                                                <select class="form-control dropdown"  name="seller_id" id="seller_id">
                                                    <option value="" >فروشنده را انتخاب کنید...</option>
                                                    @foreach($sellers as $seller)
                                                        <option value="{{ $seller->id }}" {{ $seller->id==old('seller_id')?'selected':'' }}>{{ $seller->getFullName() }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('type') }} </span>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-12 col-md-5">
                                                <label  class="control-label IRANYekanRegular">کالا</label>
                                                <select class="widht-100 form-control select2" name="goods[]" required>
                                                    @foreach($goods as $good)
                                                        <option value="{{ $good->id }}" selected>{{ $good->title.' - برند  ('.$good->brand.") -  کد  (".$good->code.")" }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6 col-md-2">
                                                <label  class="control-label IRANYekanRegular">تعداد</label>
                                                <input id="name" type="number" min="0" class="form-control text-center"  name="count[]" value=""  autofocus placeholder="تعداد" required>
                                            </div>
                                            <div class="col-6 col-md-2">
                                                <label  class="control-label IRANYekanRegular">قیمت واحد</label>
                                                <input id="name" type="number" min="0" class="form-control text-center"  name="unit_cost[]" value=""  autofocus placeholder="قیمت واحد" required>
                                            </div>
                                            <div class="col-6 col-md-2">
                                                <label  class="control-label IRANYekanRegular">قیمت کل</label>
                                                <input id="name" type="number" min="0" class="form-control text-center"  name="total_cost[]" value=""  autofocus placeholder="قیمت کل" required>
                                            </div>
                                        </div>
                                    </div>

                                    <button id="btn" type="button" class="btn btn-success mt-2">
                                        افزودن محصول جدید
                                        <i class="fa fa-plus plusiconfont"></i>
                                    </button>

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

@section('script')
    <script type="text/javascript">
        var counter = 0;
        const btn = document.getElementById('btn');
        function appendHTML(){
            const ele = document.getElementById('goods-div');
            const newDiv = document.createElement('div');
            newDiv.innerHTML =`
                <div class="row mt-2" id="div${ ++counter}">
                           <div class="col-12 col-md-5">
                                 <select class="widht-100 form-control select2" name="goods[]" required>
                                        @foreach($goods as $good)
                                        <option value="{{ $good->id }}" selected>{{ $good->title.' - برند  ('.$good->brand.") -  کد  (".$good->code.")" }}</option>
                                        @endforeach
                                </select>
                          </div>
                         <div class="col-6 col-md-2">
                            <input id="name" type="number" min="0" class="form-control text-center"  name="count[]" value=""  autofocus placeholder="تعداد" required>
                        </div>
                       <div class="col-6 col-md-2">
                            <input id="name" type="number" min="0" class="form-control text-center"  name="unit_cost[]" value=""  autofocus placeholder="قیمت واحد" required>
                        </div>
                       <div class="col-6 col-md-2">
                            <input id="name" type="number" min="0" class="form-control text-center"  name="total_cost[]" value=""  autofocus placeholder="قیمت کل" required>
                        </div>
                        <div class="col-1">
                             <a   title="حذف" onclick="remove('div${counter}')" class='cursor-pointer'>
                                <i class="fa fa-trash text-danger font-20"></i>
                            </a>
                        </div>
                </div>`;
            ele.appendChild(newDiv);
        }
        btn.addEventListener('click', appendHTML);

        function remove(id)
        {
            const element = document.getElementById(id);
            element.remove();
        }
    </script>
@endsection
