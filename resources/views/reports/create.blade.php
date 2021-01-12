@extends('adminlte::page')
@section('content')


    <button  href="{{ route('report.users') }}">Click Me</button>
    <report-generator />
@stop
