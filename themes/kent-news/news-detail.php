<?php  
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header_news.php'); ?>
			<!-- main -->
			<div id="main">
				<!-- content -->
				<section id="content">
					<!-- news-block -->
					<div class="news-block">
						<?php
						$a = new Area('Main');
						$a->display($c);
						?>
						
						<aside class="comments-block">
							    <div id="disqus_thread"></div>

										<script type="text/javascript">
									/* * * CONFIGURATION VARIABLES * * */
									var disqus_shortname = 'schoolnewsnetwork'; 
									var disqus_identifier = <?php echo $_GET['rID'] ?>;
															
									/* * * DON'T EDIT BELOW THIS LINE * * */
									(function() {
										var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
										dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
										
										
										(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
									})();

								</script>
								<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
								<a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
						</aside>
					</div> <!-- end news-block -->
				</section>
				<?php $this->inc('elements/sidebar.php'); ?>
			</div> <!-- end main -->
<?php $this->inc('elements/footer.php'); ?>