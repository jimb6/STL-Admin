@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

{{--@section('adminlte_css_pre')--}}
{{--    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">--}}
{{--@stop--}}
{{--@section('auth_header', __('adminlte::adminlte.login_message'))--}}

@section('auth_body')
    <login></login>
@stop
