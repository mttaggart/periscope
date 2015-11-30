<?php

class Filter {
    public static $active_filters = array();
    public $id;
    public $label;
    
    protected function __construct($label) {
        
        $this->label = $label;
        
        if(empty(self::$active_filters)) {
            self::$active_filters[$this];
        }    
    }    
    public function toggle() {
        if (in_array($this, self::$active_filters)) {
            unset(self::$active_filters[array_search($this, self::$active_filters)]);
        } else {
            self::$active_filters[] = $this;
        }
    }
    
    public static function show_filters() {
        foreach(self::$active_filters as $filter) {
            echo $filter->label;
        }
       
    }
        
}


class MonthFilter extends Filter {
    
    public function __construct($label) {
        parent::__construct($label);
    }
}

class SubjectFilter extends Filter {
    public $subject;
    
    public function __construct($label) {
        parent::__construct($label);
    }  
    
}

class GradeFilter extends Filter {
    
    public $gradeLevel;
    
    public function __construct($label) {
        parent::__construct($label);
    }  
    
}
