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
                              {{ Breadcrumbs::render('services.create') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fab fa-servicestack page-icon"></i>
                             ایجاد سرگروه جدید
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main" style="margin:auto">

                                <form class="form-horizontal" action="{{ route('admin.services.store') }}" method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <label for="name" class="control-label IRANYekanRegular">نام سرگروه</label>
                                            <input type="text" class="form-control input" name="name" id="name" placeholder="نام سرگروه را وارد کنید" value="{{ old('name') }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('name') }} </span>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="slogan" class="control-label IRANYekanRegular">شعار<span class="text-danger ml-1">(اختیاری)</span></label>
                                            <input type="text" class="form-control input" name="slogan" id="slogan" placeholder="شعار را وارد کنید" value="{{ old('slogan') }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('slogan') }} </span>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-12">
                                            <label for="desc" class="col-form-label IRANYekanRegular">توضیحات</label>
                                            <textarea  id="desc" name="desc" placeholder="توضیحات...">{{ old('desc') }}</textarea>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('desc') }} </span>
                                        </div>
                                    </div>

                                    <div class="row mt-2">

                                        <div class="col-12 col-md-4">
                                            <label for="prent" class="col-form-label text-md-right IRANYekanRegular" id="prent" >دسته اصلی:</label>
                                            <select class='form-control dropdopwn' name='parent' id='parent' onchange="subcat(this.value)">
                                                <option value='' class='dropdopwn'>دسته اصلی را وارد نمایید...</option>
                                                @foreach($parents as $parent)
                                                    <option value="{{ $parent->id }}" class="dropdopwn" {{ $parent->id==old('parent')?'selected':'' }}>{{ $parent->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('sport_league') }} </span>
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <label for="child" class="col-form-label text-md-right IRANYekanRegular" id="prent" >دسته فرعی:</label>
                                            <div id="child_cat">
                                                <select class='form-control dropdopwn' name='child' id='child'>
                                                    <option value='' class='dropdopwn'>دسته فرعی را وارد نمایید...</option>
                                                    @if(old('parent'))
                                                    @foreach(App\Models\ServiceCategory::where('parent_id',old('parent'))->where('status',App\Enums\Status::Active)->get() as $cat)
                                                    <option value='{{ $cat->id }}' class='dropdopwn' {{ $cat->id==old('child')?'selected':'' }}>{{ $cat->name }}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('child') }} </span>
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <label for="label" class="col-form-label text-md-right IRANYekanRegular" id="prent" >برچسب</label>
                                            <select class='form-control dropdopwn' name='label' id='label'>
                                                <option value='' class='dropdopwn'>بدون برچسب</option>
                                                <option value='{{ App\Enums\Lables::Suggested }}' class='dropdopwn'>{{ App\Enums\Lables::Suggested }}</option>
                                                <option value='{{ App\Enums\Lables::Popular }}' class='dropdopwn'>{{ App\Enums\Lables::Popular }}</option>
                                            </select>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('label') }} </span>
                                        </div>

                                    </div>

                                    <div class="row mt-3">
                                        <div class="form-group col-12 col-md-4">
                                            <div class="fileupload btn btn-success waves-effect waves-light">
                                                <span><i class="mdi mdi-cloud-upload mr-1"></i>تصویر شاخص</span>
                                                <input type="file" class="upload" name="thumbnail" value="" accept="image/*">
                                            </div>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('thumbnail') }} </span>
                                        </div>

                                        <div class="form-group col-12 col-md-4">
                                            <input type="checkbox" id="displayed" name="displayed" value="displayed" @if(old('displayed')=='displayed') checked @endif>
                                            <label for="vehicle3" class="IR">نمایش در صفحه اصلی</label><br>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('displayed') }} </span>
                                        </div>


                                        <div class="col-12 col-md-4" style="display:inherit;">
                                            <input type="radio" id="active"  name="status" value="{{ App\Enums\Status::Active }}" checked >
                                            &nbsp;
                                            <label for="active" class="mt-2 IR">فعال</label><br>
                                            &nbsp;&nbsp; &nbsp;
                                            <input type="radio" id="deactive" name="status" value="{{ App\Enums\Status::Deactive }}">
                                            &nbsp;
                                            <label for="deactive" class="mt-2 IR">غیرفعال</label><br>
                                        </div>


                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-12">
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
    <script>

        //گرفتن  زیردسته های مربوطه توسط ایجکس
        function subcat(cat_id)
        {
            $.ajax({
                url: "{{ route('admin.services.categories.fetch_child') }}",
                type: 'get',
                dataType: 'json',
                data:'cat_id='+cat_id,
                success: function(response)
                {
                    var len = 0;
                    $('#child_cat').empty();
                    if(response['childs'] != null){
                        len = response['childs'].length;
                    }

                    var tr_str ="<select class='form-control dropdopwn' name='child' id='child'>"+
                        "<option value='' class='dropdopwn'>دسته فرعی را انتخاب کنید...</option>";
                    for(var i=0; i<len; i++)
                    {
                        tr_str += "<option value='"+response['childs'][i].id+"' class='dropdopwn'>"+response['childs'][i].name+"</option>";
                    }
                    tr_str +="</select>";


                    $("#child_cat").append(tr_str);

                }
            });
        }

    </script>
@endsection
