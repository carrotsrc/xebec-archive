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
	$collections = collection_db_get_all($db);
	if(!$collections) {
		echo "No collections";
		return;
	}

	foreach($collections as $c)
		echo "<a href=\"{$c['collection']}/\">{$c['collection']}</a><br />";
?>
