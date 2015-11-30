<?php

$errors = array();

function fieldname_as_text($fieldname) {
	$fieldname = str_replace("_", " ", $fieldname);
 	$fieldname = ucfirst($fieldname);
 	return $fieldname;

}

function val_presence($field) {

	return isset($field) && $field != null;

}

function val_date($datefield) {
	//check for proper date form as MM/DD/YYYY

}

function val_presences($required_fields) {
	//processes all array fields.
	global $errors;
	foreach($required_fields as $field) {
		$value = trim($_POST[$field]);
		if(!val_presence($value)) {
			$errors[$field] = "Field " . fieldname_as_text($field) . " cannot be blank.";
		}
	}
	
}

function val_max_lengths($fields_with_max_lengths) {
	global $errors;
	// Expects an assoc. array 
	
	foreach($fields_with_max_lengths as $field => $max) {
		$value = trim($_POST[$field]);
		if (!has_max_length($value, $max)) {
	   	$errors[$field] = fieldname_as_text($field) . " is too long.";
	  }
	}
}

function val_filetypes($file, $accepted_types) {
	global $errors;
	$ext = pathinfo($file, PATHINFO_EXTENSION);
	if(!in_array($ext,$accepted_types)) {
   	$errors["file"] = "File is of wrong type.";
   }
}

?>