<?php
	foreach (glob("Files/Classes/*.php") as $filename)
	{
		//echo $filename;
		include_once $filename;
	}
?>