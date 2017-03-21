<?php
class Shift extends FenixEduEntity {
    private $data;

    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
    }

    /** Returns the name of this Shift.
     */
    public function getName() {
        return $this->data->name;
    }

    /** Returns an array with the types of this Shift.
     */
    public function getTypes() {
        return $this->data->types;
    }

    /** Returns the current occupation of this Shift.
     */
    public function getOccupation() {
        return $this->data->occupation->current;
    }

    /** Returns the maximum capacity of this Shift.
     */
    public function getCapacity() {
        return $this->data->occupation->max;
    }

    /** Returns an array with the Events of the lessons of this Shift.
     */
    public function getLessons() {
        require_once("Event.php");
        $lessons = array();
        foreach($this->data->lessons as $lesson) {
            $lesson->course = $this->data->course;
            $lesson->period = $lesson; //don't ask...
            $lesson->type = "LESSON";
            $lesson->location = array($lesson->room);
            $lessons[] = new Event($this->fenixEdu, $lesson);
        }
        return $lessons;
    }
}
