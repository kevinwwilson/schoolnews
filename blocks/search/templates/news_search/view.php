<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
<form action="#" method="get" class="ccm-search-block-form">	
	<input name="q" type="text" value="<?php if($_GET['q'] != ''){echo $_GET['q'];}?>" class="ccm-search-block-text" />	
	<input name="submit" type="submit" value="<?php echo $buttonText?>" class="ccm-search-block-submit" />
</form>