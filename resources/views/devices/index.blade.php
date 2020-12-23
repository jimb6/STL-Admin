@extends('adminlte::page')
@section('content')
    <Device canAdd="{{ Auth::user()->hasRole('Super-Admin') }}"/>
@stop
