<?php
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php');
?>
<!-- main -->
<div id="main">
    <!-- content -->
    <section id="content">
        <!-- news-block -->
        <div class="news-block district-list">
            <!-- heading -->
            <header class="heading">
                <h1><span><?php echo $c->getCollectionName() ?></span></h1>
            </header>
            <!-- news-box -->
            <aside class="news-box">
                <div class="news-box-holder">
                    <div class="district-intro">
                        <?php
                        $a = new Area('District Intro');
                        $a->display($c);
                        ?>
                    </div>
                    <div class ="feature-area">
                        <div class="feature">
                            <?php
                            $a = new Area('District Feature');
                            $a->display($c);
                            ?> 
                        </div>
                        <div class="ad1">
                            <img src="<?php print $this->getThemePath(); ?>/images/ad_placeholder.png"/>
                        </div>
                        <div class="ad2">
                            <img src="<?php print $this->getThemePath(); ?>/images/ad_placeholder.png"/>
                        </div> 
                    </div>


                    <ul class="list-area">
                        <?php
                        $a = new Area('District List');
                        $a->display($c);
                        ?>
                    </ul>
                </div>
            </aside>

        </div>
    </section>
    
    <script>
        
$( document ).ready(function() {
    initCarousel();
});
    // scroll gallery init
function initCarousel() {
	jQuery('#scroller').scrollGallery({
	});
}
    </script>
    <!-- sidebar -->
    <?php $this->inc('elements/sidebar.php'); ?>
</div> <!-- end main -->
<?php $this->inc('elements/footer.php'); ?>