<!-- sidebar -->
<aside id="sidebar" class="nocontent">
						<?php $this->inc('elements/home-news.php'); ?>
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
						<?php $this->inc('elements/series-list.php'); ?>
                    <div class="sidebar-box">
	                    <div class="tab green" style="width: 76%;">
	                        Sustaining Sponsors
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

					<?php
					$stack = Stack::getByName('Sidebar 3');
					if ($stack) {
						$stack->display();
					}
					?>
				</aside>
