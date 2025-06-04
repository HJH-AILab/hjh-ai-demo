@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form id="loginform" name="loginform" method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="button" class="btn btn-primary" onclick="doSubmitForm()">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>

                        <div class="form-group row">
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                            <input type="checkbox" id="agreement1">
                            <span>阅读并同意<a target="_blank" href="https://hjh.wepromo.cn/%E5%A5%BD%E6%9C%BA%E7%BB%98%E4%BA%A7%E5%93%81%E5%8D%8F%E8%AE%AE01.html">好机绘产品协议</a></span>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                            <input type="checkbox" id="agreement2">
                            <span>阅读并同意<a target="_blank" href="https://hjh.wepromo.cn/%E5%A5%BD%E6%9C%BA%E7%BB%98%E4%BA%A7%E5%93%81%E9%9A%90%E7%A7%81%E6%94%BF%E7%AD%9601.html">好机绘产品隐私政策</a></span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div role="alert" id="element" aria-live="assertive" aria-atomic="true" class="toast" data-autohide="false">
        <div class="toast-header">
            <img src="..." class="rounded mr-2" alt="...">
            <strong class="mr-auto">好机绘产品协议</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            请勾选阅读并同意好机绘产品协议
        </div>
    </div>
    <div role="alert" id="element1" aria-live="assertive" aria-atomic="true" class="toast" data-autohide="false">
        <div class="toast-header">
            <img src="..." class="rounded mr-2" alt="...">
            <strong class="mr-auto">好机绘产品隐私政策</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            请勾选阅读并同意好机绘产品隐私政策
        </div>
    </div>
</div>
@endsection
<script>
    function doSubmitForm() {
        var form = document.getElementById('loginform');
        if (form.checkValidity()) {
            //form.submit();
            if($("#agreement1").is(":checked")) {
                if($("#agreement2").is(":checked")) {
                } else {  // 进行拦截校验
                    //alert('请勾选阅读并同意好机绘产品隐私政策');
                    $('#element1').toast('show')
                }
            } else {  // 进行拦截校验
                //alert('请勾选阅读并同意好机绘产品协议');
                $('#element').toast('show')
            }
        } else {
            form.reportValidity();
        }
    }
</script>