<!-- items-block -->
<aside class="items-block news-region">
	<a href="/news-region/"><h2>SCHOOL NEWS BY REGION</h2></a>
	<div class="items-block-holder">
		<div class="item">
			<a href="/news-region/n">
				<?php

                                echo $northThumbnail;

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
				echo $SWThumbnail;
				?>
			</a>
			<div class="expandable-box">
				<a href="/news-region/s-sw"><span class="opener">SOUTH/SOUTHWEST</span></a>
				<div class="slide">
					<a href="/news-region/s-sw">
						<span><a href="/districts/byron-center">Byron Center Public</a></span>
						<span><a href="/districts/godfrey-lee">Godfrey Lee Public</a></span>
						<span><a href="/districts/godwin-heights">Godwin Heights Public</a></span>
						<span><a href="/districts/grandville">Grandville Public</a></span>
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
				echo $SEThumbnail;
				?>
			</a>
			<div class="expandable-box">
				<a href="/news-region/e-se"><span class="opener">EAST/SOUTHEAST</span></a>
				<div class="slide">
					<a href="/news-region/e-se">
						<span><a href="/districts/caledonia">Caledonia Community</a></span>
						<span><a href="/districts/east-grand-rapids">East Grand Rapids Public</a></span>
                                                <span><a href="/districts/forest-hills">Forest Hills Public</a></span>
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
				echo $centralThumbnail;
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
