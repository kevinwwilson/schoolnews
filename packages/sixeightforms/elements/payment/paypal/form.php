<?php 
defined('C5_EXECUTE') or die(_("Access Denied."));
?>
<input type="hidden" name="amount" value="<?php  echo $answerSet->amountCharged; ?>" />
<div>Order total: $<?php  echo $answerSet->amountCharged; ?></div>
<input type="hidden" name="paypal_email" value="<?php  echo $commerceAnswers['paypal_email']['value']; ?>" />
<?php 
if($commerceAnswers['paypal_currency']['value'] == '') {
	$paypalCurrency = $commerceAnswers['paypal_currency']['value'];	
} else {
	$paypalCurrency = 'USD';
}
?>
<input type="hidden" name="paypal_currency" value="<?php  echo $commerceAnswers['paypal_currency']['value']; ?>" />
<input type="hidden" name="paypal_item_description" value="<?php  echo $commerceAnswers['paypal_description']['value']; ?>" />
<input type="submit" value="<?php  echo SEM_PAYMENT_SUBMIT_BUTTON_VALUE; ?>" />