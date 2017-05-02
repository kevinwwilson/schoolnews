<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

//Access control by article state.  If the user is not logged in, then only show Published Articles
//Also if status is badly set (ie more than one on an article) then deny access
global $c;
global $u;

$i = 0;
$status = $c->getCollectionAttributeValue('group_status');
foreach ($status as $articleState) {
    $i++;
    $visibility = $articleState->value;
}
if ($i > 1) {
    die("Access Denied.  Bad Article Status");
} elseif ($visibility != 'Published' && !$u->isLoggedIn()) {
    die("Access Denied");
}


Loader::model("attribute/categories/collection");
$districtPagesHelper = Loader::helper('district_pages');

$newsTitle = $c->getCollectionName();
$newsDate = $c->getCollectionDatePublic('F jS Y');

$ak_g = CollectionAttributeKey::getByHandle('news_tag');
$tags = $c->getCollectionAttributeValue($ak_g);

$ak_u = CollectionAttributeKey::getByHandle('news_url');
$url = $c->getCollectionAttributeValue($ak_u);

//load everything related to the author of the article
$author = $c->getAttribute('author');
Loader::model('author_list');
$authorList = new AuthorList();
$authorList->loadValues();

Loader::model('series_list');
$seriesId = $c->getCollectionAttributeValue('series_index_id');
if ($seriesId > 0) {
    $seriesIndex = seriesList::getById($seriesId);
} else {
    $seriesIndex = null;
}

//add the series name

$secondaryheadline = $c->getAttribute('secondary_headline');
$photo_caption = $c->getAttribute('photo_caption');
$district = $c->getAttribute('district');
$dateline = $c->getAttribute('dateline');
$slideimage = $c->getAttribute('files');
$sliderimages = explode('^', $slideimage);


// convert district to array for counting and display
foreach ($district as $d) {
    $districtArr[] = $d->value;
}
?>
<root>

<header class="heading">
    <div class ="upper-social-media">
        <span class="share-caption">Share</span>
        <span id="share" class='st_sharethis_large' displayText='ShareThis' st_url="<?php echo BASE_URL. $this->url($this->getCollectionObject()->cPath); ?>"></span>
        <span id="print" onclick="window.print();"></span>
        <div id="search"><a href="/customsearch" title="Search the School News Network Site"></a></div>
        <div class="share-story"><a href="mailto:snn@kentisd.org">Send us your story ideas</a></div>
    </div>
    <div class="p-news">
        <?php
        if (is_array($districtArr) && count($districtArr) == 1) {
            $districtUrl = $districtPagesHelper->getDistrictLink($districtArr[0]);
        ?>
        <?php if (!@is_null($districtPagesHelper->getDistrictImage($districtArr[0])))  { ?>

        <div class="district-logo">
            <img src="<?php echo $districtPagesHelper->getDistrictImage($districtArr[0])?>">
        </div>
        <?php  } ?>
        <a href="<?php echo $districtUrl ?>">

            <h1>
                <span> <?php  echo $districtPagesHelper->getDistrictTitle($districtArr[0]); ?> </span>
            </h1>

        </a>
        <span class="more-district">
            <a href="<?php echo $districtUrl ?>">
                <?php if ($district == 'All Districts') { ?>
                    More News</a>
                <?php } else { ?>
                    More  District News</a>
                <?php } ?>
        </span>

        <?php
        } else {
            echo '<h1>&nbsp</h1>';
        }
        ?>
    </div>
</header>

<div class="article">
    <?php if ($c->getAttribute('main_photo') != '' && $c->getAttribute('single_multiple_photo_status') == 1) { ?>
        <div class="image-holder">
            <?php
            $CatImage = $c->getAttribute('main_photo');
            if ($CatImage) {
                $ih = Loader::helper('image');
                $image_arr['realimg'] = $CatImage->getRelativePath();
                $thumb = $ih->getThumbnail($CatImage, 400, 283);
                $image = '';
                $image = '<img alt="" src="' . $thumb->src . '">';
                echo $image;
            }
            ?>
            <strong class="title"><?php echo $photo_caption ?></strong>
        </div>
<?php } elseif ($slideimage != '' && $c->getAttribute('single_multiple_photo_status') == 2) { ?>

        <div class="slideshow-holder">
            <ul class="news-slideshow">
                <?php foreach ($sliderimages as $simages) {

                    $sliders = explode('||', $simages)
                    ?>

                    <li>
                        <?php
                        $f = File::getByID($sliders[0]);
                        $ih = Loader::helper('image');
                        $image_arr['realimg'] = $f->getRelativePath();
                        $thumb = $ih->getThumbnail($f, 400, 283);
                        $image = '';
                        $image = '<img alt="" src="' . $thumb->src . '">';
                        echo $image;
                        ?>

                        <strong class="title"><?php echo htmlspecialchars_decode($sliders[1]) ?></strong>
                    </li>
    <?php } ?>
            </ul>
            <nav>
                <ul class="switcher">
                    <?php foreach ($sliderimages as $simages) { ?>
                        <li class=""><a href="#"></a></li>
    <?php } ?>
                </ul>
            </nav>
        </div>
<?php } ?>

    <?php
    if (is_object($seriesIndex)) {
    ?>
    <a class="series" href="<?php echo $seriesIndex->link ?>">Part of the SNN series "<?php echo $seriesIndex->title ?>"</a>
    <?php } ?>
    <h2><?php echo $newsTitle ?></h2>
    <h4><?php echo $secondaryheadline ?></h4>
    <strong class="date">by <?php echo $author ?>
        <a href="mailto:<?php echo $authorList->getEmailByName($author)?>">
        &nbsp;<i class="fa fa-envelope-o" aria-hidden="true"></i>
        </a>
    </strong>
    <div id="article_content">
        <?php
        if (strlen($dateline) > 0) {
            echo '<span class="dateline">' . $dateline . ', MI — &nbsp</span>';
        } else {
            echo '<span class="dateline"> — &nbsp</span>';
        }

        $content = $controller->getContent();
        print $content;
        ?>
        <strong class="date">Submitted on: <span id="pub_date"><?php echo $newsDate; ?></span></strong>
        <div class="article-box">



                        <!-- Article Box [async] -->
            <script type="text/javascript">if (!window.AdButler){(function(){var s = document.createElement("script"); s.async = true; s.type = "text/javascript";s.src = 'https://servedbyadbutler.com/app.js';var n = document.getElementsByTagName("script")[0]; n.parentNode.insertBefore(s, n);}());}</script>
            <script type="text/javascript">
            var AdButler = AdButler || {}; AdButler.ads = AdButler.ads || [];
            var abkw = window.abkw || '';
            var plc181293 = window.plc181293 || 0;
            var html = $('#article_content').html();
            html = html.replace('♥', '<div class="article-box" id="placement_181293_'+plc181293+'"></div>');
            $('#article_content').html(html);
