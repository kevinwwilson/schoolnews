<?php    
defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');

$f = sixeightForm::getByID(intval($_GET['fID']));

if($f->userCanEdit()) {
	$answerSets = $f->getAnswerSetSamples(intval($_GET['ffID']));
}

echo json_encode($answerSets);