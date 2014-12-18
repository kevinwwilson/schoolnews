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
$author = $c->getAttribute('author');
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
    <div class="p-news">
        <h1>
            <span>
                <?php
                //if there are less than 4 districts, display them by name
                //if there are 4 or more or if a district is not supplied with the 
                //article, then display "Kent ISD" as default backup

                if (is_array($districtArr) && count($districtArr) < 4) {

                    foreach ($districtArr as $d) {
                        echo $d, '<br/>';
                    }
                } else {
                    echo 'Kent ISD';
                }
                ?>
            </span>
        </h1>
    </div>
    <div class ="upper-social-media">
        <span id="upper-facebook">
            <fb:like layout="button" action="like" show_faces="false" share="false"></fb:like>
        </span>
        <span id="upper-twitter">
            <a href="https://twitter.com/share?count=none" class="twitter-share-button" data-dnt="true">Tweet</a>
            <script>!function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                    if (!d.getElementById(id)) {
                        js = d.createElement(s);
                        js.id = id;
                        js.src = p + '://platform.twitter.com/widgets.js';
                        fjs.parentNode.insertBefore(js, fjs);
                    }
                }(document, 'script', 'twitter-wjs');</script>
        </span>

    </div>

    <span id="print-button">
        <a title="Print this Article" href="" onclick="window.print()">
            <span class="glyphicon glyphicon-print"></span>
            <span class="print-text">Print Article</span>
        </a>                            
    </span>                          
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


    <h2><?php echo $newsTitle ?></h2>
    <h4><?php echo $secondaryheadline ?></h4>
    <strong class="date">by <?php echo $author ?></strong>
    <div id="article_content">
        <?php
        echo '<span class="dateline">' . $dateline . ', MI — &nbsp</span>';
        $content = $controller->getContent();
        print $content;
        ?>
        <strong class="date">Submitted on: <span id="pub_date"><?php echo $newsDate; ?></span></strong>
    </div>
    <?php
//display a link to the district page only if there is one district assigned to  the article
    if (count($districtArr) == 1) {
        $districtMap = $districtPagesHelper->getDistrictMap();
        $districtUrl = $districtMap[$districtArr[0]];
        $districtPageLink = '<a href="' . $districtUrl . '">More ' . $districtArr[0] . ' News</a>';
        echo $districtPageLink;
    }
    ?>
    </root>
    <div id="social-call" class=" ccm-block-styles">
        <h3>Spread the word!</h3></div>
    <div id="HTMLBlock648" class="HTMLBlock">
        <div id="social-media">
            <span id="facebook"> 
                <fb:like send="false" layout="button_count" width="450" show_faces="false" fb-xfbml-state="rendered" class="fb_edge_widget_with_comment fb_iframe_widget"><span style="height: 20px; width: 72px;"><iframe id="f201b9c6a" name="f2bde98bbc" scrolling="no" title="Like this content on Facebook." class="fb_ltr" src="http://www.facebook.com/plugins/like.php?api_key=378843365553326&amp;channel_url=http%3A%2F%2Fstatic.ak.facebook.com%2Fconnect%2Fxd_arbiter.php%3Fversion%3D27%23cb%3Df39aa7bfec%26domain%3Dwww.schoolnewsnetwork.org%26origin%3Dhttp%253A%252F%252Fwww.schoolnewsnetwork.org%252Ff20d77428%26relation%3Dparent.parent&amp;colorscheme=light&amp;extended_social_context=false&amp;href=http%3A%2F%2Fwww.schoolnewsnetwork.org%2F%2Fnews%2F%3FrID%3D799&amp;layout=button_count&amp;locale=en_US&amp;node_type=link&amp;sdk=joey&amp;send=false&amp;show_faces=false&amp;width=450" style="border: none; overflow: hidden; height: 20px; width: 72px;"></iframe></span></fb:like>
            </span>
            <span id="twitter"> 
                <a href="https://twitter.com/share" class="twitter-share-button" data-dnt="true">Tweet</a>

            </span>

            <span id="newsletter"><a href="/newsletter">Subscribe to Weekly Headlines</a></span>
        </div>
    </div>
    <div id="disqus_thread"></div>
    <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = 'schoolnewsnetwork'; // required: replace example with your forum shortname

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
    <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
