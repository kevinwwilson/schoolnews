<?php      
defined('C5_EXECUTE') or die(_("Access Denied."));
$ch = Page::getByPath("/dashboard/sixeightforms/forms");
$chp = new Permissions($ch);
if (!$chp->canRead()) {
	die(_("Access Denied."));
}

set_time_limit(500);
header('Expires: 0');
header('Cache-control: private');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Description: File Transfer');
header('Content-Type: text/csv; charset=utf-8');
header('Content-disposition: attachment; filename=export.' . time() . '.csv');

Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');
Loader::model('answer_set_list','sixeightforms');

$fID = intval($_GET['fID']);
$form = sixeightForm::getByID($fID);
$form->updateExportTimestamp();
$fields = $form->getFields();

$asl = sixeightAnswerSetList::get($fID);
$asl->setPageSize(0);

if($_GET['requireApproval'] == 1) { 
	$asl->requireApproval();
}

if($_GET['dateRange'] == 'unexported') {
	$asl->excludeExported(true);
}

if($_GET['dateRange'] == 'range') {
	$startDate = strtotime($_GET['startDate']);
	$endDate = strtotime($_GET['endDate']);
	$asl->setDateRange($startDate,$endDate);
}

$answerSets = $asl->getAnswerSets();

//Print the record ID column header
echo '"Record ID",';

//Print the date column header
echo '"Date Submitted",';

//Print the IP address column header
echo '"IP Address",';

//Print the username column header
echo '"Owner",';

//Print the other field column headers
if($_GET['ffIDs'] == '') {
	foreach($fields as $field) {
		echo '"' . str_replace("\r\n","",str_replace('"','',$field->label)) . '",';
	}
} else {
	$ffIDs = explode(',',$_GET['ffIDs']);
	foreach($ffIDs as $ffID) {
		echo '"' . str_replace("\r\n","",str_replace('"','',$fields[$ffID]->label)) . '",';
	}
}

//Add eCommerce column headers
if($form->hasCommerceField()) {
	echo '"Amount Charged",';
	echo '"Amount Paid",';
}

echo "\n";

//Loop through the answer sets
foreach($answerSets as $as) {
	//Print the record ID
	echo '"' . $as->asID . '",';
	
	//Print the date
	echo '"' . date('Y-m-d g:i:s a',$as->dateSubmitted) . '",';
	
	//Print the IP address
	echo '"' . $as->ipAddress . '",';
	
	//Print the username
	echo '"' . $as->getOwnerUserName() . '",';
	
	//Print the field data
	if($_GET['ffIDs'] == '') {
		foreach($fields as $field) {
			echo '"' . str_replace("\r\n",",",str_replace('"','',$as->answers[$field->ffID]['value'])) . '",';
		}
	} else {
		$ffIDs = explode(',',$_GET['ffIDs']);
		foreach($ffIDs as $ffID) {
			echo '"' . str_replace("\r\n",",",str_replace('"','',$as->answers[$ffID]['value'])) . '",';
		}
	}
	
	//Print the eCommerce info
	if($form->hasCommerceField()) {
		echo '"' . $as->amountCharged . '",';
		echo '"' . $as->amountPaid . '",';
	}
	
	echo "\n";
}