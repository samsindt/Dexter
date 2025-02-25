<!doctype html>
<html lang="en-us">
    <head>
		<meta charset="utf-8">
		<title><?php $pokemonName?></title>
		<?php
			print "<style>";
                include __DIR__ . "/../Styles/navigationStyles.css";
                include __DIR__ . "/../Styles/profileStyles.css";
			print "</style>";
		?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>

		<body>
			<div class="topBar">
				<div class="title"><b>Dexter</b></div>
				<ul class="nav">
					<?php 
						if ($isAdmin) {
							print '<li id="admin" class="navItem"><a href="/Dexter/admin/show">Admin</a></li>';
						}
					?>
					<li class="navItem"><a href="/Dexter/home">Home</a></li>
					<li class="navItem">
						<?php
							if ($isLoggedIn) {
								print '<a id="favorites" href="/Dexter/favorites/show">Favorites</a>';
							}
							else {
								print '<a id="favorites" href="/Dexter/login/show" id="login">Favorites</a>';
							}
						?>
					</li>
					<li class="navItem">
						<?php
							if ($isLoggedIn) {
								print '<a href="#" id="logout">Logout</a>';
							}
							else {
								print '<a href="/Dexter/login/show" id="login">Login</a>';
							}
						?>
					</li>
				</ul>
			</div>
			
			<div class="wrapper">
				<div class="card">
					<div class="header">
						<h1 id="pkName"><?php print $pokemonName ?></h1>
						<?php 
							if ($isLoggedIn) {
								if ($isFavorited) {
									print "<h1 class='fave' id='faved'>★</h1> ";
								}
								else {
									print "<h1 class='fave' id='unfaved'>☆</h1>";
								}
							}
							else {
								print "<h1 class='fave' id='unfaved'><a href='/Dexter/login/show'>☆</a></h1>";
							}
						?>
						</div>
					
					<img src=<?php print "'data:" . $profileImage["Type"] . ";base64, " . base64_encode($profileImage["Image"]) ."'";?> id="pkImage";></br>
					
					<div id="numAndTypes">
					
						<h4 id="pkNumber"><?php print 'Dexter # ' . $dexNumber ?></h4>
						
						<div id="pkTypes">
							<h4>Type: </h4>
							<?php 
								foreach ($typeImages as $typeImage) {
                                    print "<img src='data:" . $typeImage["Type"] . ";base64, " . base64_encode($typeImage["Image"]) . "'>";
								}
							?>
						
						</div>
					</div>
					
					<div id="pkEvol">
						<?php
							
							if (!empty($evolvesFrom)) {
								 print "<h4 id='evolFr'>Evolves From: <a href='/Dexter/Profile/Show/" . $evolvesFrom["EvolvesFrom"] . "/'>" 
									. $evolvesFrom["Name"] . "</a></h4>";
							}
							
							
							if (!empty($evolvesTo)) {
								print "<h4 id='evolTo'>Evolves To: <a href='/Dexter/Profile/Show/" . $evolvesTo["EvolvesTo"] . "'>"
									. $evolvesTo["Name"] . "</a></h4>";
							}
						?>
					</div>
					
					<div id="pkStats">
					
					<h4><u>Base Stats</u></h4>
					<table>
						<tr>
							<th>HP</th><th><?php print $HP ?></th>
						</tr>
						<tr>
							<th>Attack</th><th><?php print $attack ?></th>
						</tr>
						<tr>
							<th>Defense</th><th><?php print $defense ?></th>
						</tr>
						<tr>
							<th>Special Attack</th><th><?php print $spAttack ?></th>
						</tr>
						<tr>
							<th>Special Defense</th><th><?php print $spDefense ?></th>
						</tr>
						<tr>
							<th>Speed</th><th><?php print $speed ?></th>
						</tr>
					</table>
				</div>
				
				<div id="pkEggs">
					<?php
						if (!empty($eggGroups)) {
							$eggNamesStr = "";
							
							foreach ($eggGroups as $eggGroup) {
								$eggNamesStr = $eggNamesStr . $eggGrou['GroupName'];
								if ($eggGrou != end ($eggGroups)) {
									$eggNamesStr = $eggNamesStr . " and ";
								}
							}
							
							print "<h4>Egg Group: " . $eggNamesStr . "</h4>";
						}
					?>
				</div>
				<div class="adminChanges">
						<?php
							if($isAdmin) {

								//Colors
								print "<form method='post' action='/Dexter/admin/addcolor'>
									<input type='hidden' name='pokemonID' value='" . $pokemonID . "'><select name='colorID'>";

								foreach ($unusedColors as $color) {
									print '<option value=' . $color[0] . '> ' . $color[1] . '</option>';
								}
					
								print "</select><input TYPE='submit' NAME='btn' VALUE='Add Color'></form>";
								
								//Anologs
								print "<form method='post' action='/Dexter/admin/addanalog'>
									<input type='hidden' name='pokemonID' value='" . $pokemonID . "'><select name='analogID'>";
								
								foreach ($unusedAnalogs as $analog) {
									print "<option value=" . $analog[0] . ">" . $analog[1] . "</option>";
								}
								
								print "</select><input TYPE='submit' NAME='btn' VALUE='Add Analog'></form>";
									
							}
						?>
				</div>
			</div>
		</di>
	</body>
	<?php 
		print "<script>";
		include __DIR__ . "/../Scripts/profileFavoriting.js";
		include __DIR__ . "/../Scripts/logout.js";
		if ($isFavorited) {
			print "addUnfavoriteListener(" . $pokemonID . ");";
		}
		else {
			print "addFavoriteListener(" . $pokemonID . ");";
		}

		print "addLogoutAndStayListener ();</script>";
	?>
</html>