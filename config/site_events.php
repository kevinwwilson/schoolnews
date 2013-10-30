<?php defined('C5_EXECUTE') or die("Access Denied.");
 /*=========================== 3rd sept 2013 comment by client request start ======================*/
/* Events::extend('on_page_add', 'ChangeNotifications', 'on_page_add', 'libraries/change_notifications.php');
Events::extend('on_page_update', 'ChangeNotifications', 'on_page_update', 'libraries/change_notifications.php');
Events::extend('on_page_delete', 'ChangeNotifications', 'on_page_delete', 'libraries/change_notifications.php');
Events::extend('on_page_move', 'ChangeNotifications', 'on_page_move', 'libraries/change_notifications.php');
Events::extend('on_page_duplicate', 'ChangeNotifications', 'on_page_duplicate', 'libraries/change_notifications.php');
Events::extend('on_page_version_add', 'ChangeNotifications', 'on_page_version_add', 'libraries/change_notifications.php');
Events::extend('on_page_version_approve', 'ChangeNotifications', 'on_page_version_approve', 'libraries/change_notifications.php');*/
/*=========================== 3rd sept 2013 comment by client request end ======================*/

Events::extend('on_start', 'ChangeNotifications', 'on_start', 'libraries/change_notifications.php');
//Events::extend('on_start', 'PageReplacement', 'on_start','models/page_replacement.php');