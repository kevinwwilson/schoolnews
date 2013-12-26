<?php   
/* Adjusted to use new model format on 11-26-10 */

defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');

$uh = Loader::helper('concrete/urls');
$f = sixeightForm::getByID(intval($_POST['fID']));
$fields = $f->getFields();
$answerSet = sixeightAnswerSet::getByID(intval($_POST['asID']));
$commerceAnswers = $answerSet->getCommerceAnswers($answerSet);
if(SEM_PAYMENT_POST_URL == '') {
	$postURL = SEM_PAYMENT_POST_URL;
} else {
	$postUrl = $uh->getToolsURL('payment_process','sixeightforms');
}
?>
<div id="sem-commerce-message"><?php   echo $f->properties['ecommerceConfirmation']; ?></div>
<form method="post" action="<?php   echo $postUrl; ?>">
	<input type="hidden" name="fID" value="<?php   echo intval($_POST['fID']); ?>" />
	<input type="hidden" name="asID" value="<?php   echo intval($_POST['asID']); ?>" />
	<input type="hidden" name="cID" value="<?php   echo intval($_POST['cID']); ?>" />
	<input type="hidden" name="bID" value="<?php   echo intval($_POST['bID']); ?>" />
	<?php  
	$f->loadGatewayConfig();
	$f->loadGatewayForm(array('f' => $f, 'fields' => $fields, 'answerSet' => $answerSet, 'commerceAnswers' => $commerceAnswers));
    ?>
</form>