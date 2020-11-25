@extends('layouts.app')
@section('content')
    {{ Carbon\Carbon::now()->format('i')}}
    {{ Carbon\Carbon::now()->get('second')}}
@endsection
