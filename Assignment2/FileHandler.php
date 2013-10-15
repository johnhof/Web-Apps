<?php

Class FileHandler{

//--------------------------------------------------------------------------------------------------------------------------------------
//--------  I/O HANDLERS
//--------------------------------------------------------------------------------------------------------------------------------------

//TODO: create and return read handler
    function rCreate($file){
        //attempt to create the file
        $handler = fopen($file, 'w');
        if ($handler) fclose($handler);
        else return null;

        //open for read and return
        return $this->rOpen($file);
    }

    function rOpen($file){
        if(file_exists($file)){ 
            $handler = fopen($file, 'r');
            if(!flock($handler,LOCK_SH )){//attempt to acquire a shared lock
                fclose($handler);
                return NO_LOCK;
            }
            
            return $handler;
        }
        return null;
    }

    function wOpen($file){
        if(file_exists($file)) {
            $handler = fopen($file, 'w');
            if(!flock($handler ,LOCK_EX)){//attempt to acquire an exclusive lock
                fclose($handler);
                return NO_LOCK;
            }

            return $handler;
        }
        return null;
    }
    function close($handler){
        flock($handler,LOCK_UN);
        fclose($handler);
    }

//--------------------------------------------------------------------------------------------------------------------------------------
//--------  CONTENT FORMATTERS
//--------------------------------------------------------------------------------------------------------------------------------------

    //parses the data file
    function parseRows($file, $firstDelim, $delims){
        $rows=array();

        if(!is_resource($file)) return '';

        while (!feof($file)) {
            $line = fgets($file);

            if($line == '') continue;

            //tokenize the line
            $tokens = explode($firstDelim,$line);

            //grab the header and store it
            $header = $tokens[0];
            if(sizeOf($tokens)==1) continue;

            //retokenize based the substring
            $tokens = explode($delims, $tokens[1]);

            //add each value to the array under the header
            foreach($tokens as $key => &$value) $rows[$header][$key] = $value;
        }
        return $rows;
    }

    //writes the text to the specified file
    function writeToFile($file, $text){
        $handler = $this->wOpen($file);

        //the lock must be free, and the file must exist
        if($handler == NO_LOCK || $handler == null) return $handler;

        fwrite($handler, $text);

        $this->close($handler);

    }
} 

?>