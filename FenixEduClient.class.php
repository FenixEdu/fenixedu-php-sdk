<?php

require_once("fenixedu.config.php");
require_once "AbstractOAuthClient.class.php";

class FenixEduClient extends AbstractOAuthClient {

	private static $INSTANCE;

	protected function __construct() {
		global $_FENIX_EDU;
		parent::__construct($_FENIX_EDU);
	}

	public static function getSingleton() {
		if(self::$INSTANCE == null) {
			self::$INSTANCE = new self();
		}
		return self::$INSTANCE;
	}

	public function getIstId() {
		return $this->getUser()->{'istId'};
	}

	public function getAboutInfo() {
		return $this->get("about");
	}

	public function getCourse($id) {
		return $this->get("courses/".$id);
	}

	public function getCourseEvaluations($id) {
		return $this->get("courses/".$id."/evaluations");
	}

	public function getCourseGroups($id) {
		return $this->get("courses/".$id."/groups");
	}

	public function getCourseSchedule($id) {
		return $this->get("courses/".$id."/schedule");
	}

	public function getCourseStudents($id) {
		return $this->get("courses/".$id."/students");
	}

	public function getDegrees() {
		return $this->get("degrees");
	}

	public function getDegree($id) {
		return $this->get("degrees/".$id);
	}

	public function getDegreeCourses($id) {
		return $this->get("degrees/".$id."/courses");
	}

	public function getPerson() {
		return $this->get("person");
	}

	public function getPersonCalendarClasses() {
		return $this->get("person/calendar/classes");
	}

	public function getPersonCourses() {
		return $this->get("person/courses");
	}

	public function getCurriculum() {
		return $this->get("person/curriculum");
	}

	public function getPersonEvaluations() {
		return $this->get("person/evaluations");
	}

	public function subscribePersonEvaluations() {
		return $this->put("person/evaluations/".$id);
	}

	public function getPersonPayments() {
		return $this->get("/person/payments");
	}

	public function getSpaces() {
		return $this->get("spaces");
	}

	public function getSpace($id) {
		return $this->get("spaces/".$id);
	}

}

?>