<?php     
defined('C5_EXECUTE') or die(_("Access Denied."));
$bt->inc('edit_form.php', array('fID' => $controller->getFormID(),'sID' => $controller->getStyleID(),'requireSSL' => $controller->requireSSL));
?>