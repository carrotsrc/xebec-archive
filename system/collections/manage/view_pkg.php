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
	include('lib/versions.php');
	// add and modify tasks
	$tasks['collection'][1] = '../../';
	$tasks['overview'][1] = '../';
	$tasks['new-package'][1] = '../new-package/';

	$package = collection_routine_get_package($tokens[2], $collection['id'], $db);
	if(!$package) {
		echo "Package details not found";
		return;
	}
	$package = $package[0];
	$error = false;
	$msg = null;

	$action = null;
	if(isset($_POST['action']))
		$action = $_POST['action'];
	else
	if(isset($_GET['action']))
		$action = $_GET['action'];

	if($action == "new-version") {

		$version = explode(".", $_POST['version']);
		$s = sizeof($version)-1;
		$i = 0;
		$stage = "";
		while(isset($version[$s][$i])) {
			if(!is_numeric($version[$s][$i])) {
				$n = substr($version[$s], 0, $i);
				$stage = substr($version[$s], $i);
				$version[$s] = $n;
			}
			$i++;
		}

		if(!isset($version[1]))
			$version[1] = "0";

		if(!isset($version[2]))
			$version[2] = "0";
		
		$archive = null;

		if(isset($_FILES['archive']))
			$archive = archive_routine_store($version, $stage, $tokens[0], $tokens[2], $_FILES['archive']);
		if(!package_routine_add_version($version, $stage, $tokens[2], $archive, $db)) {
			$error = true;
			$msg = 'Failed to add version';
		} else {
			$msg = 'Created new version';
			package_routine_updated($package['id'], $db);
		}

	} else
	if($action == "new-repo") {
		if(isset($_POST['url']) && $_POST['url'] != "") {
			if(!package_routine_add_scm($_POST['url'], $package['id'], $db)) {
				$error = true;
				$msg = 'Failed to add repository';
			} else {
				$msg = 'Added repository';
				package_routine_updated($package['id'], $db);
			}
		} else {
			$error = true;
			$msg = "No repository URL given";
		}

	} else
	if($action == "remove") {
		if(isset($_GET['repo'])) {
			package_routine_remove_scm($package['id'], $_GET['repo'], $db);
			package_routine_updated($package['id'], $db);
		}
		else
		if(isset($_GET['version'])) {
			package_routine_remove_version($tokens[0], $package['name'], $package['id'], $_GET['version'], $db);
			package_routine_updated($package['id'], $db);
		}
	} else 
	if($action == "deprecate") {
		package_routine_deprecate_version($_GET['version'], $package['id'], $_GET['val'], $db);
	}




	$versions = package_db_get_versions($package['id'], $db, true);
	$scm = package_db_get_scm($package['id'], $db);
?>
<div style="display: inline-block; vertical-align: top; min-width: 30%;">
	<h2 style="margin-top: 0px;"><a href="../"><?php echo $tokens[0] ."</a> / ". $package['name']; ?></h2>
	<?php echo $package['desc']; ?><br />
	<a href="modify/" class="fsmall">Modify Details</a>
	<div class="cat-container">

		<div class="cat-container">
			<div class="version-list">
			<strong>Versions</strong>
			<table>
			<?php
				if($versions) {
					foreach($versions as $v) {
						echo "<tr>";
						echo "<td>{$v['major']}.{$v['minor']}.{$v['maintenance']}{$v['stage']}</td>";
						echo "<td>";
						if($v['archive'])
							echo "<a href=\"/repo/{$tokens[0]}/{$tokens[2]}/{$v['archive']}\">Archive</a>";
						else
							echo "<span class=\"color-inactive\">Archive</span>";

						echo "</td>";

						echo "<td>";
						if($v['deprecated'])
							echo "<span class=\"\">D</span>";
						else
							echo "<span class=\"color-success\">A</span>";
						echo "</td>";

						echo "<td>";
						if($v['deprecated'])
							echo "<a href=\"?action=deprecate&version={$v['id']}&val=0\" class=\"acritical\">Activate</a>";
						else
							echo "<a href=\"?action=deprecate&version={$v['id']}&val=1\" class=\"acritical\">Deprecate</a>";
						echo "</td>";

						echo "<td>";
							echo "<a href=\"?action=remove&version={$v['id']}\" class=\"acritical\">Remove</a>";
						echo "</td>";
						echo "</tr>";
					}
				} else {
					echo "<br />No versions of this package exist";
				}
			?>
			</table>

			</div>
		</div>

		<div class="cat-container">
			<strong>SCM Repositories</strong><br />
			<?php
				if($scm) {
					echo "<table>";
					foreach($scm as $r) {
						echo "<tr>";
						echo "<td>";
						echo $r['url'];
						echo "</td>";

						echo "<td>";
						echo "<a href=\"?action=remove&repo={$r['id']}\" class=\"acritical\">Remove</a>";
						echo "</td>";
						
						echo "</tr>";

					}
					echo "</table>";
				} else
					echo "No repositories added";
			?>
		</div>
	</div>
</div>

<!-- ================== TOOL BARS =================== -->

<div style="display: inline-block;" class="left-spacer-small">
	<div class="cat-container">
		<strong>Add Version</strong>
		<form method="post" enctype="multipart/form-data">
			<div class="vspacer-small">
				Version Number:<br />
				<input type="text" name="version" style="width: 200px;" class="vspacer-micro" />
			</div>

			<div class="vspacer-small">
				Archive:<br />
				<input type="file" name="archive" class="vspacer-micro" />
			</div>

			<div class="vspacer-small">
				<input type="submit" value="Create" style="float: right;" />
				<input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
				<input type="hidden" name="action" value="new-version" />
			</div>
		</form>
	</div>
	<div class="cat-container">
		<strong>Add Repository</strong>
		<form method="post" class="vspacer-small">
			URL<br />
			<input type="text" name="url" style="width: 200px;" /></td>
			<input type="submit" value="Add"  class="vspacer-micro"/>
			<input type="hidden" name="action" value="new-repo" />
		</form>
	</div>
	<div class="cat-container">
		<?php
		if($error)
			echo "<div class=\"color-error\">";
		else
			echo "<div class=\"color-success\">";
			echo $msg;
			echo "</div>";

		?>
	</div>
</div>


