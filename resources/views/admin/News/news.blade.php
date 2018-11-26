@extends('adminlte::page')


@section('title', 'Dashboard')


@section('content_header')

    <h1 align="center">List of news </h1>
    <br><div class="col-sm-2">
        <select id="tag_list" name="tags_list[]" class="form-control"></select>
    </div>
    <button id="find_tags" class="btn btn-default-sm">
    <i class="fa fa-search"></i></button>
    <a href="{{ route('dashboard:news') }}" type="button" class="btn btn-primary">Clear</a>
    <a href="{{ route('dashboard:addNews') }}" type="button" class="btn btn-primary">Add</a>
    
    
</button>
</span>
@stop


@section('content')
@if(\Session::has('alert'))
 {!! \Session::get('alert')['message']!!}
@endif



<table class="table table-bordered" id="news-table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Title</th>
                <th>Description</th>
                <th>Tags</th>
                <th>Actions</th>
                
            </tr>
        </thead>
    </table>

@stop


@section('css')
    <link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="/css/admin_custom.css">

@stop


@section('js')
<script>
$(function() {
    var datatable = $('#news-table').DataTable({
        processing: true,
        //serverSide: true,
        ajax: '{!! route("dashboard:newsList") !!}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'title', name: 'Title' },
            { data: 'description', name: 'Description' },
            { data: 'tags', name: 'Tags' },
            { data: 'action', name: 'Actions'},
        ],
        columnDefs: [
            {
                "targets": [ 4, 3 ],
                "searchable": false,
                "orderable": false,
            }
        ]
    });
    $('#tag_list').select2({
        placeholder: "  Search tags...",
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
    $('#find_tags').on('click', function () {
        datatable.clear().draw();
        
        var tags = $("#tag_list option:selected").val();
        var url = '{{route("dashboard:postGetTagsID")}}';
        var token = "{{ csrf_token() }}";
        $.post( url,{tags_id : tags, _token: token},function( data ) {
            datatable.rows.add(data.data).draw();
        });
        
    });
});

</script>
@stop