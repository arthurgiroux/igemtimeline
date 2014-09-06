<?php
	function getJSON($dbh) {


		$select = $dbh->query('SELECT * FROM `igem_posts` ORDER BY `id` DESC');

		$events = array();

		while ($item = $select->fetch()) {
			$newItem = array();
			$newItem['startDate'] = str_replace('-', ',', $item['date']);
			$endDate = strtotime($item['date'].' +1 days');
			$newItem['endDate'] = date('Y,m,d', $endDate);
			$newItem['headline'] = $item['title'];
			$newItem['text'] = $item['text'];
			$newItem['tag'] = $item['tag'];
			$newItem['asset'] = array(
				'media' => $item['asset'],
				'caption' => $item['caption'],
			);
			$events[] = $newItem;
		}
		$jsonArray = array(
			'timeline' => 
			array(
				'headline' => 'Our notebook',
				'type' => 'default',
				'text' => '<p>This is what we did during the project</p>',
				'date' => $events,
			),

		);

		return json_encode($jsonArray);
	}

	function updateIGEM($json, $username, $password) {

		$ch = curl_init();		
		// LOGIN
		curl_setopt($ch, CURLOPT_URL, "http://igem.org/Login");
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_COOKIESESSION, true);
		curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

		curl_setopt($ch, CURLOPT_POSTFIELDS, 
				          http_build_query(array(
							'd' => 0,
							'new_user_center' => '',
							'new_user_right' => '',
							'hidden_new_user' => '',
							'return_to' => 'http://igem.org/',
				          	'username' => $username,
				          	'password' => $password,
				          	'Login' => 'Log in',
				          	)));

		curl_exec ($ch);

		// GET TOKEN

		curl_setopt($ch, CURLOPT_URL, "http://2014.igem.org/wiki/index.php?title=Template:JS/EPFL_timeline_json&action=edit");
		curl_setopt($ch, CURLOPT_POST, false);

		$server_output = curl_exec($ch);

		preg_match('#\<input type\="hidden" value\="(.*)" name\="wpEditToken" /\>#', $server_output, $token);


		// POST JSON
		curl_setopt($ch, CURLOPT_URL, "http://2014.igem.org/wiki/index.php?title=Template:JS/EPFL_timeline_json&action=submit");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, 
		          array(
		          	'wpSection' => '',
		          	'wpStarttime' => 20140905205647,
		          	'wpEdittime' => 20140905202626,
		          	'wpScrolltop' => 0,
		          	'oldid' => 0,
		          	'wpSummary' => '',
		          	'wpAutoSummary' => 'd41d8cd98f00b204e9800998ecf8427e',
		          	'wpTextbox1' => $json,
		          	'wpSave' => 'Save page',
		          	'wpEditToken' => $token[1],
		          	));

		curl_exec($ch);

		curl_close ($ch);

	}
?>