<?php

/**
 * config.php
 *
 * Author: pixelcave
 *
 * Configuration file. It contains variables used in the template as well as the primary navigation array from which the navigation is created
 *
 */

/* Template variables */
$usrFname = '';
$usrLname = '';
$usrType = '';

if (isset($this->session->userdata['logged_in'])) {
    $usrFname = $this->session->userdata['logged_in']['user_fname'];
    $usrLname = $this->session->userdata['logged_in']['user_lname'];
    $usrType = $this->session->userdata['logged_in']['usertype'];
}

$template = array(
    'name' => 'Codeflix',
    'version' => '3.2',
    'author' => 'Hardik',
    'robots' => 'noindex, nofollow',
    'title' => 'HIR NDUSTRIES',
    'description' => 'ProUI is a Responsive Bootstrap Admin Template created by pixelcave and published on Themeforest.',
    // true                     enable page preloader
    // false                    disable page preloader
    'page_preloader' => false,
    // true                     enable main menu auto scrolling when opening a submenu
    // false                    disable main menu auto scrolling when opening a submenu
    'menu_scroll' => true,
    // 'navbar-default'         for a light header
    // 'navbar-inverse'         for a dark header
    'header_navbar' => 'navbar-default',
    // ''                       empty for a static layout
    // 'navbar-fixed-top'       for a top fixed header / fixed sidebars
    // 'navbar-fixed-bottom'    for a bottom fixed header / fixed sidebars
    'header' => '',
    // ''                                               for a full main and alternative sidebar hidden by default (> 991px)
    // 'sidebar-visible-lg'                             for a full main sidebar visible by default (> 991px)
    // 'sidebar-partial'                                for a partial main sidebar which opens on mouse hover, hidden by default (> 991px)
    // 'sidebar-partial sidebar-visible-lg'             for a partial main sidebar which opens on mouse hover, visible by default (> 991px)
    // 'sidebar-mini sidebar-visible-lg-mini'           for a mini main sidebar with a flyout menu, enabled by default (> 991px + Best with static layout)
    // 'sidebar-mini sidebar-visible-lg'                for a mini main sidebar with a flyout menu, disabled by default (> 991px + Best with static layout)
    // 'sidebar-alt-visible-lg'                         for a full alternative sidebar visible by default (> 991px)
    // 'sidebar-alt-partial'                            for a partial alternative sidebar which opens on mouse hover, hidden by default (> 991px)
    // 'sidebar-alt-partial sidebar-alt-visible-lg'     for a partial alternative sidebar which opens on mouse hover, visible by default (> 991px)
    // 'sidebar-partial sidebar-alt-partial'            for both sidebars partial which open on mouse hover, hidden by default (> 991px)
    // 'sidebar-no-animations'                          add this as extra for disabling sidebar animations on large screens (> 991px) - Better performance with heavy pages!
    'sidebar' => 'sidebar-partial sidebar-visible-lg sidebar-no-animations',
    // ''                       empty for a static footer
    // 'footer-fixed'           for a fixed footer
    'footer' => '',
    // ''                       empty for default style
    // 'style-alt'              for an alternative main style (affects main page background as well as blocks style)
    'main_style' => '',
    // ''                           Disable cookies (best for setting an active color theme from the next variable)
    // 'enable-cookies'             Enables cookies for remembering active color theme when changed from the sidebar links (the next color theme variable will be ignored)
    'cookies' => '',
    // 'night', 'amethyst', 'modern', 'autumn', 'flatie', 'spring', 'fancy', 'fire', 'coral', 'lake',
    // 'forest', 'waterlily', 'emerald', 'blackberry' or '' leave empty for the Default Blue theme
    'theme' => 'flatie',
    // ''                       for default content in header
    // 'horizontal-menu'        for a horizontal menu in header
    // This option is just used for feature demostration and you can remove it if you like. You can keep or alter header's content in page_head.php
    'header_content' => '',
    'active_page' => "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
    'user_fname' => $usrFname, // assign value to varriable
    'user_lname' => $usrLname,
    'usertype' => $usrType
);