//            document.write('<div id="placement_181293_'+plc181293+'"></div>');
            AdButler.ads.push({handler: function(opt){ AdButler.register(166541, 181293, [300,250], 'placement_181293_'+opt.place, opt); }, opt: { place: plc181293++, keywords: abkw, domain: 'servedbyadbutler.com' }});
            </script>
        </div>
    </div>
    </root>
    <div id="social-call" class=" ccm-block-styles">
        <h3>Spread the word!</h3></div>
    <div id="HTMLBlock648" class="HTMLBlock">
        <div id="social-media">
            <span class='st_facebook_large' displayText='Facebook' st_url="<?php echo BASE_URL. $this->url($this->getCollectionObject()->cPath); ?>"></span>
            <span class='st_twitter_large' displayText='Tweet'></span>
            <span class='st_linkedin_large' displayText='LinkedIn'></span>
            <span class='st_pinterest_large' displayText='Pinterest'></span>
            <span class='st_googleplus_large' displayText='Google +'></span>
            <span class='st_tumblr_large' displayText='Tumblr'></span>
            <span class='st_sharethis_large' displayText='ShareThis'></span>
            <span class='st_email_large' displayText='Email'></span>
            <span class='st_fblike_large' displayText='Facebook Like' st_url="<?php echo SOCIAL_URL. $this->url($this->getCollectionObject()->cPath); ?>"></span>

            <span id="newsletter"><a href="/newsletter">Subscribe to Weekly Headlines</a></span>
        </div>
    </div>

    <?php if (count($districtArr) == 1) { ?>
    <div id="more-district" class="nocontent">
    <?php
    //display a link to the district page only if there is one district assigned to  the article
        $newsInfo = Loader::helper('get_news_info');
        $articles = $newsInfo->getRecentDistrictNewsFromFile($districtArr[0], 2, $newsTitle);
        foreach ($articles as $article) {
    ?>
        <div class="listing">
            <?php if (strlen($article->Thumbnail) > 0) { ?>
                <img src="<?php echo $article->Thumbnail?>">
            <?php } ?>
            <h3 class="title">
                <a href="<?php echo $article->URL ?>">
                    <?php echo $article->Headline ?>
                </a>
            </h3>
            <div class="news-summary"><?php echo '<span class="dateline">'
            . $dateline . ', MI — &nbsp</span>'
                    . $article->Summary?>
            </div>
        </div>
    <?php } //end foreach ?>

        <?php
        //don't display this part for an All Distrcits article, as that doesn't have a landing page.
        if ($districtArr[0] != 'All Districts') {
            $districtPageLink = '<div class="district-page"><a href="' . $districtUrl . '">More ' . $districtArr[0] . ' News</a></div>';
            echo $districtPageLink;
            echo '</div>'; //close the more-district div
        }

    } //end if only one district
     ?>
<div id="social-share" class="modal">
    <div id="HTMLBlock648" class="HTMLBlock">
        <div id="social-media">
            <span class='st_facebook_large' displayText='Facebook'></span>
            <span class='st_twitter_large' displayText='Tweet'></span>
            <span class='st_linkedin_large' displayText='LinkedIn'></span>
            <span class='st_pinterest_large' displayText='Pinterest'></span>
            <span class='st_googleplus_large' displayText='Google +'></span>
            <span class='st_tumblr_large' displayText='Tumblr'></span>

            <span class='st_sharethis_large' displayText='ShareThis'></span>
            <span class='st_email_large' displayText='Email'></span>
            <span class='st_fblike_large' displayText='Facebook Like'></span>
        </div>
        <span id="print-button">
            <a id="print" title="Print this Article" href="">
                <span class="glyphicon glyphicon-print"></span>
                <span class="print-text">Print Article</span>
            </a>
        </span>
    </div>
</div>
