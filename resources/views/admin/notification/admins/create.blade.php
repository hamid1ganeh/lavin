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
{{--                                {{ Breadcrumbs::render('notifications.create') }}--}}
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <i class="fas fa-bell page-icon"></i>
                            ارسال اعلان جدید
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body">

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible">
                                    <strong>
                                        {!! implode('<br/>', $errors->all('<span class="IR">:message</span>')) !!}
                                    </strong>
                                </div>
                            @endif


                            <form method="POST" action="{{ route('admin.notifications.users.store') }}">
                                @csrf
                                <input name="type" value="admin" type="hidden">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="title" class="col-form-label text-md-right IRANYekanRegular">عنوان:</label>
                                        <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}"  autofocus placeholder="عنوان">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <label for="message" class="col-form-label text-md-right IRANYekanRegular">متن پیام:</label>
                                        <textarea id="message" row="10" name="message"  autofocus placeholder="متن پیام...">{{ old('message') }}</textarea>
                                    </div>
                                </div>

                                <!-- <div class="row mt-1">
                                    <div class="col-12">
                                        <label for="users" class="control-label IRANYekanRegular">کاربران (نام و نام خانوادگی یا موبایل...)</label>
                                        <select class="select2 select2-multiple IRANYekanRegular" multiple="multiple" multiple data-placeholder="انتخاب کاربران..." name="users[]" id="users"></select>
                                    </div>
                                </div> -->


                                <div class="row">
                                    <div class="col-12">
                                        <label for="roles" class="col-form-label IRANYekanRegular">نقش ها:</label>
                                            <select name="roles[]" id="roles" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}" @if(old('roles')!=null) {{ in_array($role->id,old('roles'))?'selected':'' }} @endif>{{"(".$role->description.") ".$role->name }}</option>
                                                @endforeach
                                            </select>
                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('roles') }} </span>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-12">
                                        <label for="admins" class="col-form-label IRANYekanRegular">ادمین ها:</label>
                                        <select name="admins[]" id="admins" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple>
                                            @foreach($admins as $admin)
                                                <option value="{{ $admin->id }}" @if(old('admins')!=null) {{ in_array($admin->id,old('admins'))?'selected':'' }} @endif>{{ $admin->fullname }}</option>
                                            @endforeach
                                        </select>
                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('admins') }} </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <label for="users" class="col-form-label IRANYekanRegular">به جز:</label>
                                        <select name="excepts[]" id="excepts" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple>
                                            @foreach($admins as $admin)
                                                <option value="{{ $admin->id }}" @if(old('admins')!=null) {{ in_array($admin->id,old('excepts'))?'selected':'' }} @endif>{{ $admin->fullname }}</option>
                                            @endforeach
                                        </select>
                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('excepts') }} </span>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12" style="display:inherit;">
                                        <input type="radio" id="active" name="status" value="{{ App\Enums\Status::Active }}"  @if(old('status')!=App\Enums\Status::Deactive) checked @endif>
                                         &nbsp;
                                        <label for="active">فعال</label><br>
                                         &nbsp;&nbsp; &nbsp;
                                        <input type="radio" id="deactive" name="status" value="{{ App\Enums\Status::Deactive }}"  @if(old('status')==App\Enums\Status::Deactive) checked @endif>
                                         &nbsp;
                                        <label for="deactive">غیرفعال</label><br>
                                    </div>
                                </div>


                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <input type="checkbox" name="sms" id="sms" value="sms" @if(old('sms')!=null) checked @endif>
                                        <label  for="sms" class="control-label IRANYekanRegular">ارسال پیامک</label>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <button type="submit" title="ثبت" class="btn btn-primary">ثبت</button>
                                    </div>
                                </div>

                            </form>
                        </div>

                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div>
    </div>
</div>


<!-- @section('script')

    <script type="text/javascript">
        $("#users").select2({
            placeholder: '...نام و نام خانوادگی یا شماره موبایل',
            ajax: {
                url: '{{ route("admin.users.fetch") }}',
                processResults: function (data, params) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id,
                                data: item
                            };
                        })
                    };
                }
            }
        });
    </script>

@endsection -->



@endsection

