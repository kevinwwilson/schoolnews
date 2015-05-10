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
                        <div class="ad1" id="div-gpt-ad-1417707641356-0">
                            <script type="text/javascript">
                                googletag.cmd.push(function() { googletag.display('div-gpt-ad-1417707641356-0'); });
                            </script>
                        </div>
                        <div class="ad2" id="div-gpt-ad-1417707677693-0">
                            <script type="text/javascript">
                                googletag.cmd.push(function() { googletag.display('div-gpt-ad-1417707677693-0'); });
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