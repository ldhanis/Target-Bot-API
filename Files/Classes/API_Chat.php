<?php

	class API_Chat{

		private $message_history = array();
		private $action;

		//adds a message, 
		//from_to == 1 -> user answer;
		//from_to == 0 -> server answer;

		public function add_message ($from_to, $message_object)
		{
			$this->message_history[] = array(
				'USER'		=> $from_to,
				'OBJECT'	=> $message_object
			);
		}

		public function add_action ($action_object)
		{
			$this->action[] = $action_object;
		}

		public function display_chat_content ()
		{
			echo '<div id="chatContent">';
			echo '<div id="message_history">';
			foreach($this->message_history as $message)
			{
				$classes = 'left grey';
				if ($message['USER'])
					$classes = 'right blue';
				//var_dump($message);
				echo '<div class="'.$classes.'">';
				$message['OBJECT']->display();
				echo '</div>';
			}
			echo '</div>';
			echo '<div id="action">';
				foreach($this->action as $action)
				{
					if($action != NULL)
						$action->display();
				}
			echo '</form>';
			echo '</div>';
		}

		public function has_empty_action()
		{
			if (!$this->action || sizeof($this->action) == 0)
				return true;
			return false;
		}
	}