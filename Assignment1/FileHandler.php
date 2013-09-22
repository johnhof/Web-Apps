<?php

Class FileHandler{
    private $fileCache;

    function __construct(){
        $this->fileCache = array();
    }

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
        return rOpen($file);
    }

    function rOpen($file){
        if(file_exists($file)){ 
            $handler = fopen($file, 'r');
/*
            if(flock($handler,LOCK_SH )){
                fclose($handler);
                return NO_LOCK;
            }*/
            
            array_push($this->fileCache, $handler);
            return $handler;
        }
        return null;
    }

    function wOpen($file){
        if(file_exists($file)) {
            $handler = fopen($file, 'w');
            /*
            if(!flock($handler ,LOCK_EX)){
                fclose($handler);
                return NO_LOCK;
            }*/
            
            array_push($this->fileCache, $handler);
            return $handler;
        }
        return null;
    }
    function closeAll(){
        foreach($this->fileCache as $index => $handler){
            // /flock($handler,LOCK_UN);            
            //fclose($handler);
           // $this->fileCache->splice($index);
        }
    }

//--------------------------------------------------------------------------------------------------------------------------------------
//--------  CONTENT FORMATTERS
//--------------------------------------------------------------------------------------------------------------------------------------

    function parseRows($file, $firstDelim, $delims){
        $rows=array();

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

    function writeToFile($file, $text){
        $handler = $this->wOpen($file);

        fwrite($handler, $text);

        $this->closeAll();
    }
} 

?>