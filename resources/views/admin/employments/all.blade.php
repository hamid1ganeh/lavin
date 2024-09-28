@extends('admin.master')


@section('script')

    <script type="text/javascript">
        @foreach($employments as  $employment)
        $("#start-education{{ $employment->id}}").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#start-education{{ $employment->id}}",
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

        $("#end-education{{ $employment->id}}").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#end-education{{ $employment->id}}",
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

        function reset(id)
        {
            document.getElementById(id).value='';
        }
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
                                 {{ Breadcrumbs::render('employments.employments.index') }}
                                </ol>
                            </div>
                            <h4 class="page-title">
                                <i class="mdi mdi-briefcase page-icon"></i>
                                درخواست های استخدام
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
                                <div class="table-responsive">
                                    <table id="tech-companies-1" class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th><b class="IRANYekanRegular">ردیف</b></th>
                                            <th><b class="IRANYekanRegular">نام و نام خانوادگی</b></th>
                                            <th><b class="IRANYekanRegular">موبایل</b></th>
                                            <th><b class="IRANYekanRegular">دسته اصلی</b></th>
                                            <th><b class="IRANYekanRegular">دسته فرعی</b></th>
                                            <th><b class="IRANYekanRegular">شغل</b></th>
                                            <th><b class="IRANYekanRegular">درباه شما</b></th>
                                            <th><b class="IRANYekanRegular">رزومه</b></th>
                                            <th><b class="IRANYekanRegular">وضعیت</b></th>
                                            <th><b class="IRANYekanRegular">اقدامات</b></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($employments as $index=>$employment)
                                            <tr>
                                                <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $employment->fullname ?? '' }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $employment->mobile ?? '' }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $employment->job->main_category->title ?? '' }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $employment->job->sub_category->title ?? '' }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $employment->job->title ?? '' }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $employment->about ?? '' }}</strong></td>
                                                <td>
                                                    <a href="{{ url('/').$employment->resume }}" target="_blank">
                                                        <strong class="IRANYekanRegular">دانلود</strong>
                                                    </a>
                                                </td>
                                                <td>
                                                    <strong class="IRANYekanRegular">
                                                        @if($employment->status == App\Enums\EmploymentStatus::pending)
                                                            <span class="badge badge-warning IR p-1">در انتظار</span>
                                                        @elseif($employment->status == App\Enums\EmploymentStatus::refer)
                                                            <span class="badge badge-light-purple IR p-1">ارجاع</span>
                                                        @elseif($employment->status == App\Enums\EmploymentStatus::checked)
                                                            <span class="badge badge-success IR p-1">بررسی شده</span>
                                                        @elseif($employment->status == App\Enums\EmploymentStatus::reject)
                                                            <span class="badge badge-danger IR p-1">رد شده</span>
                                                        @elseif($employment->status == App\Enums\EmploymentStatus::confirm)
                                                            <span class="badge badge-primary IR p-1">تایید شده</span>
                                                        @elseif($employment->status == App\Enums\EmploymentStatus::education)
                                                            <span class="badge badge-light-info IR p-1">آموزش</span>
                                                        @elseif($employment->status == App\Enums\EmploymentStatus::interview)
                                                            <span class="badge badge-info IR p-1">مصاحبه</span>
                                                        @endif
                                                    </strong>
                                                </td>

                                                <td>

                                                    @if(Auth::guard('admin')->user()->can('employments.response'))
                                                    <a href="#response{{ $employment->id }}" data-toggle="modal" class="btn btn-icon" title="پاسخ به درخواست استخدام">
                                                        <i class="mdi mdi-replay text-info font-20"></i>
                                                    </a>
                                                    <!-- Response Modal -->
                                                    <div class="modal fade" id="response{{ $employment->id }}"  tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs text-left">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">پاسخ به درخواست استخدام</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="{{ route('admin.employments.response', $employment) }}"  method="POST" class="d-inline" id="response-form{{ $employment->id }}">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <div class="form-group">
                                                                            <div class="row">
                                                                                <div class="col-12">
                                                                                    <label for="name" class="control-label IRANYekanRegular">نتیجه</label>
                                                                                    <input type="text" class="form-control input" name="result" id="result" placeholder="نتیجه  را وارد کنید" value="{{ $employment->result }}"  required>
                                                                                    <span class="form-text text-danger erroralarm"> {{ $errors->first('result') }} </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <div class="row">
                                                                                <div class="col-12">
                                                                                    <label for="st" class="col-form-label IRANYekanRegular">وضعیت</label>
                                                                                    <select name="status" id="st"  class="form-control IRANYekanRegular">
                                                                                        <option value="{{ \App\Enums\EmploymentStatus::pending }}" {{$employment->status == \App\Enums\EmploymentStatus::pending?'selected':'' }}>درانتظار</option>
                                                                                        <option value="{{ \App\Enums\EmploymentStatus::checked }}" {{$employment->status == \App\Enums\EmploymentStatus::checked?'selected':'' }}>بررسی شده</option>
                                                                                        <option value="{{ \App\Enums\EmploymentStatus::refer }}" {{$employment->status == \App\Enums\EmploymentStatus::refer?'selected':'' }}>ارجاع</option>
                                                                                        <option value="{{ \App\Enums\EmploymentStatus::reject }}" {{$employment->status == \App\Enums\EmploymentStatus::reject?'selected':'' }}>رد شده</option>
                                                                                        <option value="{{ \App\Enums\EmploymentStatus::interview }}" {{$employment->status == \App\Enums\EmploymentStatus::interview?'selected':'' }}>مصاحبه</option>
                                                                                        <option value="{{ \App\Enums\EmploymentStatus::education }}" {{$employment->status == \App\Enums\EmploymentStatus::education?'selected':'' }}>آموزش</option>
                                                                                        <option value="{{ \App\Enums\EmploymentStatus::confirm }}" {{$employment->status == \App\Enums\EmploymentStatus::confirm?'selected':'' }}>تایید شده</option>
                                                                                    </select>
                                                                                    <span class="form-text text-danger erroralarm"> {{ $errors->first('status') }} </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <div class="row">
                                                                                <div class="col-6">
                                                                                    <label for="startEducation" class="col-form-label IRANYekanRegular">تاریخ شروع آموزش</label>
                                                                                    <input type="text"   class="form-control text-center" id="start-education{{ $employment->id}}" name="startEducation" value="{{ $employment->startEducation() }}"  readonly>
                                                                                    <i class="mdi mdi-replay text-danger font-20 cursor-pointer" title="پاک کردن" onclick="reset('start-education{{ $employment->id }}')"></i>
                                                                                    <span class="form-text text-danger erroralarm"> {{ $errors->first('startEducation') }} </span>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <label for="endEducation" class="col-form-label IRANYekanRegular">تاریخ پایان آموزش</label>
                                                                                    <input type="text"   class="form-control text-center" id="end-education{{ $employment->id}}" name="endEducation"  value="{{ $employment->endEducation() }}" readonly>
                                                                                    <i class="mdi mdi-replay text-danger font-20 cursor-pointer" title="پاک کردن" onclick="reset('end-education{{ $employment->id }}')"></i>
                                                                                    <span class="form-text text-danger erroralarm"> {{ $errors->first('endEducation') }} </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </form>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary px-8" title="ثبت" form="response-form{{ $employment->id }}">ثبت </button>
                                                                    &nbsp;
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">
                                                                        انصراف
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if(Auth::guard('admin')->user()->can('employments.refer'))
                                                    <a href="#refer{{ $employment->id }}" data-toggle="modal" class="btn btn-icon" title="ارجاع به نقش">
                                                        <i class="mdi mdi-forward text-warning font-20"></i>
                                                    </a>

                                                    <!-- Refer Modal -->
                                                    <div class="modal fade" id="refer{{ $employment->id }}"  tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs text-left">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">ارجاع به نقش</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="{{ route('admin.employments.refer', $employment) }}"  method="POST" class="d-inline" id="response-refer{{ $employment->id }}">
                                                                        @csrf
                                                                        @method('PATCH')


                                                                        <div class="form-group">
                                                                            <div class="col-12">
                                                                                <label for="sub_cat_id" class="col-form-label IRANYekanRegular">ارجاع به</label>
                                                                                <select name="role" id="role"  class="form-control IRANYekanRegular">
                                                                                    <option value="" >بدون ارجاع</option>
                                                                                @foreach($roles as $role)
                                                                                    <option value="{{ $role->id }}" {{$employment->role_id == $role->id?'selected':'' }}>{{ $role->name.' ('.$role->description.' )' }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('refer') }} </span>
                                                                            </div>
                                                                        </div>

                                                                    </form>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary px-8" title="ثبت" form="response-refer{{ $employment->id }}">ثبت</button>
                                                                    &nbsp;
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">
                                                                        انصراف
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif

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
