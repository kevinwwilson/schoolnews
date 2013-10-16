<?php  defined('C5_EXECUTE') or die(_("Access Denied.")); ?>
<?php 
$ih = Loader::helper('concrete/interface');
Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');
?>
<?php 
$formBT = BlockType::getByHandle('sixeightforms');
$formBT->controller->displayInDialog = 1;

if($_GET['asID'] != '') {
	$as = sixeightAnswerSet::getByIDAndEditCode(intval($_GET['asID']),$_GET['editCode']);
	$formBT->controller->fID = $as->fID;
} else {
	$formBT->controller->fID = intval($_GET['fID']);
}

$formBT->render('view');

?>