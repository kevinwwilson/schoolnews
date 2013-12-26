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
				</section> <!-- end content -->
				<aside id="sidebar">
				<?php
					$a = new Area('Sidebar');
					$a->display($c);
					?>
				</aside>
			</div> <!-- end main -->
<?php $this->inc('elements/footer.php'); ?>