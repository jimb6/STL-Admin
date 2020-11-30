@extends('adminlte::page')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header content-header0">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Mobile <small>Global Management</small></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="http://pashub-dashboard.herokuapp.com"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                        <li class="breadcrumb-item active">Mobile Application Setting</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <div class="content">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Roles & Permissions</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0" style="display: block;">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item active">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-inbox"></i> Permissions
                                    {{--                                    <span class="badge bg-primary float-right">12</span>--}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-inbox"></i> Roles
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-users"></i> Users
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div>
                <div class="card ">
                    <div class="card-header">
                        <h3 class="card-title">Mobile App Settings</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item">
                                <a href="http://pashub-dashboard.herokuapp.com/settings/mobile/globals" class="nav-link selected">
                                    <i class="fa fa-inbox"></i> Global Settings
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="http://pashub-dashboard.herokuapp.com/settings/mobile/colors" class="nav-link ">
                                    <i class="fa fa-inbox"></i> Theme
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="clearfix"></div>
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                            <li class="nav-item">
                                <a class="nav-link active" href="http://pashub-dashboard.herokuapp.com/settings/mobile/colors"><i class="fa fa-cog mr-2"></i>Theme</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="http://pashub-dashboard.herokuapp.com/settings/update" accept-charset="UTF-8"><input name="_method" type="hidden" value="PATCH"><input name="_token" type="hidden" value="p4V4j2tGMkKDdkl80GiNAUkuq2zjZdS2gb8C0uaZ">
                            <div class="row">
                                <h5 class="col-12 pb-4"><i class="mr-3 fa fa-pencil"></i>Application Theme</h5>

                                <!-- Main Color Field -->
                                <div class="form-group row col-6">
                                    <label for="main_color" class="col-4 control-label text-right">Main Color Bright Theme</label>
                                    <div class="col-8">
{{--                                        <div id="main-colorpicker" class="input-group colorpicker-component colorpicker-element">--}}
{{--                                            <input class="form-control colorpicker-element" placeholder="#12ED3A" autocomplete="off" name="main_color" type="text" value="#ffff77" id="main_color">--}}
{{--                                            <div class=" input-group-append ">--}}
{{--                                                <span class="input-group-addon input-group-text"><i style="background-color: rgb(255, 255, 119);"></i></span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                        <div class="input-group my-colorpicker2 colorpicker-element" data-colorpicker-id="2">
                                            <input type="text" class="form-control" data-original-title="" title="">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fas fa-square"></i></span>
                                            </div>
                                        </div>
                                        <div class="form-text text-muted">
                                            Insert the main color of the app
                                        </div>
                                    </div>
                                </div>

                                <!-- main_dark_color Field -->
                                <div class="form-group row col-6">
                                    <label for="main_dark_color" class="col-4 control-label text-right">Main Color for Dark Theme</label>
                                    <div class="col-8">
                                        <div id="main-colorpicker" class="input-group colorpicker-component colorpicker-element">
                                            <input class="form-control colorpicker-element" placeholder="#12ED3A" autocomplete="off" name="main_dark_color" type="text" value="#ff0000" id="main_dark_color">
                                            <div class=" input-group-append ">
                                                <span class="input-group-addon input-group-text"><i style="background-color: rgb(255, 0, 0);"></i></span>
                                            </div>
                                        </div>
                                        <div class="form-text text-muted">
                                            Insert the main dark color of the app
                                        </div>
                                    </div>
                                </div>

                                <!-- second_color Field -->
                                <div class="form-group row col-6">
                                    <label for="second_color" class="col-4 control-label text-right">Second Color for bright theme</label>
                                    <div class="col-8">
                                        <div id="main-colorpicker" class="input-group colorpicker-component colorpicker-element">
                                            <input class="form-control colorpicker-element" placeholder="#1466ED" autocomplete="off" name="second_color" type="text" value="#043832" id="second_color">
                                            <div class=" input-group-append ">
                                                <span class="input-group-addon input-group-text"><i style="background-color: rgb(4, 56, 50);"></i></span>
                                            </div>
                                        </div>
                                        <div class="form-text text-muted">
                                            Insert Second Color for bright theme
                                        </div>
                                    </div>
                                </div>

                                <!-- second_dark_color Field -->
                                <div class="form-group row col-6">
                                    <label for="second_dark_color" class="col-4 control-label text-right">Second Color for dark theme</label>
                                    <div class="col-8">
                                        <div id="main-colorpicker" class="input-group colorpicker-component colorpicker-element">
                                            <input class="form-control colorpicker-element" placeholder="#1466ED" autocomplete="off" name="second_dark_color" type="text" value="#ccccdd" id="second_dark_color">
                                            <div class=" input-group-append ">
                                                <span class="input-group-addon input-group-text"><i style="background-color: rgb(204, 204, 221);"></i></span>
                                            </div>
                                        </div>
                                        <div class="form-text text-muted">
                                            Insert Second Color for dark theme
                                        </div>
                                    </div>
                                </div>


                                <!-- accent_color Field -->
                                <div class="form-group row col-6">
                                    <label for="accent_color" class="col-4 control-label text-right">Accent Color</label>
                                    <div class="col-8">
                                        <div id="main-colorpicker" class="input-group colorpicker-component colorpicker-element">
                                            <input class="form-control colorpicker-element" placeholder="#ae329d" autocomplete="off" name="accent_color" type="text" value="#8c98a8" id="accent_color">
                                            <div class=" input-group-append ">
                                                <span class="input-group-addon input-group-text"><i style="background-color: rgb(140, 152, 168);"></i></span>
                                            </div>
                                        </div>
                                        <div class="form-text text-muted">
                                            Accent Color
                                        </div>
                                    </div>
                                </div>

                                <!-- accent_dark_color Field -->
                                <div class="form-group row col-6">
                                    <label for="accent_dark_color" class="col-4 control-label text-right">Accent Dark Color</label>
                                    <div class="col-8">
                                        <div id="main-colorpicker" class="input-group colorpicker-component colorpicker-element">
                                            <input class="form-control colorpicker-element" placeholder="#ae329d" autocomplete="off" name="accent_dark_color" type="text" value="#9999aa" id="accent_dark_color">
                                            <div class=" input-group-append ">
                                                <span class="input-group-addon input-group-text"><i style="background-color: rgb(153, 153, 170);"></i></span>
                                            </div>
                                        </div>
                                        <div class="form-text text-muted">
                                            Insert Dark color
                                        </div>
                                    </div>
                                </div>


                                <!-- scaffold_dark_color Field -->
                                <div class="form-group row col-6">
                                    <label for="scaffold_dark_color" class="col-4 control-label text-right">Background Color for Dark theme</label>
                                    <div class="col-8">
                                        <div id="main-colorpicker" class="input-group colorpicker-component colorpicker-element">
                                            <input class="form-control colorpicker-element" placeholder="#1466ED" autocomplete="off" name="scaffold_dark_color" type="text" value="#2c2c2c" id="scaffold_dark_color">
                                            <div class=" input-group-append ">
                                                <span class="input-group-addon input-group-text"><i style="background-color: rgb(44, 44, 44);"></i></span>
                                            </div>
                                        </div>
                                        <div class="form-text text-muted">
                                            Insert Background Color for dark theme
                                        </div>
                                    </div>
                                </div>

                                <!-- scaffold_color Field -->
                                <div class="form-group row col-6">
                                    <label for="scaffold_color" class="col-4 control-label text-right">Background Color for bright theme</label>
                                    <div class="col-8">
                                        <div id="main-colorpicker" class="input-group colorpicker-component colorpicker-element">
                                            <input class="form-control colorpicker-element" placeholder="#1466ED" autocomplete="off" name="scaffold_color" type="text" value="#fafafa" id="scaffold_color">
                                            <div class=" input-group-append ">
                                                <span class="input-group-addon input-group-text"><i style="background-color: rgb(250, 250, 250);"></i></span>
                                            </div>
                                        </div>
                                        <div class="form-text text-muted">
                                            Insert Background Color for bright theme
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Field -->
                                <div class="form-group mt-4 col-12 text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save"></i> Save Settings
                                    </button>
                                    <a href="http://pashub-dashboard.herokuapp.com/users" class="btn btn-default"><i class="fa fa-undo"></i> Cancel</a>
                                </div>
                            </div>
                        </form>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
