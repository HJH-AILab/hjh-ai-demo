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
                            <span>阅读并同意<a href="javascript:void(0)" onclick="viewAgreement1(this);return false;">《好机绘产品协议》</a></span>
                            <div role="alert" id="element" aria-live="assertive" aria-atomic="true" class="toast fade hide" data-autohide="true" data-delay="2000">
                                <div class="toast-header">
                                    <strong class="mr-auto">好机绘产品协议</strong>
                                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="toast-body">
                                    请勾选阅读并同意好机绘产品协议
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                            <input type="checkbox" id="agreement2">
                            <span>阅读并同意<a href="javascript:void(0)" onclick="viewAgreement2(this);return false;">《好机绘产品隐私政策》</a></span>
                            <div role="alert" id="element1" aria-live="assertive" aria-atomic="true" class="toast fade hide" data-autohide="true" data-delay="2000">
                                <div class="toast-header">
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
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">好机绘产品协议</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            @include('auth.agreement1')
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
            <button type="button" class="btn btn-primary" id="btnAgreement1" disabled onclick="agreement1()">同意</button>
        </div>
        </div>
    </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modal1" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">好机绘产品隐私政策</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            @include('auth.agreement2')
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
            <button type="button" class="btn btn-primary" onclick="agreement2()">同意</button>
        </div>
        </div>
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
    function viewAgreement1(that) {
        $('#modal').modal('show');
    }
    function agreement1() {
        $('#modal').modal('hide');
        $("#agreement1").prop("checked", true);
    }
    function viewAgreement2(that) {
        $('#modal1').modal('show');
    }
    function agreement2() {
        $('#modal1').modal('hide');
        $("#agreement2").prop("checked", true);
    }
    const dom = document.getElementById('modal');
        dom.addEventListener('scroll', () => {
        const clientHeight = dom.clientHeight;
        const scrollTop = dom.scrollTop;
        const scrollHeight = dom.scrollHeight;
        if (clientHeight + scrollTop === scrollHeight) {
            console.log('竖向滚动条已经滚动到底部');
            document.getElementById('btnAgreement1').disabled = false;
        }
    });
</script>