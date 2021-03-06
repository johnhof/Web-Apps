<?php

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	ELEMENT SPECIFIC FORMATTERS
//--------------------------------------------------------------------------------------------------------------------------------------

	//transforms table to HTML, assuming it is a scheduling table
	function genbasicTable($table){return genTable($table, array('border' => '1','align'=>'center','cellpadding'=>'4'), array('align'=>'center'));}
	function gentable($table, $attributes, $cellAttributes){
		$html = '';
		foreach ($table as $key=>$values) {
			$row = '';

			//markup each cell
			foreach($values as $value)$row=$row.markupAttributes('td', $value, $cellAttributes);

			//markup a form around the row, with a hidden input to identify which row it is when the inpu tis submitted
			////cleanup malfomred tags
			$temp = $values[$key];
			$temp = str_replace('"','',$temp);
			$row = $row.markupAttributes('input', '', array('type'=>'hidden', 'name'=>'id', 'value'=>$temp));
			
			$temp = $key;
			$temp = str_replace('"','',$temp);
			$row = $row.markupAttributes('input', '', array('type'=>'hidden', 'name'=>'row', 'value'=>$temp));
			$row = markupAttributes('form', $row, array('action'=>MAIN_PAGE, 'method'=>'post'));

			//markup the row
			$html = $html.markup('tr', $row);
		}

		//return a marked up table
		return markupAttributes('table',$html, $attributes);
	}

	function genform($formAttrs, $inputAttrs){
		$html = markupAttributes('input','', $inputAttrs);
		$html = markupAttributes('form', $html, $formAttrs);
		return $html;
	}

	function genlink($link, $text){
		$href = array();
		$href['href'] = $link;
		$this->markupAttributes('a', $text, $href);
	}

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	UUTILITY FUNCTIONS
//--------------------------------------------------------------------------------------------------------------------------------------

	//both of these convert a string tag and string data into HTML
	function markup($tag, $string){
		if(func_num_args() > 2) return markupAttributes($tag, $string, func_get_arg(2));
		return '<'.$tag.'>'.$string.'</'.$tag.'>';
	}
	//allows for an array of attribute to value mappings
	function markupAttributes($tag, $string, $attributes){
		$startTag = '<'.$tag;
		$endTag = '</'.$tag.'>';

		//add each key/value pair as an attribute
		foreach ($attributes as $key => $value) {
			$startTag=$startTag.' '.$key.'="'.$value.'"';
		}
		$startTag = $startTag.'>';

		return $startTag.$string.$endTag;
	}
?>