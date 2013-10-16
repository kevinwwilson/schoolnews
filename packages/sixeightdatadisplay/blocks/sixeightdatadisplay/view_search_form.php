<?php    
defined('C5_EXECUTE') or die(_("Access Denied."));
?>

<div class="sem-search-form-container">
	<form class="sem-search-form" action="<?php  echo $pageBase; ?>" method="get">
		<input type="hidden" name="cID" value="<?php  echo intval($c->getCollectionID()); ?>" />
		<input type="hidden" name="sortBy" value="<?php  echo intval($_GET['sortBy']); ?>" />
		<input type="hidden" name="sortOrder" value="<?php  echo htmlentities($_GET['sortOrder']); ?>" />
		<input class="sem-search-input" id="sem-search-input-<?php  echo $bID; ?>" type="text" size="20" name="q" value="<?php  echo htmlentities($searchPlaceholder); ?>" onfocus="this.value='';" />
		<input class="sem-search-submit" type="submit" value="<?php  echo $searchButtonText; ?>" />
		<?php  if($enableSearchReset) { ?>
			<input class="sem-search-reset" id="sem-search-reset-<?php  echo $bID; ?>" type="submit" value="<?php  echo $searchResetButtonText; ?>" />
		<?php  } ?>
	</form>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$('#sem-search-reset-<?php  echo $bID; ?>').click(function() {
		$('#sem-search-input-<?php  echo $bID; ?>').val('');
	});
});
</script>