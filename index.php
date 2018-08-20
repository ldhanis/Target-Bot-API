<?php
	
	//Including the file loader for each classes used in this example
	include_once 'Files/Loader.php';
	session_start();

	/*
		A Well formated request is a HTTP POST request
		This point of the API waits for a POST array respecting this pattern: 
		'key'			=>	a value given in the "Manage APIs" section of the dashboard
		'message'		=>	the text message of the request (content of the textarea or button text)
		'payload'		=>	the payload of the message (used for sending the next block id from buttons)
		'end_user_id'	=>	unique identifier of the user that interracts with the chatbot (used for storing purpose, make sure it is unique to avoid having two persons with differents answers). Used to follow the flow from the dashboard.
	*/

	if (isset ($_GET['cid']) || isset ($_SESSION['cid']))
	{
		if (isset($_GET['cid']))
			$_SESSION['cid'] = $_GET['cid'];

		$welcome = new API_TextMsg();
		$welcome->set_value('Hey! Click the "Hello" button below to start the bot');
		$chat = new API_Chat();

		if (isset($_GET['answ']))
		{
			$response = array(
				'key'			=> $_SESSION['cid'],
				'message'		=> $_GET['message'],
				'payload'		=> $_GET['answ'],
				'end_user_id'	=> 'user@mail.com'
			);

			//SENDING THE WELL FORMATED REQUEST
			$curl = curl_init();
			
			curl_setopt_array($curl, array(
				CURLOPT_URL 			=> 'https://targetbot.be/dashboard/API/Callback/WebApi.php',
				CURLOPT_POST 			=> 1,
				CURLOPT_POSTFIELDS 		=> $response
			));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$resp = curl_exec($curl);
			curl_close($curl);

			//Ok now handle the response
			//echo $resp;
			$output = json_decode($resp, true);
			if ($output['errors'])
			{
				foreach ($output['errors'] as $error)
				{
					echo 'Error: '.$error.'<br/>';
				}
			}
			else
			{
				//var_dump($output['response']);
				foreach ($output['response']['messages'] as $response)
				{
					switch ($response['type']){
						case 1 :
							$m = new API_TextMsg();
							break;
						case 2 :
							//$m = new UrlMessage($this->_bdd);
							break;
						case 3 :
							//$m = new ImageMessage($this->_bdd);
							break;
						case 4 :
							//$m = new QRMessage($this->_bdd);
							break;
						case 5 :
							//$m = new LocationMessage($this->_bdd);
							break;
					}
					$m->value_from_output($response);
					$chat->add_message(0, $m);
				}

				switch ($output['response']['action']['type']){
					case 3 :
						$a = new API_Button();
						$a->set_by_array ($output['response']['action']);
						break;
					case 4 :
						//$m = new QRMessage($this->_bdd);
						break;
				}
				$chat->add_action($a);
			}
		}
		else
		{
			$chat->add_message(0, $welcome);
		}

		if ($chat->has_empty_action())
		{
			$a =new API_Button();
			$a->add_button('Hello there!', 'START_WEB_API');
			$chat->add_action($a);
		}

		$chat->display_chat_content();
	}
	else
		echo 'Use the get parameter with the "cid" key to say which campain to test (campain id) or try with your external identifier to launch the distant API requests<br/>
			<a href="?cid=38">Click here to test campain 38</a><br/>
			<a href="?cid=38">Click here to test campain 38</a>';
?>