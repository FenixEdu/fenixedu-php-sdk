<?php
class Schedule extends FenixEduEntity {
    private $data;

    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
    }
    
    /** Returns an array with the Periods of this Schedule's lessons.
     */
    public function getLessonPeriods() {
        require_once("Period.php");
        $periods = array();
        foreach($this->data->lessonPeriods as $period) $periods[] = new Period($this->fenixEdu, $period);
        return $periods;
    }
    
    /** Returns the CourseLoads of this Schedule.
     */
    public function getCourseLoads() {
        require_once("CourseLoad.php");
        $loads = array();
        foreach($this->data->courseLoads as $load) $loads[] = new CourseLoad($this->fenixEdu, $load);
        return $loads;
    }
    
    /** Returns the Shifts of this Schedule's Course.
     */
    public function getShifts() {
        require_once("Shift.php");
        $shifts = array();
        foreach($this->data->shifts as $shift) {
            $shift->course = $this->data->course;
            $shifts[] = new Shift($this->fenixEdu, $shift);
        }
        return $shifts;
    }
}
