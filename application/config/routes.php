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
|	https://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = 'Login';
// $route['projects/(:any)'] = 'index/projects/$1';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['admin/results/(:any)'] 					= "admin/results/index/$1";
$route['admin/config/(:any)/(:any)'] 			= "admin/config/$1/$2";
$route['admin/users/(:any)'] 						= "admin/users/index/$1";

$route['admin/export/(:any)/(:any)'] 			= "admin/export/$1/$2";
$route['admin/export/(:any)'] 					= "admin/export/$1";

$route['admin/maintenance/(:any)'] 				= "admin/maintenance/$1";

$route['admin/achievements/(:any)']				= "admin/achievements/index/$1";

$route['admin/project/instructions/(:num)'] 	= "admin/project/instructions/$1";
$route['admin/project/statistics/(:num)']	 	= "admin/project/statistics/$1";
$route['admin/project/management/(:num)'] 	= "admin/project/management/$1";
$route['admin/student/details/(:any)'] 		= "admin/student/details/$1";
$route['admin/grade/(:any)/(:num)/(:num)'] 	= "admin/grade/index/$1/$2/$3";
$route['admin'] 										= "admin/dashboard";

$route['student/gallery/(:num)'] 				= "student/gallery/index/$1";
$route['student/projects'] 						= "student/projects/index";
$route['student/project/(:any)/(:num)']		= "student/project/$1/$2";
$route['student']										= "student/dashboard";

$route['gallery/(:num)/(:num)'] 					= "gallery/index/$1/$2";
$route['gallery/(:num)'] 							= "gallery/index/$1";

//$route['gallery/(:num)'] = "gallery/$1";
//$route['(:any)'] = "student/dashboard";


//$route['student/(:any)'] = "student/$1";
//$route['(:any)'] = "site/$1";
