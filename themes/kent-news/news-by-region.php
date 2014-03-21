<?php  
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php'); ?>
			<!-- main -->
						<div id="main">
				<!-- content -->
				<section id="content">
					<!-- news-block -->
					<div class="news-block">
						<!-- heading -->
						<header class="heading">
							<h1><?php echo $c->getCollectionName()?></h1>
						</header>
						<!-- news-box -->
						<aside class="news-box">
                                                            <div class="expandable-box">
                                                            <span class="opener north"><h2>NORTH</h2></span>
                                                                <div class="slide">
                                                                                <span><a href="/districts/cedar-springs"/>Cedar Springs Public</a></span>
                                                                                <span><a href="/districts/comstock-park"/>Comstock Park Public</a></span>
                                                                                <span><a href="/districts/kenowa-hills">Kenowa Hills Public</a></span>
                                                                                <span><a href="/districts/kent-city">Kent City Community</a></span>
                                                                                <span><a href="/districts/northview">Northview Public</a></span>
                                                                                <span><a href="/districts/rockford">Rockford Public</a></span>
                                                                                <span><a href="/districts/sparta">Sparta Area</a></span>

                                                                </div>
                                                            </div>
                                                        <div class="news-box-holder">
									<?php
									$a = new Area('Regional Main North');
									$a->display($c);
									?>
								<ul class="list-area">
									<?php	
									$a = new Area('Regional List North');
									$a->display($c);
									?>
								</ul>
							</div>
							<div class="more-link">
								<a href="<?php echo BASE_URL.DIR_REL ?>/index.php/news-region/n">VIEW ALL NORTH REGION NEWS</a>
							</div>
						</aside>
						<!-- news-box -->
						<aside class="news-box">
							 <div class="expandable-box">
                                                            <span class="opener south"><h2>SOUTH/SOUTHWEST</h2></span>
                                                                <div class="slide">
                                                                       
                                                                                <span><a href="/districts/byron-center">Byron Center Public</a></span>
                                                                                <span><a href="/districts/godfrey-lee">Godfrey Lee Public</a></span>
                                                                                <span><a href="/districts/godwin-heights">Godwin Heights Public</a></span>
                                                                                <span><a href="/districts/kelloggsville">Kelloggsville Public</a></span>
                                                                                <span><a href="/districts/wyoming">Wyoming Public</a></span>

                                                                </div>
                                                            </div>
							<div class="news-box-holder">
								<?php
									$a = new Area('Regional Main South');
									$a->display($c);
									?>
								<ul class="list-area">
									<?php
									$a = new Area('Regional List South');
									$a->display($c);
									?>
								</ul>
							</div>
							<div class="more-link">
								<a href="<?php echo BASE_URL.DIR_REL ?>/index.php/news-region/s-sw">VIEW ALL SOUTH/SOUTHWEST REGION NEWS</a>
							</div>
						</aside>
						<!-- news-box -->
						<aside class="news-box">
							<div class="expandable-box">
                                                            <span class="opener east"><h2>EAST/SOUTHEAST</h2></span>
                                                                <div class="slide">
                                                                       
                                                                                <span><a href="/districts/caledonia">Caledonia Community</a></span>
                                                                                <span><a href="/districts/east-grand-rapids">East Grand Rapids Public</a></span>
                                                                                <span><a href="/districts/kentwood">Kentwood Public</a></span>
                                                                                <span><a href="/districts/lowell">Lowell Area</a></span>
                                                                                <span><a href="/districts/thornapple-kellogg">Thornapple Kellogg</a></span>

                                                                </div>
                                                            </div>
							<div class="news-box-holder">
								<?php
									$a = new Area('Regional Main East');
									$a->display($c);
									?>
								<ul class="list-area">
									<?php
									$a = new Area('Regional List East');
									$a->display($c);
									?>
								</ul>
							</div>
							<div class="more-link">
								<a href="<?php echo BASE_URL.DIR_REL ?>/index.php/news-region/e-se">VIEW ALL EAST/SOUTHEAST REGION NEWS</a>
							</div>
						</aside>
						<!-- news-box -->
						<aside class="news-box">
							<div class="expandable-box">
                                                            <span class="opener central"><h2>CITY CENTRAL & KENT ISD</h2></span>
                                                                <div class="slide">
                                                                        
                                                                               <span><a href="/districts/grand-rapids">Grand Rapids Public</a></span>
                                                                                <span><a href="/districts/kent-isd">Kent ISD-wide Programs</a></span>
                                                                </div>
                                                            </div>
							<div class="news-box-holder">
							
								<?php
									$a = new Area('Regional Main Central');
									$a->display($c);
									?>
								
								<ul class="list-area">
									<?php
									$a = new Area('Regional List Central');
									$a->display($c);
									?>
								</ul>
							</div>
							<div class="more-link">
								<a href="<?php echo BASE_URL.DIR_REL ?>/index.php/news-region/central-kisd">VIEW ALL CITY CENTRAL REGION & KENT ISD NEWS</a>
							</div>
						</aside>
					</div>
				</section>
				
				<!-- sidebar -->
				<?php $this->inc('elements/sidebar.php'); ?>
			</div> <!-- end main -->
<?php $this->inc('elements/footer.php'); ?>