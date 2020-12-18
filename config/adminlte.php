<?php


return [
    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#61-title
    |
    */

    'title' => config('name', 'STL Application'),
    'title_prefix' => config('base', ''),
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#62-favicon
    |
    */

    'use_ico_only' => true,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#63-logo
    |
    */

    'logo' => '<b>STL</b> APPLICATION',
    'logo_img' => 'vendor/adminlte/dist/img/logo_v2.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'STL-ADMIN',

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#64-user-menu
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-danger',
    'usermenu_image' => true,
    'usermenu_desc' => true,
    'usermenu_profile_url' => true,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#71-layout
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => ['xs' => true, 'lg' => false],
    'layout_fixed_navbar' => ['xs' => true, 'lg' => false],
    'layout_fixed_footer' => ['xs' => true, 'lg' => false],

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#721-authentication-views-classes
    |
    */

    'classes_auth_card' => 'bg-gradient-dark',
    'classes_auth_header' => '',
    'classes_auth_body' => 'bg-gradient-dark',
    'classes_auth_footer' => 'text-center',
    'classes_auth_icon' => 'text-light',
    'classes_auth_btn' => 'btn-flat btn-light',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#722-admin-panel-classes
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-danger elevation-4',
    'classes_sidebar_nav' => 'nav-compact',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#73-sidebar
    |
    */

    'sidebar_mini' => 'md',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => true,
    'sidebar_collapse_remember' => true,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 200,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#74-control-sidebar-right-sidebar
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#65-urls
    |
    */

    'use_route_url' => false,

    'dashboard_url' => 'admin/home',

    'logout_url' => 'logout',

    'login_url' => 'login',

    'register_url' => 'register',

    'password_reset_url' => 'password/reset',

    'password_email_url' => 'password/email',

    'profile_url' => true,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#92-laravel-mix
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#8-menu-configuration
    |
    */


    'menu' => [
        [
            'text' => 'Dashboard',
            'url' => '/admin/home',
            'icon' => 'fas fa-tachometer-alt',
            'label_color' => 'success',
            'can' => 'menu dashboard',
            'key' => 'dashboard',
            'classes' => '',
        ],
        [
            'header' => 'App Management',
        ],
        [
            'text' => 'Bases',
            'icon' => 'fas fa-house-user',
            'label_color' => 'success',
            'can' => 'menu bases',
            'classes' => '',
            'key' => 'bases',
            'submenu' => [
                [
                    'text' => 'All Bases',
                    'can' => 'list bases',
                    'route' => 'bases.index',
                ],
                [
                    'text' => 'Add New',
                    'can' => 'create bases',
                    'route' => 'bases.index',
                ],
            ],
        ],
        [
            'text' => 'Agents',
             'url'         => '/#',
            'icon' => 'fas fa-users',
            'label_color' => 'success',
            'can' => 'menu agents',
            'classes' => '',
            'submenu' => [
                [
                    'text' => 'All Agents',
                    'can' => 'list agents',
                    'route' => 'agents.index',
                    'active' => ['admin/agents']
                ],

            ]

        ],
        [
            'text' => 'Booths',
            'key' => 'boots',
            // 'url'         => '/#',
            'icon' => 'fas fa-store',
            'label_color' => 'success',
            'classes' => '',
            'can' => 'menu booths',
            'submenu' => [
                [
                    'text' => 'All Booths',
                    'can' => 'view booths',
                    'route' => 'booths.index',
                    'active' => ['admin/booths']
                ],
                [
                    'text' => 'Add New',
                    'can' => 'view booths',
                    'route' => 'booths.create',
                    'active' => ['admin/booths/create']
                ],

            ]
        ],
        [
            'text' => 'Bets',
            'key' => 'bets',
            'can' => 'menu bets',
            // 'url'         => '/#',
            'icon' => 'fas fa-coins',
            'label_color' => 'success',
            'classes' => '',
            'submenu' => [
                [
                    'text' => 'All Bets',
                    'can' => 'view booths',
                    'route' => 'bets.index',
                    'active' => ['admin/bets', 'admin/bets*']
                ],
            ]
        ],
        [
            'text' => 'Games',
            'key' => 'games',
            'can' => 'menu games',
            // 'url'         => '/#',
            'icon' => 'fas fa-dice',
            'label_color' => 'success',
            'classes' => '',
            'submenu' => [
                [
                    'text' => 'All Games',
                    'can' => 'view games',
                    'route' => 'games.index',
                    'active' => ['admin/games', 'admin/games*']
                ],
            ]
        ],
        [
            'text' => 'Draw Periods',
            'key' => 'draw-periods',
            'can' => 'menu draw periods',
            // 'url'         => '/#',
            'icon' => 'fas fa-clock',
            'label_color' => 'success',
            'classes' => '',
            'submenu' => [
                [
                    'text' => 'All Draw Periods',
                    'can' => 'view draw periods',
                    'route' => 'draw-periods.index',
                    'active' => ['admin/draw-periods', 'admin/draw-periods*']
                ],
            ]
        ],
        ['header' => 'Accounting Management'],
        [
            'text' => 'Collections',
            // 'url'         => '/#',
            'icon' => 'fas fa-briefcase',
            'label_color' => 'success',
            'can' => 'menu collections',
            'classes' => 'xs text-wrap',
            'submenu' => [
                [
                    'text' => 'All Collections',
                    'can' => 'view booths',
                    'route' => 'booths.index',
                ],
                [
                    'text' => 'Add New',
                    'can' => 'view booths',
                    'route' => 'booths.create',
                ],
            ]
        ],
        [
            'header' => 'Reporting',
            'can' => 'menu reports',
        ],
        [
            'text' => 'Reports',
            // 'url'         => '/#',
            'icon' => 'fas fa-print',
            'label_color' => 'success',
            'can' => 'menu reports',
            'classes' => 'xs',

            'submenu' => [
                [
                    'text' => 'All Reports',
                    'can' => 'view booths',
                    'route' => 'booths.index',
                ],
                [
                    'text' => 'Add New',
                    'can' => 'view booths',
                    'route' => 'booths.create',
                ],
            ]
        ],

        ['header' => 'Settings'],
        [
            'text' => 'Account',
            // 'url'         => '/#',
            'icon' => 'fas fa-user-lock',
            'label_color' => 'success',
//            'can' => 'menu settings',
            'classes' => '',
            'submenu' => [
                [
                    'text' => 'Profile',
                    'route' => 'user.profile',
                ],
                [
                    'text' => 'Logout',
                    'url' => '#',
                ],
            ]
        ],
        [
            'text' => 'Device Settings',
            // 'url'         => '/#',
            'icon' => 'fas fa-mobile-alt',
            'label_color' => 'success',
            'can' => 'menu mobilesettings',
            'classes' => '',
            'submenu' => [
                [
                    'text' => 'Registered Devices',
                    'route' => 'devices.index',
                    'active' => ['admin/devices']
                ],
                [
                    'text' => 'Global Settings',
                    'route' => 'devices.create',
                    'active' => ['admin/devices/*']
                ],
            ]
        ],
        [
            'text' => 'Settings',
            // 'url'         => '/#',
            'icon' => 'fas fa-cogs',
            'label_color' => 'success',
            'can' => 'menu settings',
            'classes' => '',
            'submenu' => [
                [
                    'text' => 'Global Settings',
                    'route' => 'settings.global',
                    'active' => ['admin/settings/app']
                ],
                [
                    'text' => 'Users',
                    'route' => 'users.index',
                    'active' => ['admin/users'],
                ],
                [
                    'text' => 'Roles & Permissions',
                    // 'url'         => '/#',
//                    'icon' => 'fas fa-user-secret',
                    'label_color' => 'success',
                    'classes' => '',
                    'submenu' => [
                        [
                            'text' => 'Permissions List',
                            'route' => 'permissions.index',
                            'active' => ['admin/permissions'],
                        ],
                        [
                            'text' => 'Create Permission',
                            'route' => 'permissions.create',
                            'active' => ['admin/permissions/create'],
                        ],
                        [
                            'text' => 'Roles List',
                            'route' => 'roles.index',
                            'active' => ['admin/roles'],
                        ],
                        [
                            'text' => 'Create Role',
                            'route' => 'roles.create',
                            'active' => ['admin/roles/create'],
                        ],
                    ]
                ],
                [
                    'text' => 'Push Notifications',
                    'url' => '/#',
                ],
                [
                    'text' => 'Mail',
                    'url' => '/#',
                ],
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#83-custom-menu-filters
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#91-plugins
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#93-livewire
    */

    'livewire' => false,
];
