@extends('layouts.app')
@section('title', $title)
@section('content')
<div class="row masonry">
    @foreach($workflows as $workflow)
      <div>
        <h3 class="text-center">{{ $workflow["name"] }}</h3>
        @foreach($workflow["workflows"] as $workflow1)
          <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-12 item">
            <div class="card">
              <img src="{{ $workflow1['effect_thumbnail_image'] }}" class="card-img-top" alt="{{ $workflow1['name'] }}">
            </div>
          </div>
        @endforeach
      </div>
    @endforeach
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
