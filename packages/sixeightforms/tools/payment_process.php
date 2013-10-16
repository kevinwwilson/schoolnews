<?php   
/* Adjusted to use new model format on 11-26-10 */

defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');

$f = sixeightForm::getByID(intval($_POST['fID']));
$answerSet = sixeightAnswerSet::getByID(intval($_POST['asID']));
//Loader::element('sixeightforms/payment/' . $form->properties['gateway'] . '/config');
//$gateway = new semGateway();
//Loader::element('sixeightforms/payment/' . $form->properties['gateway'] . '/processor',array('answerSet' => $answerSet));

$f->loadGatewayConfig();
$f->loadGatewayProcessor(array('answerSet' => $answerSet));

?>
