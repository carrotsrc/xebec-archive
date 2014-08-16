<?php
	include('lib/collections.php');

	if(!isset($tokens[0])) {
		$collections = collection_db_get_all($db);
		encode_section("collections", $collections, array('id'));
	} else {
		$collection = collection_db_get_details($tokens[0], $db);
		if($collection) {
			$collection = $collection[0];
			include('request/browse/packages.php');
		}
		else
			echo "<error>Collection not found</error>";
	}
?>