<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', '好机绘 - 天工AI')" />
    <meta name="keywords" content="@yield('keywords', '好机绘 - 天工AI')" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'hjh') - 好机绘 - 天工AI</title>
    <!-- 样式 -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script>
    var _hmt = _hmt || [];
    (function() {
    var hm = document.createElement("script");
    hm.src = "https://hm.baidu.com/hm.js?159461ab2c3180111a5d34d847aca402";
    var s = document.getElementsByTagName("script")[0]; 
    s.parentNode.insertBefore(hm, s);
    })();
    </script>
</head>
<body>
    <div class="{{ route_class() }}-page">
        @include('layouts._header')
        <div class="container-fluid">
            @yield('content')
        </div>
        @include('layouts._footer')
    </div>
    <!-- JS 脚本 -->
    <script src="{{ mix('js/app.js') }}"></script>
    @stack('pbl-js')
    <!-- Go to www.addthis.com/dashboard to customize your tools -->
</body>
</html>