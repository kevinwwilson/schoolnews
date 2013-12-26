<style type="text/css">
a:hover {text-decoration:none;} /*BG color is a must for IE6*/
a.tooltip span {display:none; padding:2px 3px; margin-left:8px; margin-top: -20px;}
a.tooltip:hover span{display:inline; position:absolute; background:#ffffff; border:1px solid #cccccc; color:#6c6c6c;}
th {text-align: left;}
.align_top{vertical-align: top;}
.ccm-results-list tr td{ border-bottom-color: #dfdfdf; border-bottom-width: 1px; border-bottom-style: solid;}
.icon {
display: block;
float: left;
height:20px;
width:20px;
background-image:url('<?php  echo ASSETS_URL_IMAGES?>/icons_sprite.png'); /*your location of the image may differ*/
}
.edit {background-position: -22px -2225px;margin-right: 6px!important;}
.copy {background-position: -22px -439px;margin-right: 6px!important;}
.delete {background-position: -22px -635px;}
.gro-select select{width: 110px !important}
.gro-select .greensel{background:#0ff707;}
.gro-select .whitesel{background:#fff;}
.gro-select .redesel{background:#fc0404;}
.gro-select .yellowesel{background:#fdfd00;}



</style>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('View/Search News'), false, false, false);?>
	<div class="ccm-pane-body">
		<?php 
		if($remove_name){
		?>
		<div class="alert-message block-message error">
		  <a class="close" href="<?php  echo $this->action('clear_warning');?>">×</a>
		  <p><strong><?php  echo t('Holy guacamole! This is a warning!');?></strong></p><br/>
		  <p><?php  echo t('Are you sure you want to delete '.$remove_name.'?');?></p>
		  <p><?php  echo t('This action may not be undone!');?></p>
		  <div class="alert-actions">
		    <a class="btn small" href="<?php  echo $this->action('deletethis', $remove_cid, $remove_name)?>"><?php  echo t('Yes Remove This');?></a> <a class="btn small" href="<?php  echo $this->action('clear_warning');?>"><?php  echo t('Cancel');?></a>
		  </div>
		</div>
		<?php 
		}
		?>
		<!--
		<ul class="breadcrumb">
		  <li class="active">List <span class="divider">|</span></li>
		  <li><a href="/index.php/dashboard/pronews/add_news/">Add/Edit</a> </li>
		</ul>
		-->
		<form method="get" action="<?php  echo $this->action('view')?>">
		<?php  
		$sections[0] = '** All';
		asort($sections);
		?>
		<table class="ccm-results-list">
			<tr>
				<th><strong><?php  echo $form->label('cParentID', t('District'))?></strong></th>
				<th><strong><?php  echo t('by Name')?></strong></th>
				<th><strong><?php  echo t('by Author')?></strong></th>
				<th><strong><?php  echo t('by Region')?></strong></th>
				<th><strong><?php  echo t('by Tag')?></strong></th>
				<th></th>
			</tr>
			<tr>
				<td><select name="dist" style="width: 130px!important;">
					<option value=''>--</option>
				<?php 
				foreach($dist_values as $dist){
					if($_GET['dist']==$dist['value']){$selected = 'selected="selected"';}else{$selected=null;}
					echo '<option '.$selected.'>'.$dist['value'].'</option>';
				}	
				?>
				</select></td>
				<td><?php  echo $form->text('like', $like)?></td>
				<td><?php  echo $form->text('author', $author)?></td>
				<td>
				<select name="cat" style="width: 110px!important;">
					<option value=''>--</option>
				<?php 
				foreach($cat_values as $cat){
					if($_GET['cat']==$cat['value']){$selected = 'selected="selected"';}else{$selected=null;}
					echo '<option '.$selected.'>'.$cat['value'].'</option>';
				}	
				?>
				</select>
				</td>
				<td>
				<select name="tag" style="width: 110px!important;">
					<option value=''>--</option>
				<?php 
				foreach($tag_values as $tag){
					if($_GET['tag']==$tag['value']){$selected = 'selected="selected"';}else{$selected=null;}
					echo '<option '.$selected.'>'.$tag['value'].'</option>';
				}	
				?>
				</select>
				</td>
				<td>
				<?php  echo $form->submit('submit', 'Search')?>
				</td>
			</tr>
		</table>
		
		</form>
		<br/>
		<?php  
		$nh = Loader::helper('navigation');
		if ($newsList->getTotal() > 0) { 
			$newsList->displaySummary();
			?>
			
		<table border="0" class="ccm-results-list" cellspacing="0" cellpadding="0">
			<tr>
				<th>&nbsp;</th>
				<th class="<?php  echo $newsList->getSearchResultsClass('cvName')?>"><a href="<?php  echo $newsList->getSortByURL('cvName', 'asc')?>"><?php  echo t('Name')?></a></th>
				<th class="<?php  echo $newsList->getSearchResultsClass('cvDatePublic')?>"><a href="<?php  echo $newsList->getSortByURL('cvDatePublic', 'asc')?>"><?php  echo t('Date')?></a></th>
				<th><?php  echo t('District')?></th>
				<th class="<?php  echo $newsList->getSearchResultsClass('news_category')?>"><a href="<?php  echo $newsList->getSortByURL('news_category', 'asc')?>"><?php  echo t('Region')?></a></th>
				
				<th><?php  echo t('Group Status')?></th>
			</tr>
			<?php  
			$pkt = Loader::helper('concrete/urls');
			$pkg= Package::getByHandle('pronews');
			foreach($newsResults as $cobj) { 
			
				Loader::model('attribute/categories/collection');
						
				$akct = CollectionAttributeKey::getByHandle('news_category');
				$news_category = $cobj->getCollectionAttributeValue($akct);
				
			?>
			<tr>
				<td width="60px">
				<a href="<?php  echo $this->url('/dashboard/pronews/add_news', 'edit', $cobj->getCollectionID())?>" class="icon edit"></a>				
				</td>
				<td><a href="<?php  echo $nh->getLinkToCollection($cobj)?>"><?php  echo $cobj->getCollectionName()?></a></td>
				<td>
				<?php  
				if ($cobj->getCollectionDatePublic() > date(DATE_APP_GENERIC_MDYT_FULL) ){
					echo '<font style="color:green;">';
					echo $cobj->getCollectionDatePublic();
					echo '</font>';
				}else{
					echo $cobj->getCollectionDatePublic(DATE_APP_GENERIC_MDYT_FULL);
				}
				?>
				</td>
				<td>
				   <?php 
				   $district = $cobj->getCollectionAttributeValue('district');
				   if($district>0){
				   foreach($district as $dist){
				   echo $dist->value, ',';
				   }
				   }
				   
				   
				   ?>
				
					
				</td>
				<td><?php  echo $news_category;?></td>
				
				<td class="gro-select" id = "<?php echo 'cat'.$cobj->cID ?>">
				<form >
				<?php echo Loader::helper('form')->hidden('news_id',$cobj->cID); ?>
				<?php  
						Loader::model("attribute/categories/collection");
						$akct = CollectionAttributeKey::getByHandle('group_status');
						if (is_object($cobj)) {
							$tcvalue = $cobj->getAttributeValueObject($akct);
						}
						?>
						<?php  echo $akct->render('form', $tcvalue, true);?>
				</form>
				
				<script type="text/javascript">
				 var id = '<?php echo "cat".$cobj->cID ?>';
				 
				 
				 if($("#"+id+" .ccm-input-select").val() == '93'){
				 
				 $('#'+id+' .ccm-input-select').addClass('greensel');
				 
				 }else if($("#"+id+" .ccm-input-select").val() == '92'){
					 
				$('#'+id+' .ccm-input-select').addClass('whitesel');	 
				 }else if($("#"+id+" .ccm-input-select").val() == '94'){
				 
				$('#'+id+' .ccm-input-select').addClass('yellowesel');
					 
				 }else{
					$('#'+id+' .ccm-input-select').addClass('redesel'); 
				 }
				 
				 </script>
				
				 
				
						</td>
			</tr>
			
			
			
			<?php  } ?>
			</table>
			<br/>
			<?php  
			$newsList->displayPaging();
			
		} else {
			print t('No news entries found.');
		}
		?>
	</div>
    <div class="ccm-pane-footer">

    </div>
    </form>
    
    <script type="text/javascript">
    
    $(document).ready(function(){
    
    $(".gro-select select option[value='']").remove();
       
    
	$('.gro-select select').change(function(){	
	    var crid = $(this).parent().parent().attr('id');
	    
		var data=$(this).parent().serialize();
	 $.ajax({
				   type: "POST",
			   	   url: "<?php echo Loader::helper('concrete/urls')->getToolsURL('change_status');  ?>",
				   data: data,
				  		 success: function(data) {
				  		 
				  		 //alert(data);
					  		if($.trim(data) == 'whitesel'){
					  		
					  		$("#"+crid+" .ccm-input-select").removeClass('redesel');
					  		$("#"+crid+" .ccm-input-select").removeClass('greensel');
					  		$("#"+crid+" .ccm-input-select").removeClass('yellowesel');
					  		$("#"+crid+" .ccm-input-select").addClass('whitesel');
					  		  
					  		}else if($.trim(data) == 'greensel'){
					  		
						  	$("#"+crid+" .ccm-input-select").removeClass('redesel');
					  		$("#"+crid+" .ccm-input-select").removeClass('whitesel');
					  		$("#"+crid+" .ccm-input-select").removeClass('yellowesel');
					  		$("#"+crid+" .ccm-input-select").addClass('greensel');	
						  		
					  		}else if($.trim(data) == 'redesel'){
						  		
						  	$("#"+crid+" .ccm-input-select").removeClass('greensel');
					  		$("#"+crid+" .ccm-input-select").removeClass('whitesel');
					  		$("#"+crid+" .ccm-input-select").removeClass('yellowesel');
					  		$("#"+crid+" .ccm-input-select").addClass('redesel');
						  		
					  		}
					  		
					  		else if($.trim(data) == 'yellowesel'){
						  		
						  	$("#"+crid+" .ccm-input-select").removeClass('greensel');
					  		$("#"+crid+" .ccm-input-select").removeClass('whitesel');
					  		$("#"+crid+" .ccm-input-select").removeClass('redesel');
					  		$("#"+crid+" .ccm-input-select").addClass('yellowesel');
						  		
					  		}
					  		 
				  		 
				  		 						 
							 
							 }
			    });
		});
	
	
	});
    
    
   </script>   