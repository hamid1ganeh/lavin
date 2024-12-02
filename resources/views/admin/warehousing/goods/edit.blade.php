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
                                {{ Breadcrumbs::render('warehousing.goods.edit',$good) }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-shopping-cart page-icon"></i>
                             ویرایش کالا
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

                                <form class="form-horizontal" action="{{ route('admin.warehousing.goods.update',$good) }}" method="post">
                                     @csrf
                                    @method('patch')
                                    <div class="form-group row">
                                        <div class="col-12 col-md-6">
                                            <label for="factor_number" class="control-label IRANYekanRegular">شماره فاکتور</label>
                                            <input type="text" class="form-control input text-right" name="factor_number" id="factor_number" placeholder=" شماره فاکتور را وارد کنید" value="{{ old('factor_number') ?? $good->factor_number  }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('factor_number') }} </span>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="code" class="control-label IRANYekanRegular">کد کالا</label>
                                            <input type="text" class="form-control input text-right" name="code" id="code" placeholder="کد کالا را وارد کنید" value="{{ old('code') ?? $good->code  }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('code') }} </span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12 col-md-6">
                                            <label for="title" class="control-label IRANYekanRegular">عنوان</label>
                                            <input type="text" class="form-control input" name="title" id="title" placeholder="عنوان کالا را وارد کنید" value="{{ old('title') ?? $good->title  }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('title') }} </span>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="brand" class="control-label IRANYekanRegular">برند</label>
                                            <input type="text" class="form-control input" name="brand" id="brand" placeholder="برند کالا را وارد کنید" value="{{ old('brand') ?? $good->brand  }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('brand') }} </span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12 col-md-6">
                                            <label for="main" class="col-form-label IRANYekanRegular">دسته بندی اصلی</label>
                                            <select name="main_cat_id" id="main_cat_id"  class="form-control  IRANYekanRegular" onchange="subcat(this.value)">
                                                <option value="">دسته مورد نظر را انتخاب کنید</option>
                                                @foreach($mains as $main)
                                                    <option value="{{ $main->id }}" {{$main->id == old('main_cat_id') || $main->id == $good->main_cat_id ?'selected':'' }}>{{ $main->title }}</option>
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
                                                        <option value="{{ $sub->id }}" {{$sub->id == old('sub_cat_id') || $sub->id == $good->sub_cat_id?'selected':'' }}>{{ $sub->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('sub_cat_id') }} </span>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <div class="col-12 col-md-3">
                                            <label for="unit" class="control-label IRANYekanRegular">واحد مصرفی</label>
                                            <select name="unit" id="unit"  class="form-control  IRANYekanRegular" >
                                                <option value="{{ App\Enums\Unit::cc }}" {{ App\Enums\Unit::cc== old('unit') || App\Enums\Unit::cc== $good->unit?'selected':'' }}>{{ App\Enums\Unit::cc }}</option>
                                                <option value="{{ App\Enums\Unit::mg }}" {{ App\Enums\Unit::mg== old('unit')|| App\Enums\Unit::mg== $good->unit?'selected':'' }}>{{ App\Enums\Unit::mg }}</option>
                                                <option value="{{ App\Enums\Unit::gr }}" {{ App\Enums\Unit::gr== old('unit')|| App\Enums\Unit::gr== $good->unit?'selected':'' }}>{{ App\Enums\Unit::gr }}</option>
                                                <option value="{{ App\Enums\Unit::kg }}" {{ App\Enums\Unit::kg== old('unit')|| App\Enums\Unit::kg== $good->unit?'selected':'' }}>{{ App\Enums\Unit::kg }}</option>
                                                <option value="{{ App\Enums\Unit::t }}" {{ App\Enums\Unit::t== old('unit')|| App\Enums\Unit::t== $good->unit?'selected':'' }}>{{ App\Enums\Unit::t }}</option>
                                                <option value="{{ App\Enums\Unit::mm }}" {{ App\Enums\Unit::mm== old('unit')|| App\Enums\Unit::mm== $good->unit?'selected':'' }}>{{ App\Enums\Unit::mm }}</option>
                                                <option value="{{ App\Enums\Unit::cm }}" {{ App\Enums\Unit::cm== old('unit')|| App\Enums\Unit::cm== $good->unit?'selected':'' }}>{{ App\Enums\Unit::cm }}</option>
                                                <option value="{{ App\Enums\Unit::m }}" {{ App\Enums\Unit::m== old('unit')|| App\Enums\Unit::m== $good->unit?'selected':'' }}>{{ App\Enums\Unit::m }}</option>
                                                <option value="{{ App\Enums\Unit::box }}" {{ App\Enums\Unit::box== old('unit')|| App\Enums\Unit::box== $good->unit?'selected':'' }}>{{ App\Enums\Unit::box }}</option>
                                                <option value="{{ App\Enums\Unit::per }}" {{ App\Enums\Unit::per== old('unit')|| App\Enums\Unit::per== $good->unit?'selected':'' }}>{{ App\Enums\Unit::per }}</option>
                                                <option value="{{ App\Enums\Unit::ma }}" {{ App\Enums\Unit::ma== old('unit')|| App\Enums\Unit::ma== $good->unit?'selected':'' }}>{{ App\Enums\Unit::ma }}</option>
                                                <option value="{{ App\Enums\Unit::a }}" {{ App\Enums\Unit::a== old('unit')|| App\Enums\Unit::a== $good->unit?'selected':'' }}>{{ App\Enums\Unit::a }}</option>
                                                <option value="{{ App\Enums\Unit::v }}" {{ App\Enums\Unit::v== old('unit')|| App\Enums\Unit::v== $good->unit?'selected':'' }}>{{ App\Enums\Unit::v }}</option>
                                                <option value="{{ App\Enums\Unit::Shut }}" {{ App\Enums\Unit::Shut== old('unit')|| App\Enums\Unit::Shut== $good->unit?'selected':'' }}>{{ App\Enums\Unit::Shut }}</option>
                                            </select>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('consumption_unit') }} </span>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <label for="value_per_count" class="control-label IRANYekanRegular">حجم واحد در هر عدد</label>
                                            <input type="text" class="form-control input text-right" name="value_per_count" id=value_per_count" placeholder="حجم واحد هر حدد را وارد کنید" value="{{ old('value_per_count') ?? $good->value_per_count  }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('value_per_count') }} </span>
                                        </div>

                                        <div class="col-12 col-md-3">
                                            <label for="count_stock" class="control-label IRANYekanRegular">موجودی تعداد در انبار</label>
                                            <input type="text" class="form-control input text-right" name="count_stock" id="count_stock" placeholder="موجودی جبعه در انبار را وارد کنید" value="{{ old('count_stock') ?? $good->count_stock  }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('count_stock') }} </span>
                                        </div>

                                        <div class="col-12 col-md-3">
                                            <label for="price" class="control-label IRANYekanRegular">قیمت واحد مصرفی</label>
                                            <input type="text" class="form-control input text-left" name="price" id=price" placeholder="قیمت واحد مصرفی کالا را وارد کنید" value="{{ old('price') ?? $good->price }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('price') }} </span>
                                        </div>

                                    </div>


                                    <div class="form-group row">
                                        <div class="col-12 col-md-2">
                                            <label for="expireDate" class="control-label IRANYekanRegular">تاریخ انقضاء</label>
                                            <input type="text"   class="form-control text-center" id="expireDate" name="expireDate" readonly value="{{ old('expireDate') ?? $good->expireDate()  }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('expireDate') }} </span>
                                            <i class="mdi mdi-replay text-danger font-20 cursor-pointer" title="پاک کردن" onclick="reset('expireDate')"></i>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <label for="description" class="control-label IRANYekanRegular">توضیحات</label>
                                            <input type="text" class="form-control input text-left" name="description" id=description" placeholder="توضیحات کالا را وارد کنید" value="{{ old('description')  ?? $good->description   }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('description') ?? $good->description  }} </span>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <div class="col-12" style="display:inherit;">
                                            <input type="radio" id="active" name="status" value="{{ App\Enums\Status::Active }}" @if($good->status!=App\Enums\Status::Deactive) checked @endif>
                                            &nbsp;
                                            <label for="active" class="IRANYekanRegular">فعال</label><br>
                                            &nbsp;&nbsp; &nbsp;
                                            <input type="radio" id="deactive" name="status" value="{{ App\Enums\Status::Deactive }}" @if($good->status==App\Enums\Status::Deactive) checked @endif>
                                            &nbsp;
                                            <label for="deactive" class="IRANYekanRegular">غیرفعال</label><br>
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



