<?php
	
	class API_TextMsg{

		private $message;

		public function set_value ($value)
		{
			$this->message = $value;
		}

		public function value_from_output($array)
		{
			$this->message = $array['text'];
		}

		public function display ()
		{
			echo '<p>'.$this->message.'</p>';
		}
	}