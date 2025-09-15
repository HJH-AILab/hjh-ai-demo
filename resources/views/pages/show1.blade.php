@extends('layouts.app')
@section('title', $video->desc)
@section('keywords', implode(',',$video->keywords))
@section('description', $video->desc)
@section('content')
<div class="row">
  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
    <div class="box-content text-truncate">
      <a href="{{ route('video.user', ['id'=>$video->user->id]) }}">
        <img src="{{ $video->user->avatar }}" style="height: 60px; width:60px;" class="rounded-circle">
      </a>
      {{ $video->user->name }}
    </div>
  </div>
</div>
<div class="row">
  <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12 offset-xl-2 offset-lg-2 offset-md-2 text-center" style="margin-top:20px;">
    <div class="box" style="background: #000;">
      <video src="{{ $video->thumb }}" controls class="img-fluid" style="width:960px;" alt="{{ $video->desc }}" title="{{ $video->desc }}">
    </div>
  </div>
</div>
<div class="row">
  <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12 offset-xl-2 offset-lg-2 offset-md-2 text-center">
    <div class="row">
      @foreach($more as $one)
      <div class="col-xl-2 col-lg-3 col-md-3 col-sm-12 col-12">
        <div class="box2">
          <a href="{{ route('video.show', ['id'=>$one->id]) }}">
            <video src="{{ $one->thumb }}" class="img-fluid" alt="{{ $one->desc }}" title="{{ $one->desc }}" />
          </a>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>
@push('pbl-js')
<link rel="stylesheet" href="https://tiangong2.wepromo.cn/css/share.min.css">
</link>
<script src="https://tiangong2.wepromo.cn/js/social-share.min.js"></script>
<script src="https://tiangong2.wepromo.cn/js/spotlight.bundle.js"></script>
<script src="https://tiangong2.wepromo.cn/js/download2.js"></script>
<script type="text/javascript">
  var $config = {
    sites: ['weibo', 'wechat', 'douban', 'qzone', 'qq'], // 启用的站点
    disabled: ['google', 'facebook', 'twitter'], // 禁用的站点
    wechatQrcodeTitle: '微信扫一扫:分享', // 微信二维码提示文字
    wechatQrcodeHelper: '<p>微信里点“发现”，扫一下</p><p>二维码便可将本文分享至朋友圈。</p>'
  };
  socialShare('.social-share-cs', $config);

  function downImg() {
    var image_url = $("input[name='inlineRadioOptions']:checked").val();
    var x = new XMLHttpRequest();
    x.open("GET", image_url, true);
    x.responseType = 'blob';
    x.onload = function(e) {
      var url = window.URL.createObjectURL(x.response);
      var a = document.createElement('a');
      a.href = url;
      a.download = '';
      a.click();
    }
    x.send();
  }

  function favorite(id) {
    $.ajax({
      type: "post",
      url: "/favorite",
      contentType: "application/json",
      dataType: "json",
      data: JSON.stringify({
        id: id
      }),
      success: function(result) {
        if (result.msg == "success") {
          alert('收藏成功');
        }
        if (result.msg == "hasFavorited") {
          alert('已经收藏,无需重复收藏');
        }
      },
      error: function(xhr, status) {
        if (xhr.status == 401) { //跳转到验证页
          if (confirm('加入收藏需要登录,是否跳转到登录界面?')) {
            location.href = "/login";
          }
        }
      }
    });
  }
</script>
@endpush
@stop