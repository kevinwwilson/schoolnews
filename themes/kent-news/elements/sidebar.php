<!-- sidebar -->
				<aside id="sidebar">
					<span id="today"> <?php echo date('F j\, Y'); ?> </span>
				
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