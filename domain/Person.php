<?php
require_once("FenixEduEntity.php");

class Person extends FenixEduEntity {
    private $person;
    
    public function __construct($fenixEdu) {
        parent::__construct($fenixEdu);
        $this->person = $fenixEdu->get("person");
    }

    public function getIstId() {
		return $this->username;
	}
    
    public function getCalendarClasses() {
		return $this->get("person/calendar/classes");
	}
    
    public function getCalendarEvaluations() {
		return $this->get("person/calendar/evaluations");
	}
    
    public function getCourses() {
		return $this->get("person/courses");
	}

	public function getCurriculum() {
		return $this->get("person/curriculum");
	}

	public function getEvaluations() {
		return $this->get("person/evaluations");
	}
    
    public function enrollEvaluation($id) {
		return $this->put("person/evaluations/".$id, "enrol=yes");
	}

	public function disenrollEvaluation($id) {
		return $this->put("person/evaluations/".$id, "enrol=no");
	}

	public function getPayments() {
		return $this->get("person/payments");
	}
}
?>