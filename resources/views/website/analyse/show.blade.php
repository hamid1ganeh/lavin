@extends('layouts.master')

@section('content')
    @include('layouts.header', ['title'=>$analyse->title, 'background'=>'/images/front/service-bg.jpg'])
    <div class="col-12 text-right px-0 px-md-3">
        <div class="container">

            <div class="col-12 mx-0 row px-0 px-md-3">
                <div class="col-md-9 px-md-5 mb-5">
                    @if($analyse->thumbnail != null)
                    <div>
                        <div class="col-12 text-center my-3">
                            <img  src="{{ $analyse->get_thumbnail('large') }}" class="w-100">
                        </div>
                    </div>
                    @endif

                    <div class="col-12 mb-4">
                        <h2 class="h6 dima">{{ $analyse->title }}</h2>
                        <div class="text-dark mt-4">{!! $analyse->description !!}</div>
                    </div>
                    <hr class="mb-3 border">

                </div>

                <div class="col-md-3 px-md-5 mb-5">

                    <form class="rounded col-12 py-3 m-3" style="width: 100%; border:2px solid #2ed3ae;background-color: rgba(255,255,255,0.45)" method="POST" action="{{ route('website.analyse.store',$analyse) }}" enctype="multipart/form-data">
                        @csrf

                        @foreach($analyse->images as $image)
                             <div class="row">
                                 <div class="clo-12">
                                     <h5>{{ $image->title }}</h5>
                                     <p>{{ $image->description  }}</p>
                                      <img src="{{ $image->get_thumbnail('medium') }}" title="تصویر نمونه" alt="{{ $image->title }}">
                                     تصویر نمونه
                                     <input type="file" name="{{ $image->id }}" accept="image/*" @if($image->required) required @endif>
                                 </div>
                             </div>
                        @endforeach

                        <div> بین {{  $analyse->min_price }}  و {{  $analyse->max_price }} تومان چقدر میخوای هزینه کنی؟ </div>
                        <input type="range" value="1000" min="{{  $analyse->min_price }}" max="{{  $analyse->max_price }}" oninput="this.nextElementSibling.value = this.value" name="price">
                        <output>{{  $analyse->min_price }} </output>

                        <div class="col-12 text-center mt-3">
                            @auth
                                <input type="submit" class="button bg-accent border-0 text-white py-2 pointer" value="درخواست آنالیز">
                            @else
                                <a href="#loginModal" data-toggle="modal" class="button bg-accent border-0 text-white py-2">درخواست آنالیز</a>
                            @endauth
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
@stop

@push('css')
    <style>

    </style>
@endpush

@push('js')
    <script>

    </script>
@endpush
