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
                                <a class="nav-link active" href="http://pashub-dashboard.herokuapp.com/settings/mobile/globals"><i class="fa fa-cog mr-2"></i>Global Settings</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="http://pashub-dashboard.herokuapp.com/settings/update" accept-charset="UTF-8"><input name="_method" type="hidden" value="PATCH"><input name="_token" type="hidden" value="p4V4j2tGMkKDdkl80GiNAUkuq2zjZdS2gb8C0uaZ">
                            <div class="row">
                                <h5 class="col-12 pb-4"><i class="mr-3 fa fa-map"></i>Google Maps Key</h5>

                                <div class="form-group row col-12">
                                    <label for="google_maps_key" class="col-2 control-label text-right">Google Maps Key</label>
                                    <div class="col-10">
                                        <input class="form-control" placeholder="A2d6s65rz33r65f56erf2f5FFDghuopEFssf" name="google_maps_key" type="text" value="AIzaSyBfG9pwn-LbW9SZ_xihJ5WqZmgOWTpPf7I" id="google_maps_key">
                                        <div class="form-text text-muted">
                                            Insert google maps key ( <a href="https://console.developers.google.com/apis/dashboard">https://console.developers.google.com/apis/dashboard</a> )
                                        </div>
                                    </div>
                                </div>

                                <!-- Theme Color Field -->
                                <div class="form-group row col-6">
                                    <label for="distance_unit" class="col-4 control-label text-right">Default unit of distance</label>
                                    <div class="col-8">
                                        <select class="select2 form-control select2-hidden-accessible" id="distance_unit" name="distance_unit" tabindex="-1" aria-hidden="true"><option value="km" selected="selected">Km</option><option value="mi">mi</option></select><span class="select2 select2-container select2-container--default" dir="ltr" style="width: 361.656px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-autocomplete="list" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-distance_unit-container"><span class="select2-selection__rendered" id="select2-distance_unit-container" title="Km">Km</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                        <div class="form-text text-muted">Enter the unit of distance (must restart the app to refresh it)</div>
                                    </div>
                                </div>

                                <h5 class="col-12 pb-4 custom-field-container"><i class="mr-3 fa fa-globe"></i>Application language</h5>

                                <!-- Lang Field -->
                                <div class="form-group row col-6">
                                    <label for="mobile_language" class="col-4 control-label text-right">Language</label>
                                    <div class="col-8">
                                        <select class="select2 form-control select2-hidden-accessible" id="mobile_language" name="mobile_language" tabindex="-1" aria-hidden="true"><option value="aa">Afar</option><option value="ab">Abkhaz</option><option value="ae">Avestan</option><option value="af">Afrikaans</option><option value="ak">Akan</option><option value="am">Amharic</option><option value="an">Aragonese</option><option value="ar">Arabic</option><option value="as">Assamese</option><option value="av">Avaric</option><option value="ay">Aymara</option><option value="az">Azerbaijani</option><option value="ba">Bashkir</option><option value="be">Belarusian</option><option value="bg">Bulgarian</option><option value="bh">Bihari</option><option value="bi">Bislama</option><option value="bm">Bambara</option><option value="bn">Bengali</option><option value="bo">Tibetan Standard, Tibetan, Central</option><option value="br">Breton</option><option value="bs">Bosnian</option><option value="ca">Catalan; Valencian</option><option value="ce">Chechen</option><option value="ch">Chamorro</option><option value="co">Corsican</option><option value="cr">Cree</option><option value="cs">Czech</option><option value="cu">Old Church Slavonic, Church Slavic, Church Slavonic, Old Bulgarian, Old Slavonic</option><option value="cv">Chuvash</option><option value="cy">Welsh</option><option value="da">Danish</option><option value="de">German</option><option value="dv">Divehi; Dhivehi; Maldivian;</option><option value="dz">Dzongkha</option><option value="ee">Ewe</option><option value="el">Greek, Modern</option><option value="en" selected="selected">English</option><option value="eo">Esperanto</option><option value="es">Spanish; Castilian</option><option value="et">Estonian</option><option value="eu">Basque</option><option value="fa">Persian</option><option value="ff">Fula; Fulah; Pulaar; Pular</option><option value="fi">Finnish</option><option value="fj">Fijian</option><option value="fo">Faroese</option><option value="fr">French</option><option value="fy">Western Frisian</option><option value="ga">Irish</option><option value="gd">Scottish Gaelic; Gaelic</option><option value="gl">Galician</option><option value="gn">GuaranÃƒÂ&shy;</option><option value="gu">Gujarati</option><option value="gv">Manx</option><option value="ha">Hausa</option><option value="he">Hebrew (modern)</option><option value="hi">Hindi</option><option value="ho">Hiri Motu</option><option value="hr">Croatian</option><option value="ht">Haitian; Haitian Creole</option><option value="hu">Hungarian</option><option value="hy">Armenian</option><option value="hz">Herero</option><option value="ia">Interlingua</option><option value="id">Indonesian</option><option value="ie">Interlingue</option><option value="ig">Igbo</option><option value="ii">Nuosu</option><option value="ik">Inupiaq</option><option value="io">Ido</option><option value="is">Icelandic</option><option value="it">Italian</option><option value="iu">Inuktitut</option><option value="ja">Japanese (ja)</option><option value="jv">Javanese (jv)</option><option value="ka">Georgian</option><option value="kg">Kongo</option><option value="ki">Kikuyu, Gikuyu</option><option value="kj">Kwanyama, Kuanyama</option><option value="kk">Kazakh</option><option value="kl">Kalaallisut, Greenlandic</option><option value="km">Khmer</option><option value="kn">Kannada</option><option value="ko">Korean</option><option value="kr">Kanuri</option><option value="ks">Kashmiri</option><option value="ku">Kurdish</option><option value="kv">Komi</option><option value="kw">Cornish</option><option value="ky">Kirghiz, Kyrgyz</option><option value="la">Latin</option><option value="lb">Luxembourgish, Letzeburgesch</option><option value="lg">Luganda</option><option value="li">Limburgish, Limburgan, Limburger</option><option value="ln">Lingala</option><option value="lo">Lao</option><option value="lt">Lithuanian</option><option value="lu">Luba-Katanga</option><option value="lv">Latvian</option><option value="mg">Malagasy</option><option value="mh">Marshallese</option><option value="mi">Maori</option><option value="mk">Macedonian</option><option value="ml">Malayalam</option><option value="mn">Mongolian</option><option value="mr">Marathi (Mara?hi)</option><option value="ms">Malay</option><option value="mt">Maltese</option><option value="my">Burmese</option><option value="na">Nauru</option><option value="nb">Norwegian BokmÃƒÂ¥l</option><option value="nd">North Ndebele</option><option value="ne">Nepali</option><option value="ng">Ndonga</option><option value="nl">Dutch</option><option value="nn">Norwegian Nynorsk</option><option value="no">Norwegian</option><option value="nr">South Ndebele</option><option value="nv">Navajo, Navaho</option><option value="ny">Chichewa; Chewa; Nyanja</option><option value="oc">Occitan</option><option value="oj">Ojibwe, Ojibwa</option><option value="om">Oromo</option><option value="or">Oriya</option><option value="os">Ossetian, Ossetic</option><option value="pa">Panjabi, Punjabi</option><option value="pi">Pali</option><option value="pl">Polish</option><option value="ps">Pashto, Pushto</option><option value="pt">Portuguese</option><option value="qu">Quechua</option><option value="rm">Romansh</option><option value="rn">Kirundi</option><option value="ro">Romanian, Moldavian, Moldovan</option><option value="ru">Russian</option><option value="rw">Kinyarwanda</option><option value="sa">Sanskrit (Sa?sk?ta)</option><option value="sc">Sardinian</option><option value="sd">Sindhi</option><option value="se">Northern Sami</option><option value="sg">Sango</option><option value="si">Sinhala, Sinhalese</option><option value="sk">Slovak</option><option value="sl">Slovene</option><option value="sm">Samoan</option><option value="sn">Shona</option><option value="so">Somali</option><option value="sq">Albanian</option><option value="sr">Serbian</option><option value="ss">Swati</option><option value="st">Southern Sotho</option><option value="su">Sundanese</option><option value="sv">Swedish</option><option value="sw">Swahili</option><option value="ta">Tamil</option><option value="te">Telugu</option><option value="tg">Tajik</option><option value="th">Thai</option><option value="ti">Tigrinya</option><option value="tk">Turkmen</option><option value="tl">Tagalog</option><option value="tn">Tswana</option><option value="to">Tonga (Tonga Islands)</option><option value="tr">Turkish</option><option value="ts">Tsonga</option><option value="tt">Tatar</option><option value="tw">Twi</option><option value="ty">Tahitian</option><option value="ug">Uighur, Uyghur</option><option value="uk">Ukrainian</option><option value="ur">Urdu</option><option value="uz">Uzbek</option><option value="ve">Venda</option><option value="vi">Vietnamese</option><option value="vo">VolapÃƒÂ¼k</option><option value="wa">Walloon</option><option value="wo">Wolof</option><option value="xh">Xhosa</option><option value="yi">Yiddish</option><option value="yo">Yoruba</option><option value="za">Zhuang, Chuang</option><option value="zh">Chinese</option><option value="zu">Zulu</option></select><span class="select2 select2-container select2-container--default" dir="ltr" style="width: 361.656px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-autocomplete="list" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-mobile_language-container"><span class="select2-selection__rendered" id="select2-mobile_language-container" title="English">English</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                        <div class="form-text text-muted">Select the default language of the application</div>
                                    </div>
                                </div>

                                <h5 class="col-12 pb-4 custom-field-container"><i class="mr-3 fa fa-mobile-phone"></i>Version</h5>

                                <!-- app_version Field -->
                                <div class="form-group row col-6">
                                    <label for="app_version" class="col-4 control-label text-right">Application Version</label>
                                    <div class="col-8">
                                        <input class="form-control" placeholder="1.7.2" name="app_version" type="text" value="1.3.0" id="app_version">
                                        <div class="form-text text-muted">
                                            Insert the application version
                                        </div>
                                    </div>
                                </div>
                                <!-- 'Boolean enable_facebook Field' -->
                                <div class="form-group row col-6">
                                    <label for="enable_version" class="col-4 control-label text-right">Show Version</label>
                                    <div class="checkbox icheck">
                                        <label class="w-100 ml-2 form-check-inline">
                                            <input name="enable_version" type="hidden" id="enable_version">
                                            <div class="icheckbox_flat-blue checked" aria-checked="false" aria-disabled="false" style="position: relative;"><input checked="checked" name="enable_version" type="checkbox" value="1" id="enable_version" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                                        </label>
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
