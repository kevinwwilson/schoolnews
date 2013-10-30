<?php   
$fm = Loader::helper('form');  
$pgp=Loader::helper('form/page_selector');
?>
<style type="text/css">
table td{padding: 12px!important;}
.page-link{width: 100%; float: left}
.page-link a{float: right; color:#ffffff; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); background-color: #0074cc; background-image: -moz-linear-gradient(top, 
#0088cc, #0055cc); background-image: -ms-linear-gradient(top, #0088cc, #0055cc); background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#0088cc), to(#0055cc)); background-image: -webkit-linear-gradient(top, #0088cc, #0055cc); background-image: -o-linear-gradient(top, #0088cc, #0055cc); background-image: linear-gradient(top, #0088cc, #0055cc); background-repeat: repeat-x; filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#0088cc', ndColorstr='#0055cc', GradientType=0); border-color: #0055cc #0055cc #003580; border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25); filter: progid:DXImageTransform.Micro; padding: 10px 5px 10px 5px; border-radius: 5px;}
.page-link a:hover{color:#ffffff; text-decoration: none;} 
.ccm-pane-footer.page-link{width: 100%; float: left;}
.group-list{margin-top: 20px;}
.group-list .ccm-results-list{width: 50% !important;}



</style>
<div class="ccm-ui">
	<?php   echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t($title.' News Group'), false, false, false);?>
	<div class="ccm-pane-body">
	<div class="page-link">
	   <a href="<?php echo BASE_URL.DIR_REL?>/index.php/dashboard/pronews/shedule_news_group/add_group" class="but_link">Add Group</a>
	</div>
	<br/><br/>
	<div class="group-list">
	
	<table class="ccm-results-list">
	    <tr>
				<th><strong><?php  echo t('Sheduled Date')?></strong></th>
				<th><strong><?php  echo t('Articles')?></strong></th>				
				<th></th>
		</tr>
		<?php foreach($group as $listgroup){ 
		         $atids = explode("||",$listgroup[atID]);
		         $resultcount = count($atids); ?>
		<tr>
		     
				<td><strong><?php  echo $listgroup['time']; ?></strong></td>
				<td><strong><?php  echo $resultcount; ?></strong></td>
				<td><strong><a href="<?php echo BASE_URL.DIR_REL?>/index.php/dashboard/pronews/shedule_news_group/add_group/edit_group/<?php echo $listgroup['ID']; ?>"><?php  echo t('Edit')?></a></strong></td>				
		</tr>
		<?php } ?>
	
	</table>
	
	
	</div>
	
</div>
<div class="ccm-pane-footer">
    	<?php    $ih = Loader::helper('concrete/interface'); ?>
        
    </div>

</div>