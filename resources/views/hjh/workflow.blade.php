@extends('layouts.app')
@section('title', $title)
@section('content')
<div class="row masonry mt-1">
    @foreach($workflows as $workflow)
        <div style="width: 100%">
          <h3 class="text-center">{{ $workflow["name"] }}</h3>  
        </div>
        @foreach($workflow["workflows"] as $workflow1)
          <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12 item">
            <div class="box">
              <img src="{{ $workflow1['effect_thumbnail_image'] }}" data-src="{{ $workflow1['effect_thumbnail_image'] }}" class="img-fluid lazyload fit" alt="{{ $workflow1['name'] }}" title="{{ $workflow1['name'] }}"/>
              <div class="box-content">
                  <span class="down" style="background-color: #f8f9fa;">
                    @if($workflow['type'] == 701)
                    <a class="btn btn-outline-success" href="{{ route('workflow.image.upload1') }}?workflow_id={{ $workflow1['id'] }}&workflow_name={{ $workflow1['name'] }}&space_name={{ $workflow1['space_name'] }}&type=$workflow['type']" role="button">
                        开始AI视频
                        <span class="bi-arrow-up"></span>
                    </a>
                    @else
                    <a class="btn btn-outline-success" href="{{ route('workflow.image.upload') }}?workflow_id={{ $workflow1['id'] }}&workflow_name={{ $workflow1['name'] }}&space_name={{ $workflow1['space_name'] }}&type=$workflow['type']" role="button">
                        开始AI绘图
                        <span class="bi-arrow-up"></span>
                    </a>
                    @endif
                  </span>
              </div>
            </div>
          </div>
        @endforeach
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

  function create(workflowId) {

  }
</script>
@endpush
@stop
