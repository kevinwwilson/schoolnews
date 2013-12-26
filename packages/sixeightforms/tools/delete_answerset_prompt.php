<?php   
defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');

$ih = Loader::helper('concrete/interface');
$uh = Loader::helper('concrete/urls');
$as = sixeightAnswerSet::getByIDAndEditCode(intval($_GET['asID']),$_GET['editCode']);
?>

<?php  if(intval($as->cID) == 0) { ?>
	<div style="margin:0 0 10px 0">
		<h3><?php  echo t('Are you sure you want to delete this record?'); ?></h3>
		<div><?php  echo t('Click "Delete" to confirm or "Close" to cancel.'); ?></div>	
	</div>
	<?php  echo $ih->button(t('Delete'),$uh->getToolsURL('delete_answerset?asID=' . intval($_GET['asID']) . '&editCode=' . $_GET['editCode'] . '&cID=' . intval($_GET['cID']),'sixeightforms'),'left'); ?>
<?php  } else { ?>
	<div style="margin:0 0 10px 0">
		<h3><?php  echo t('This record has a page associated with it.'); ?></h3>
		<?php  echo t('What would you like to do? (Deleting the page will also delete any subpages.)'); ?>
		
	</div>
	<?php  echo $ih->button(t('Delete Record Only'),$uh->getToolsURL('delete_answerset?asID=' . intval($_GET['asID']) . '&editCode=' . $_GET['editCode'] . '&cID=' . intval($_GET['cID']),'sixeightforms'),'left'); ?>
	<?php  echo $ih->button(t('Delete Record and Page'),$uh->getToolsURL('delete_answerset?asID=' . intval($_GET['asID']) . '&editCode=' . $_GET['editCode'] . '&cID=' . intval($_GET['cID']) . '&deletePage=1','sixeightforms'),'left'); ?>
<?php  } ?>