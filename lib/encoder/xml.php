<?php
	function xml_encode_row(array $row, $alias = null, $ignore = null)
	{
		echo "<row>";
		foreach($row as $col => $field) {
			if($ignore && in_array($col, $ignore))
				continue;

			$c = $col;
			if(isset($alias[$col]))
				$c = $alias[$col];
			
			echo "<$c>";
			echo string_prepare_xml($field);
			echo "</$c>";
		}
		echo "</row>";
	}

	function encode_section($section, array $data, $ignore = null, $alias = null)
	{
		echo "<$section>";
		foreach($data as $row)
			echo xml_encode_row($row, $alias, $ignore);
		echo "</$section>";

	}

	function xml_doc()
	{
		echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?><request>";
	}

	function xml_end()
	{
		echo "</request>";
	}
?>