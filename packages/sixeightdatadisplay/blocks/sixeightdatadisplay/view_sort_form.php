<?php    
defined('C5_EXECUTE') or die(_("Access Denied."));
?>
<div class="sem-sort-form-container">
	<form class="sem-sort-form" action="<?php  echo $pageBase; ?>" method="get">
		<input type="hidden" name="cID" value="<?php  echo $c->getCollectionID(); ?>" />
		<input type="hidden" name="q" value="<?php  echo $_GET['q']; ?>" />
		<input type="hidden" name="page" value="<?php  echo $_GET['page']; ?>" />
		<?php  echo $sortLabel; ?>
		<select class="sem-sort-form-sort-by" name="sortBy"><option value=""></option>
		<?php     
			foreach($formFields as $formField){ 
				if ($controller->fieldIsSortable($formField->ffID,$sortableFields)) {
					echo '<option value="' . $formField->ffID . '" ';
					if ($_GET['sortBy'] == $formField->ffID) {
						echo 'selected="selected"';
					}
					echo ' >' . $formField->label . '</option>';
				}
			}
		?>
		</select>
		<select class="sem-sort-form-sort-order" name="sortOrder">
			<option value="ASC" <?php  if ($_GET['sortOrder'] == 'ASC') { echo 'selected="selected"'; } ?> >Ascending</option>
			<option value="DESC" <?php  if ($_GET['sortOrder'] == 'DESC') { echo 'selected="selected"'; } ?> >Descending</option>
		</select>
		<input class="sem-sort-form-submit" type="submit" value="<?php  echo $sortButtonLabel; ?>" />
	</form>
</div>