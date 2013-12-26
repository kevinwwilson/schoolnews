<?php    
defined('C5_EXECUTE') or die(_("Access Denied."));
global $c;

if($answerSet) {
	$dataDisplay = new SixeightdatadisplayBlockController;
	$detail = $dataDisplay->generateTemplateContent($detailTemplateContent,$questions,$answerSet);
	//Replace list URL placeholder			
	$detail = str_replace('{{LISTURL}}',DIR_REL . '/index.php?cID=' . intval($_GET['ref_cID']),$detail);
	$detail = str_replace('{{CURRENTURL}}', urlencode((!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']),$detail);
	$detail = str_replace('<?php  xml version="1.0"?>','',$detail);
	$detail = str_replace('<?php xml version="1.0"?>','',$detail);
	echo $detail;
?>

<?php  } else { //No answer set found ?>
	<?php  echo $detailTemplateEmpty; ?>
<?php  } ?>