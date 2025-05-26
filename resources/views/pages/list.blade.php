@extends('layouts.app')
@section('title', $title)
@section('content')
<div class="row masonry">
    @foreach($images as $image)
      <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-12 item">
        <div class="card">
          <img src="{{ $image->newthumb640 }}" class="card-img-top" alt="{{ $image->desc }}">
          <ul class="list-group list-group-flush">
            @foreach($image->musics()->Released()->get() as $music)
              <li class="list-group-item">{{ $music->name }} by {{ $music->artist }}</li>
            @endforeach
          </ul>
          <div class="card-body text-center">
            <a href="{{ route('music', ['id'=>$image->id]) }}" class="btn btn-danger">播放</a>
          </div>
        </div>
      </div>
    @endforeach
</div>
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-center mt-3">
      {{ $images->links() }}
    </div>
</div>

@push('pbl-js')
<script src="https://tiangong2.wepromo.cn/js/jquery.lazyload.min.js"></script>
<script src="https://tiangong2.wepromo.cn/js/masonry.pkgd.min.js"></script>
<script src="https://tiangong2.wepromo.cn/js/imagesloaded.pkgd.min.js"></script>
<script>
  $(function(){
      $(".lazyload").lazyload();//图片懒加载
      $('.masonry').masonry({
        itemSelector: '.item',
      });
      var $container = $('.masonry');
      $container.imagesLoaded(function(){
        $container.masonry({
          itemSelector: '.item',
          isAnimated: true,
        });
      });
  });
</script>
@endpush
@stop
