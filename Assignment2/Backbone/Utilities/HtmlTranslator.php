<?php

class HtmlTranslator{

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	ELEMENT SPECIFIC FORMATTERS
//--------------------------------------------------------------------------------------------------------------------------------------

	//transforms table to HTML, assuming it is a scheduling table
	function basicTable($table){return $this->table($table, array('border' => '1','align'=>'center','cellpadding'=>'4'), array('align'=>'center'));}
	function table($table, $attributes, $cellAttributes){
		$html = '';
		foreach ($table as $key=>$values) {
			$row = '';

			//markup each cell
			foreach($values as $value)$row=$row.$this->markupAttributes('td', $value, $cellAttributes);

			//markup a form around the row, with a hidden input to identify which row it is when the inpu tis submitted
			////cleanup malfomred tags
			$temp = $values[0];
			$temp = str_replace('"','',$temp);
			$row = $row.$this->markupAttributes('input', '', array('type'=>'hidden', 'name'=>'id', 'value'=>$temp));
			
			$temp = $key;
			$temp = str_replace('"','',$temp);
			$row = $row.$this->markupAttributes('input', '', array('type'=>'hidden', 'name'=>'row', 'value'=>$temp));
			$row = $this->markupAttributes('form', $row, array('action'=>MAIN_PAGE, 'method'=>'post'));

			//markup the row
			$html = $html.$this->markup('tr', $row);
		}

		//return a marked up table
		return $this->markupAttributes('table',$html, $attributes);
	}

	function form($formAttrs, $inputAttrs){
		$html = $this->markupAttributes('input','', $inputAttrs);
		$html = $this->markupAttributes('form', $html, $formAttrs);
		return $html;
	}

	function link($link, $text){
		$href = array();
		$href['href'] = $link;
		$this->markupAttributes('a', $text, $href);
	}

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	UUTILITY FUNCTIONS
//--------------------------------------------------------------------------------------------------------------------------------------

	//both of these convert a string tag and string data into HTML
	function markup($tag, $string){
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
}
?>