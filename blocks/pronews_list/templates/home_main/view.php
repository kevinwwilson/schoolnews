<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
$textHelper = Loader::helper("text");
// now that we're in the specialized content file for this block type, 
// we'll include this block type's class, and pass the block to it, and get
// the content

$db = Loader::db();
$row = $db->GetArray('SELECT * FROM btselectProNewsList');
foreach ($row as $data) {
    if ($data['active'] == 1) {
        $active_artid = $data['atID'];
    }
}




$articleids = explode('||', $active_artid);
$atids = array();
foreach ($articleids as $displayid) {
    Loader::model('page_list');
    $pl = new PageList();
    $pl->filter(false, '( cv.cID in(' . $displayid . ') )');
    //$pl->setAttribute('group_status','Active');	  
    //$pl->filter(false,"ak_group_status like '%Active%'");                             

    $pages = $pl->getPage();
    foreach ($pages as $cpage) {

        $atids[] = $cpage->cID;
    }
}


$random_id = $atids[array_rand($atids)];


foreach ($articleids as $displayid) {

    Loader::model('page_list');
    $pl = new PageList();
    $pl->filter(false, '( cv.cID in(' . $displayid . ') )');
    //$pl->setAttribute('group_status','Active');
    //$pl->filter(false,"ak_group_status like '%Active%'");                             


    $pages = $pl->getPage();
    foreach ($pages as $cpage) {

        $cpage->setAttribute('group_status', 'Active');
        $nh = Loader::helper('navigation');
        $url = $nh->getLinkToCollection($cpage);

        $title = $cpage->getCollectionName();
        $author = $cpage->getAttribute('author');
        $photo_caption = $cpage->getAttribute('photo_caption');
        $secondary_headline = $cpage->getAttribute('secondary_headline');
        $dateline = $cpage->getAttribute('dateline');
        $CatImage = $cpage->getAttribute('main_photo');
        $image = '';
        if (is_object($CatImage)) {
            $ih = Loader::helper('image');
            $image_arr['realimg'] = $CatImage->getRelativePath();
            $thumb = $ih->getThumbnail($CatImage, 469, 331);

            $image = '<img alt="" src="' . $thumb->src . '">';
        }

        $id = $cpage->cID;


        if ($cpage->cID == $random_id) {
            ?> 

            <div class="slide-block-noborder gallery-js-ready autorotation-active">
                <ul class="slideshow" style="height: 331px;">
                    <li class="active" style="display: block;">
            <?php echo $image; ?>
                        <strong class="caption" style="display: none;"><?php echo $photo_caption ?></strong>
                    </li>
                </ul>
            </div>
            <article class="post">
                <h1><?php echo $title ?></h1>

                <h2><?php echo $secondary_headline ?></h2>



                <strong class="date">by <?php echo $author ?></strong>        
                <p>
                    <span class="dateline"><?php echo $dateline ?> —&nbsp;</span>
            <?php
            if ($use_content > 0) {
                $block = $cpage->getBlocks('Main');
                foreach ($block as $bi) {
                    if ($bi->getBlockTypeHandle() == 'content') {
                        $content = $bi->getInstance()->getContent();
                    }
                }
            } else {
                $content = $cpage->getCollectionDescription();
            }
            if (!$controller->truncateSummaries) {
                echo $content;
            } else {
                echo $textHelper->shorten($content, $controller->truncateChars);
            }
            ?>
                    <br/>
                </p>
                <a href="<?php echo $nh->getLinkToCollection($cpage) ?>">READ FULL STORY »</a>
            </article>

            <?php
            if (!$previewMode && $controller->rss) {
                $bt = BlockType::getByHandle('pronews_list');
                $uh = Loader::helper('concrete/urls');
                $rssUrl = $controller->getRssUrl($b);
                ?>

                <div class="rssIcon" style="margin-top:10px">            
                <?php echo t('Subscribe ') ?> &nbsp;<a href="<?php echo $rssUrl ?>" target="_blank"><img src="<?php echo BASE_URL . DIR_REL ?>/blocks/pronews_list/rss.png" alt="codestrat concrete5 addon development" title="CodeStrat Concrete5 Addon Development" width="14" height="14" /></a>
                </div>
                <link href="<?php echo $rssUrl ?>" rel="alternate" type="application/rss+xml" title="<?php echo $controller->rssTitle ?>" />
                <?php
                }
            }
        }
    }


    if ($paginate && $num > 0 && is_object($pl)) {
        $pl->displayPaging();
    }
    ?>