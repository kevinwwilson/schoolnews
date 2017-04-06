<!-- sidebar -->
				<aside id="sidebar" class="nocontent">
						<?php $this->inc('elements/home-news.php'); ?>
					<!-- links-box -->
					<?php
					$stack = Stack::getByName('Sidebar 1');
					if ($stack) {
						echo '<section class="links-box">';
						$stack->display();
						echo '</section>';
					}
					?>

					<?php $this->inc('elements/home-news.php'); ?>

					<!-- info-box -->
						<?php
							$stack = Stack::getByName('Sidebar 2');
							if ($stack) {
								echo '<section class="info-box">';
								$stack->display();
								echo '</section>';
							}
						?>


					<!-- any additional per-page content -->
					<?php
						$a = new Area('Side Content');
						$a->display($c);
					?>

					<?php $this->inc('elements/series-list.php'); ?>

					<!-- sponsors -->
					<?php
					$stack = Stack::getByName('Sidebar 3');
					if ($stack) {
						$stack->display();
					}
					?>
				</aside>
