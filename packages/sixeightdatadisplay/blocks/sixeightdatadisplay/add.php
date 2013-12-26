<?php    
defined('C5_EXECUTE') or die(_("Access Denied."));

$vars = array();
$vars['isReady'] = $isReady;
$vars['forms'] = $forms;
$vars['fID'] = $fID;
$vars['listTemplates'] = $listTemplates;
$vars['detailTemplates'] = $detailTemplates;
$vars['formCount'] = $formCount;
$vars['showExpiredRecords'] = $showExpiredRecords;
$vars['approvedOnly'] = $approvedOnly;
$vars['showOwnedRecords'] = $showOwnedRecords;
$vars['itemsPerPage'] = $itemsPerPage;
$vars['displayPaginator'] = $displayPaginator;
$vars['detailsInline'] = $detailsInline;
$vars['detailPage'] = $detailPage;
$vars['enableSearch'] = $enableSearch;
$vars['enableSearchReset'] = $enableSearchReset;
$vars['searchPlaceholder'] = $searchPlaceholder;
$vars['searchButtonText'] = $searchButtonText;
$vars['sortOrder'] = $sortOrder;
$vars['enableUserSort'] = $enableUserSort;
$vars['sortBy'] = $sortBy;
$vars['sortLabel'] = $sortLabel;
$vars['sortButtonLabel'] = $sortButtonLabel;
$vars['listTemplateID'] = $listTemplateID;
$vars['detailTemplateID'] = $detailTemplateID;
$vars['defaultFilterField'] = $defaultFilterField;
$vars['defaultFilterValue'] = $defaultFilterValue;
$vars['controller'] = $controller;

$bt->inc('datadisplayform.php',$vars);
?>