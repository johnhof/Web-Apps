<?php

foreach (glob("./Backbone/Utilities/*.php") as $filename)
{
    if($filename != 'Utils.php'){
    	include_once $filename;
    }
}

?>