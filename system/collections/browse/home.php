<?php
/*
* Copyright 2014, Zunautica Initiatives Ltd.
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*/
	include('lib/packages.php');
	$packages = collection_routine_get_packages($collection['id'], $db);
?>
<h2 style="margin-top: 0px;"><?php echo $collection['collection']; ?></h2>
<?php
	if($packages) {
		echo "<table>";
		echo "<tr>";
			echo "<th>Package</th>";
			echo "<th>Description</th>";
			echo "<th>Lastest</th>";
			echo "<th>Updated</th>";
		echo "</tr>";
		foreach($packages as $p) {
			$versions = package_db_get_versions($p['id'], $db);
			echo "<tr>";
			echo "<td><a href=\"{$p['name']}/\">{$p['name']}</a></td>";
			echo "<td>{$p['desc']}</td>";

			if($versions) {
				$v = $versions[0];
				echo "<td>";
				echo "{$v['major']}.";
				echo "{$v['minor']}.";
				echo "{$v['maintenance']}";
				echo "{$v['stage']}";
				echo "</td>";
			}
			else
				echo "<td></td>";
			echo "<td>{$p['updated']}</td>";
			echo "<tr>";
		}
		echo "</table>";
	} else
		echo "No packages in this collection";
?>
