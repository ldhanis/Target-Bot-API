<?php

	class API_Button{

		private $buttons;

		public function set_by_array ($array)
		{
			$array = $array['answers'];
			while ($answ = current($array)) {
				$this->add_button (key($array), $answ);
				next($array);
			}
		}

		public function add_button ($message, $payload)
		{
			$this->buttons[] = array(
					'message'	=>	$message,
					'payload'	=>	$payload
				);
		}

		public function display ()
		{
			foreach ($this->buttons as $button)
			{
				echo '<a href="index.php?answ='.$button['payload'].'">'.$button['message'].'</a><br/>';
			}
		}
	}