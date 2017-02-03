<?php
require_once("FenixEduEntity.php");

class DomainRoot extends FenixEduEntity {
    
    /* Returns some basic information about the institution where the
     * application is deployed. It also returns a list of RSS feeds, the
     * current academic term, available languages and default language.
     */
    public function getAbout() {
		return $this->fenixEdu->getAbout();
	}

    
	public function getCourse($id) {
        require_once("Course.php");
		return new Course($this->fenixEdu, $id);
	}

	public function getDegrees() {
		return $this->get("degrees");
	}

	public function getDegree($id) {
		return new Degree($this, $id);
	}

	public function getPerson() {
		return new Person($this);
	}

	public function getSpaces() {
		return $this->get("spaces");
	}

	public function getSpace($id) {
		return new Space($this, $id);
	}
}