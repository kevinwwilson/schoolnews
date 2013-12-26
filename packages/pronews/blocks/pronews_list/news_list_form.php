<?php  defined('C5_EXECUTE') or die(_("Access Denied.")); ?> 
<?php  $c = Page::getCurrentPage(); ?>

<input type="hidden" name="pageListToolsDir" value="<?php  echo $uh->getBlockTypeToolsURL($bt)?>/" />
<div class="deals-attributes">
	 <strong><?php  echo t('News Title')?></strong>
	 <input id="ccm-pagelist-rssTitle" type="text" name="title" style="width:250px" value="<?php  echo $title?>" /><br /><br />
</div> 
<div id="ccm-pagelistPane-add" class="ccm-pagelistPane">
	<div class="ccm-block-field-group">
	  <strong><?php  echo t('Number and Type of Pages')?></strong>
	  <?php  echo t('Display')?>
	  <input type="text" name="num" value="<?php  echo $num?>" style="width: 30px">
	  <?php  echo t('pages of type')?>
	  <?php  
			$ctArray = CollectionType::getList();
	
			if (is_array($ctArray)) { ?>
	  <select name="ctID" id="selectCTID">
		<option value="0">** All **</option>
		<?php  foreach ($ctArray as $ct) { ?>
		<option value="<?php  echo $ct->getCollectionTypeID()?>" <?php  if ($ctID == $ct->getCollectionTypeID()) { ?> selected <?php  } ?>>
		<?php  echo $ct->getCollectionTypeName()?>
		</option>
		<?php  } ?>
	  </select>
	  <?php  } ?>
	  
	  <strong><?php  echo t('Filter')?></strong>
	  
	<div class="news-attributes">
		<div>
			<select name="category">
			<?php  
			$db = Loader::db();
			$k=$db->query("SELECT akID FROM AttributeKeys WHERE akHandle = 'news_category'");
			while($row=$k->fetchrow()){
				$key=$row['akID'];
			}
			$r=$db->query("SELECT value FROM atSelectOptions WHERE akID = $key");
			while($row=$r->fetchrow()){
				$cats[]=$row['value'];
			}
			if(is_array($cats)){
				foreach($cats as $cat){
					if ($cat==$category){ $select = 'selected';}else{ $select = '';}
					echo '<option value="'.$cat.'" '.$select.'>'.$cat.'</option>';
				}
				if (!isset($category) || $category=='All Categories'){ 
					echo '<option value="All Categories" selected>All Categories</option>';
				}else{
					echo '<option value="All Categories">All Categories</option>';
				}
			}
			if (!isset($category) || $category=='All Categories'){ 
				echo '<option value="All Categories" selected>All Categories</option>';
			}else{
				echo '<option value="All Categories">All Categories</option>';
			}
			?>
			</select>
		</div>
	</div>
	<br/>
		<?php  
		  Loader::model('attribute/categories/collection');
		  $cadf = CollectionAttributeKey::getByHandle('is_featured');
		?>
		<input <?php   if (!is_object($cadf)) { ?> disabled <?php   } ?> type="checkbox" name="displayFeaturedOnly" value="1" <?php   if ($displayFeaturedOnly == 1) { ?> checked <?php   } ?> style="vertical-align: middle" />
	  <?php  echo t('Featured pages only.')?>
		<?php   if (!is_object($cadf)) { ?>
			 <?php  echo t('(<strong>Note</strong>: You must create the "is_featured" page attribute first.)');?></span>
		<?php   } ?>
		<br/>
	
		<input type="checkbox" name="displayAliases" value="1" <?php  if ($displayAliases == 1) { ?> checked <?php  } ?> />
		<?php  echo t('Display page aliases.')?>
		<br/>
		
		<input type="checkbox" name="displayArchive" value="1" <?php  if ($displayArchive == 1) { ?> checked <?php  } ?> />
		<?php  echo t('Filter as archive.')?>
		<br/>
	</div>
	<div class="ccm-block-field-group">
		<strong><?php  echo t('Pagination')?></strong>
		<input type="checkbox" name="paginate" value="1" <?php  if ($paginate == 1) { ?> checked <?php  } ?> />
		<?php  echo t('Display pagination interface if more items are available than are displayed.')?>
	</div>
	<div class="ccm-block-field-group">
	  <strong><?php  echo t('Location in Website')?></strong>
	  <?php  echo t('Display pages that are located')?>:<br/>
	  <br/>
	  <div>
			<input type="radio" name="cParentID" id="cEverywhereField" value="0" <?php  if ($cParentID == 0) { ?> checked<?php  } ?> />
			<?php  echo t('everywhere')?>
			
			&nbsp;&nbsp; 
			<input type="radio" name="cParentID" id="cThisPageField" value="<?php  echo $c->getCollectionID()?>" <?php  if ($cParentID == $c->getCollectionID() || $cThis) { ?> checked<?php  } ?>>
			<?php  echo t('beneath this page')?>
			
			&nbsp;&nbsp;
			<input type="radio" name="cParentID" id="cOtherField" value="OTHER" <?php  if ($isOtherPage) { ?> checked<?php  } ?>>
			<?php  echo t('beneath another page')?> </div>
			
			<div class="ccm-page-list-page-other" <?php  if (!$isOtherPage) { ?> style="display: none" <?php  } ?>>
			
			<?php  $form = Loader::helper('form/page_selector');
			if ($isOtherPage) {
				print $form->selectPage('cParentIDValue', $cParentID);
			} else {
				print $form->selectPage('cParentIDValue');
			}
			?>
			
			</div>
	</div>
	<div class="ccm-block-field-group">
	  <strong><?php  echo t('Sort Pages')?></strong>
	  <?php  echo t('Pages should appear')?>
	  <select name="orderBy">
		<option value="display_asc" <?php  if ($orderBy == 'display_asc') { ?> selected <?php  } ?>><?php  echo t('in their sitemap order')?></option>
		<option value="chrono_desc" <?php  if ($orderBy == 'chrono_desc') { ?> selected <?php  } ?>><?php  echo t('with the most recent first')?></option>
		<option value="chrono_asc" <?php  if ($orderBy == 'chrono_asc') { ?> selected <?php  } ?>><?php  echo t('with the earlist first')?></option>
		<option value="alpha_asc" <?php  if ($orderBy == 'alpha_asc') { ?> selected <?php  } ?>><?php  echo t('in alphabetical order')?></option>
		<option value="alpha_desc" <?php  if ($orderBy == 'alpha_desc') { ?> selected <?php  } ?>><?php  echo t('in reverse alphabetical order')?></option>
	  </select>
	</div>
	
	<div class="ccm-block-field-group">
	  <strong><?php  echo t('Provide RSS Feed')?></strong>
	   <input id="ccm-pagelist-rssSelectorOn" type="radio" name="rss" class="rssSelector" value="1" <?php  echo ($rss?"checked=\"checked\"":"")?>/> <?php  echo t('Yes')?>   
	   &nbsp;&nbsp;
	   <input type="radio" name="rss" class="rssSelector" value="0" <?php  echo ($rss?"":"checked=\"checked\"")?>/> <?php  echo t('No')?>   
	   <br /><br />
	   <div id="ccm-pagelist-rssDetails" <?php  echo ($rss?"":"style=\"display:none;\"")?>>
		   <strong><?php  echo t('RSS Feed Title')?></strong><br />
		   <input id="ccm-pagelist-rssTitle" type="text" name="rssTitle" style="width:250px" value="<?php  echo $rssTitle?>" /><br /><br />
		   <strong><?php  echo t('RSS Feed Description')?></strong><br />
		   <textarea name="rssDescription" style="width:250px" ><?php  echo $rssDescription?></textarea>
	   </div>
	</div>
	
	<style type="text/css">
	#ccm-pagelist-truncateTxt.faintText{ color:#999; }
	<?php  if(truncateChars==0 && !$truncateSummaries) $truncateChars=128; ?>
	</style>
	<div class="ccm-block-field-group">
	   <strong><?php  echo t('Truncate Summaries')?></strong>	  
	   <input id="ccm-pagelist-truncateSummariesOn" name="truncateSummaries" type="checkbox" value="1" <?php  echo ($truncateSummaries?"checked=\"checked\"":"")?> /> 
	   <span id="ccm-pagelist-truncateTxt" <?php  echo ($truncateSummaries?"":"class=\"faintText\"")?>>
	   		<?php  echo t('Truncate descriptions after')?> 
			<input id="ccm-pagelist-truncateChars" <?php  echo ($truncateSummaries?"":"disabled=\"disabled\"")?> type="text" name="truncateChars" size="3" value="<?php  echo intval($truncateChars)?>" /> 
			<?php  echo t('characters')?>
	   </span>
	</div>
	<div class="ccm-block-field-group">
	   <strong><?php  echo t('Use Content instead of Description?')?></strong>	
	   <input id="ccm-pagelist-content" name="use_content" type="checkbox" value="1" <?php  echo ($use_content?"checked=\"checked\"":"")?> /> 
	   <span id="ccm-pagelist-contentTxt">
			<?php  echo t('Yes, use the page "content" block')?>
	   </span>
	</div>
	
</div>