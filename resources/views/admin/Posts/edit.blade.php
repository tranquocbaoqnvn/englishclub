@extends('adminlte::page')


@section('title', 'Dashboard')


@section('content_header')

    <h1>Update News everyday</h1>

@stop


@section('content')
@if(\Session::has('alert'))
 {!! \Session::get('alert')['message']!!}
@endif
    <form method='POST' action='{{route('dashboard:postEditPosts', ['id' => $id])}}' enctype="multipart/form-data">
            @csrf
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-4 col-form-label">Image</label>
                <div class="col-md-6">
                    <input  type='file' name='posts_image' id='upload' style='width:150px !important;'  />
                    {{ $errors->first('posts_image') }}
                   <div id="result">
                    @if($posts_image != "")
                        <img width="150px" src="/upload/posts/{{$posts_image}}">
                    @endif
                    </div>
                  
                 </div>
        </div>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-4 col-form-label">Title</label>
                <div class="col-sm-6">
                    <input type="text" value="{{old('title', $title)}}" class="form-control" name="title" placeholder="@lang('news.hint_title')">
                    {{ $errors->first('title') }}
                </div>
        </div>
            <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Description</label>
                    <div class="col-sm-6">
                        <textarea class="form-control" name="description" rows="3" placeholder="@lang('news.hint_description')">{!!old('description', $description)!!}</textarea>
                    {{ $errors->first('description') }}
                    </div>
            </div>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-4 col-form-label">Content</label>
                <div class="col-sm-6">
                    <textarea id="mytextarea" class="form-control" name="content" rows="3" placeholder="@lang('news.hint_content')">{!!old('content', $content)!!}</textarea>
                    {{ $errors->first('content') }}
                </div>
            </div>
            <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">URL</label>
                    <div class="col-sm-6">
                        <input type="text" value="{{old('url', $url)}}" class="form-control" name="url" placeholder="@lang('news.hint_url')">
                    {{ $errors->first('url') }}
                    </div>
            </div>
            <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Tags</label>
                    
                    <div class="col-sm-6">
                        <select id="tag_list" name="tags_list[]" value="{{old('tags', $tags)}}" class="form-control" multiple></select>
                    </div>
                    
            </div>
            
            
            <div class="form-group row">
                    <div class="offset-sm-3 col-sm-9">
                        <button type="submit" class="btn btn-primary">@lang('common.button_edit')</button>
                    </div>
                </div>
    </form>


@stop


@section('css')
<!----------------------------------------- CSS Select 2 ------------------------------------------->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
<!----------------------------------------- CSS TinyMCE ------------------------------------------->   
    <link rel="stylesheet" href="/css/admin_custom.css">

@stop


@section('js')
<!----------------------------------------- JS Select 2 ------------------------------------------->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<!----------------------------------------- JS Tiny MCE ------------------------------------------->
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=laa4zo5r1rup4339drgml99qu4ktjl114p1bj0298yrrvdre"></script>

    <script type="text/javascript">
    tinymce.init({
        selector: '#mytextarea',
        images_upload_base_path: 'english.lc',
        automatic_uploads: false,
        plugins: "fullscreen, image code",
        file_picker_types: 'image',
        images_upload_url: '{{route("dashboard:tinymce")}}',
    });
    $('#tag_list').select2({
        placeholder: "  Choose tags...",
        minimumInputLength: 0,
        ajax: {
            url: '{!!route("dashboard:findTags")!!}',
            dataType: 'json',
            data: function (params) {
                return {
                    q: $.trim(params.term)
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            }
        }
    });
    </script>
   

@stop

