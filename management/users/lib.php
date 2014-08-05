<?php
	function get_action_result()
	{
		if(!isset($GLOBALS['action_result']))
			return null;

		return $GLOBALS['action_result'];
	}

	function get_action_msg()
	{
		if(!isset($GLOBALS['action_msg']))
			return null;

		return $GLOBALS['action_msg'];
	}

	function set_action_result($result, $msg)
	{
		$GLOBALS['action_result'] = $result;
		$GLOBALS['action_msg'] = $msg;
	}

	function get_interface()
	{
		if(isset($_GET['interface']))
			return $_GET['interface'];

		return 'view';
	}

	function init_manager($db)
	{
		global $repo_config;
		$interface = get_interface();
		include($repo_config['manroot']."/init_interface.php");
		init_interface($db);
	}

	function display_manager($db)
	{
		global $repo_config;
		$interface = get_interface();
		ob_start();
		switch($interface) {
		case 'view':
			include($repo_config['manroot']."/template_view.php");
		break;
		case 'add':
			include($repo_config['manroot']."/template_add.php");
		break;
		default:
			echo "No interface found";
			break;
		}

		$mu = ob_get_contents();
		ob_clean();
		return $mu;
	}
?>
