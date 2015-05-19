<?php  
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php'); ?>
			<!-- main -->
			<div id="main">
				<!-- content -->
				<section id="content" class="guest">
				
				
					<header class="heading heading-box news-block">
						<h1><span><?php echo $c->getCollectionName()?></span></h1>
					</header>
										
					<div class="guest-bio">
						<?php
							$a = new Area('guest-bio');
							$a->display($c);
							?>
					</div>
					<div class="news-block">
						<?php
							$a = new Area('Main');
							$a->display($c);
							?>
					</div>
					<div class="article-fade"> </div>
					<div class="more-guest">
						<?php
							$a = new Area('more-guest');
							$a->display($c);
							?>
					</div>
					<ul id="guest-list" class="news-list">
						<?php
							$a = new Area('news-list');
							$a->display($c);
							?>
					</ul>
					<?php $this->inc('elements/footer-ad.php'); ?>
				</section> <!-- end content -->
				<?php $this->inc('elements/sidebar.php'); ?>
			</div> <!-- end main -->
<?php $this->inc('elements/footer.php'); ?>