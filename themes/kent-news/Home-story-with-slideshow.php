<?php  
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php'); 

/*Loader::model('single_page');   
Loader::model('package');
$iak = CollectionAttributeKey::getByHandle('icon_dashboard');
$ci = Loader::helper('concrete/urls');
$pkg=Package::getByHandle('pronews'); //package name
$an = SinglePage::add('/dashboard/pronews/shedule_news_group', $pkg);
$an->setAttribute($iak,'icon-time');
$pnag = SinglePage::add('/dashboard/pronews/shedule_news_group/add_group', $pkg);*/

?>
	<!-- main -->
	<div id="main">
		<!-- content -->
		<section id="content">
			<div class="content-block">
				<div class="content-block-holder">
				
					<?php
						$a = new Area('Main Story');
						$a->display($c);
						?>
									
				</div>
				<!-- slide-col -->
				<div class="slide-col">
					<div class="mask">
						<div class="carousel">
							<div class="slide">
								<?php
								$a = new Area('Featurettes');
								$a->display($c);
								?>
							</div>
						</div>
					</div>

				</div>
			</div>
			
			<?php $this->inc('elements/region-list.php'); ?>			
		
		</section>
		<!-- sidebar -->
		<?php $this->inc('elements/sidebar.php'); ?>
	</div>
	<!-- footer -->
	<?php $this->inc('elements/footer.php'); ?>