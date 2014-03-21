<!-- items-block -->
<aside class="items-block">
	<a href="/news-region/"><h2>SCHOOL NEWS BY REGION</h2></a>
	<div class="items-block-holder">
		<div class="item">
			<a href="/news-region/n">
				<?php
				$a = new Area('Regional North Photo');
				$a->display($c);
				?>
			</a>
		<div class="expandable-box">
			<a href = "/news-region/n"><span class="opener">NORTH</span></a>
			<div class="slide">
				<a href="/news-region/n"></a>
					<span><a href="/districts/cedar-springs"/>Cedar Springs Public</a></span>
					<span><a href="/districts/comstock-park"/>Comstock Park Public</a></span>
					<span><a href="/districts/kenowa-hills">Kenowa Hills Public</a></span>
					<span><a href="/districts/kent-city">Kent City Community</a></span>
					<span><a href="/districts/northview">Northview Public</a></span>
					<span><a href="/districts/rockford">Rockford Public</a></span>
                                        <span><a href="/districts/sparta">Sparta Area</a></span>
				
			</div>
		</div>
			<ul>
				<?php
				$a = new Area('Regional North Headlines');
				$a->display($c);
				?>
			</ul>
		</div>
		<div class="item">
			<a href="/news-region/s-sw">
			<?php
				$a = new Area('Regional South Photo');
				$a->display($c);
				?>
			</a>
			<div class="expandable-box">
				<a href="/news-region/s-sw"><span class="opener">SOUTH/SOUTHWEST</span></a>
				<div class="slide">
					<a href="/news-region/s-sw">
						<span><a href="/districts/byron-center">Byron Center Public</a></span>
						<span><a href="/districts/godfrey-lee">Godfrey Lee Public</a></span>
						<span><a href="/districts/godwin-heights">Godwin Heights Public</a></span>
						<span><a href="/districts/kelloggsville">Kelloggsville Public</a></span>
						<span><a href="/districts/wyoming">Wyoming Public</a></span>
					</a>
				</div>
			</div>
			<ul>
				<?php
				$a = new Area('Regional South Headlines');
				$a->display($c);
				?>
			</ul>
		</div>
		<div class="item">
			<a href="/news-region/e-se">
			<?php
				$a = new Area('Regional East Photo');
				$a->display($c);
				?>
			</a>
			<div class="expandable-box">
				<a href="/news-region/e-se"><span class="opener">EAST/SOUTHEAST</span></a>
				<div class="slide">
					<a href="/news-region/e-se">
						<span><a href="/districts/caledonia">Caledonia Community</a></span>
						<span><a href="/districts/east-grand-rapids">East Grand Rapids Public</a></span>
						<span><a href="/districts/kentwood">Kentwood Public</a></span>
						<span><a href="/districts/lowell">Lowell Area</a></span>
						<span><a href="/districts/thornapple-kellogg">Thornapple Kellogg</a></span>
					</a>
				</div>
			</div>
			<ul>
				<?php
				$a = new Area('Regional East Headlines');
				$a->display($c);
				?>
			</ul>
		</div>
		<div class="item">
			<a href="/news-region/central-kisd">
			<?php
				$a = new Area('Regional Central Photo');
				$a->display($c);
				?>
			</a>
			<div class="expandable-box">
				<a href="/news-region/central-kisd"><span class="opener">CITY CENTRAL & KENT ISD</span></a>
				<div class="slide">
					<a href="/news-region/central-kisd">
						<span><a href="districts/grand-rapids">Grand Rapids Public</a></span>
						<span><a href="/districts/kent-isd">Kent ISD-wide Programs</a></span>
					</a>
				</div>
			</div>
			<ul>
				<?php
				$a = new Area('Regional Central Headlines');
				$a->display($c);
				?>
			</ul>
		</div>
	</div>
</aside>