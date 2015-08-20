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
                <h1 class="district-name"><span><?php echo $c->getCollectionName() ?></span></h1>
                <div class="district-logo">
                    <?php
                    $a = new Area('District Logo');
                    $a->display($c);
                    ?>
                </div>
                
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
                            <!-- District Index Top Box Ad [async] -->
                            <script type="text/javascript">if (!window.AdButler){(function(){var s = document.createElement("script"); s.async = true; s.type = "text/javascript";s.src = 'http://ab166541.adbutler-chargino.com/app.js';var n = document.getElementsByTagName("script")[0]; n.parentNode.insertBefore(s, n);}());}</script>
                            <script type="text/javascript">
                            var AdButler = AdButler || {}; AdButler.ads = AdButler.ads || [];
                            var abkw = window.abkw || '';
                            var plc187875 = window.plc187875 || 0;
                            document.write('<'+'div id="placement_187875_'+plc187875+'"></'+'div>');
                            AdButler.ads.push({handler: function(opt){ AdButler.register(166541, 187875, [300,250], 'placement_187875_'+opt.place, opt); }, opt: { place: plc187875++, keywords: abkw, domain: 'ab166541.adbutler-chargino.com' }});
                            </script>
                        </div>
                        <div class="ad2">
                            <!-- District Index Bottom Box Ad [async] -->
                            <script type="text/javascript">if (!window.AdButler){(function(){var s = document.createElement("script"); s.async = true; s.type = "text/javascript";s.src = 'http://ab166541.adbutler-chargino.com/app.js';var n = document.getElementsByTagName("script")[0]; n.parentNode.insertBefore(s, n);}());}</script>
                            <script type="text/javascript">
                            var AdButler = AdButler || {}; AdButler.ads = AdButler.ads || [];
                            var abkw = window.abkw || '';
                            var plc187876 = window.plc187876 || 0;
                            document.write('<'+'div id="placement_187876_'+plc187876+'"></'+'div>');
                            AdButler.ads.push({handler: function(opt){ AdButler.register(166541, 187876, [300,250], 'placement_187876_'+opt.place, opt); }, opt: { place: plc187876++, keywords: abkw, domain: 'ab166541.adbutler-chargino.com' }});
                            </script>                            
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
        <?php $this->inc('elements/footer-ad.php'); ?>
    </section>
    
    <!-- sidebar -->
    <div class="home-news">
        <!-- sidebar -->
        <aside id="sidebar">
                <?php $this->inc('elements/home-news.php'); ?>
            <!-- links-box -->
            <?php
            $stack = Stack::getByName('Quicklinks');
            if ($stack) {
                echo '<section class="links-box">';
                $stack->display();
                echo '</section>';
            }
            ?>



            <!-- info-box -->
            <?php
            $stack = Stack::getByName('Update Box');
            if ($stack) {
                echo '<section class="info-box">';
                $stack->display();
                echo '</section>';
            }
            ?>

            <!-- sponsors -->
            <?php
            $stack = Stack::getByName('Sponsors');
            if ($stack) {
                $stack->display();
            }
            ?>

            <!-- any additional per-page content -->
            <?php
            $a = new Area('Side Content');
            $a->display($c);
            ?>


        </aside>
    </div>
</div> <!-- end main -->
<?php $this->inc('elements/footer.php'); ?>