<?php
	function getJSON($dbh) {


		$select = $dbh->query('SELECT * FROM `igem_posts` ORDER BY `id` DESC');

		$events = array();

		while ($item = $select->fetch()) {
			$newItem = array();
			$newItem['startDate'] = str_replace('-', ',', $item['date']);
			$endDate = strtotime($item['date'].' +1 days');
			$newItem['endDate'] = date('Y,m,d', $endDate);
			$newItem['headline'] = stripslashes($item['title']);
			$newItem['text'] = stripslashes($item['text']);
			$newItem['tag'] = $item['tag'];
			$newItem['tag_list'] = $item['tag_list'];
			$newItem['asset'] = array(
				'media' => $item['asset'],
				'caption' => stripslashes($item['caption']),
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

	function updateIGEM($title, $text, $username, $password) {

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

		curl_setopt($ch, CURLOPT_URL, "http://2014.igem.org/wiki/index.php?title=".$title."&action=edit");
		curl_setopt($ch, CURLOPT_POST, false);

		$server_output = curl_exec($ch);

		preg_match('#\<input type\="hidden" value\="(.*)" name\="wpEditToken" /\>#', $server_output, $token);


		// POST JSON
		curl_setopt($ch, CURLOPT_URL, "http://2014.igem.org/wiki/index.php?title=".$title."&action=submit");
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
		          	'wpTextbox1' => $text,
		          	'wpSave' => 'Save page',
		          	'wpEditToken' => $token[1],
		          	));

		curl_exec($ch);

		curl_close ($ch);

	}

	function updateNotebook($dbh, $team, $username, $password) {
		$select = $dbh->prepare('SELECT * FROM `igem_notebook` WHERE `team`=? ORDER BY `date` ASC');
		$select->execute(array($team));

		$html = file_get_contents('templateigem_top.html');

		while ($item = $select->fetch()) {
			$html .= '<div class="notebook-item">';

			$html .= '<h3>'.htmlspecialchars(stripslashes($item['title'])).'</h3>';
			$html .= '<span>'.htmlspecialchars(stripslashes($item['date'])).'</span>';
			$html .= '<div class="notebook-content">'.stripslashes($item['text']).'</div>';
			$html .= '<hr /></div>';
		}

		$html .= file_get_contents('templateigem_bottom.html');

	 	updateIGEM('Team:EPF_Lausanne/Notebook/'.$team, $html, $username, $password);
	}
?>