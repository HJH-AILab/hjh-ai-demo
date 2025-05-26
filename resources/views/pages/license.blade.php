@extends('layouts.app')
@section('title', $title)
@section('content')
<div class="row">
  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 offset-xl-3 offset-lg-3 offset-md-3 offset-sm-3 offset-3" style="margin-top:20px;margin-bottom:20px;">
    <h2>关于我们</h2>
    <p class="h5 mt-3">
      好机绘 - 天工AI提供高质量且完全免费的素材图片，这些图片均在好机绘 - 天工AI许可下授权。我们精心地为所有图片贴上了标签，你可以搜索，也可以轻松通过我们的发现页面发现这些图片。
    </p>
    <h2 class="mt-5">图片</h2>
    <p class="h5 mt-3">
      我们有成千上万的免费素材图片，每天都会添加新的高分辨率图片。所有图片都是从我们用户上传的图片或免费图片网站上精心挑选的。我们确保所有发布的图片都是高质量的图片，并在好机绘 - 天工AI许可下授权。
    </p>
    <h2 class="mt-5">贡献</h2>
    <p class="h5 mt-3">
      上传你自己的图片来支持好机绘 - 天工AI社区：
    </p>
    <p>
      <a class="btn btn-danger" href="{{ route('image.request') }}" role="button">
              图片上传<span class="bi-arrow-up"></span>
      </a>
    </p>


    <h2 class="mt-5">哪些行为是允许的？</h2>
    <p class="h5 mt-3">
      1.好机绘 - 天工AI上的所有图片均可免费使用。
    </p>
    <p class="h5">
      2.需注明归属。注明摄影作者或好机绘 - 天工AI不是强制的，但这种行为值得赞赏。
    </p>
    <p class="h5">
      3.你可以修改好机绘 - 天工AI上的图片。发挥创造力，随心所欲地编辑图片。
    </p>

    <h1 class="mt-5 text-center">通过图片讲述你的故事</h1>

    <div class="row mt-3">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
          <div class="card mb-3" style="max-width: 540px;">
            <div class="row no-gutters">
              <div class="col-md-4">
                <img src="https://tiangong2.wepromo.cn/thumb640/2025052616463468342a6a6c0be1c450945f68ab0fd2ba047d27bd7147d%20(1).jpeg" alt="working">
              </div>
              <div class="col-md-8">
                <div class="card-body">
                  <h5 class="card-title">用在你的网站、博客或应用上</h5>
                  <p class="card-text">可以在线使用图片，无论是网站、电子商务商店、电子通讯、电子书、演示文稿、博客还是出售的模板，都可以使用。</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
          <div class="card mb-3" style="max-width: 540px;">
            <div class="row no-gutters">
              <div class="col-md-4">
                <img src="https://tiangong2.wepromo.cn/thumb640/2025052616463468342a6a6c0be1c450945f68ab0fd2ba047d27bd7147d%20(1).jpeg" alt="ads">
              </div>
              <div class="col-md-8">
                <div class="card-body">
                  <h5 class="card-title">推广产品</h5>
                  <p class="card-text">使用好机绘 - 天工AI上的图片制作独特的广告、标语和营销活动资料，推广你的产品。</p>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
          <div class="card mb-3" style="max-width: 540px;">
            <div class="row no-gutters">
              <div class="col-md-4">
                <img src="https://tiangong2.wepromo.cn/thumb640/2025052616463468342a6a6c0be1c450945f68ab0fd2ba047d27bd7147d%20(1).jpeg" alt="message">
              </div>
              <div class="col-md-8">
                <div class="card-body">
                  <h5 class="card-title">印制营销材料</h5>
                  <p class="card-text">将图片用于传单、明信片、邀请函、杂志、相册、书籍、CD封面等等。</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
          <div class="card mb-3" style="max-width: 540px;">
            <div class="row no-gutters">
              <div class="col-md-4">
                <img src="https://tiangong2.wepromo.cn/thumb640/2025052616463468342a6a6c0be1c450945f68ab0fd2ba047d27bd7147d%20(1).jpeg" alt="apps">
              </div>
              <div class="col-md-8">
                <div class="card-body">
                  <h5 class="card-title">在社交媒体上分享</h5>
                  <p class="card-text">在微博、豆瓣、QQ空间等社交媒体上发布引人入胜的真实图片来增加你的受众。</p>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>

  </div>
</div>
@stop
