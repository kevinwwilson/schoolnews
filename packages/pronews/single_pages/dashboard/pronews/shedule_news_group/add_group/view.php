<?php  
$df = Loader::helper('form/date_time');
$ih = Loader::helper('concrete/interface');

if (is_object($news)) { 
    $secondaryheadline = $news->getCollectionAttributeValue('secondary_headline');
	$author = $news->getCollectionAttributeValue('author');
	$mainphoto = $news->getCollectionAttributeValue('main_photo');
	$photocaption = $news->getCollectionAttributeValue('photo_caption');
	$dateline = $news->getCollectionAttributeValue('dateline');
	$district = $news->getCollectionAttributeValue('district');
	$regional_feature = $news->getCollectionAttributeValue('regional_feature');	
	$news_tag = $news->getCollectionAttributeValue('news_tag');	
	$long_summary = $news->getCollectionAttributeValue('long_summary');	
	$files = $news->getCollectionAttributeValue('files');
	$newsTitle = $news->getCollectionName();
	$newsDescription = $news->getCollectionDescription();
	$newsDate = $news->getCollectionDatePublic();
	$cParentID = $news->getCollectionParentID();
	$ctID = $news->getCollectionTypeID();
	$newsBody = '';
	$eb = $news->getBlocks('Main');
	foreach($eb as $b) {
		if($b->getBlockTypeHandle()=='content'){
			$newsBody = $b->getInstance()->getContent();
		}
	}
	$task = 'edit_group';
	$buttonText = t('Update News Item');
	$title = 'Update';
} else {
	$task = 'add_group';
	$buttonText = t('Add News Item');
	$title= 'Add';
}
?>




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
.top-buttons .btn {
    margin-left: 10px;
}
.edit {background-position: -22px -2225px;margin-right: 6px!important;}
.copy {background-position: -22px -439px;margin-right: 6px!important;}
.delete {background-position: -22px -635px;}
.group-article{height: 200px; overflow: scroll; border: 1px solid #666; width: 700px}
.group-article .article{margin-left: 10px; padding-bottom: 10px; margin-right: 10px; border-bottom: 1px solid #ccc; padding-top: 10px;}
.next-result{position: relative;}
.next-result .img-loader{position: absolute; width: 100%; height: 100%; text-align: center; opacity: 0.5; background: #000;}
.next-result .img-loader img{position: absolute; top:50%; left: 50%; opacity: 1;}
.group-article .det_link{float: right; padding-right: 30px}
.ccm-pane-footer .ccm-button-v2-left{color:#fff; background: #ef3939;}
.ccm-pane-footer .ccm-button-v2-left:hover{color:#fff; background: #e02e2e;}
.ccm-ui div.ccm-pagination span {
    margin-right: 4px;
}
</style>
<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper1(t('Add News Group'), false, false, false);?>


<?php  if ($this->controller->getTask() == 'edit_group') { 
        if($selart>0){
        foreach($selart as $sectarticles){
	        $sel_id = $sectarticles['ID'];
        
        ?>
		<form method="post" action="<?php    echo $this->action('edit_groups/'.$sel_id.'')?>" id="news-form">		
	<?php } } }else{ ?>
		<form method="post" action="<?php    echo $this->action('save_group')?>" id="news-form">
	<?php  } ?>

     

	<div class="ccm-pane-body">
            <div class="top-buttons">
                <?php  
                if ($this->controller->getTask() == 'edit_group') { 
                    print $ih->submit(t('Edit Group'), 'news-form', 'right', 'primary'); 
                } else { 
                    print $ih->submit(t('Add Group'), 'news-form', 'right', 'primary'); 
                }
                    print $ih->button(t('Cancel'), $this->url('/dashboard/pronews/shedule_news_group/'), 'right'); 
                ?>
            </div>
	<div class="group-add-box">
	
     <div class="clearfix">
					<?php  echo $form->label('groupDate', t('Group Date'))?>
					<div class="input">
						<?php 
						if($selart>0){
						foreach($selart as $sectarticles){
						$date_field = $sectarticles['time'];					
						echo Loader::helper('form/date_time')->datetime('date_field', $date_field);
						} 
						}else{
						echo Loader::helper('form/date_time')->datetime('date_field', $date_field);							
						}
						
						?>
						
					</div>
	 </div>
	 
	 
	 
	 <div class="clearfix">
					<?php  echo t('Selected Article'); ?>
					<div class="input group-article">
					
					<?php 
					
					
					if($selart>0){
						foreach($selart as $sectarticles){
							$sectarticlesid = explode("||",$sectarticles['atID']);
							
														
							  foreach($sectarticlesid as $displayid){
							  
							 
							  
							  if($displayid != ''){
								 Loader::model('page_list');
	                             $pl = new PageList();	                                 
	                             $pl->filter(false, '( cv.cID in('.$displayid.') )');
	                              
	                             $pages = $pl->getPage();
	                             foreach($pages as $cpage){ 
	                             $nh = Loader::helper('navigation');
	                             $url = $nh->getLinkToCollection($cpage);
	                             $cpages = $cpage->getCollectionName();
	                             $id = $cpage->cID;
	                             
		                             echo '<div class="article"><a href="'.$url.'">'.$cpages.'</a><input type="hidden" name="articlesar[]"   value="'.$id.'"><a class="det_link" href="javascript:void(0)" onclick="removepage(this)">Remove</a></div>';
		                             
		                             
	                              }
	                            }
	                             								  
							  }
						}
					}
					?>
						
					</div>
	 </div>
     
     </div>
	
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
		<div class="hidden-result"></div>
		<?php  
		$sections[0] = '** All';
		asort($sections);
		?>
		<table class="ccm-results-list">
			<tr>
				<th><strong><?php  echo $form->label('cParentID', t('District'))?></strong></th>
				<th><strong><?php  echo t('by Name')?></strong></th>
				<th><strong><?php  echo t('by Region')?></strong></th>
				<th><strong><?php  echo t('by Tag')?></strong></th>
				<th></th>
			</tr>
			<tr>
				<td><select name="dist" style="width: 130px!important;" id="searchdist">
					<option value=''>--</option>
				<?php 
				foreach($dist_values as $dist){
					if($_GET['dist']==$dist['value']){$selected = 'selected="selected"';}else{$selected=null;}
					echo '<option '.$selected.'>'.$dist['value'].'</option>';
				}	
				?>
				</select></td>
				<td><?php  echo $form->text('like', $like)?></td>
				<td>
				<select name="cat" style="width: 110px!important;" id="searchcat">
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
				<select name="tag" style="width: 110px!important;" id="searchtag">
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
				<a href="javascript://"  class="search-article btn">Search</a>	
				</td>
			</tr>
		</table>
		
		
		<br/>
		<?php  
		$nh = Loader::helper('navigation');
		if ($newsList->getTotal() > 0) { 
			$newsList->displaySummary();
			?>
		<div class="next-result">
		<div class="img-loader" style="display:none;">
				<img class="loader" width="50" height="50" src="<?php echo BASE_URL.DIR_REL ?>/themes/kent-news/images/loader2.gif" />
				</div>	
		<table border="0" class="ccm-results-list search-result" cellspacing="0" cellpadding="0">
			<tr>
				<th>&nbsp;</th>
				<th class="<?php  echo $newsList->getSearchResultsClass('cvName')?>"><a href="<?php  echo $newsList->getSortByURL('cvName', 'asc')?>"><?php  echo t('Name')?></a></th>
				<th class="<?php  echo $newsList->getSearchResultsClass('cvDatePublic')?>"><a href="<?php  echo $newsList->getSortByURL('cvDatePublic', 'asc')?>"><?php  echo t('Date')?></a></th>
				<th><?php  echo t('District')?></th>
				<th class="<?php  echo $newsList->getSearchResultsClass('news_category')?>"><a href="<?php  echo $newsList->getSortByURL('news_category', 'asc')?>"><?php  echo t('Region')?></a></th>
				
			</tr>
			<?php  
			$pkt = Loader::helper('concrete/urls');
			$pkg= Package::getByHandle('pronews');
			foreach($newsResults as $cobj) { 
			
			    if($cobj->getCollectionAttributeValue('group_status') == 'Published')
				Loader::model('attribute/categories/collection');
						
				$akct = CollectionAttributeKey::getByHandle('news_category');
				$news_category = $cobj->getCollectionAttributeValue($akct);
				
			?>
			<tr>
				<td width="60px">
                                    <a href="javascript://" onclick="addtoselectedarticle('<?php echo $cobj->getCollectionID(); ?>','<?php echo str_replace("'","\'", $cobj->getCollectionName()); ?>','<?php echo $nh->getLinkToCollection($cobj); ?>')" class="add-link">Add</a>	
				</td>
				<td> <a href="<?php  echo $nh->getLinkToCollection($cobj)?>"><?php  echo $cobj->getCollectionName()?></a></td>
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
				   if($district >0){
				   foreach($district as $dist){
				   echo $dist->value, ',';
				   }
				   }
				   
				   
				   ?>
				</td>
				
				<td><?php  echo $news_category;?></td>
				
			</tr>
			<?php  } ?>
			</table>
			<br/>
			<div class="ajax-page">
			<?php  
			$newsList->displayPaging();
			?>
			</div>
		</div>
			<?php
		} else {
			print t('No news entries found.');
		}
		?>
	</div>
    <div class="ccm-pane-footer">
    	<?php  if ($this->controller->getTask() == 'edit_group') { ?>
    	 <?php  print $ih->submit(t('Edit Group'), 'news-form', 'right', 'primary'); ?>
    	<?php } else { ?>
        <?php  print $ih->submit(t('Add Group'), 'news-form', 'right', 'primary'); ?>
        <?php } ?>
        <?php  print $ih->button(t('Cancel'), $this->url('/dashboard/pronews/shedule_news_group/'), 'right'); ?>
        <?php  if ($this->controller->getTask() == 'edit_group') { ?>
        <?php  print $ih->button(t('Delete Group'), $this->url('/dashboard/pronews/shedule_news_group/add_group/delete_group/'.$remove_cid.''), 'left'); }?>
    </div>
	</form>
	<?php echo Loader::helper('form')->hidden('currentpage',1); ?>
	
<script type="text/javascript">

function addtoselectedarticle(id, name, url) {
  $('.group-article').append('<div class="article"><a href="<?php echo BASE_URL.DIR_REL?>'+url+'">'+name+'</a><input type="hidden" name="articlesar[]"   value="'+id+'"><a class="det_link" href="javascript:void(0)" onclick="removepage(this)">Remove</a></div>'); 
   var disver =$('#currentpage').val();
   displaypagenext(disver); 
   function displaypagenext(displaypage){
   $('.img-loader').hide().ajaxStart( function() {   
   $(this).show();   
   } ).ajaxStop ( function(){ 
   $(this).hide();
  
  });
  
	$.ajax({
       type: "POST",
       url: "<?php echo Loader::helper('concrete/urls')->getToolsURL('get_nextpage');  ?>",
   	   data: $('#news-form').serialize()+"&displaypage="+displaypage,
       
       success: function(msg){    
       $('.next-result').html(msg);
       
       $('.ajax-page a').each(function(){
		$(this).attr('href','javascript::void(0)');
		var pagelink=$(this).html();
		$(this).attr('displaypage',pagelink);
		$(this).addClass('getajaxpage');
	
		});
		$('.getajaxpage').click(function(){
			var disppage=$(this).attr('displaypage');
	
	    var currentpage = $('#currentpage').val();
		if(disppage=='Next »'){
			disppage=parseInt(currentpage)+1;
		}
		if(disppage=='« Previous'){
			disppage=parseInt(currentpage)-1;
		}		
			$('#currentpage').val(disppage);				
				displaypagenext(disppage);
			});	
 
       }       
     });      
       
       
   }   
  } 
  
   
 </script>
 
 <script type="text/javascript">
 function removepage(tthis){
 var disver =$('#currentpage').val();
	 $(tthis).parent().remove(); 
	 displaypagenext(disver);
	 
	 function displaypagenext(displaypage){
   $('.img-loader').hide().ajaxStart( function() {   
   $(this).show();   
   } ).ajaxStop ( function(){ 
   $(this).hide();
  
  });
  
	$.ajax({
       type: "POST",
       url: "<?php echo Loader::helper('concrete/urls')->getToolsURL('get_nextpage');  ?>",
   	   data: $('#news-form').serialize()+"&displaypage="+displaypage,
       
       success: function(msg){    
       $('.next-result').html(msg);
       
       $('.ajax-page a').each(function(){
		$(this).attr('href','javascript::void(0)');
		var pagelink=$(this).html();
		$(this).attr('displaypage',pagelink);
		$(this).addClass('getajaxpage');
	
		});
		$('.getajaxpage').click(function(){
			var disppage=$(this).attr('displaypage');
	
	    var currentpage = $('#currentpage').val();
		if(disppage=='Next »'){
			disppage=parseInt(currentpage)+1;
		}
		if(disppage=='« Previous'){
			disppage=parseInt(currentpage)-1;
		}		
			$('#currentpage').val(disppage);				
				displaypagenext(disppage);
			});	
 
       }       
     });      
       
       
   }  
 }
$(document).ready(function(){

     
   

	$('.ajax-page a').each(function(){
		$(this).attr('href','javascript::void(0)');
		var pagelink=$(this).html();
		$(this).attr('displaypage',pagelink);
		$(this).addClass('getajaxpage');
		
	});
	
	$('.getajaxpage').click(function(){
		    var disppage=$(this).attr('displaypage');
		
		    var currentpage = $('#currentpage').val();
			if(disppage=='Next »'){
				disppage=parseInt(currentpage)+1;
			}
			if(disppage=='« Previous'){
				disppage=parseInt(currentpage)-1;
			}		
	       $('#currentpage').val(disppage);	
		   displaypagenext(disppage);
	});


 $('.search-article').click(function(){    
   $('.img-loader').hide().ajaxStart( function() {
   $(this).show();  
  } ).ajaxStop ( function(){  
  $(this).hide();  
  }); 
  
  
  $.ajax({
       type: "POST",
       url: "<?php echo Loader::helper('concrete/urls')->getToolsURL('article_search');  ?>",
       data : $('#news-form').serialize(), 
       
       success: function(msg){    
       $('.next-result').html(msg);
       
       $('.ajax-page a').each(function(){
		$(this).attr('href','javascript::void(0)');
		var pagelink=$(this).html();
		$(this).attr('displaypage',pagelink);
		$(this).addClass('getajaxpage');
	
		});
		$('.getajaxpage').click(function(){
			var disppage=$(this).attr('displaypage');
	
	    var currentpage = $('#currentpage').val();
		if(disppage=='Next »'){
			disppage=parseInt(currentpage)+1;
		}
		if(disppage=='« Previous'){
			disppage=parseInt(currentpage)-1;
		}		
		$('#currentpage').val(disppage);				
		displaypagenext(disppage);
		});			
		var searchcat = $('#searchcat').val();
			var searchtag = $('#searchtag').val();
			var searchdist = $('#searchdist').val();
			var searchname = $('#like').val();
			
		$('.hidden-result').append('<input type="hidden" name="search-cat" value="'+searchcat+'"><input type="hidden" name="search-tag" value="'+searchtag+'"><input type="hidden" name="search-dist" value="'+searchdist+'"><input type="hidden" name="search-name" value="'+searchname+'">'); 
       }       
     }); 
  });
  
  
   function displaypagenext(displaypage){
   $('.img-loader').hide().ajaxStart( function() {   
   $(this).show();   
   } ).ajaxStop ( function(){ 
   $(this).hide();
  
  });
  
	$.ajax({
       type: "POST",
       url: "<?php echo Loader::helper('concrete/urls')->getToolsURL('get_nextpage');  ?>",
   	   data: $('#news-form').serialize()+"&displaypage="+displaypage,
       
       success: function(msg){    
       $('.next-result').html(msg);
       
       $('.ajax-page a').each(function(){
		$(this).attr('href','javascript::void(0)');
		var pagelink=$(this).html();
		$(this).attr('displaypage',pagelink);
		$(this).addClass('getajaxpage');
	
		});
		$('.getajaxpage').click(function(){
			var disppage=$(this).attr('displaypage');
	
	    var currentpage = $('#currentpage').val();
		if(disppage=='Next »'){
			disppage=parseInt(currentpage)+1;
		}
		if(disppage=='« Previous'){
			disppage=parseInt(currentpage)-1;
		}		
			$('#currentpage').val(disppage);				
				displaypagenext(disppage);
			});	
 
       }       
     });      
       
       
   }  
 });      
</script> 

     
 
 
 
 
 
 
 