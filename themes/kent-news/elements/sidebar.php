<!-- sidebar -->
				<aside id="sidebar" class="nocontent">
					<!-- links-box -->
                    <div class="sidebar-box">
                    <div class="tab green" style="width: 67%;">
                        Featured Columns
                    </div>
                    <div class="frame green">
    					<?php
    					$stack = Stack::getByName('Sidebar 1');
    					if ($stack) {
    						echo '<section class="links-box">';
    						$stack->display();
    						echo '</section>';
    					}
    					?>
                    </div>
                    </div>

                    <div class="sidebar-box">
                    <div class="tab green" style="width: 67%;">
                        Featured Columns
                    </div>
                    <div class="frame green">
					<?php $this->inc('elements/home-news.php'); ?>
                    </div>
                    </div>

					<!-- info-box -->
                    <div class="sidebar-box">
                    <div class="tab green" style="width: 67%;">
                        Featured Columns
                    </div>
                    <div class="frame green">
                    	<?php
							$stack = Stack::getByName('Sidebar 2');
							if ($stack) {
								echo '<section class="info-box">';
								$stack->display();
								echo '</section>';
							}
						?>
                    </div>
                    </div>

					<!-- any additional per-page content -->
					<?php
						$a = new Area('Side Content');
						$a->display($c);
					?>

                    <div class="sidebar-box">
                    <div class="tab purple" style="width: 67%;">
                        Featured Columns
                    </div>
                    <div class="frame purple">
					<?php $this->inc('elements/series-list.php'); ?>
                    </div>
                    </div>

					<!-- sponsors -->
					<?php
					$stack = Stack::getByName('Sidebar 3');
					if ($stack) {
						$stack->display();
					}
					?>
				</aside>
