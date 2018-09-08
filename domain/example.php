<?php

class Example extends DomainObject {

    public function __construct() {
        parent::__construct();
        $this->queries['getAll'] = 'question_getAll';
        $this->queries['get'] = 'war_get';
        $this->queries['delete'] = 'category_delete';
        $this->queries['add'] = 'category_add';
		$this->queries['update'] = 'category_update';

    }

}