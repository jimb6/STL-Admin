@extends('adminlte::page')
@section('content')

    <User :user-data="'{{ $users  }}'" />
@stop
