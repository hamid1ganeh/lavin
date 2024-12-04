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
{{--                                {{ Breadcrumbs::render('employments.categories.main.create',$main) }}--}}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-file page-icon"></i>
                             ایجاد رسید جدید
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

                                <form class="form-horizontal" action="{{ route('admin.warehousing.receipt.store') }}" method="post">
                                     @csrf

                                    <button id="btn" type="button" class="btn btn-success">
                                        افزودن محصول جدید
                                        <i class="fa fa-plus plusiconfont"></i>
                                    </button>
                                    <div id="goods-div">
                                        <div class="row">
                                            <div class="col-8">
                                                <label  class="control-label IRANYekanRegular">کالا</label>
                                                <select class="widht-100 form-control select2" name="goods[]" required>
                                                    @foreach($goods as $good)
                                                        <option value="{{ $good->id }}" selected>{{ $good->title.' - برند  ('.$good->brand.") -  کد  (".$good->code.")" }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <label  class="control-label IRANYekanRegular">تعداد</label>
                                                <input id="name" type="number" min="0" class="form-control"  name="counts[]" value=""  autofocus placeholder="تعداد" required>
                                            </div>
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
        const btn = document.getElementById('btn');
        function appendHTML(){
            const ele = document.getElementById('goods-div');
            const newDiv = document.createElement('div');
            newDiv.innerHTML =
                `
                <div class="row">
                                            <div class="col-8">
                                                <label  class="control-label IRANYekanRegular">کالا</label>
                                                <select class="widht-100 form-control select2" name="goods[]" required>
                                                    @foreach($goods as $good)
                <option value="{{ $good->id }}" selected>{{ $good->title.' - برند  ('.$good->brand.") -  کد  (".$good->code.")" }}</option>
                                                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <label  class="control-label IRANYekanRegular">تعداد</label>
                <input id="name" type="number" min="0" class="form-control"  name="counts[]" value=""  autofocus placeholder="تعداد" required>
            </div>
        </div>
`;
            ele.appendChild(newDiv);
        }
        btn.addEventListener('click', appendHTML);
    </script>
@endsection
