<?php     
defined('C5_EXECUTE') or die(_("Access Denied."));
Loader::model('sixeightdatadisplay','sixeightdatadisplay');

$template = Sixeightdatadisplay::getTemplate(intval($_GET['tID']));
if($_GET['section']=='alt') {
	$data = $template['templateAlternateContent'];
} else {
	$data = $template['templateContent'];
}

echo '<?php xml version="1.0"?><template>' . $data . '</template>';

?>
