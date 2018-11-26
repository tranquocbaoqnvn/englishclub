@extends('adminlte::page')


@section('title', 'Dashboard')


@section('content_header')

    <h1>List of posts <a href="{{ route('dashboard:addPosts') }}" type="button" class="btn btn-primary">Add</a></h1>

@stop


@section('content')
@if(\Session::has('alert'))
 {!! \Session::get('alert')['message']!!}
@endif
<table class="table table-bordered" id="posts-table">
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
    $('#posts-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route("dashboard:postsList") !!}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'title', name: 'Title' },
            { data: 'description', name: 'Description' },
            { data: 'tags', name: 'Tags' },
            { data: 'action', name: 'Actions'},
        ],
        columnDefs: [
            {
                "targets": [ 4 ],
                "searchable": false,
                "orderable": false,
            }
        ]
    });
});
</script>
@stop