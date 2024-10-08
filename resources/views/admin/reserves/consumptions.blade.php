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
                                {{ Breadcrumbs::render('reserves.consumptions',$reserve) }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                          <i class="fas fa-shopping-cart  page-icon"></i>
                            مواد مصرفی رزرو
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
                                        <a href="#create" data-toggle="modal" class="btn btn-primary" title="ایجاد مواد مصرفی جدید">
                                            <i class="fa fa-plus plusiconfont"></i>
                                            <b class="IRANYekanRegular">ایجاد مواد مصرفی جدید</b>
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
                                                        <form action="{{ route('admin.reserves.consumptions.store',$reserve) }}"  method="POST" class="d-inline" id="order">
                                                            @csrf

                                                            <div class="form-group row">
                                                                <div class="col-12">
                                                                    <label for="warehouse" class="col-form-label IRANYekanRegular">انبار</label>
                                                                    <select name="warehouse" id="warehouse"  class="width-100 form-control IRANYekanRegular" onchange="goods(this.value,'goods_div')" required>
                                                                        <option value="">انبار مورد نظر را انتخاب کنید</option>
                                                                        @foreach($warehouses as $ws)
                                                                            <option value="{{ $ws->id }}" {{$ws->id == old('warehouse')?'selected':'' }}>{{ $ws->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <span class="form-text text-danger erroralarm"> {{ $errors->first('warehouse') }} </span>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-12">
                                                                    <label for="good" class="col-form-label IRANYekanRegular">کالا</label>
                                                                    <div id="goods_div">
                                                                        <select name="good" id="good"  class="width-100 form-control IRANYekanRegular" required>
                                                                            <option value="">کالا مورد نظر را انتخاب کنید</option>
                                                                            @foreach($goods as $good)
                                                                                <option value="{{ $good->id }}" {{$good->id == old('good')?'selected':'' }}>{{ $good->title.' ('.$good->brand.' )' }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <span class="form-text text-danger erroralarm"> {{ $errors->first('good') }} </span>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-12 col-md-6">
                                                                    <label for="value" class="control-label IRANYekanRegular">واحد مصرفی</label>
                                                                    <input type="number" class="form-control input text-center" name="value" id="value" min="1" placeholder=" حجم واحد مورد نظر را وارد کنید" value="{{ old('value')  }}" required>
                                                                    <span class="form-text text-danger erroralarm"> {{ $errors->first('value') }} </span>
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
                                        <th><b class="IRANYekanRegular">انبار</b></th>
                                        <th><b class="IRANYekanRegular">نام کالا</b></th>
                                        <th><b class="IRANYekanRegular">برند</b></th>
                                        <th><b class="IRANYekanRegular">دسته اصلی</b></th>
                                        <th><b class="IRANYekanRegular">دسته فرعی</b></th>
                                        <th><b class="IRANYekanRegular">حجم واحد مصرفی</b></th>
                                        <th><b class="IRANYekanRegular">قیمت واحد (تومان)</b></th>
                                        <th><b class="IRANYekanRegular">قیمت کل (تومان)</b></th>
                                        <th><b class="IRANYekanRegular">اقدامات</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($consumptions as $index=>$consumption)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $consumption->warehouse->name ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $consumption->good->title ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $consumption->good->brand ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $consumption->good->main_category->title ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $consumption->good->sub_category->title ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $consumption->value.' '.$consumption->unit }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $consumption->price_per_unit ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $consumption->total_price ?? '' }}</strong></td>
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
                                                            <form action="{{ route('admin.reserves.consumptions.delete',[$reserve,$consumption]) }}"  method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger px-8" title="حذف">حذف</button>
                                                                &nbsp;&nbsp;
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
                                                                <form action="{{ route('admin.reserves.consumptions.update',[$reserve,$consumption]) }}"  method="POST" class="d-inline" id="update{{$consumption->id}}">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <div class="form-group row">
                                                                        <div class="col-12">
                                                                            <label for="warehouse" class="col-form-label IRANYekanRegular">انبار</label>
                                                                            <select name="warehouse" id="warehouse"  class="width-100 form-control IRANYekanRegular" onchange="goods(this.value,'goods_div{{$consumption->id}}')" required>
                                                                                <option value="">انبار مورد نظر را انتخاب کنید</option>
                                                                                @foreach($warehouses as $ws)
                                                                                    <option value="{{ $ws->id }}" {{$ws->id == old('warehouse') || $ws->id == $consumption->warehouse_id ?'selected':'' }}>{{ $ws->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('warehouse') }} </span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <div class="col-12">
                                                                            <label for="good" class="col-form-label IRANYekanRegular">کالا</label>
                                                                            <div id="goods_div{{$consumption->id}}">
                                                                                <select name="good" id="good"  class="width-100 form-control IRANYekanRegular" required>
                                                                                    <option value="">کالا مورد نظر را انتخاب کنید</option>
                                                                                    @foreach($consumption->warehouse->stocks->pluck('good') as $good)
                                                                                        <option value="{{ $good->id }}" {{$good->id == old('good') || $good->id == $consumption->goods_id  ?'selected':'' }}>{{ $good->title.' ('.$good->brand.' )' }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('good') }} </span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <div class="col-12 col-md-6">
                                                                            <label for="value" class="control-label IRANYekanRegular">واحد مصرفی</label>
                                                                            <input type="number" class="form-control input text-center" name="value" id="value" min="1" placeholder=" حجم واحد مورد نظر را وارد کنید" value="{{ old('value') ?? $consumption->value }}" required>
                                                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('value') }} </span>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-success px-8" title="بروزرسانی"  form="update{{$consumption->id}}">بروزرسانی</button>
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
