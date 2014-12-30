			<!-- footer -->
			<footer id="footer">
			
				<div class="footer-holder">
					<!-- search-form -->
					<form action="/index.php/search" method="get" class="search-form">
						<fieldset>
							<input type="text" name="query" />
							<input type="submit" value="Submit" />
						</fieldset>
					</form>
					<!-- social-networks -->
					<ul class="social-networks">
						<?php
						   $stack = Stack::getByName('Social Media');
							if ($stack) $stack->display();
							?>
					</ul>
				</div> <!-- end footer-holder -->
				
				
				
				<div class="footer-frame footer-nav">
					<!-- footer-nav -->
					<nav>
					<?php
					   $stack = Stack::getByName('Footer Navigation');
						if ($stack) $stack->display();
						?>
					</nav>
				</div> <!-- end footer-frame-->
				<div class="footer-text">
					<div class="address">
							<?php
					   $stack = Stack::getByName('Address');
						if ($stack) $stack->display();
						?>
					</div>
					<div class="notice">
						<p>School News Network is funded with advertising dollars and developed in collaboration with Kent ISD and our districts.</p>
					</div>
				</div>
                                <ul class="login">
                                        <li>
                                                <?php $u = new User();
                                                if ((!$u->isRegistered()) || $c->isEditMode()) { ?>
                                                <a href="<?php echo $this->url('/login'); ?>">:&nbsp;:</a>
                                                <?php } else { ?>
                                                <a href="<?php echo $this->url('/login','logout'); ?>">Log Out</a>
                                                <?php }?>
                                        </li>
                                </ul>
				
			</footer>
			
			
			
		</div> <!-- end wrapper-holder -->
	</div> <!-- end wrapper -->
	<?php Loader::element('footer_required'); ?>
	<script>
		FB.Event.subscribe('edge.create', function(href, widget) {
			_gaq.push(['_trackSocial', 'facebook', 'like']);
		});
	</script>
</body>
</html>