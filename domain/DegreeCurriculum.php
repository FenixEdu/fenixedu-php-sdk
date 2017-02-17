<?php
require_once("FenixEduEntity.php");

class DegreeCurriculum extends FenixEduEntity {
    private $data;

    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
    }
    
    /** Returns the Degree of this DegreeCurriculum.
     */
    public function getDegree() {
        require_once("Degree.php");
        return new Degree($this->fenixEdu, $this->fenixEdu->getDegree($this->data->degree->id));
    }
    
    /** Returns the DateTime when the Person started the Degree.
     */
    public function getStart() {
        return $this->parseDateTime($this->data->start);
    }
    
    /** Returns the DateTime when the Person finished the Degree or NULL if the
     * Person has not finished the Degree yet.
     */
    public function getEnd() {
        return $this->parseDateTime($this->data->end);
    }
    
    /** Returns the ECTS the Person has acquired in the Degree.
     */
    public function getCredits() {
        return $this->data->credits;
    }
    
    /** Returns the average grade the Person has acquired in the Degree.
     */
    public function getAverage() {
        return $this->data->average;
    }
    
    /** Returns the calculated average grade the Person has acquired in the
     * Degree.
     */
    public function getCalculatedAverage() {
        return $this->data->calculatedAverage;
    }
    
    /** Returns whether the Person has finished the Degree.
     */
    public function isFinished() {
        return $this->data->isFinished;
    }
    
    /** Returns the number of Courses the Person has finished in the Degree.
     */
    public function getNumberOfApprovedCourses() {
        return $this->data->numberOfApprovedCourses;
    }
    
    /** Returns the curricular year of the Person in the Degree.
     */
    public function getCurrentYear() {
        return $this->data->currentYear;
    }
    
    /** Returns an array with the Courses the Person has finished in the
     * Degree.
     */
    public function getApprovedCourses() {
        require_once("Course.php");
        $courses = array();
        foreach($this->data->approvedCourses as $approvedCourse) {
            $course = $this->fenixEdu->getCourse($approvedCourse->course->id);
            $course->grade = $approvedCourse->grade;
            $course->credits = $approvedCourse->ects;
            $courses[] = new Course($this->fenixEdu, $course);
        }
        return $courses;
    }
}
