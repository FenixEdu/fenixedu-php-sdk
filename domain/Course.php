<?php
require_once("FenixEduEntity.php");

class Course extends FenixEduEntity {
    private $course;
    private $id;
    
    public function __construct($fenixEdu, $id) {
        parent::__construct($fenixEdu);
        $this->course = $fenixEdu->get("courses/".$id);
        $this->id = $id;
    }
    
    public function getEvaluations() {
		return $this->get("courses/".$this->id."/evaluations");
	}

	public function getGroups() {
		return $this->get("courses/".$this->id."/groups");
	}

	public function getSchedule() {
		return $this->get("courses/".$this->id."/schedule");
	}

	public function getStudents() {
		return $this->get("courses/".$this->id."/students");
	}
}
?>