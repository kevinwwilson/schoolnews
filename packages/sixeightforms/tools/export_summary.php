<?php 
/* Adjusted to use new model format on 11-26-10 */

defined('C5_EXECUTE') or die(_("Access Denied."));
$ch = Page::getByPath("/dashboard/sixeightforms/forms");
$chp = new Permissions($ch);
if (!$chp->canRead()) {
	die(_("Access Denied."));
}


header('Expires: 0');
header('Cache-control: private');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Description: File Transfer');
header('Content-Type: text/csv; charset=utf-8');
header('Content-disposition: attachment; filename=summary.' . time() . '.csv');


Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');
Loader::model('answer_set_list','sixeightforms');

$fID = intval($_GET['fID']);
$form = sixeightForm::getByID($fID);
$fields = $form->getFields();
$asl = sixeightAnswerSetList::get($fID);
$asl->setPageSize(0);
$answerSets = $asl->getAnswerSets();
$answerSetCount = count($answerSets);

echo '"Field Name","Response","Count","Percentage"' . "\n";

foreach($fields as $field) {
	if(count($field->options) > 0) {
		foreach($field->options as $option) {
			//Count answers
			$answerCount = 0;
			foreach($answerSets as $as) {
				$answers = explode("\r\n",$as->answers[$field->ffID]['value']);
				if(count($answers) > 0) {
					foreach($answers as $a) {
						if ($option['value'] == $a) {
							$answerCount++;
						}
					}
				}
			}
			
			echo '"' . str_replace("\r\n","",str_replace('"','',$field->label)) . '",';
			echo '"' . $option['value'] . '",';
			echo '"' . $answerCount . '",';
			$percentage = round($answerCount / $answerSetCount * 100);
			echo '"' . $percentage . '%",';
			echo "\n";
		}
	}
	//echo '"' . str_replace("\r\n","",str_replace('"','',$field['label'])) . '",';
}