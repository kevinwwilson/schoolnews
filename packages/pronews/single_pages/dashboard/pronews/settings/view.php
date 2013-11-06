<?php   
$fm = Loader::helper('form');  
$pgp=Loader::helper('form/page_selector');
?>
<style type="text/css">
table td{padding: 12px!important;}
</style>
<div class="ccm-ui">
	<?php   echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t($title.' News'), false, false, false);?>
	<div class="ccm-pane-body">
	
		<!--
		<ul class="breadcrumb">
		  <li><a href="/index.php/dashboard/problog/list/">List</a> <span class="divider">|</span></li>
		  <li><a href="/index.php/dashboard/problog/add_blog/">Add/Edit</a> <span class="divider">|</span></li>
		  <li><a href="/index.php/dashboard/problog/comments/">Comments</a> <span class="divider">|</span></li>
		  <li class="active">Settings </li>
		</ul>
		-->
		<h4><?php    echo t('Options')?></h4> 
		<br/><br/>
		
		<form enctype="multipart/form-data" method="post" action="<?php echo $this->action('save_settings')?>" id="settings" style="width: 480px!important;" >		
		<h4><?php   echo t('PageType Settings')?></h4><br/>
		<div style="width: 380px;">
			<table id="settings3" class="ccm-grid" style="width: 380px;">
				<tr>
					<th class="header">
					<strong><?php    echo t('Page Type')?></strong>
					</th>
				</tr>
				<tr>
					<td>

					<?php    echo $fm->select('PAGE_TYPE_ID', $pageTypes, $page_type_id)?>
					</td>
				</tr>
			</table>
		</div>
		
		<br/><br/>
		<h4><?php   echo t('Section Settings')?></h4><br/>
		<div style="width: 380px;">
			<table id="settings3" class="ccm-grid" style="width: 380px;">
				<tr>
					<th class="header">
					<strong><?php    echo t('Section')?></strong>
					</th>
				</tr>
				<tr>
					<td>
					
					<?php    echo $fm->select('SECTIONS_ID', $sections, $sections_id)?>
					</td>
				</tr>
			</table>
		</div>
		
		<br/><br/>
		
		<div class="ccm-pane-footer" style="width: 341px;">
    	<?php    $ih = Loader::helper('concrete/interface'); ?>
        <?php    print $ih->submit(t('Save Settings'), 'settings-form', 'right', 'primary'); ?>
        </form>
	
	</div>
	
    
    <h4><?php   echo t('Add news using csv')?></h4><br/>
		<div style="width: 380px;">
			<table id="settings3" class="ccm-grid" style="width: 380px;">
				<tr>
					<th class="header">
					<strong><?php    echo t('CSV File')?></strong>
					</th>
				</tr>
				<tr>
					<td>
					<form action="<?php echo $this->action('import_news')?>" enctype="multipart/form-data" method="post">			
					
					
		                <input type="file" name="cvFile" />                
		            
					
					</td>
				</tr>
			</table>
		</div>
		
		<br /><br />
		
		
		<div class="ccm-pane-footer" style="width: 341px;">	
					<input type="submit" value="Import" name="import" class="btn ccm-button-v2 primary ccm-button-v2-right">
					</div>				
					</form>
		</div>
</div>






