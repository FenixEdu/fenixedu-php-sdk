<?php
require_once("FenixEduEntity.php");

class Calendar extends FenixEduEntity {
    private $data;

    public function __construct($fenixEdu) {
        parent::__construct($fenixEdu);
        $this->data = NULL;
    }
    
    private function loadClasses() {
        $data = $this->fenixEdu->getPersonalCalendarClasses();
        if($this->data === NULL) {
            $this->data = $data;
            $this->data->classes = $data->events;
            unset($this->data->events);
        } else {
            $this->data->classes = $data->events;
        }
    }
    
    private function loadEvaluations() {
        $data = $this->fenixEdu->getPersonalCalendarEvaluations();
        if($this->data === NULL) {
            $this->data = $data;
            $this->data->evaluations = $data->events;
            unset($this->data->events);
        } else {
            $this->data->evaluations = $data->events;
        }
    }
    
    /** Returns an array with the Spaces this Event will be in.
     */
    public function getAcademicTerm() {
        if(!property_exists($this->data, 'academicTerm')) $this->loadClasses();
        return $this->data->academicTerm;
    }
    
    /** Returns an array with the current user's classes.
     */
    public function getClasses() {
        if(!property_exists($this->data, 'classes')) $this->loadClasses();
        require_once("Event.php");
        $classes = array();
        foreach($this->data->classes as $class) {
            $class->type = "LESSON";
            $class->period = $class->classPeriod;
            unset($class->classPeriod);
            $classes[] = new Event($this->fenixEdu, $class);
        }
        return $classes;
    }
    
    /** Returns an array with the current user's evaluations.
     */
    public function getEvaluations() {
        if(!property_exists($this->data, 'evaluations')) $this->loadEvaluations();
        require_once("Event.php");
        $evaluations = array();
        foreach($this->data->classes as $evaluation) {
            //FIXME I could not find a scheduled evaluation at this time in order to get the correct type
            $evaluation->type = "EVALUATION";
            $evaluation->period = $evaluation->evaluationPeriod;
            unset($evaluation->evaluationPeriod);
            $evaluations[] = new Event($this->fenixEdu, $evaluation);
        }
        return $evaluations;
    }
}