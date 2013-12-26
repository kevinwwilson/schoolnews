<script type="text/javascript" src="/concrete/js/jquery.js?v=d5e31ee523921b0586bfb6dec5757fdc"></script>
<?php   
defined('C5_EXECUTE') or die(_("Access Denied."));
$articlesar = $_REQUEST['articlesar'];

$newsSectionList = new PageList();
		$newsSectionList->setItemsPerPage(15);
		$newsSectionList->filterByNewsSection(1);
		$newsSectionList->sortBy('cvName', 'asc');
		$tmpSections = $newsSectionList->get();
		$sections = array();
		foreach($tmpSections as $_c) {
			$sections[$_c->getCollectionID()] = $_c->getCollectionName();
		}
$newsList = new PageList();
		$newsList->sortBy('cvDatePublic', 'desc');
		
		                if($articlesar > 0){			               
			            foreach($articlesar as $displayid){
					    $newsList->filter(false, '( cv.cID not in('.$displayid.') )');					    
					    }  
		                }
		
		$newsList->filter(false,"ak_group_status like '%Published%' or ak_group_status like '%Ready%'");
		
			if(isset($_REQUEST['cParentID']) && $_REQUEST['cParentID'] > 0){
			$newsList->filterByParentID($_GET['cParentID']);
			}
			if(empty($_REQUEST['cParentID'])){
			//$sections = $this->get('sections');
			$keys = array_keys($sections);
			$keys[] = -1;
			$newsList->filterByParentID($keys);
			}
			
			
			            if(!empty($_REQUEST['like'])){
						$newsList->filterByName($_REQUEST['like']);
						echo $_REQUEST['like'];
						}
						if(!empty($_REQUEST['cat'])){
					    $cat = $_REQUEST['cat'];
					    $newsList->filter(false,"ak_news_category like '%$cat%'");
					    echo $cat;
					    } 
					    
					    if(!empty($_REQUEST['tag'])){
					    $tag = $_REQUEST['tag'];
					    $newsList->filter(false,"ak_news_tag like '%$tag%'");
					    echo $tag;
					    }
					    
					    if(!empty($_REQUEST['dist'])){
					    $dist = $_REQUEST['dist'];
					    $newsList->filter(false,"ak_district like '%$dist%'");
					    
					    echo $dist;
					    
					    }        
            
			$newsList->setItemsPerPage(15);
		
		    $newsResults=$newsList->getPage();		
		?>
		
<div class="next-result">
<div class="img-loader" style="display:none;">
				<img class="loader" width="50" height="50" src="<?php echo BASE_URL.DIR_REL ?>/themes/kent-news/images/loader2.gif" />
				</div>
<table border="0" class="ccm-results-list search-result" cellspacing="0" cellpadding="0" >
			<tr>
				<th>&nbsp;</th>
				<th class="<?php  echo $newsList->getSearchResultsClass('cvName')?>"><a href="<?php  echo $newsList->getSortByURL('cvName', 'asc')?>"><?php  echo t('Name')?></a></th>
				<th class="<?php  echo $newsList->getSearchResultsClass('cvDatePublic')?>"><a href="<?php  echo $newsList->getSortByURL('cvDatePublic', 'asc')?>"><?php  echo t('Date')?></a></th>
				<th><?php  echo t('District')?></th>
				<th class="<?php  echo $newsList->getSearchResultsClass('news_category')?>"><a href="<?php  echo $newsList->getSortByURL('news_category', 'asc')?>"><?php  echo t('Region')?></a></th>
				
			</tr>
			<?php  
			$nh = Loader::helper('navigation');
			$pkt = Loader::helper('concrete/urls');
			$pkg= Package::getByHandle('pronews');
			foreach($newsResults as $cobj) { 
			
				Loader::model('attribute/categories/collection');
						
				$akct = CollectionAttributeKey::getByHandle('news_category');
				$news_category = $cobj->getCollectionAttributeValue($akct);
				
			?>
			<tr>
				<td width="60px">
				<a href="javascript://" onclick="addtoselectedarticle('<?php echo $cobj->getCollectionID(); ?>','<?php echo $cobj->getCollectionName(); ?>','<?php echo $nh->getLinkToCollection($cobj); ?>')" class="add-link">Add</a>	
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

<script type="text/javascript">
$(document).ready(function(){

      $('.add-link').click(function(){	
      
      alert('sdfds');
      
	  $('.img-loader').hide().ajaxStart( function() {
	  $(this).show();	  
	  } ).ajaxStop ( function(){	  
	  $(this).hide();	  
	  });
	  
	  $.ajax({
       type: "POST",
       url: "<?php echo Loader::helper('concrete/urls')->getToolsURL('add_group');  ?>",
       data : $('#news-form').serialize(),       
       success: function(msg){    
       $('.next-result').html(msg);
              
       		
		
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
   });
   
   $('.det_link').click(function(){	
      
      alert('sdfds');
      
	  $('.img-loader').hide().ajaxStart( function() {
	  $(this).show();	  
	  } ).ajaxStop ( function(){	  
	  $(this).hide();	  
	  });
	  
	  $.ajax({
       type: "POST",
       url: "<?php echo Loader::helper('concrete/urls')->getToolsURL('add_group');  ?>",
       data : $('#news-form').serialize(),       
       success: function(msg){    
       $('.next-result').html(msg);
              
       		
		
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
   });
   
   
   });
   
</script>   
			




		