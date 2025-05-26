@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">搭配音乐</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('music.update') }}" class="was-validated" enctype="multipart/form-data">
                        @csrf
                        @if(session('message'))
                            <div class="alert alert-info">{{ session('message') }}</div>
                        @endif

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-2">
                                <img src="{{ $image->newthumb640 }}"  class="img-fluid"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-2">
                                <input type="text" class="form-control" name="name" placeholder="请填写音乐名称" autocomplete="off" required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-2">
                                <input type="text" class="form-control" name="artist" placeholder="请填写音乐作者" autocomplete="off" required>
                                @error('artist')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-2">
                                <div id="editor">
                                    <textarea style="display:none;" name="desc"></textarea>
                                </div>
                                @error('desc')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        
                        <div class="form-group row">
                            <div class="col-md-8 offset-md-2">
  <!--                               <label for="cover">音乐封面</label>
                                <input type="file" class="form-control-file" name="cover" required> -->
                                <div class="custom-file">
                                  <input type="file" class="custom-file-input" name="cover" required>
                                  <label class="custom-file-label" for="cover" data-browse="选择封面">点击选择音乐封面</label>
                                </div>
                                @error('cover')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-2">
   <!--                              <label for="source">音乐文件</label>
                                <input type="file" class="form-control-file" name="source" required> -->
                                <div class="custom-file">
                                  <input type="file" class="custom-file-input" name="source" required>
                                  <label class="custom-file-label" for="source" data-browse="选择文件">点击选择音乐文件</label>
                                </div>
                                @error('source')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-2">
                                <input type="hidden" name="image_id" value="{{$image->id}}">
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
@push('pbl-js')
<link rel="stylesheet" href="https://tiangong2.wepromo.cn/editor.md/css/editormd.min.css" />
<script src="https://tiangong2.wepromo.cn/editor.md/editormd.min.js"></script>
<script type="text/javascript">
    $(function() {
        var editor = editormd("editor", {
            width: "100%",
            height: 300,
            watch : false,
            imageUpload:false,
            placeholder:"请填写一段话,可以是关于图片或者音乐或者感悟",
            emoji:true,
            path   : "editor.md/lib/",
            toolbarIcons : function() {
                return ["undo","redo","bold","h1","h2","h3","h4","h5","h6","list-ul","list-ol","emoji"]
             },
        });
    });
</script>
@endpush
