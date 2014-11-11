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
    <!-- sidebar -->
    <div class="home-news">
        <!-- sidebar -->
        <aside id="sidebar">
            <span id="today"> <?php echo date('F j\, Y'); ?> </span>
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