<?php 
defined('C5_EXECUTE') or die(_("Access Denied."));

$h = Loader::helper('concrete/interface'); 
$uh = Loader::helper('concrete/urls');
?>
<?php  echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Tools'), false);?>

<?php  echo $h->button_js( t('Return to Form List'), 'window.location = \'' . $this->url("/dashboard/sixeightforms/forms") . '\'','right','success'); ?>
<?php  if($csvNumRows == 0) { ?>
<h3><span><?php  echo t('Import Data from CSV'); ?></span></h3>
	<b><?php  echo t('Note:'); ?></b> <?php  echo t('If using Excel for Mac, save your file as'); ?> "Windows Comma Separated".
	<form method="post" id="import-form" action="<?php  echo $this->url('/dashboard/sixeightforms/import', 'startImport')?>" enctype="multipart/form-data" >
    	<h5><?php  echo t('Select a File'); ?></h5>
        <input type="file" name="data_file" /><br />
		<h5><?php  echo t('Select a Form'); ?></h5>
		<select name="fID">
			<option value="0">[Import to New Form]</option>
			<?php  if(is_array($forms)) { foreach($forms as $form) { ?>
			<option value="<?php  echo $form->fID; ?>"><?php  echo $form->properties['name']; ?></option>
			<?php  } } ?>
		</select>
		<?php  echo $h->button_js(t('Start Import'),"$('#import-form').submit()",'left'); ?>
	</form>
<?php  } ?>

<?php  if($csvNumRows > 0) { ?>
	<h3><?php  echo $csvNumRows; ?> <?php  echo t('Columns Found'); ?></h3>
	<h5><?php  echo t('Map columns to form fields'); ?></h5>
	<form method="post" id="column-map-form" action="<?php  echo $this->url('/dashboard/sixeightforms/import', 'processImport')?>">
	<input type="hidden" name="formID" value="<?php  echo $f->fID; ?>" />
	<input type="hidden" name="fileID" value="<?php  echo $fileID; ?>" />
	<table cellpadding="8" cellspacing="0" border="0">
		<tr>
			<td><strong><?php  echo t('CSV Column'); ?></strong></td>
			<td><strong><?php  echo t('Form Field'); ?></strong></td>
			<td><strong><?php  echo t('Convert new line to'); ?> &lt;br /&gt;?</strong></td>
			<td><strong><?php  echo t('Is file?'); ?></strong></td>
		</tr>
		<?php  foreach($csvData as $key => $col) { ?>
		<tr>
			<td><?php  echo sixeightField::shortenText($col,25,''); ?></td>
			<td>
				<select name="col[<?php  echo $key; ?>]">
					<option value="0"><?php  echo t('Do not import'); ?></option>
					<option value="new"><?php  echo t('Create new field - ') . sixeightField::shortenText($col,25,''); ?></option>
					<option value="timestamp"><?php  echo t('Timestamp'); ?> (YYYY-MM-DD HH:MM:SS <?php  echo t('or'); ?> Unix Timestamp)</option>
					<option value="isApproved"><?php  echo t('Approval Status (true/false)'); ?></option>
					<option value="owner"><?php  echo t('Owner (Existent only)'); ?></option>
					<option value="createdOwner"><?php  echo t('Owner (Create if non-existent)'); ?></option>
					<?php  foreach($fields as $field) { ?>
						<option value="<?php  echo $field->ffID; ?>"><?php  echo $field->shortLabel; ?></option>
					<?php  } ?>
				</select>
			</td>
			<td align="center">
				<input type="checkbox" name="nl2br[<?php  echo $key; ?>]" value="1" />
			</td>
			<td align="center">
				<input type="checkbox" name="isFile[<?php  echo $key; ?>]" value="1" />
			</td>
		</tr>
		<?php  } ?>
		<tr>
			<td colspan="4">
			<?php  echo $h->button_js(t('Finish Import'),"$('#column-map-form').submit()",'left','primary'); ?>
    		</td>
    	</tr>
	</table>
	</form>
<?php  } ?>

<?php  if($rowsImported > 0) { ?>
<h3><span><?php  echo t('Importing Complete'); ?></span></h3>

<?php  echo $rowsImported; ?> <?php  echo t('rows imported.'); ?>

<?php  } ?>

<hr />

<h3><span><?php  echo t('Form Converter'); ?></span></h3>

	<form method="get" id="convert-form" action="<?php  echo $this->url('/dashboard/sixeightforms/import', 'convertForm')?>">
	<p><?php  echo t('Convert any of your core form blocks to an advanced form.'); ?></p>
		<select name="bID">
		<?php  if(count($surveys) > 0) { ?>
			<?php  foreach($surveys as $survey) { ?>
			<option value="<?php  echo $survey['bID']; ?>"><?php  echo $survey['surveyName']; ?></option>
			<?php  } ?>
		<?php  } ?>
		</select>
		<?php  echo $h->button_js(t('Convert'),"$('#convert-form').submit()",'left'); ?>
	</form>

<hr />
	
<h3><span><?php  echo t('Export Form Definition'); ?></span></h3>

	<p><?php  echo t('You can export your form definition to XML to move it to a separate Concrete5 installation'); ?></p>
	<form method="get" id="export-form" action="<?php  echo $uh->getToolsURL('export_form_definition','sixeightforms');?>">
		<select name="fID">
			<?php  if(is_array($forms)) { foreach($forms as $form) { ?>
			<option value="<?php  echo $form->fID; ?>"><?php  echo $form->properties['name']; ?></option>
			<?php  } } ?>
		</select>
		<?php  echo $h->button_js(t('Export'),"$('#export-form').submit()",'left'); ?>
	</form>


<h3><span><?php  echo t('Import from HTML File'); ?></span><h3>
	<form method="post" id="import-form" action="<?php  echo $this->url('/dashboard/sixeightforms/import', 'startImport')?>" enctype="multipart/form-data" >
	<p>
		<input type="file" />
	</p>
	</form>

<h3><span><?php  echo t('Import from HTML text'); ?></span><h3>
	<form method="post" id="import-form" action="<?php  echo $this->url('/dashboard/sixeightforms/import', 'createFromText')?>" >
	<p>
		<textarea name="html" style="width:50%;height:250px;"></textarea><br /><br />
		<input type="submit" class="btn" />
	</p>
	</form>