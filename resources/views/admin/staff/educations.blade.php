@extends('admin.master')


@section('script')

    <script type="text/javascript">
        $("#start").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#start",
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

        $("#end").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#end",
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

        @foreach($majors as $major)
        $("#start{{ $major->id }}").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#start{{ $major->id }}",
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

        $("#end{{ $major->id }}").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#end{{ $major->id }}",
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
                                {{ Breadcrumbs::render('staff.documents.educations') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <img   width="30px" src="{{ url('images/front/education.png') }}" alt="مدارک تحصیلی">
                            مدارک تحصیلی
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
                                    <div class="btn-group" >

                                        <a href="#create" data-toggle="modal"  title="ایجاد" class="btn btn-sm btn-primary">
                                            <i class="fa fa-plus plusiconfont"></i>
                                            <b class="IRANYekanRegular">ایجاد رشته جدید</b>
                                        </a>

                                        <div class="modal fade" id="create" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xs">
                                                <div class="modal-content">
                                                    <div class="modal-header py-3">
                                                        <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">ایجاد رشته جدید</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <form action="{{ route('admin.staff.documents.educations.store') }}"  method="POST" class="d-inline" id="create-form">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-6 text-left">
                                                                    <label for="major" class="control-label IRANYekanRegular">رشته تحصیلی</label>
                                                                    <input type="text" class="form-control input" name="major" id="major" placeholder="رشته تحصیلی را وارد کنید" value="{{ old('major') }}" required>
                                                                </div>
                                                                <div class="col-6 text-left">
                                                                    <label for="orientation" class="control-label IRANYekanRegular">گرایش</label>
                                                                    <input type="text" class="form-control input" name="orientation" id="orientation" placeholder=" گرایش را وارد کنید" value="{{ old('orientation') }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-6 text-left">
                                                                    <label for="level" class="control-label IRANYekanRegular">مدرک</label>
                                                                    <input type="text" class="form-control input" name="level" id="level" placeholder="مدرک  را وارد کنید" value="{{ old('level') }}" required>
                                                                </div>
                                                                <div class="col-6 text-left">
                                                                    <label for="center_name" class="control-label IRANYekanRegular">واحد آموزشی</label>
                                                                    <input type="text" class="form-control input" name="center_name" id="center_name" placeholder=" واحد آموزشی را وارد کنید" value="{{ old('center_name') }}" required>
                                                                </div>
                                                            </div>

                                                            <div class="row mt-2">
                                                                <div class="col-md-6 text-left">
                                                                    <label for="province" class="IRANYekanRegular">استان</label>
                                                                    <select class='form-control dropdown' name='province_id' id='province' onchange="getCities(this.value)" required>
                                                                        <option value="">استان مورد نظر را انتخاب کنید...</option>
                                                                        @foreach($provinces as $province)
                                                                            <option value="{{ $province->id }}" {{ $province->id==old('province_id')?'selected':'' }}>{{ $province->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <span class="form-text text-danger erroralarm"> {{ $errors->first('province_id') }} </span>
                                                                </div>

                                                                <div class="col-md-6 text-left">
                                                                    <label for="city" class="IRANYekanRegular">شهر</label>
                                                                    <div id="fetch_city">
                                                                        <select class='form-control dropdown' name='city_id' id='city' required>
                                                                            <option value="">شهر مورد نظر را انتخاب کنید...</option>
                                                                            @if(old('province_id'))
                                                                                @foreach(App\Models\City::where('province_id',old('province'))->get() as $city)
                                                                                    <option value="{{ $city->id }}" {{ $city->id==old('city')?'selected':'' }}>{{ $city->name }}</option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                    <span class="form-text text-danger erroralarm"> {{ $errors->first('city_id') }} </span>
                                                                </div>
                                                            </div>

                                                            <div class="row mt-2">
                                                                <div class="col-md-6 text-left">
                                                                    <label for="start" class="col-form-label IRANYekanRegular">تاریخ شروع</label>
                                                                    <input type="text"   class="form-control text-center" id="start" name="start"  readonly required>
                                                                </div>
                                                                <div class="col-md-6 text-left">
                                                                    <label for="end" class="col-form-label IRANYekanRegular">تاریخ پایان</label>
                                                                    <input type="text"   class="form-control text-center" id="end" name="end"  readonly required>
                                                                </div>
                                                            </div>

                                                            <div class="row mt-2">
                                                                <div class="col-6 text-left">
                                                                    <label for="grade" class="control-label IRANYekanRegular">معدل</label>
                                                                    <input class="form-control input text-center" type="number" step="0.01" name="grade" id="grade" value="{{ old('grade') ?? 10 }}" min="0" max="20" required>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>

                                                    <div class="modal-footer">
                                                       <button type="submit" title="حذف" class="btn btn-primary px-8" form="create-form">ثبت</button>
                                                        &nbsp; &nbsp;&nbsp;
                                                        <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="tech-companies-1" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th><b class="IRANYekanRegular">ردیف</b></th>
                                            <th><b class="IRANYekanRegular">رشته</b></th>
                                            <th><b class="IRANYekanRegular">گرایش</b></th>
                                            <th><b class="IRANYekanRegular">مقطع</b></th>
                                            <th><b class="IRANYekanRegular">واحد آموزشی</b></th>
                                            <th><b class="IRANYekanRegular">استان</b></th>
                                            <th><b class="IRANYekanRegular">شهر</b></th>
                                            <th><b class="IRANYekanRegular">معدل</b></th>
                                            <th><b class="IRANYekanRegular">شروع</b></th>
                                            <th><b class="IRANYekanRegular">پایان</b></th>
                                            <th><b class="IRANYekanRegular">اقدامات</b></th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach($majors as $index=>$major)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $major->major }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $major->orientation }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $major->level }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $major->center_name }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $major->proviance->name ?? ''}}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $major->city->name ?? ''}}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $major->grade }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $major->startAt() }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $major->endAt() }}</strong></td>
                                            <td>

                                                <a href="#edit{{ $major->id }}" data-toggle="modal" class="font18 m-1" title="ویرایش">
                                                    <i class="fa fa-edit text-success"></i>
                                                </a>

                                                <div class="modal fade" id="edit{{ $major->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">ویرایش رشته</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">

                                                                <form action="{{ route('admin.staff.documents.educations.update',$major) }}"  method="POST" class="d-inline" id="update-form{{ $major->id }}">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <div class="row">
                                                                        <div class="col-6 text-left">
                                                                            <label for="major" class="control-label IRANYekanRegular">رشته تحصیلی</label>
                                                                            <input type="text" class="form-control input" name="major" id="major{{ $province->id }}" placeholder="رشته تحصیلی را وارد کنید" value="{{ old('major') ?? $major->major }}" required>
                                                                        </div>
                                                                        <div class="col-6 text-left">
                                                                            <label for="orientation" class="control-label IRANYekanRegular">گرایش</label>
                                                                            <input type="text" class="form-control input" name="orientation" id="orientation{{ $province->id }}" placeholder=" گرایش را وارد کنید" value="{{ old('orientation') ?? $major->orientation }}" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mt-2">
                                                                        <div class="col-6 text-left">
                                                                            <label for="level" class="control-label IRANYekanRegular">مدرک</label>
                                                                            <input type="text" class="form-control input" name="level" id="level{{ $province->id }}" placeholder="مدرک  را وارد کنید" value="{{ old('level') ?? $major->level }}" required>
                                                                        </div>
                                                                        <div class="col-6 text-left">
                                                                            <label for="center_name" class="control-label IRANYekanRegular">واحد آموزشی</label>
                                                                            <input type="text" class="form-control input" name="center_name" id="center_name{{ $province->id }}" placeholder=" واحد آموزشی را وارد کنید" value="{{ old('center_name') ?? $major->center_name }}" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mt-2">
                                                                        <div class="col-md-6 text-left">
                                                                            <label for="province" class="IRANYekanRegular">استان</label>
                                                                            <select class='form-control dropdown' name='province_id' id='province{{ $province->id }}' onchange="getCitiesForUpdate(this.value,{{ $major->id }})" required>
                                                                                <option value="">استان مورد نظر را انتخاب کنید...</option>
                                                                                @foreach($provinces as $province)
                                                                                    <option value="{{ $province->id }}" {{ $province->id==$major->province_id?'selected':'' }}>{{ $province->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('province_id') }} </span>
                                                                        </div>

                                                                        <div class="col-md-6 text-left">
                                                                            <label for="city" class="IRANYekanRegular">شهر</label>
                                                                            <div id="fetch_city{{ $major->id }}">
                                                                                <select class='form-control dropdown' name='city_id' id='city{{ $province->id }}' required>
                                                                                    <option value="">شهر مورد نظر را انتخاب کنید...</option>
                                                                                    @if(!is_null($major->province_id))
                                                                                        @foreach(App\Models\City::where('province_id',$major->province_id)->get() as $city)
                                                                                            <option value="{{ $city->id }}" {{ $city->id==$major->city_id?'selected':'' }}>{{ $city->name }}</option>
                                                                                        @endforeach
                                                                                    @endif
                                                                                </select>
                                                                            </div>
                                                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('city_id') }} </span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mt-2">
                                                                        <div class="col-md-6 text-left">
                                                                            <label for="start" class="col-form-label IRANYekanRegular">تاریخ شروع</label>
                                                                            <input type="text"   class="form-control text-center" id="start{{ $major->id }}" name="start"  readonly  required value="{{ \Morilog\Jalali\CalendarUtils::convertNumbers(\Morilog\Jalali\CalendarUtils::strftime('Y/m/d',strtotime($major->start))) }}">
                                                                        </div>
                                                                        <div class="col-md-6 text-left">
                                                                            <label for="end" class="col-form-label IRANYekanRegular">تاریخ پایان</label>
                                                                            <input type="text"   class="form-control text-center" id="end{{ $major->id }}" name="end"  readonly required  value="{{ \Morilog\Jalali\CalendarUtils::convertNumbers(\Morilog\Jalali\CalendarUtils::strftime('Y/m/d',strtotime($major->end))) }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mt-2">
                                                                        <div class="col-6 text-left">
                                                                            <label for="grade" class="control-label IRANYekanRegular">معدل</label>
                                                                            <input class="form-control input text-center" type="number" step="0.01" name="grade" id="grade{{ $province->id }}" value="{{ old('grade') ??  $major->grade }}" min="0" max="20" required>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="submit" title="حذف" class="btn btn-success px-8" form="update-form{{ $major->id }}">بروزرسانی</button>
                                                                &nbsp;
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                              <a href="#remove{{ $major->id }}" data-toggle="modal" class="font18 m-1" title="حذف">
                                                 <i class="fa fa-trash text-danger"></i>
                                               </a>

                                               <!-- Remove Modal -->
                                                <div class="modal fade" id="remove{{ $major->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف رشته</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید رشته {{ $major->major }} را حذف کنید؟</h5>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ route('admin.staff.documents.educations.delete',$major) }}"  method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" title="حذف" class="btn btn-danger px-8">حذف</button>
                                                                </form>
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
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

    <script>
        //گرفتن  زیردسته های مربوطه توسط ایجکس
        function getCities(provance_id)
        {
            $.ajax({
                url: "{{ route('admin.fetch_cities') }}",
                type: 'get',
                dataType: 'json',
                data:'provance_id='+provance_id,
                success: function(response)
                {
                    var len = 0;
                    $('#fetch_city').empty();
                    if(response['cities'] != null)
                    {
                        len = response['cities'].length;
                    }

                    var tr_str ="<select class='form-control dropdown' name='city_id' id='city'>"+
                        "<option value='' class='dropdopwn'>شهر مورد نظر را انتخاب کنید...</option>";
                    for(var i=0; i<len; i++)
                    {
                        tr_str += "<option value='"+response['cities'][i].id+"'>"+response['cities'][i].name+"</option>";
                    }
                    tr_str +="</select>";

                    $("#fetch_city").append(tr_str);

                }
            });
        }

        //گرفتن  زیردسته های مربوطه توسط ایجکس
        function getCitiesForUpdate(provance_id,id)
        {
            $.ajax({
                url: "{{ route('admin.fetch_cities') }}",
                type: 'get',
                dataType: 'json',
                data:'provance_id='+provance_id,
                success: function(response)
                {
                    var len = 0;
                    $('#fetch_city'+id).empty();
                    if(response['cities'] != null)
                    {
                        len = response['cities'].length;
                    }

                    var tr_str ="<select class='form-control dropdown' name='city_id'>"+
                        "<option value='' class='dropdopwn'>شهر مورد نظر را انتخاب کنید...</option>";
                    for(var i=0; i<len; i++)
                    {
                        tr_str += "<option value='"+response['cities'][i].id+"'>"+response['cities'][i].name+"</option>";
                    }
                    tr_str +="</select>";

                    $("#fetch_city"+id).append(tr_str);
                }
            });
        }
    </script>

@endsection
