@extends('adminlte::page')


@section('title', 'Config')


@section('content_header')

    <h1>Config information</h1>

@stop


@section('content')
@if(\Session::has('alert'))
    {!! \Session::get('alert')['message']!!}
@endif
<div class="bd">
    <form method='POST' action='{{route('dashboard:postConfig')}}'>
            @csrf
            <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Slogan</label>
                    <div class="col-sm-6">
                        <textarea class="form-control" name="slogan" rows="3" placeholder="@lang('config.hint_slogan')">{!!old('slogan', $slogan)!!}</textarea>
                        {{ $errors->first('slogan') }}
                    </div>
            </div>
            <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Phone</label>
                    <div class="col-sm-6">
                        <input type="text" value="{{old('phone', $phone)}}" class="form-control" name="phone" placeholder="@lang('config.hint_phone')">
                        {{ $errors->first('phone') }}
                    </div>
            </div>
            <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Member number</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" value="{{old('member_number', $member_number)}}" name="member_number" placeholder="@lang('config.hint_member')">
                        {{ $errors->first('member_number') }}
                    </div>
            </div>
            <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Active years</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" value="{{old('active_years', $active_years)}}" name="active_years" placeholder="@lang('config.hint_year')">
                        {{ $errors->first('active_years') }}
                    </div>
            </div>
            
            <div class="form-group row">
                    <div class="offset-sm-3 col-sm-9">
                        <button type="submit" class="btn btn-primary">@lang('common.button_submit')</button>
                    </div>
                </div>
    </form>
</div>
@stop


@section('css')

    <link rel="stylesheet" href="/css/admin_custom.css">

@stop


@section('js')

    <script> console.log('B ga!'); </script>

@stop