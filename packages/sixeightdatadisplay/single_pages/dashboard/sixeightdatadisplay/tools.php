<?php     
defined('C5_EXECUTE') or die(_("Access Denied."));
$h = Loader::helper('concrete/interface'); 
$uh = Loader::helper('concrete/urls');
?>
<script type="text/javascript">
function validateExport() {
	if($('.export-item:checked').length > 0) {
		$('#export-form').submit();
	} else {
		alert('<?php  echo t('Please select at least one form or template to export.'); ?>');
	}
}
</script>
<h1><span><?php  echo t('Import')?></span></h1>
<div class="ccm-dashboard-inner">
	<?php     
	$extensions_string = strtolower(str_replace(array("*","."),"",UPLOAD_FILE_EXTENSIONS_ALLOWED));
	$allowed_extensions = explode(";",$extensions_string);
	if(in_array('xml',$allowed_extensions)) {
	?>
	<form method="post" id="import-form" action="<?php  echo $this->url('/dashboard/sixeightdatadisplay/tools', 'importData')?>" enctype="multipart/form-data" >
		<table cellpadding="8" cellspacing="0" border="0">
			<tr>
				<td>
                	<?php  echo t('Select a File'); ?><br />
                    <input type="file" name="data_file"/>
				</td>
                <td>
                    <?php     
                    $b1 = $h->submit(t('Import'), 'import-form');
                    print $h->buttons($b1);
                    ?>
                </td>
			</tr>
		</table>
	</form>
    <?php  } else { ?>
    <?php  echo t('You must add "xml" to the list of'); ?> <a href="<?php  echo $this->url('/dashboard/files/access') ?>"><strong><?php  echo t('allowed files types'); ?></strong></a> <?php  echo t('before you can import a template set.'); ?>
    <?php  } ?>
    
</div>
<h1><span><?php  echo t('Export')?></span></h1>
<div class="ccm-dashboard-inner">
	<h2><?php  echo t('Items to include in export:'); ?></h2>
    <form id="export-form" action="<?php  echo $uh->getToolsURL('export','sixeightdatadisplay'); ?>" method="get">
    <h3><?php  echo t('Forms'); ?></h3>
    <?php  if(count($forms) > 0) { ?>
    	<?php  foreach($forms as $f) { ?>
    		<input class="export-item" name="forms[]" value="<?php  echo $f->fID; ?>" type="checkbox" /> <?php  echo $f->properties['name']; ?><br />
    	<?php  } ?>
    <?php  } ?><br />	
    <h3><?php  echo t('Styles'); ?></h3>
      <?php  if(count($styles) > 0) { ?>
    	<?php  foreach($styles as $s) { ?>
    		<input class="export-item" name="styles[]" value="<?php  echo $s->sID; ?>" type="checkbox" /> <?php  echo $s->name; ?><br />
    	<?php  } ?>
    <?php  } ?><br />	
    <h3><?php  echo t('List Templates'); ?></h3>
    <?php  
		if((is_array($listTemplates)) && (count($listTemplates)>0)){
			foreach($listTemplates as $template) {
				echo '<input class="export-item" name="templates[]" value="' . $template['tID'] . '" type="checkbox" /> ' . $template['templateName'] . '<br />';
			}
		} else {
			echo t('No list templates found.<br />');
		}
	?><br />	
    <h3>Detail Templates</h3>
    <?php  
		if((is_array($detailTemplates)) && (count($detailTemplates)>0)) {
			foreach($detailTemplates as $template) {
				echo '<input class="export-item" name="templates[]" value="' . $template['tID'] . '" type="checkbox" /> ' . $template['templateName'] . '<br />';
			}
		} else {
			echo t('No list templates found.<br />');
		}
	?>
    <br />
    <?php  
	echo $h->button_js( t('Export'), 'validateExport()','left');
	?>
    </form>
    <div style="clear:both"></div>
</div>
<?php  if(Package::getByHandle('datadisplay')) { ?>
<h1><span><?php  echo t('Template Converter')?></span></h1>
<div class="ccm-dashboard-inner">
	<?php  echo t('<b>Convert templates from previous versions of Data Display:</b>'); ?>
    <form id="template-conversion-form" action="<?php  echo $this->url('/dashboard/sixeightdatadisplay/tools', 'convertTemplate')?>" method="get">
	<table border="0">
		<tr>
			<td>
				Select a template:<br />
				<select name="tID">
					<option value="">---</option>
					<?php  if(is_array($oldListTemplates)) { ?>
						<?php  foreach($oldListTemplates as $olt) { ?>
							<option value="<?php  echo $olt['tID']; ?>"><?php  echo $olt['templateName'] . t(' (List)'); ?></option>
						<?php  } ?>
					<?php  } ?>
					<?php  if(is_array($oldDetailTemplates)) { ?>
						<?php  foreach($oldDetailTemplates as $odt) { ?>
							<option value="<?php  echo $odt['tID']; ?>"><?php  echo $odt['templateName'] . t(' (Detail)'); ?></option>
						<?php  } ?>
					<?php  } ?>
				</select>
			</td>
			<td>
				<?php  
                $b1 = $h->submit(t('Convert'), 'template-conversion-form');
                print $h->buttons($b1);
                ?>
			</td>
		</tr>
	</table>
	</form>
</div>
<?php  } ?>