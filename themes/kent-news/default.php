<?php  
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php'); ?>
			<!-- main -->
			<div id="main">
				<!-- content -->
				<section id="content">
					<div id="block-content">
						<?php
							$a = new Area('Main');
							$a->display($c);
							?>
					</div>
                                    <?php $this->inc('elements/footer-ad.php'); ?>
				</section> <!-- end content -->
				<?php $this->inc('elements/sidebar.php'); ?>
			</div> <!-- end main -->
<?php $this->inc('elements/footer.php'); ?>