<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
$textHelper = Loader::helper("text");
$ih = Loader::helper('image');
global $c;
// now that we're in the specialized content file for this block type, 
// we'll include this block type's class, and pass the block to it, and get
// the content


if (count($cArray) > 0) {
    ?>
    <?php
    for ($i = 0; $i < count($cArray); $i++) {
        $cobj = $cArray[$i];
        
        //this section will display duplicates of other sections on the page.  Use the master
        //article list to de-duplicate
        if ($c->displayedArticles[$cobj->cID] != 'pronews_list_thumbnails') {
            continue;
        }
        $title = $cobj->getCollectionName();
        $thumbnail = $cobj->getAttribute('thumbnail');
        $author = $cobj->getAttribute('author');
        $dateline = $cobj->getAttribute('dateline');
        $feature = $cobj->getAttribute('regional_feature');
        $status = $cobj->getAttribute('group_status');
        
        if (is_object($thumbnail)) {
           $resizeThumb = $ih->getThumbnail($thumbnail, 143, 101);
//            $resizeThumb = $ih->getThumbnail($thumbnail, 150, 105);
            $imageCode = '<img class="district-article-thumbnail" alt="" src="' . $resizeThumb->src . '">';
        }
        ?>
        <li>
            <?php echo $imageCode ?>
            <h3 class="ccm-page-list-title"><a href="<?php echo $nh->getLinkToCollection($cobj) ?>"><?php echo $title ?></a></h3>    
            <strong class="date">by <?php echo $author ?></strong>    
            <p>
                <span class="dateline"><?php echo $dateline ?> —&nbsp;</span>
                <?php
                if ($use_content > 0) {
                    $block = $cobj->getBlocks('Main');
                    foreach ($block as $bi) {
                        if ($bi->getBlockTypeHandle() == 'content') {
                            $content = $bi->getInstance()->getContent();
                        }
                    }
                } else {
                    $content = $cobj->getCollectionDescription();
                }
                if (!$controller->truncateSummaries) {
                    echo $content;
                } else {
                    echo $textHelper->shorten($content, $controller->truncateChars);
                }
                ?>
                <br/>
            </p>
        </li>
    <?php
    }
}
if (!$previewMode && $controller->rss) {
    $bt = BlockType::getByHandle('pronews_list');
    $uh = Loader::helper('concrete/urls');
    $rssUrl = $controller->getRssUrl($b);
    ?>

    <div class="rssIcon" style="margin-top:10px;">
    <?php echo t('Subscribe') ?> &nbsp;<a href="<?php echo $rssUrl ?>" target="_blank"><img src="<?php echo BASE_URL . DIR_REL ?>/blocks/pronews_list/rss.png" alt="codestrat concrete5 addon development" title="CodeStrat Concrete5 Addon Development" width="14" height="14" /></a>
    </div>
    <link href="<?php echo $rssUrl ?>" rel="alternate" type="application/rss+xml" title="<?php echo $controller->rssTitle ?>" />


<?php
}

if ($paginate && $num > 0 && is_object($pl)) {
    $pl->displayPaging();
}
?>