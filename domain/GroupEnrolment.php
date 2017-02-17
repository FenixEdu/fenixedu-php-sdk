<?php
require_once("FenixEduEntity.php");

class GroupEnrolment extends FenixEduEntity {
    private $data;

    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
    }
    
    /** Returns the name of this GroupEnrolment's activity.
     */
    public function getName() {
        return $this->data->name;
    }
    
    /** Returns this GroupEnrolment's description;
     */
    public function getDescription() {
        return $this->data->description;
    }
    
    /** Returns the starting DateTime of this GroupEnrolment's enrolment 
     * period.
     */
    public function getStart() {
        return $this->parseDateTime($this->data->enrolmentPeriod->start);
    }
    
    /** Returns the ending DateTime of this GroupEnrolment's enrolment
     * period.
     */
    public function getEnd() {
        return $this->parseDateTime($this->data->enrolmentPeriod->end);
    }
    
    /** Returns the enrolment policy of this GroupEnrolment.
     */
    public function getEnrolmentPolicy() {
        return $this->data->enrolmentPolicy;
    }
    
    /** Returns the minimum number of Group members for this GroupEnrolment.
     */
    public function getMinimumCapacity() {
        return $this->data->minimumCapacity;
    }
    
    /** Returns the maximum number of Group members for this GroupEnrolment.
     */
    public function getMaximumCapacity() {
        return $this->data->maximumCapacity;
    }
    
    /** Returns the ideal number of Group members for this GroupEnrolment.
     */
    public function getIdealCapacity() {
        return $this->data->idealCapacity;
    }
    
    /** Returns an array with this GroupEnrolment's associated Courses;
     */
    public function getAssociatedCourses() {
        require_once("Course.php");
        $courses = array();
        foreach($this->data->associatedCourses as $course) {
            $courses[] = new Course($this->fenixEdu, $this->fenixEdu->getCourse($course->id));
        }
        return $courses;
    }
    
    /** Returns an array with this GroupEnrolment's Groups;
     */
    public function getGroups() {
        require_once("Group.php");
        $groups = array();
        foreach($this->data->associatedGroups as $group) $groups[] = new Group($this->fenixEdu, $group);
        return $groups;
    }
}