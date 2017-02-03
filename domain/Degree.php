<?php
require_once("FenixEduEntity.php");

class Degree extends FenixEduEntity {
    private $degree;
    private $id;
    
    public function __construct($fenixEdu, $id) {
        parent::__construct($fenixEdu);
        $this->degree = $fenixEdu->get("degrees/".$id);
        $this->id = $id;
    }

    public function getCourses() {
		return $this->get("degrees/".$this->id."/courses");
	}
}
?>