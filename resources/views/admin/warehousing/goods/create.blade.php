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
                                {{ Breadcrumbs::render('employments.jobs.create') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fa fa-graduation-cap page-icon"></i>
                             ایجاد شغل جدید
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

                                <form class="form-horizontal" action="{{ route('admin.employments.jobs.store') }}" method="post">
                                     @csrf

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label for="name" class="control-label IRANYekanRegular">عنوان</label>
                                            <input type="text" class="form-control input" name="title" id="title" placeholder="عنوان زیردسته را وارد کنید" value="{{ old('title')  }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('title') }} </span>
                                        </div>
                                    </div>

                                    <div class="row  p-2">
                                        <div class="form-group col-12 col-md-6">
                                            <label for="main" class="col-form-label IRANYekanRegular">دسته بندی اصلی</label>
                                            <select name="main_cat_id" id="main_cat_id"  class="form-control  IRANYekanRegular" onchange="subcat(this.value)">
                                                <option value="">نقش مورد نظر را انتخاب کنید</option>
                                                @foreach($mains as $main)
                                                    <option value="{{ $main->id }}" {{$main->id == old('main')?'selected':'' }}>{{ $main->title }}</option>
                                                @endforeach
                                            </select>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('main_cat_id') }} </span>
                                        </div>

                                        <div class="form-group col-12 col-md-6">
                                            <label for="sub_cat_id" class="col-form-label IRANYekanRegular">زیردسته</label>
                                            <div id="sub_div">
                                                <select name="sub_cat_id" id="sub_cat_id"  class="form-control IRANYekanRegular">
                                                    <option value="">زیردسته مورد نظر را انتخاب کنید</option>
                                                    @foreach($mains as $main)
                                                        <option value="{{ $main->id }}" {{$main->id == old('main')?'selected':'' }}>{{ $main->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('sub_cat_id') }} </span>
                                        </div>
                                    </div>

                                    <div class="row mt-2 p-2">
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
                url: "{{ route('admin.employments.categories.fetch_sub',) }}",
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

    </script>
@endsection


