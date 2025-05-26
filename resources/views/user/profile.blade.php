@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">资料修改</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}" class="was-validated" enctype="multipart/form-data">
                        @csrf
                        @if(session('message'))
                            <div class="alert alert-info">{{ session('message') }}</div>
                        @endif

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-3">
                                <div class="custom-file mb-3">
                                    <img src="{{ Auth::user()->avatar }}" class="img-fluid img-thumbnail">
                                    <input type="file" name="avatar" id="validatedCustomFile" class="custom-file-input">
                                    <label class="custom-file text-center" for="validatedCustomFile" style="margin-top: -15px;"><strong>点击更新头像</strong></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">

                            <div class="col-md-6 offset-md-3">
                                <input id="email" type="text" class="form-control" name="email" value="{{ Auth::user()->email }}" readonly>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">

                            <div class="col-md-6 offset-md-3">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ Auth::user()->name }}" autocomplete="name" required autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-3">
                                <button type="submit" class="btn btn-primary">
                                    确定
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
