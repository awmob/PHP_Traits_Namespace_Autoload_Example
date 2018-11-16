<?php
namespace Includes;

trait Helpers{
	public function set_age($age_input){
		$this->age = $age_input;
	}

	public function get_age(){
		return $this->age;
	}
}
