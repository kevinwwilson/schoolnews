<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
$textHelper = Loader::helper("text");
// now that we're in the specialized content file for this block type, 
// we'll include this block type's class, and pass the block to it, and get
// the content

$db = Loader::db();
$nh = Loader::helper('navigation');
Loader::model('page_list');

$row = $db->GetArray('SELECT * FROM btselectProNewsList');
foreach ($row as $data) {
    if ($data['active'] == 1) {
        $active_artid = $data['atID'];
    }
}
$articleids = explode('||', $active_artid);
shuffle($articleids);
$m = 0;
foreach ($articleids as $displayid) {
    $cobj = Page::getByID($displayid, $version = 'RECENT');
    $title = $cobj->getCollectionName();
    $author = $cobj->getAttribute('author');
    $dateline = $cobj->getAttribute('dateline');

    $ak_long_summary = CollectionAttributeKey::getByHandle('long_summary');
    $long_summary = $cobj->getCollectionAttributeValue($ak_long_summary);                         	                             
    $secondary_headline = $cobj->getAttribute('secondary_headline');
    $dateline = $cobj->getAttribute('dateline');
    $photo_type = $cobj->getAttribute('single_multiple_photo_status');


    if ($photo_type == 1) {
        //this is a single photo with a main photo assigned
        $CatImage = $cobj->getAttribute('main_photo');
        $photo_caption = $cobj->getAttribute('photo_caption');
    } elseif ($photo_type == 2) {
        //this is a slideshow 
        $slideimage = $cobj->getAttribute('files');
        $sliderimages = explode('^', $slideimage);

        //Get the first photo in the slideshow
        $sliders = explode('||', $sliderimages[0]);
        $CatImage = File::getByID($sliders[0]);
        $photo_caption = $sliders[1];
    }
    $image = '';
    if (is_object($CatImage)) {
        $ih = Loader::helper('image');
        $image_arr['realimg'] = $CatImage->getRelativePath();
        $thumb = $ih->getThumbnail($CatImage, 469, 331);

        $image = '<img alt="" src="' . $thumb->src . '">';
    }

    if ($m == 0) {
        ?>
        <div class="content-block-holder">
            <div class="slide-block-noborder gallery-js-ready autorotation-active">
                <ul class="slideshow-test">
                    <li style="display: block; " class="active">
                        <?php echo $image; ?>
                        <strong class="caption" style="display: none; "><?php echo $photo_caption ?></strong>
                    </li>
                </ul>
            </div>
            <article class="post home">
                <a href="<?php echo $nh->getLinkToCollection($cobj) ?>">
                    <h1><?php echo $title ?></h1>
                </a>
                <h2><?php echo $secondary_headline ?></h2>
                <strong class="date">by <?php echo $author ?></strong>
                <p class="summary"><span class="dateline"><?php echo $dateline ?> —&nbsp;</span>
                    <?php echo $long_summary ?><span class="dots">...</span>
                    <br/>
                </p>
            </article>							
        </div>

        <div class="slide-col">
            <div class="mask">
                <div class="carousel">
                    <div class="slide">
                    <?php } else { ?>
                        <div class="box">
                            <?php if ($m == 3) {
                                $thumb = $ih->getThumbnail($CatImage, 212, 150);
                                $image = '<img alt="" src="' . $thumb->src . '">';
                                echo $image;
                             } ?>
                             <a href="<?php echo $nh->getLinkToCollection($cobj) ?>">
                                 <strong class="title"><?php echo $title ?></strong>
                             </a>
                            
                            <strong class="date">by <?php echo $author ?></strong>
                            <p><span class="dateline"><?php echo $dateline ?> —&nbsp;</span>
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
                                ?><span class="dots">...</span>
                                <br/>
                            </p>
                            

                        </div>
                        <?php
                    }

                    //} 
                    //} 
                    $m++;
                }
                ?>

            </div>
        </div>
    </div>
</div> 

