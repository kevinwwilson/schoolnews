<?php 
$uh = Loader::helper('concrete/urls');

$path = Page::getCollectionPathFromID(intval($_POST['cID']));
if (URL_REWRITING == true) {
	$path = BASE_URL . DIR_REL . $path;
} else {
	$path = BASE_URL . DIR_REL . '/' . DISPATCHER_FILENAME . $path;
}

$ipn = BASE_URL . $uh->getToolsURL('paypal_ipn','sixeightforms');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html lang="en" xmlns="http://www.w3.org/1999/xhtml"> 
<head>
</head>
<body onload="document.semPaypalForm.submit();">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="semPaypalForm">
	<input type="hidden" name="cmd" value="_xclick" />
	<input type="hidden" name="amount" value="<?php  echo $_POST['amount']; ?>" />
	<input type="hidden" name="business" value="<?php  echo $_POST['paypal_email']; ?>" />
	<input type="hidden" name="currency_code" value="<?php  echo $_POST['paypal_currency']; ?>" />
	<?php  if($_POST['paypal_item_description'] == '') { ?>
	<input type="hidden" name="item_name" value="<?php  echo SITE; ?>" />
	<?php  } else { ?>
	<input type="hidden" name="item_name" value="<?php  echo $_POST['paypal_item_description']; ?>" />
	<?php  } ?>
	<input type="hidden" name="return" value="<?php  echo $path; ?>" />
	<input type="hidden" name="notify_url" value="<?php  echo $ipn; ?>" />
	<input type="hidden" name="invoice" value="<?php  echo $_POST['asID']; ?>" />
</form>
</body>
</html>