function getValue($value, $globalArr)
{
    $result = array();
    if (count($value) > 0) {
        foreach ($value as $key) {

            if (array_key_exists($key, $globalArr)) {
                $result[] = $globalArr[$key];
            }
        }
    } else {
        $result = $globalArr;
    }

    return $result;
}

/* Primary navigation array (the primary navigation will be created automatically based on this array, up to 3 levels deep) */
$global =
    array(
        "Dashboard" => array(
            'name' => 'Dashboard',
            'url' => BASE_URL . 'admin',
            'icon' => 'gi gi-stopwatch'
        ),
        "LiveMap" => array(
            'name' => 'Live Map',
            'icon' => 'fa fa-map-marker fa-fw',
            'url' => BASE_URL . 'admin/users/liveMap'
        ),
        "Users" => array(
            'name' => 'Users',
            'icon' => 'fa fa-user fa-fw',
            'sub' => array(
                array(
                    'name' => 'List',
                    'url' => BASE_URL . 'admin/users',
                ),
                array(
                    'name' => 'Users Tree',
                    'url' => BASE_URL . 'admin/users/treeview',
                ),
                array(
                    'name' => 'Active User',
                    'url' => BASE_URL . 'admin/users/attendanceList',
                ),
                array(
                    'name' => 'Employee Grade',
                    'url' => BASE_URL . 'admin/users/employeeGrade',
                ),
                array(
                    'name' => 'Attendance Report',
                    'url' => BASE_URL . 'admin/users/attendanceReport',
                ),
                // array(
                //     'name' => 'Archive User',
                //     'url' => BASE_URL . 'admin/users/archiveuser',
                // ),
                array(
                    'name' => 'Location Report',
                    'url' => BASE_URL . 'admin/users/locationReport',
                )
            )
        ),
        "Party" => array(
            'name' => 'Parties',
            'icon' => 'fa fa-users fa-fw',
            // 'url'   => BASE_URL . 'admin/dealers',
            'sub' => array(
                array(
                    'name' => 'Parties List',
                    'url' => BASE_URL . 'admin/dealers',
                ),
                array(
                    'name' => 'Parties Category',
                    'url' => BASE_URL . 'admin/dealercategories',
                ),
                // array(
                //     'name' => 'Parties Type',
                //     'url' => BASE_URL . 'admin/dealertypes',
                // ),
            )
        ),
        "Product" => array(
            'name' => 'Products',
            'icon' => 'fa fa-users fa-fw',
            // 'url'   => BASE_URL . 'admin/dealers',
            'sub' => array(
                array(
                    'name' => 'Product List',
                    'url' => BASE_URL . 'admin/products',
                ),
                array(
                    'name' => 'Products Category',
                    'url' => BASE_URL . 'admin/productcategories',
                ),
                array(
                    'name' => 'Parties Product Price',
                    'url' => BASE_URL . 'admin/Dealersproduct/dealerLists',
                ),
                array(
                    'name' => 'Product Order',
                    'url' => BASE_URL . 'admin/productorder',
                ),
            )
        ),
        "Visits" => array(
            'name' => 'Employee Visits',
            'icon' => 'fa fa-plane fa-fw',
            'url' => BASE_URL . 'admin/visits'
        ),
        "CityGrade" => array(
            'name' => 'City Grade',
            'url' => BASE_URL . 'admin/cities',
            'icon' => 'fa fa-university fa-fw'
        ),
        "Expenses" => array(
            'name' => 'Expenses',
            'url' => BASE_URL . 'admin',
            'icon' => 'fa fa-money fa-fw',
            'sub' => array(
                array(
                    'name' => 'Category List',
                    'url' => BASE_URL . 'admin/expenses/categories',
                ), array(
                    'name' => 'Expense List',
                    'url' => BASE_URL . 'admin/expenses/list',
                ),
            )
        ),
        "Payments" => array(
            'name' => 'Payments',
            'url' => BASE_URL . 'admin',
            'icon' => 'fa fa-money fa-fw',
            'sub' => array(
                array(
                    'name' => 'Pending',
                    'url' => BASE_URL . 'admin/payments/pending',
                ), array(
                    'name' => 'Approved',
                    'url' => BASE_URL . 'admin/payments/approved',
                ),
            )
        ),
        "Complains" => array(
            'name' => 'Complains',
            'url' => BASE_URL . 'admin/complains',
            'icon' => 'fa fa-comments',
        ),
        "Follow Up" => array(
            'name' => 'Follow Up',
            'url' => BASE_URL . 'admin/followup',
            'icon' => 'fa fa-fighter-jet',
        ),
        "Enquiries" => array(
            'name' => 'Enquires',
            'url' => BASE_URL . 'admin/enquires',
            'icon' => 'fa fa-envelope'
        ),
        "Messages" => array(
            'name' => 'Messages (SMS)',
            'url' => BASE_URL . 'admin',
            'icon' => 'fa fa-envelope',
            'sub' => array(
                array(
                    'name' => 'Messages Scheduler',
                    'url' => BASE_URL . 'admin/messages/',
                ), array(
                    'name' => 'Offdays',
                    'url' => BASE_URL . 'admin/offdays/',
                ),
            )
        ),
        "Notification" => array(
            'name' => 'Notification',
            'url' => BASE_URL . 'admin',
            'icon' => 'fa fa-envelope',
            'sub' => array(
                array(
                    'name' => 'Notification List',
                    'url' => BASE_URL . 'admin/notifications/',
                ),
                array(
                    'name' => 'Notification Send',
                    'url' => BASE_URL . 'admin/notifications/send',
                ),
            )
        ),
        "Master Module" => array(
            'name' => 'Master Module',
            'url' => BASE_URL . 'admin',
            'icon' => 'fa fa-bars',
            'sub' => array(
                array(
                    'name' => 'Countries',
                    'url' => BASE_URL . 'admin/countries',
                ), array(
                    'name' => 'Zones',
                    'url' => BASE_URL . 'admin/zones',
                ),
                array(
                    'name' => 'States',
                    'url' => BASE_URL . 'admin/states',
                ),
            )
        ),
        "Settings" => array(
            'name' => 'Settings',
            'icon' => 'fa fa-wrench fa-fw',
            'url' => BASE_URL . 'admin/settings',
            'sub' => array(
                array(
                    'name' => 'Currency',
                    'url' => BASE_URL . 'admin/settings/currency',
                ),
                // array(
                //     'name'  => 'Site Settings',
                //     'url'   => BASE_URL.'admin/settings/site_settings',
                // )
                array(
                    'name' => 'Add Settings',
                    'url' => BASE_URL . 'admin/settings/add_settings',
                )
            )
        ),

    );


$single = array(
    'name' => 'Customer Area',
    'icon' => 'fa fa-user-secret fa-fw',
    'sub' => array(

        array(
            'name' => 'Customer Login',
            'url' => BASE_URL . 'admin/customerarea/customerlogin'
        ),
    )
);

$userType = (isset($this->session->userdata['logged_in']['usertype'])) ? $this->session->userdata['logged_in']['usertype'] : '';

$custmrStatus = (isset($this->session->userdata['signup_cust']['status'])) ? $this->session->userdata['signup_cust']['status'] : '';

switch ($userType) {

    case 'Super Admin':
        $global['Settings']['sub'][] = array('name' => 'Site Settings', 'url' => BASE_URL . 'admin/settings/site_settings');
        $primary_nav = getValue($this->config->item('admin_dashboard'), $global);

        break;

    case 'Admin':
        $global['Settings']['sub'][] = array('name' => 'Site Settings', 'url' => BASE_URL . 'admin/settings/site_settings');
        $primary_nav = getValue($this->config->item('admin_dashboard'), $global);
        break;

    case 'Sub Admin':
        unset($global['Settings']);
        $primary_nav = getValue($this->config->item('subadmin_dashboard'), $global);

    default:
        # code...
        break;
}
