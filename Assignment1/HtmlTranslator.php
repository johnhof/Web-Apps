<?php

class HtmlTranslator{

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	ELEMENT SPECIFIC FORMATTERS
//--------------------------------------------------------------------------------------------------------------------------------------

	function basicTable($table){return $this->table($table, array('border' => '1','align'=>'center','cellpadding'=>'4'), array('align'=>'center'));}
	function table($table, $attributes, $cellAttributes){
		$html = '';
		foreach ($table as $key=>$values) {
			$row = '';

			//markup each cell
			foreach($values as $value)$row=$row.$this->markupAttributes('td', $value, $cellAttributes);

			//markp a form around the row, with a hidden input to mark which row it is
			$row = $row.$this->markupAttributes('input', '', array('type'=>'hidden', 'value'=>$key));
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

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	UUTILITY FUNCTIONS
//--------------------------------------------------------------------------------------------------------------------------------------

	function markup($tag, $string){
		return '<'.$tag.'>'.$string.'</'.$tag.'>';
	}
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