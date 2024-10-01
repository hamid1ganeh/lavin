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
                                {{ Breadcrumbs::render('warehousing.goods.create') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-shopping-cart page-icon"></i>
                             ایجاد کالا جدید
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

                                <form class="form-horizontal" action="{{ route('admin.warehousing.goods.store') }}" method="post">
                                     @csrf

                                    <div class="form-group row">
                                        <div class="col-12 col-md-6">
                                            <label for="title" class="control-label IRANYekanRegular">عنوان</label>
                                            <input type="text" class="form-control input" name="title" id="title" placeholder="عنوان کالا را وارد کنید" value="{{ old('title')  }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('title') }} </span>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="code" class="control-label IRANYekanRegular">کد کالا</label>
                                            <input type="text" class="form-control input text-right" name="code" id="code" placeholder="کد کالا را وارد کنید" value="{{ old('code')  }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('code') }} </span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12 col-md-6">
                                            <label for="main" class="col-form-label IRANYekanRegular">دسته بندی اصلی</label>
                                            <select name="main_cat_id" id="main_cat_id"  class="form-control  IRANYekanRegular" onchange="subcat(this.value)">
                                                <option value="">دسته مورد نظر را انتخاب کنید</option>
                                                @foreach($mains as $main)
                                                    <option value="{{ $main->id }}" {{$main->id == old('main_cat_id')?'selected':'' }}>{{ $main->title }}</option>
                                                @endforeach
                                            </select>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('main_cat_id') }} </span>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="sub_cat_id" class="col-form-label IRANYekanRegular">زیردسته</label>
                                            <div id="sub_div">
                                                <select name="sub_cat_id" id="sub_cat_id"  class="form-control IRANYekanRegular">
                                                    <option value="">زیردسته مورد نظر را انتخاب کنید</option>
                                                    @foreach($subs as $sub)
                                                        <option value="{{ $sub->id }}" {{$sub->id == old('sub_cat_id')?'selected':'' }}>{{ $sub->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('sub_cat_id') }} </span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12 col-md-4">
                                            <label for="unit" class="control-label IRANYekanRegular">واحد</label>
                                            <select name="unit" id="unit"  class="form-control  IRANYekanRegular" >
                                                <option value="{{ App\Enums\Unit::mg }}" {{ App\Enums\Unit::mg== old('unit')?'selected':'' }}>{{ App\Enums\Unit::mg }}</option>
                                                <option value="{{ App\Enums\Unit::gr }}" {{ App\Enums\Unit::gr== old('unit')?'selected':'' }}>{{ App\Enums\Unit::gr }}</option>
                                                <option value="{{ App\Enums\Unit::kg }}" {{ App\Enums\Unit::kg== old('unit')?'selected':'' }}>{{ App\Enums\Unit::kg }}</option>
                                                <option value="{{ App\Enums\Unit::t }}" {{ App\Enums\Unit::t== old('unit')?'selected':'' }}>{{ App\Enums\Unit::t }}</option>
                                                <option value="{{ App\Enums\Unit::mm }}" {{ App\Enums\Unit::mm== old('unit')?'selected':'' }}>{{ App\Enums\Unit::mm }}</option>
                                                <option value="{{ App\Enums\Unit::cc }}" {{ App\Enums\Unit::cc== old('unit')?'selected':'' }}>{{ App\Enums\Unit::cc }}</option>
                                                <option value="{{ App\Enums\Unit::cm }}" {{ App\Enums\Unit::cm== old('unit')?'selected':'' }}>{{ App\Enums\Unit::cm }}</option>
                                                <option value="{{ App\Enums\Unit::m }}" {{ App\Enums\Unit::m== old('unit')?'selected':'' }}>{{ App\Enums\Unit::m }}</option>
                                                <option value="{{ App\Enums\Unit::box }}" {{ App\Enums\Unit::box== old('unit')?'selected':'' }}>{{ App\Enums\Unit::box }}</option>
                                                <option value="{{ App\Enums\Unit::per }}" {{ App\Enums\Unit::per== old('unit')?'selected':'' }}>{{ App\Enums\Unit::per }}</option>
                                                <option value="{{ App\Enums\Unit::ma }}" {{ App\Enums\Unit::ma== old('unit')?'selected':'' }}>{{ App\Enums\Unit::ma }}</option>
                                                <option value="{{ App\Enums\Unit::a }}" {{ App\Enums\Unit::a== old('unit')?'selected':'' }}>{{ App\Enums\Unit::a }}</option>
                                                <option value="{{ App\Enums\Unit::v }}" {{ App\Enums\Unit::v== old('unit')?'selected':'' }}>{{ App\Enums\Unit::v }}</option>
                                                <option value="{{ App\Enums\Unit::Shut }}" {{ App\Enums\Unit::Shut== old('unit')?'selected':'' }}>{{ App\Enums\Unit::Shut }}</option>
                                            </select>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('unit') }} </span>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label for="stock" class="control-label IRANYekanRegular">موجودی</label>
                                            <input type="text" class="form-control input text-right" name="stock" id=stock" placeholder="موجودی کل کالا را وارد کنید" value="{{ old('stock')  }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('stock') }} </span>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label for="price" class="control-label IRANYekanRegular">قیمت (تومان)</label>
                                            <input type="text" class="form-control input text-right" name="price" id="price" placeholder="قیمت کالا را وارد کنید" value="{{ old('price')  }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('price') }} </span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12 col-md-4">
                                            <label for="consumption_unit" class="control-label IRANYekanRegular">واحد مصرفی</label>
                                            <select name="consumption_unit" id="consumption_unit"  class="form-control  IRANYekanRegular" >
                                                <option value="{{ App\Enums\Unit::mg }}" {{ App\Enums\Unit::mg== old('unit')?'selected':'' }}>{{ App\Enums\Unit::mg }}</option>
                                                <option value="{{ App\Enums\Unit::gr }}" {{ App\Enums\Unit::gr== old('unit')?'selected':'' }}>{{ App\Enums\Unit::gr }}</option>
                                                <option value="{{ App\Enums\Unit::kg }}" {{ App\Enums\Unit::kg== old('unit')?'selected':'' }}>{{ App\Enums\Unit::kg }}</option>
                                                <option value="{{ App\Enums\Unit::t }}" {{ App\Enums\Unit::t== old('unit')?'selected':'' }}>{{ App\Enums\Unit::t }}</option>
                                                <option value="{{ App\Enums\Unit::mm }}" {{ App\Enums\Unit::mm== old('unit')?'selected':'' }}>{{ App\Enums\Unit::mm }}</option>
                                                <option value="{{ App\Enums\Unit::cc }}" {{ App\Enums\Unit::cc== old('unit')?'selected':'' }}>{{ App\Enums\Unit::cc }}</option>
                                                <option value="{{ App\Enums\Unit::cm }}" {{ App\Enums\Unit::cm== old('unit')?'selected':'' }}>{{ App\Enums\Unit::cm }}</option>
                                                <option value="{{ App\Enums\Unit::m }}" {{ App\Enums\Unit::m== old('unit')?'selected':'' }}>{{ App\Enums\Unit::m }}</option>
                                                <option value="{{ App\Enums\Unit::box }}" {{ App\Enums\Unit::box== old('unit')?'selected':'' }}>{{ App\Enums\Unit::box }}</option>
                                                <option value="{{ App\Enums\Unit::per }}" {{ App\Enums\Unit::per== old('unit')?'selected':'' }}>{{ App\Enums\Unit::per }}</option>
                                                <option value="{{ App\Enums\Unit::ma }}" {{ App\Enums\Unit::ma== old('unit')?'selected':'' }}>{{ App\Enums\Unit::ma }}</option>
                                                <option value="{{ App\Enums\Unit::a }}" {{ App\Enums\Unit::a== old('unit')?'selected':'' }}>{{ App\Enums\Unit::a }}</option>
                                                <option value="{{ App\Enums\Unit::v }}" {{ App\Enums\Unit::v== old('unit')?'selected':'' }}>{{ App\Enums\Unit::v }}</option>
                                                <option value="{{ App\Enums\Unit::Shut }}" {{ App\Enums\Unit::Shut== old('unit')?'selected':'' }}>{{ App\Enums\Unit::Shut }}</option>
                                            </select>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('consumption_unit') }} </span>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label for="consumption_stock" class="control-label IRANYekanRegular">موجودی مصرفی</label>
                                            <input type="text" class="form-control input text-right" name="consumption_stock" id=consumption_stock" placeholder="موجودی مصرفی کالا را وارد کنید" value="{{ old('stock')  }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('consumption_stock') }} </span>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label for="consumption_price" class="control-label IRANYekanRegular">قیمت مصرفی(تومان)</label>
                                            <input type="text" class="form-control input text-right" name="consumption_price" id="consumption_price" placeholder="قیمت مصرفی کالا را وارد کنید" value="{{ old('price')  }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('price') }} </span>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <div class="col-12 col-md-2">
                                            <label for="expireDate" class="control-label IRANYekanRegular">تاریخ انقضاء</label>
                                            <input type="text"   class="form-control text-center" id="expireDate" name="expireDate" readonly value="{{ old('expireDate')  }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('expireDate') }} </span>
                                            <i class="mdi mdi-replay text-danger font-20 cursor-pointer" title="پاک کردن" onclick="reset('expireDate')"></i>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <label for="description" class="control-label IRANYekanRegular">توضیحات</label>
                                            <input type="text" class="form-control input text-left" name="description" id=description" placeholder="توضیحات کالا را وارد کنید" value="{{ old('description')  }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('description') }} </span>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <div class="col-12" style="display:inherit;">
                                            <input type="radio" id="active" name="status" value="{{ App\Enums\Status::Active }}" @if(old('status')!=App\Enums\Status::Deactive) checked @endif>
                                            &nbsp;
                                            <label for="active" class="IRANYekanRegular">فعال</label><br>
                                            &nbsp;&nbsp; &nbsp;
                                            <input type="radio" id="deactive" name="status" value="{{ App\Enums\Status::Deactive }}" @if(old('status')==App\Enums\Status::Deactive) checked @endif>
                                            &nbsp;
                                            <label for="deactive" class="IRANYekanRegular">غیرفعال</label><br>
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

@section('script')
    <script type="text/javascript">
        function subcat(id)
        {
            $.ajax({
                type:'GET',
                url: "{{ route('admin.warehousing.categories.fetch_sub') }}",
                data:'main='+id+'&&_token = <?php echo csrf_token() ?>',
                success:function(response) {
                    var len = 0;
                    $('#sub_div').empty();
                    if(response['sub'] != null)
                    {
                        len = response['sub'].length;
                    }

                    var tr_str ="<select class='form-control  IRANYekanRegular' name='sub_cat_id' id='sub_cat_id'>"+
                        "<option value=''>  زیردسته مورد نظر را انتخاب کنید...</option>";
                    for(var i=0; i<len; i++)
                    {
                        tr_str += "<option value='"+response['sub'][i].id+"' class='dropdopwn'>"+response['sub'][i].title+"</option>";
                    }
                    tr_str +="</select>";

                    $("#sub_div").append(tr_str);
                }
            });
        }
        $("#expireDate").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#expireDate",
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


        function reset(id)
        {
            document.getElementById(id).value='';
        }

    </script>
@endsection



