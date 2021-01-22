@extends('adminlte::page')
@section('content')
    @if($user->hasRole('super-admin') || $user->hasRole('admin'))
        <dashboard></dashboard>
    @else
        <default-dashboard
            background-url="{{ asset('vendor/adminlte/dist/img/lottery-balls.png') }}"
            background-vector="{{ asset('vendor/adminlte/dist/img/non-admin-vector.png') }}"
        />
    @endif
@endsection
