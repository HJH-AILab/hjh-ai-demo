@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">图片上传</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('image.update') }}" class="was-validated" enctype="multipart/form-data">
                        @csrf
                        @if(session('message'))
                        <div class="alert alert-info">{{ session('message') }}</div>
                        @endif

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-3">
                                <img src="#" id="ImageShow" class="img-fluid" style="display: none;" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-3">
                                <input type="file" class="form-control-file" id="chooseImage" name="thumb" required>
                                @error('thumb')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-3">
                                <label for="desc">图片说明</label>
                                <input type="text" class="form-control" name="desc" placeholder="站在红色的花圃上的白色连衣裙的女人" autocomplete="off" required>
                                @error('desc')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-3">
                                <label for="lens">镜头</label>
                                <input type="text" class="form-control" name="lens" placeholder="50.0mm ƒ/11.0 0.0025s" autocomplete="off">
                                @error('lens')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-3">
                                <label for="size">大小</label>
                                <input type="text" class="form-control" name="size" id="size" placeholder="6.60MB、273KB" autocomplete="off">
                                @error('size')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-3">
                                <label for="resolution">分辨率</label>
                                <input type="text" class="form-control" name="resolution" id="resolution" placeholder="3200px x 4800px" autocomplete="off">
                                @error('resolution')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-3">
                                <label for="aspect_ratio">宽高比</label>
                                <input type="text" class="form-control" name="aspect_ratio" id="aspect_ratio" placeholder="2:3 该宽高比通过上一项分辨率计算得到" autocomplete="off">
                                @error('aspect_ratio')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-3">
                                <label for="keywords">标签</label>
                                <input type="text" value="hjh,image" class="form-control" name="keywords" placeholder="多个标签请用英文,分割 如女人,帽子,唯美" autocomplete="off">
                                @error('keywords')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-3">
                                <button type="submit" class="btn btn-primary">
                                    上传
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
@push('pbl-js')
<script>
    $(function() {
        $('#chooseImage').on('change', function() {
            var filePath = $(this).val(),
                fileFormat = filePath.substring(filePath.lastIndexOf(".")).toLowerCase(),
                src = window.URL.createObjectURL(this.files[0]);
            $('#ImageShow').css('display', 'block');
            $('#ImageShow').attr('src', src);
            if (this.files) {
                var f = this.files[0];
                var reader = new FileReader();
                reader.onload = function(e) {
                    var data = e.target.result;
                    var image = new Image();
                    image.onload = function() {
                        var width = image.width;
                        var height = image.height;
                        var g = gcd(width, height);
                        $("#resolution").val(width + "px" + " x " + height + "px");
                        $("#aspect_ratio").val(width / g + ":" + height / g);
                    };
                    $("#size").val(formatFileSize(f.size));
                    image.src = data;
                };
                reader.readAsDataURL(f);
            }
        });
    });

    function formatFileSize(fileSize) {
        if (fileSize < 1024) {
            return fileSize + 'B';
        } else if (fileSize < (1024 * 1024)) {
            var temp = fileSize / 1024;
            temp = temp.toFixed(2);
            return temp + 'KB';
        } else if (fileSize < (1024 * 1024 * 1024)) {
            var temp = fileSize / (1024 * 1024);
            temp = temp.toFixed(2);
            return temp + 'MB';
        } else {
            var temp = fileSize / (1024 * 1024 * 1024);
            temp = temp.toFixed(2);
            return temp + 'GB';
        }
    }

    function gcd(n, m) {
        if (m == 0) return n;
        return gcd(m, n % m);
    }
</script>
@endpush