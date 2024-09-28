 @extends('layouts.master')
 @section('content')

     <div>
         @include('layouts.header', ['title'=>'آنالیز تصویر', 'background'=>'/images/front/service-bg.jpg'])
         <div class="col-12 mt-3">
             <div class="px-lg-5">
                 <div class="col-12 mx-0 row">

                     <div class="col-md-12 px-0 px-md-3 mb-4">


                         <div class="col-12  pb-3 px-0">

                             <div class="col-12 px-1 row mx-0 mt-3">
                                 @foreach($analises as $analyse)
                                    <div class="col-lg-3 py-4">
                                        @include('includes.elements.analyse-card',['title'=>$analyse->title,'slug'=>$analyse->slug,'description'=>$analyse->description,'thumbnail'=>$analyse->get_thumbnail('thumbnail')])
                                    </div>
                                 @endforeach
                             </div>
                             <div class="d-flex justify-content-center mb-3">
                                {!! $analises->render() !!}
                             </div>
                         </div>

                     </div>


                 </div>
             </div>

         </div>

     </div>

 @stop

 @push('js')

 @endpush
 @push('css')
     <style>
         .blogs, .alert-info{
             background-color: #EAF1EE;
         }
         .blogs img{
             width: 25px;
             height: 25px;
         }
     </style>
 @endpush
