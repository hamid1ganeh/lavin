@extends('admin.master')

@section('script')

    <script type="text/javascript">
        $("#start").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#start",
            textFormat: "yyyy/MM/dd HH:mm:ss",
            isGregorian: false,
            modalMode: false,
            englishNumber: false,
            enableTimePicker: true,
            selectedDateToShow: new Date(),
            calendarViewOnChange: function(param1){
                console.log(param1);
            }
        });
        $("#end").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#end",
            textFormat: "yyyy/MM/dd HH:mm:ss",
            isGregorian: false,
            modalMode: false,
            englishNumber: false,
            enableTimePicker: true,
            selectedDateToShow: new Date(),
            calendarViewOnChange: function(param1){
                console.log(param1);
            }
        });


        @foreach($consumptions as $index=>$consumption)
            $("#start{{$consumption->id}}").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#start{{$consumption->id}}",
            textFormat: "yyyy/MM/dd HH:mm:ss",
            isGregorian: false,
            modalMode: false,
            englishNumber: false,
            enableTimePicker: true,
            selectedDateToShow: new Date(),
            calendarViewOnChange: function(param1){
            console.log(param1);
        }
        });
            $("#end{{$consumption->id}}").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#end{{$consumption->id}}",
            textFormat: "yyyy/MM/dd HH:mm:ss",
            isGregorian: false,
            modalMode: false,
            englishNumber: false,
            enableTimePicker: true,
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
{{--                                {{ Breadcrumbs::render('reserves.consumptions',$reserve) }}--}}
                            </ol>
                        </div>
                        <h4 class="page-title">
                          <i class="fas fa-shopping-cart  page-icon"></i>
                             شات مصرفی لیزر
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
                                    @if(Auth::guard('admin')->user()->can('reserves.consumptions.create') &&
                                        $reserve->status != \App\Enums\ReserveStatus::done)
                                        <div class="btn-group" >
                                            <a href="#create" data-toggle="modal" class="btn btn-primary" title="ایجاد مصرفی جدید">
                                                <i class="fa fa-plus plusiconfont"></i>
                                                <b class="IRANYekanRegular">ایجاد مصرفی جدید</b>
                                            </a>

                                            <!-- Create Modal -->
                                            <div class="modal fade" id="create" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xs">
                                                    <div class="modal-content">
                                                        <div class="modal-header py-3">
                                                            <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">ایجاد مواد مصرفی جدید</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-left">
                                                            <form action="{{ route('admin.reserves.consumptions.lasers.store',$reserve) }}"  method="POST" class="d-inline" id="order">
                                                                @csrf

                                                                <div class="form-group row">
                                                                    <div class="col-12">
                                                                        <label for="good" class="col-form-label IRANYekanRegular">دستگاه لیزر</label>
                                                                        <select name="laser" id="laser"  class="width-100 form-control IRANYekanRegular" required>
                                                                            <option value="">دستگاه لیزر مورد نظر را انتخاب کنید</option>
                                                                            @foreach($lasers as $laser)
                                                                                <option value="{{ $laser->id }}" {{ $laser->id == old('laser')?'selected':'' }}>{{ $laser->device()  }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('laser') }} </span>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <div class="col-12">
                                                                        <label for="service" class="col-form-label IRANYekanRegular">سرویس لیزر</label>
                                                                        <select name="service" id="laser"  class="width-100 form-control IRANYekanRegular" required>
                                                                            <option value="">سرویس لیزر مورد نظر را انتخاب کنید</option>
                                                                            @foreach($laserServices as $service)
                                                                                <option value="{{ $service->id }}" {{ $service->id == old('service')?'selected':'' }}>{{ $service->title  }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('service') }} </span>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <div class="col-md-6">
                                                                        <label for="start" class="col-form-label IRANYekanRegular">زمان شروع</label>
                                                                        <input type="text"   class="form-control text-center" id="start" name="start"  readonly placeholder="زمان شروع" required>
                                                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('start') }} </span>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="end" class="col-form-label IRANYekanRegular">زمان پایان</label>
                                                                        <input type="text"   class="form-control text-center" id="end" name="end"  readonly placeholder="زمان پایان" required>
                                                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('end') }} </span>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <div class="col-12 col-md-6">
                                                                        <label for="shot_number" class="control-label IRANYekanRegular">شماره شات بعد از مصرف</label>
                                                                        <input type="number" class="form-control input text-center" name="shot_number" id="shot_number" min="1" placeholder=" شماره شات بعد از مصرف وارد کنید" value="{{ old('shot_number')  }}" required>
                                                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('shot_number') }} </span>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                         </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary px-8" title="ثبت" form="order">ثبت</button>
                                                            &nbsp;
                                                            <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                   @endif
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="tech-companies-1" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th><b class="IRANYekanRegular">ردیف</b></th>
                                        <th><b class="IRANYekanRegular">دستگاه لیزر</b></th>
                                        <th><b class="IRANYekanRegular">سرویس لیزر</b></th>
                                        <th><b class="IRANYekanRegular">شماره شات قبل از مصرف</b></th>
                                        <th><b class="IRANYekanRegular">شماره شات بعد از مصرف</b></th>
                                        <th><b class="IRANYekanRegular">شات مصرفی</b></th>
                                        <th><b class="IRANYekanRegular">زمان شروع</b></th>
                                        <th><b class="IRANYekanRegular">زمان پایان</b></th>
                                        <th><b class="IRANYekanRegular">اقدامات</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($consumptions as $index=>$consumption)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $consumption->device->device() ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $consumption->service->title ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $consumption->recent_shot_number ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $consumption->shot_number ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $consumption->shot ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $consumption->startedAt() ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $consumption->finishedAt() ?? '' }}</strong></td>
                                            <td>
                                            <!-- Remove Modal -->
                                            <div class="modal fade" id="remove{{ $consumption->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xs">
                                                    <div class="modal-content">
                                                        <div class="modal-header py-3">
                                                            <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف مواد مصرفی</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-text">
                                                            <p class="IRANYekanRegular">آیا مطمئن هستید که میخواهید این مواد مصرفی را حذف کنید؟</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form action="{{ route('admin.reserves.consumptions.lasers.delete',[$reserve,$consumption]) }}"  method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger px-8" title="حذف">حذف</button>
                                                            </form>
                                                            <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="edit{{ $consumption->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xs">
                                                    <div class="modal-content">
                                                        <div class="modal-header py-3">
                                                            <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">ویرایش مواد مصرفی</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-left">
                                                            <form action="{{ route('admin.reserves.consumptions.lasers.update',[$reserve,$consumption]) }}"  method="POST" class="d-inline" id="update-form{{$consumption->id}}">
                                                                @csrf
                                                                @method('PATCH')
                                                                <div class="form-group row">
                                                                    <div class="col-12">
                                                                        <label for="good" class="col-form-label IRANYekanRegular">دستگاه لیزر</label>
                                                                        <select name="laser" id="laser"  class="width-100 form-control IRANYekanRegular" required>
                                                                            <option value="">دستگاه لیزر مورد نظر را انتخاب کنید</option>
                                                                            @foreach($lasers as $laser)
                                                                                <option value="{{ $laser->id }}" {{ $laser->id == $consumption->laser_device_id?'selected':'' }}>{{ $laser->device()  }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('laser') }} </span>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <div class="col-12">
                                                                        <label for="service" class="col-form-label IRANYekanRegular">سرویس لیزر</label>
                                                                        <select name="service" id="laser"  class="width-100 form-control IRANYekanRegular" required>
                                                                            <option value="">سرویس لیزر مورد نظر را انتخاب کنید</option>
                                                                            @foreach($laserServices as $service)
                                                                                <option value="{{ $service->id }}" {{ $service->id == $consumption->service_laser_id?'selected':'' }}>{{ $service->title  }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('service') }} </span>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <div class="col-md-6">
                                                                        <label for="start{{$consumption->id}}" class="col-form-label IRANYekanRegular">زمان شروع</label>
                                                                        <input type="text"   class="form-control text-center" id="start{{$consumption->id}}" name="start"  readonly placeholder="زمان شروع" required value="{{ $consumption->getStart() }}">
                                                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('start') }} </span>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="end{{$consumption->id}}" class="col-form-label IRANYekanRegular">زمان پایان</label>
                                                                        <input type="text"   class="form-control text-center" id="end{{$consumption->id}}" name="end"  readonly placeholder="زمان پایان" required value="{{ $consumption->getEnd() }}">
                                                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('end') }} </span>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <div class="col-12 col-md-6">
                                                                        <label for="shot_number" class="control-label IRANYekanRegular">شماره شات بعد از مصرف</label>
                                                                        <input type="number" class="form-control input text-center" name="shot_number" id="shot_number" min="1" placeholder=" شماره شات بعد از مصرف وارد کنید" value="{{ $consumption->shot_number  }}" required>
                                                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('shot_number') }} </span>
                                                                    </div>
                                                                </div>

                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success px-8" title="بروزرسانی"  form="update-form{{$consumption->id}}">بروزرسانی</button>
                                                            &nbsp;&nbsp;
                                                            <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <div class="input-group-append">
                                                    <i class=" ti-align-justify" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                                    <div class="dropdown-menu">
                                                        @if(Auth::guard('admin')->user()->can('reserves.consumptions.edit') &&
                                                             $reserve->status != \App\Enums\ReserveStatus::done)
                                                        <a href="#edit{{ $consumption->id }}" data-toggle="modal" class="dropdown-item IR cursor-pointer" title="ویرایش">
                                                            <i class="fa fa-edit text-success"></i>
                                                            ویرایش
                                                        </a>
                                                        @endif

                                                        @if(Auth::guard('admin')->user()->can('reserves.consumptions.destroy') &&
                                                            $reserve->status != \App\Enums\ReserveStatus::done)
                                                        <a href="#remove{{ $consumption->id }}" data-toggle="modal" class="dropdown-item IR cursor-pointer" title="حذف">
                                                            <i class="fa fa-trash text-danger"></i>
                                                            حذف
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

@section('script')
    <script type="text/javascript">
        function goods(warehouse,id)
        {
            $.ajax({
                type:'GET',
                url: "{{ route('admin.goodsfetch') }}",
                data:'warehouse='+warehouse+'&&_token = <?php echo csrf_token() ?>',
                success:function(response) {
                    var len = 0;
                    $('#'+id).empty();
                    if(response['goods'] != null)
                    {
                        len = response['goods'].length;
                    }

                    var tr_str ="<select class='widht-100 form-control select2 IRANYekanRegular' name='good' id='good'   required>"+
                        "<option value=''>  کالا مصرفی را انتخاب کنید...</option>";
                    for(var i=0; i<len; i++)
                    {
                        tr_str += "<option value='"+response['goods'][i].id+"' class='dropdopwn'>"+response['goods'][i].title+' ('+response['goods'][i].brand+') '+"</option>";
                    }
                    tr_str +="</select>";

                    $("#"+id).append(tr_str);
                }
            });
        }
    </script>

@endsection
