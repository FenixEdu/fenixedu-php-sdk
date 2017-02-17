<?php
require_once("FenixEduEntity.php");

class Evaluation extends FenixEduEntity {
    private $data;

    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
    }
    
    /** Returns the id of this Evaluation.
     */
    public function getId() {
        if(!property_exists($this->data, 'id')) return NULL;
        return $this->data->id;
    }
    
    /** Returns the name of this Evaluation.
     */
    public function getName() {
        return $this->data->name;
    }
    
    /** Returns the type of this Evaluation.
     */
    public function getType() {
        return $this->data->type;
    }
    
    /** Returns the starting DateTime of this Evaluation.
     */
    public function getStart() {
        return $this->parseDateTime($this->data->evaluationPeriod->start);
    }
    
    /** Returns the ending DateTime of this Evaluation.
     */
    public function getEnd() {
        return $this->parseDateTime($this->data->evaluationPeriod->end);
    }
    
    /** Returns the starting DateTime of this Evaluation's enrolment period.
     */
    public function getEnrolmentStart() {
        if(!property_exists($this->data, 'enrollmentPeriod')) return NULL;
        return $this->parseDateTime($this->data->enrollmentPeriod->start);
    }
    
    /** Returns the ending DateTime of this Evaluation's enrolment period.
     */
    public function getEnrolmentEnd() {
        if(!property_exists($this->data, 'enrollmentPeriod')) return NULL;
        return $this->parseDateTime($this->data->enrollmentPeriod->end);
    }
    
    /** Returns whether the present date is within this Evaluation's enrolment
     * period.
     */
    public function isEnrolmentPeriod() {
        if(!property_exists($this->data, 'isInEnrolmentPeriod')) return FALSE;
        return $this->data->isInEnrolmentPeriod;
    }
    
    /** Returns whether the user is enroled in this Evaluation.
     */
    public function isEnroled() {
        if(!property_exists($this->data, 'isEnrolled')) return FALSE;
        return $this->data->isEnrolled;
    }
    
    /** Returns an array with the Courses this Evaluation applies to.
     */
    public function getCourses() {
        if(!property_exists($this->data, 'courses')) return NULL;
        require_once("Course.php");
        $courses = array();
        foreach($this->data->courses as $course) {
            $courses[] = new Course($this->fenixEdu, $this->fenixEdu->getCourse($course->id));
        }
        return $courses;
    }
    
    /** Returns an array with the Spaces this Evaluation will occur in.
     */
    public function getRooms() {
        require_once("Space.php");
    	$spaces = $array();
    	foreach($this->data->rooms as $space) $spaces[] = new Space($this->fenixEdu, $space);
    	return $spaces;
    }
    
    /** Returns the Space assigned to the user for this Evaluation.
     */
    public function getAssignedRoom() {
        require_once("Space.php");
    	if(!property_exists($this->data, 'assignedRoom')) return NULL;
    	return new Space($this->fenixEdu, $this->data->assignedRoom);
    }
    
    /** Sets the user's enrolment status for this Evaluation.
     */
    public function setEnrolment($enrol) {
    	$id = $this->getId();
    	if($id !== NULL) {
    	    $this->fenixEdu->login();
    	    $this->data->setPersonEvaluationEnrollment($id, $enrol);
    	}
    }
    
    /** Enrols the user in this Evaluation.
     */
    public function enrol() {
    	$this->setEnrolment(TRUE);
    }
    
    /** Disenrols the user from this Evaluation.
     */
    public function disenrol() {
    	$this->setEnrolment(FALSE);
    }
}
