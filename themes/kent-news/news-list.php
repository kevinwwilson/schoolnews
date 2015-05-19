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
						<header class="heading heading-box">
							<h1><span><?php echo $c->getCollectionName()?></span></h1>
						</header>
						<!-- news-list-block -->
						<aside class="news-list-block">
							<ul class="news-list">
									<?php
								$a = new Area('News List');
								$a->display($c);
								?>
							</ul>
						</aside>
					</div>
                                        <?php $this->inc('elements/footer-ad.php'); ?>
				</section>
				<?php $this->inc('elements/sidebar.php'); ?>
			</div> <!-- end main -->
<?php $this->inc('elements/footer.php'); ?>