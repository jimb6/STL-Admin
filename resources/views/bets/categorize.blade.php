@extends('adminlte::page')
@section('content')
    <Bet :bet-type="'{{$betType}}'"></Bet>
@endsection
