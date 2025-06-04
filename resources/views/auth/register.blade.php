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
                        <div class="protocol reg1">	
                            <span id="isSelProto" class="red-checkbox" onClick="xy()"></span>已阅并同意	
                            <a target="_blank" class="baseColorFt" href="隐私协议链接">《隐私协议》</a>
                            <p id="notSelProtoError" class="error_msg"><span class="msgIcon"></span>请先勾选协议</p>	
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<style>
    .red-checkbox{
        background-image: url('https://img.58cdn.com.cn/git/teg-app-fe/passport-pc-ui/img/user-list-uncheck.png');//为了方便易懂，这边图放的是58的链接，仅供学习参考。
        height: 17px;
        width: 17px;
        background-size: 14px 14px;
        background-repeat: no-repeat;
        vertical-align: middle;
        display: inline-block;
        cursor: pointer;
    }
    .active{
        background-image: url('https://img.58cdn.com.cn/git/teg-app-fe/passport-pc-ui/img/user-list-check.png');//为了方便易懂，这边图放的是58的链接，仅供学习参考。
    }
    .error_msg{
        display: none;
        color: red;
    }
</style>
<script>
    function doSubmitForm() {
        var form = document.getElementById('loginform');
        if (form.checkValidity()) {
            alert('Form is valid, submitting now...');
            //form.submit();
        } else {
            form.reportValidity();
        }
    }
</script>