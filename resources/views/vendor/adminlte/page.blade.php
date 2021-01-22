@extends('adminlte::master')

@inject('layoutHelper', \JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper)

@if($layoutHelper->isLayoutTopnavEnabled())
    @php( $def_container_class = 'container' )
@else
    @php( $def_container_class = 'container-fluid' )
@endif

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())
@section('body')
    <div class="wrapper " id="app">
        {{-- Top Navbar --}}
        @if($layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.navbar.navbar-layout-topnav')
        @else
            @include('adminlte::partials.navbar.navbar')
        @endif

        {{-- Left Main Sidebar --}}
        @if(!$layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.sidebar.left-sidebar')
        @endif

        {{-- Content Wrapper --}}
        <v-app>
        <div class="content-wrapper {{ config('adminlte.classes_content_wrapper') ?? '' }}">

            {{-- Content Header --}}
            <div class="content-header">
                <div class="{{ config('adminlte.classes_content_header') ?: $def_container_class }}">
                    @yield('content_header')
                </div>
            </div>

            {{-- Main Content --}}
            <div class="content">
                <div class="{{ config('adminlte.classes_content') ?: $def_container_class }}">
                    @yield('content')
                </div>
                <div class="cstm-footer">
                    <h5>STL Palaro</h5>
                    <p>© Copyright {{ date("Y") }}. All Rights Reserved</p>
                    <a class="backToTop" href="#app"><i class="fas fa-long-arrow-alt-up"></i></a>
                </div>
            </div>
        </div>

        </v-app>

        {{-- Footer --}}
{{--        @hasSection('footer')--}}
{{--            @include('adminlte::partials.footer.footer')--}}
{{--        @endif--}}

        {{-- Right Control Sidebar --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.sidebar.right-sidebar')
        @endif
        <vue-progress-bar></vue-progress-bar>
    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
    @yield('scripts')
@stop
