@extends('admin.master')


@section('script')
    <script type="text/javascript">
        $("#date").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#date",
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

        @foreach($retrainings as $retraining)
        $("#date{{ $retraining->id }}").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#date{{ $retraining->id }}",
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
                                {{ Breadcrumbs::render('staff.documents.retrainings') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <img   width="30px" src="{{ url('images/front/retraining.png') }}" alt="بازآموزی ها">
                            بازآموزی ها
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
                                            <b class="IRANYekanRegular">ایجاد بازآموزی جدید</b>
                                        </a>

                                        <div class="modal fade" id="create" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xs">
                                                <div class="modal-content">
                                                    <div class="modal-header py-3">
                                                        <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">ایجاد بازآموزی جدید</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <form action="{{ route('admin.staff.documents.retrainings.store') }}"  method="POST" class="d-inline" id="create-form" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-6 text-left">
                                                                    <label for="major" class="control-label IRANYekanRegular">نام دوره</label>
                                                                    <input type="text" class="form-control input" name="name" id="name" placeholder="نام دوره را وارد کنید" value="{{ old('name') }}" required>
                                                                </div>
                                                                <div class="col-6 text-left">
                                                                    <label for="orientation" class="control-label IRANYekanRegular">مدت زمان</label>
                                                                    <input type="text" class="form-control input" name="duration" id="duration" placeholder=" مدت زمان دوره را وارد کنید" value="{{ old('duration') }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-6 text-left">
                                                                    <label for="institution" class="control-label IRANYekanRegular">واحد آموزشی</label>
                                                                    <input type="text" class="form-control input" name="institution" id="institution" placeholder=" واحد آموزشی را وارد کنید" value="{{ old('institution') }}" required>
                                                                </div>
                                                                <div class="col-6 text-left">
                                                                    <label for="level" class="control-label IRANYekanRegular">مرجع صدور مدرک</label>
                                                                    <input type="text" class="form-control input" name="reference" id="reference" placeholder="مرجع صدور مدرک را وارد کنید" value="{{ old('reference') }}" required>
                                                                </div>
                                                            </div>

                                                            <div class="row mt-2">
                                                                <div class="col-md-6 text-left">
                                                                    <label for="start" class="col-form-label IRANYekanRegular">تاریخ صدور مدرک</label>
                                                                    <input type="text"   class="form-control text-center" id="date" name="date"  readonly required>
                                                                </div>

                                                                <div class="col-md-6 text-left mt-4">
                                                                    <div class="fileupload btn btn-success waves-effect waves-light">
                                                                        <span><i class="mdi mdi-cloud-upload"></i>آپلود مدرک</span>
                                                                        <input type="file" class="upload" name="image" id="image" value="" accept="image/*" required>
                                                                        <output id="list"></output>
                                                                    </div>
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
                                            <th><b class="IRANYekanRegular">نام دوره</b></th>
                                            <th><b class="IRANYekanRegular">مدت دوره</b></th>
                                            <th><b class="IRANYekanRegular">مرجع صدور مدرک</b></th>
                                            <th><b class="IRANYekanRegular">تاریخ صدور مدرک</b></th>
                                            <th><b class="IRANYekanRegular">تصویر گواهینامه</b></th>
                                            <th><b class="IRANYekanRegular">اقدامات</b></th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach($retrainings as $index=>$retraining)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $retraining->name }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $retraining->duration }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $retraining->reference }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $retraining->date() }}</strong></td>
                                            <td>
                                                @php
                                                    $image = $retraining->get_image('original');
                                                @endphp
                                                @if(!is_null( $image))
                                                    <a href="{{ $image  }}" target="_blank">
                                                        <img src="{{ $image }}" width="40" height="40">
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="#edit{{ $retraining->id }}" data-toggle="modal" class="font18 m-1" title="ویرایش">
                                                    <i class="fa fa-edit text-success"></i>
                                                </a>

                                                <div class="modal fade" id="edit{{ $retraining->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">ویرایش بازآموزی</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ route('admin.staff.documents.retrainings.update',$retraining) }}"  method="POST" class="d-inline" id="update-form{{ $retraining->id }}" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <div class="row">
                                                                        <div class="col-6 text-left">
                                                                            <label for="major" class="control-label IRANYekanRegular">نام دوره</label>
                                                                            <input type="text" class="form-control input" name="name" id="name" placeholder="نام دوره را وارد کنید" value="{{ old('name') ?? $retraining->name }}" required>
                                                                        </div>
                                                                        <div class="col-6 text-left">
                                                                            <label for="orientation" class="control-label IRANYekanRegular">مدت زمان</label>
                                                                            <input type="text" class="form-control input" name="duration" id="duration" placeholder=" مدت زمان دوره را وارد کنید" value="{{ old('duration') ?? $retraining->duration }}" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mt-2">
                                                                        <div class="col-6 text-left">
                                                                            <label for="institution" class="control-label IRANYekanRegular">واحد آموزشی</label>
                                                                            <input type="text" class="form-control input" name="institution" id="institution" placeholder=" واحد آموزشی را وارد کنید" value="{{ old('institution') ?? $retraining->institution }}" required>
                                                                        </div>
                                                                        <div class="col-6 text-left">
                                                                            <label for="level" class="control-label IRANYekanRegular">مرجع صدور مدرک</label>
                                                                            <input type="text" class="form-control input" name="reference" id="reference" placeholder="مرجع صدور مدرک را وارد کنید" value="{{ old('reference') ?? $retraining->reference  }}" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mt-2">
                                                                        <div class="col-md-6 text-left">
                                                                            <label for="start" class="col-form-label IRANYekanRegular">تاریخ صدور مدرک</label>
                                                                            <input type="text"   class="form-control text-center" id="date{{ $retraining->id }}" name="date"  readonly required  value="{{ \Morilog\Jalali\CalendarUtils::convertNumbers(\Morilog\Jalali\CalendarUtils::strftime('Y/m/d',strtotime($retraining->date))) }}">
                                                                        </div>

                                                                        <div class="col-md-6 text-left mt-4">
                                                                            <div class="fileupload btn btn-success waves-effect waves-light">
                                                                                <span><i class="mdi mdi-cloud-upload"></i>آپلود مدرک</span>
                                                                                <input type="file" class="upload" name="image" id="image" value="" accept="image/*">
                                                                                <output id="list{{ $retraining->id }}" ></output>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                </form>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="submit" title="بروزرسانی" class="btn btn-success px-8" form="update-form{{ $retraining->id }}">بروزرسانی</button>
                                                                &nbsp;
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                              <a href="#remove{{ $retraining->id }}" data-toggle="modal" class="font18 m-1" title="حذف">
                                                 <i class="fa fa-trash text-danger"></i>
                                               </a>

                                               <!-- Remove Modal -->
                                                <div class="modal fade" id="remove{{ $retraining->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف بازآموزی</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید بازآموزی {{ $retraining->name }} را حذف کنید؟</h5>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ route('admin.staff.documents.retrainings.delete',$retraining) }}"  method="POST" class="d-inline">
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
        function handleFileSelect(evt) {
            var files = evt.target.files; // FileList object

            // files is a FileList of File objects. List some properties.
            var output = [];
            for (var i = 0, f; f = files[i]; i++) {
                output.push('<li><strong>',
                    escape(f.name),
                    '</strong>',
                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="remove()">',
                    '<span aria-hidden="true">&times;</span>',
                    '</button>',
                    '</li>');
            }
            document.getElementById('list').innerHTML = '<ul>' + output.join('') + '</ul>';
        }

        document.getElementById('image').addEventListener('change', handleFileSelect, false);

        function remove()
        {
            document.getElementById('image').value = "";
            document.getElementById('list').innerHTML ='';
        }
    </script>


    @endsection
