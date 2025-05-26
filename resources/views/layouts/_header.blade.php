<nav class="navbar navbar-expand-lg navbar-light bg-light navbar-static-top">
  <div class="container">
    <!-- Branding Image -->
    <a class="navbar-brand " href="{{ url('/') }}">
      天工AI
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Left Side Of Navbar -->
      <ul class="navbar-nav mr-auto">
        <form action="{{ route('image.find') }}" method="get">
          <div class="input-group">
            <input type="input" name="query" value="{{ isset($query)?$query:'' }}" class="form-control" autocomplete="off" placeholder="请输入关键词进行搜索">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary">搜索
                </button>
            </div>
          </div>
        </form>
      </ul>

      <ul class="navbar-nav">
        <li><a class="nav-link" href="{{ route('list') }}">音图话</a></li>
        <li><a class="nav-link" href="{{ route('root') }}">超清大图</a></li>
      </ul>

      <!-- Right Side Of Navbar -->
      <ul class="navbar-nav navbar-right">
        <!-- 登录注册链接开始 -->
        @guest
        <li class="nav-item"><a class="nav-link" href="{{ route('image.play') }}"><strong>我为图片配音乐玩法说明</strong></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('image.license') }}"><strong>版权说明</strong></a></li>
        <li class="nav-item">
          <a class="btn btn-outline-success" href="{{ route('image.request') }}" role="button">
              图片上传<span class="bi-arrow-up"></span>
          </a>
        </li>
        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">登录</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">注册</a></li>
        @else
        <li class="nav-item"><a class="nav-link" href="{{ route('image.license') }}"><strong>版权说明</strong></a></li>
        <li class="nav-item">
          <a class="btn btn-outline-success" href="{{ route('image.request') }}" role="button">
              图片上传<span class="bi-arrow-up"></span>
          </a>
        </li>
        <li class="nav-item dropdown" style="margin-top: -5px;">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="{{ Auth::user()->avatar }}" class="img-responsive img-circle" width="30px" height="30px">
            {{ Auth::user()->name }}
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ route('image.user', ['id'=>Auth::user()->id]) }}">我的图片</a>
            <a class="dropdown-item" href="{{ route('image.favorite') }}">我的收藏</a>
            <a class="dropdown-item" href="{{ route('change.password.request') }}">修改密码</a>
            <a class="dropdown-item" href="{{ route('profile.request') }}">修改资料</a>
            <a class="dropdown-item" id="logout" href="#"
               onclick="event.preventDefault();document.getElementById('logout-form').submit();">退出登录</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              {{ csrf_field() }}
            </form>
          </div>
        </li>
        @endguest
        <!-- 登录注册链接结束 -->
      </ul>
    </div>
  </div>
</nav>