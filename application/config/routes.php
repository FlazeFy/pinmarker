<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'LandingController';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// API Routes
// Auth Module
$route['api/v1/auth/login'] = 'api/AuthController/post_login';

// Location Module
$route['api/v1/location/weather'] = 'api/ExternalApiController/get_current_weather';
$route['api/v1/location/reverse'] = 'api/ExternalApiController/get_nearby_places';

// Pin Module
$route['api/v1/pin'] = 'api/Pin/QueryController/get_all_pin';
$route['api/v1/pin/maps'] = 'api/Pin/QueryController/get_all_pin_maps';
$route['api/v1/pin/pin_category'] = 'api/Pin/QueryController/get_pin_category';
$route['api/v1/pin/validate_new'] = 'api/Pin/QueryController/get_validate_new_marker';
$route['api/v1/pin/create'] = 'api/Pin/CommandController/post_create_pin';
$route['api/v1/pin/update/(:any)'] = 'api/Pin/CommandController/put_update_pin/$1';

// Global List Module
$route['api/v1/global_list/my'] = 'api/GlobalListController/get_my_global_list';
$route['api/v1/global_list/recommended/tag_address'] = 'api/GlobalListController/get_recommended_tag_address';

// History Module
$route['api/v1/history'] = 'api/HistoryController/get_my_activity';

// Visit Module
$route['api/v1/visit/by_pin/(:any)'] = 'api/VisitController/get_visit_by_pin_id/$1';
$route['api/v1/visit/visit_with'] = 'api/VisitController/get_all_visit_with';
$route['api/v1/visit/visit_with/analyze/(:any)'] = 'api/VisitController/get_person_analyze/$1';

// Review Module
$route['api/v1/review/(:any)'] = 'api/ReviewController/get_review_by_pin_id/$1';

// News Module
$route['api/v1/news/(:any)'] = 'api/NewsController/get_news_by_pin_id/$1';
$route['api/v1/news/by/coordinate'] = 'api/NewsController/get_news_around_me';

// Schedule Module
$route['api/v1/schedule'] = 'api/Schedule/QueryController/get_all_schedule';
$route['api/v1/schedule/(:any)'] = 'api/Schedule/QueryController/get_schedule_by_pin_id/$1';
