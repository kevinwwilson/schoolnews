<?php    

defined('C5_EXECUTE') or die(_("Access Denied."));


/* 
	you can override system layouts here  - but we're not going to by default 
	
	For example: if you would like to theme your login page with the Green Salad theme,
	you would uncomment the lines below and change the second argument of setThemeByPath 
	to be the handle of the the Green Salad theme "greensalad" 

*/

$v = View::getInstance();

$v->setThemeByPath('/login', "kent-news"); 
$v->setThemeByPath('/403', "kent-news");
$v->setThemeByPath('/register', "kent-news");
$v->setThemeByPath('/download_file', "kent-news");
$v->setThemeByPath('/install', "kent-news");
$v->setThemeByPath('/maintenance_mode', "kent-news");
$v->setThemeByPath('/members', "kent-news");
$v->setThemeByPath('/page_forbidden', "kent-news");
$v->setThemeByPath('/upgrade', "kent-news");
$v->setThemeByPath('/maintenance_mode', "kent-news");
$v->setThemeByPath('/page_not_found', "kent-news");
$v->setThemeByPath('/user_error', "kent-news");