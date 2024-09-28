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
{{--                            {{ Breadcrumbs::render('reserves.create') }}--}}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fab fa-first-order-alt page-icon"></i>
                               رزو
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
                                <form class="form-horizontal" action="{{ route('admin.reserves.store') }}" method="post">
                                   @csrf
                                    <div class="form-group row mt-1">
                                        <div class="col-12">
                                            <label for="service" class="control-label IRANYekanRegular">سرویس</label>
                                            <select class="widht-100 form-control select2" name="service" id="service" onchange="details(this.value)">
                                                <option value="">سرویس مورد نظر را انتخاب کنید...</option>
                                                @foreach($services as $service)
                                                <option value="{{ $service->id }}" @if($service->id == $reserve->service_id) selected @endif>{{ $service->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('service') }} </span>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-12">
                                            <label for="detail" class="control-label IRANYekanRegular">جزئیات سرویس</label>
                                            <div id="detail_div">
                                                <select class="widht-100 form-control select2" name="detail" id="detail">
                                                    @foreach( $details as $detail)
                                                    <option value="{{ $detail->id }}">{{ $detail->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('detail') }} </span>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-12">
                                            <label for="branch" class="control-label IRANYekanRegular">شعبه</label>
                                            <div id="branch_div">
                                                <select class="widht-100 form-control select2" name="branch" id="branch">
                                                    <option value=""> شعبه مورد نظر را انتخاب کنید...</option>
                                                    @foreach($selected->branches as $branch)
                                                        <option value="{{ $branch->id }}" {{ $branch->id == $reserve->branch_id?'selected':'' }}>{{ $branch->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('branch') }} </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-12">
                                            <label for="doctor" class="control-label IRANYekanRegular">پزشک</label>
                                            <div id="doctor_div">
                                                <select class="widht-100 form-control select2" name="doctor" id="doctor">
                                                    <option value=""> پزشک مورد نظر را انتخاب کنید...</option>
                                                    @foreach($selected->doctors as $doctor)
                                                        <option value="{{ $doctor->id }}" {{ $doctor->id == $reserve->doctor_id?'selected':'' }}>{{ $doctor->fullname }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('doctor') }} </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-12">
                                            <label for="adviser" class="control-label IRANYekanRegular">مشاور</label>
                                            <div id="adviser_div">
                                                <select class="widht-100 form-control select2" name="adviser" id="adviser">
                                                    <option value="">عدم نیاز به مشاور</option>
                                                    @foreach($selected->advisers as $adviser)
                                                        <option value="{{ $adviser->id }}" {{ (!is_null($reserve->adviser_id) && $adviser->id == $reserve->adviser->adviser_id)?'selected':'' }}>{{ $adviser->fullname }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('adviser') }} </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <label for="reception-desc" class="control-label IRANYekanRegular">توضیحات پذیرش</label>
                                            <input type="text" class="form-control input" name="reception_desc" id="reception-desc" placeholder="توضیحات پذیرش را وارد کنید..." value="{{ old('reception_desc') ?? $reserve->reception_desc }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('reception_desc') }} </span>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">ثبت</button>
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


    @section('script')
        <script type="text/javascript">
         $("#user").select2({

            placeholder: '... نام و نام خانوادگی یا شماره موبایل یا شماره ملی',
            ajax: {
                url: '{{ route("admin.users.fetch") }}',

                processResults: function (data) {
                    let res = [];

                    $.each(data.data, function (index, item) {
                        res.push({
                            'id': item.id,
                            'text': item.name
                        });
                    });


                    return {
                        results: res
                    };
                }
            }
        });



        function details(id)
        {
            $.ajax({
              type:'GET',
              url: "{{ route('admin.detailsfetch') }}",
              data:'service='+id+'&&_token = <?php echo csrf_token() ?>',
              success:function(response) {
                    var len = 0;
                    $('#detail_div').empty();
                    if(response['details'] != null)
                     {
                         len = response['details'].length;
                     }

                    var tr_str ="<select class='widht-100 form-control select2' name='detail' id='detail' onchange='doctors(this.value)'>"+
                    "<option value=''>  جزئیات سرویس را انتخاب کنید...</option>";
                    for(var i=0; i<len; i++)
                    {
                        tr_str += "<option value='"+response['details'][i].id+"' class='dropdopwn'>"+response['details'][i].name+"</option>";
                    }
                    tr_str +="</select>";

                   $("#detail_div").append(tr_str);
                }
          });
        }


        function doctors(id)
        {
            $.ajax({
               type:'GET',
               url: "{{ route('admin.doctorsfetch') }}",
               data:'service='+id+'&&_token = <?php echo csrf_token() ?>',
               success:function(response) {

                 var len = 0;
                $('#doctor_div').empty();
                if(response['doctors'] != null)
                {
                    len = response['doctors'].length;
                }

                var tr_str ="<select class='widht-100 form-control select2' name='doctor' id='doctor'>";
                for(var i=0; i<len; i++)
                {
                    tr_str += "<option value='"+response['doctors'][i].id+"' class='dropdopwn'>"+response['doctors'][i].name+"</option>";
                }
                tr_str +="</select>";

                $("#doctor_div").append(tr_str);


                   len = 0;
                   $('#adviser_div').empty();
                   if(response['advisers'] != null)
                   {
                       len = response['advisers'].length;
                   }

                   var tr_str ="<select class='widht-100 form-control select2' name='adviser' id='adviser'>"+"<option value=''>عدم نیاز به مشاوره</option>";
                   for(var i=0; i<len; i++)
                   {
                       tr_str += "<option value='"+response['advisers'][i].id+"' class='dropdopwn'>"+response['advisers'][i].name+"</option>";
                   }
                   tr_str +="</select>";

                   $("#adviser_div").append(tr_str);

                   len = 0;
                   $('#branch_div').empty();
                   if(response['advisers'] != null)
                   {
                       len = response['branches'].length;
                   }

                   var tr_str ="<select class='widht-100 form-control select2' name='branch' id='branch'>";
                   for(var i=0; i<len; i++)
                   {
                       tr_str += "<option value='"+response['branches'][i].id+"' class='dropdopwn'>"+response['branches'][i].name+"</option>";
                   }
                   tr_str +="</select>";

                   $("#branch_div").append(tr_str);

               }
            });
        }



        </script>
    @endsection

@endsection
