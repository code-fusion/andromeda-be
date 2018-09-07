<?php

class Route {
	
	public $page;
	public $variables = array();
	public $postVariables = array();
	
	public function __construct($page, array $variables = array(), array $postVariables = array()){
		
		$this->page = $page;
		$this->variables = $variables;
		$this->postVariables = $postVariables;
		
	}
	
}
