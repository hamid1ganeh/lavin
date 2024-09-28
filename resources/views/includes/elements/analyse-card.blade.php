<a href="{{ route('website.analyse.show',$slug)  }}">
    <div class="bg-light p-3 p-xl-4">
        <div class="font-weight-bold text-black">{{ $title }}</div>
        <div class="small text-muted mt-3" style="word-wrap: break-word;">{!! substr($description,0,200) !!}</div>

        <hr class="w-100 mt-3 pb-2" style="border-block-color: #dadada;">
        <img src="{{ $thumbnail  }}" width="500" height="500">


    </div>
</a>
