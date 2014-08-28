<?php  
	defined('C5_EXECUTE') or die(_("Access Denied."));
	$textHelper = Loader::helper("text"); 
        $ih= Loader::helper('image');
	// now that we're in the specialized content file for this block type, 
	// we'll include this block type's class, and pass the block to it, and get
	// the content
	
if (count($cArray) > 0) { ?>
    <div class="text-holder">
    <?php  
	for ($i = 0; $i < count($cArray); $i++ ) {
            $cobj = $cArray[$i]; 
            $title = $cobj->getCollectionName();
            $author = $cobj->getAttribute('author');		
            $CatImage = $cobj->getAttribute('thumbnail');
            $feature = $cobj->getAttribute('regional_feature');
            $dateline = $cobj->getAttribute('dateline');
            $long_summary = $cobj->getAttribute('long_summary'); 
            $image = '';
            if(is_object($CatImage)){
                $image_arr['realimg'] = $CatImage->getRelativePath();
                $thumb = $ih->getThumbnail($CatImage, 196, 138);	        
                $image = '<img alt="" src="'.$thumb->src.'">';					
            }  
?>
    <a href="<?php  echo $nh->getLinkToCollection($cobj)?>"><?php echo $image; ?></a>
    <h3 class="ccm-page-list-title"><a href="<?php  echo $nh->getLinkToCollection($cobj)?>"><?php  echo $title?></a></h3>    
    <strong class="date">by <?php echo $author ?></strong>    
    <p>
        <span class="dateline"><?php echo $dateline ?> —&nbsp;</span>
        <?php  
            if($use_content > 0){
                $block = $cobj->getBlocks('Main');
                foreach($block as $bi) {
                        if($bi->getBlockTypeHandle()=='content'){
                                $content = $bi->getInstance()->getContent();
                        }
                }
            } else {
                if (strlen($long_summary) > 0)  {
                        $content = $long_summary;
                    } else {
                        $content = $cobj->getCollectionDescription(); 
                    }
            }
            if(!$controller->truncateSummaries){
                echo $content;
            }else{
                echo $textHelper->shorten($content,$controller->truncateChars);
            }
        ?>
        <br/>
       <a href="<?php  echo $nh->getLinkToCollection($cobj)?>">More »</a> 
    </p>

<?php  }	
?>

<?php  } 

    if(!$previewMode && $controller->rss) { 
        $bt = BlockType::getByHandle('pronews_list');
        $uh = Loader::helper('concrete/urls');
        $rssUrl = $controller->getRssUrl($b);
        ?>
			
    <div class="rssIcon" style="margin-top:10px;">
            <?php  echo t('Subscribe')?> &nbsp;<a href="<?php   echo $rssUrl?>" target="_blank">
                <img src="<?php echo BASE_URL.DIR_REL ?>/blocks/pronews_list/rss.png" alt="codestrat concrete5 addon development" title="CodeStrat Concrete5 Addon Development" width="14" height="14" /></a>
    </div>
    <link href="<?php  echo $rssUrl?>" rel="alternate" type="application/rss+xml" title="<?php  echo $controller->rssTitle?>" />
    <?php  
    } 

    if ($paginate && $num > 0 && is_object($pl)) {
            $pl->displayPaging();
    }
	
?>
</div>	