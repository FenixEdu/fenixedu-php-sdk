<?php
require_once("FenixEduEntity.php");

class Person extends FenixEduEntity {
    private $data;
    
    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
    }
    
    
    /** Returns an array with the Person's roles.
     */
    public function getRoles() {
        return $this->data->roles;
    }
    
    /** Returns the Person's department if the Person is a teacher or
     * <i>NULL</i> otherwise.
     */
    public function getDepartment() {
        foreach($this->data->roles as $role) {
            if(strcasecmp($role->type, 'TEACHER') == 0) return $role->department;
        }
        return NULL;
    }
    
    /** Returns an array with the Degrees the Person is registered in.
     */
    public function getDegrees() {
        require_once("Degree.php");
        $degrees = array();
        foreach($this->data->roles as $role) {
            if(strcasecmp($role->type, 'STUDENT') == 0) {
                foreach($role->registrations as $reg) {
                    $degree = $this->fenixEdu->getDegree($reg->id, end($reg->academicTerms));
                    $degrees[] = new Degree($this->fenixEdu, $degree);
                }
            }
        }
        return $degrees;
    }
    
    /** Returns an array with the Degrees the Person has concluded.
     */
    public function getConcludedDegrees() {
        require_once("Degree.php");
        $degrees = array();
        foreach($this->data->roles as $role) {
            if(strcasecmp($role->type, 'ALUMNI') == 0) {
                foreach($role->concludedRegistrations as $reg) {
                    $degree = $this->fenixEdu->getDegree($reg->id, end($reg->academicTerms));
                    $degrees[] = new Degree($this->fenixEdu, $degree);
                }
            }
        }
        return $degrees;
    }
    
    /** Returns the campus this Person is registered in.
     */
    public function getCampus() {
        return $this->data->campus;
    }
    
    /** Returns the photo of this Person.
     * The <i>type</i> property gives the data type and format of the photo.
     * The <i>data</i> property contains the binary data of the photo.
     */
    public function getPhoto() {
        return $this->data->photo;
    }
    
    /** Returns the name of this Person.
     */
    public function getName() {
        return $this->data->name;
    }
    
    /** Returns the legal gender of this Person.
     */
    public function getGender() {
        return $this->data->gender;
    }
    
    /** Returns the birthday date of this Person.
     */
    public function getBirthday() {
        return $this->data->birthday;
    }
    
    /** Returns the IST ID of this Person.
     */
    public function getIstId() {
        return $this->data->username;
    }
    
    /** Returns the main email address of this Person.
     */
    public function getEmail() {
        return $this->data->email;
    }
    
    /** Returns an array with the personal email addresses of this Person.
     */
    public function getPersonalEmails() {
        return $this->data->personalEmails;
    }
    
    /** Returns an array with the work email addresses of this Person.
     */
    public function getWorkEmails() {
        return $this->data->workEmails;
    }
    
    /** Returns an array with the web addresses of this Person.
     */
    public function getWebAddresses() {
        return $this->data->webAddresses;
    }
    
    /** Returns an array with the work web addresses of this Person.
     */
    public function getWorkWebAddresses() {
        return $this->data->webWorkAddresses;
    }
    
    /** Returns the academic schedule of this Person.
     */
    public function getCalendar() {
        //TODO implement Calendar
        return NULL;
    }
    
    /** Returns an array with the Courses this Person is enroled in.
     */
    public function getEnroledCourses($academicTerm = NULL) {
        require_once("Course.php");
        $courses = array();
        foreach($this->fenixEdu->getPersonCourses($academicTerm)->enrolments as $enroledCourse) {
            $course = $this->fenixEdu->getCourse($enroledCourse->id);
            $course->grade = $enroledCourse->grade;
            $courses[] = new Course($this->fenixEdu, $course);
        }
    }
    
    /** Returns an array with the Courses this Person is teaching.
     */
    public function getTeachingCourses($academicTerm = NULL) {
        require_once("Course.php");
        $courses = array();
        foreach($this->fenixEdu->getPersonCourses($academicTerm)->teaching as $teachingCourse) {
            $course = $this->fenixEdu->getCourse($teachingCourse->id);
            $courses[] = new Course($this->fenixEdu, $course);
        }
    }
    
    /** Returns an array with the curriculum of each Degree this Person
     * registered in.
     */
    public function getCurriculum() {
        include_once("DegreeCurriculum.php");
        $curricula = array();
        foreach($this->fenixEdu->getPersonCurriculum() as $curriculum) {
            $curricula[] = new DegreeCurriculum($this->fenixEdu, $curriculum);
        }
        return $curricula;
    }
    
    /** Returns an array with the evalutaions applicable to this Person.
     */
    public function getEvaluations() {
        include_once("Evaluation.php");
        $evaluations = array();
        foreach($this->fenixEdu->getPersonEvaluations() as $evaluation) {
            $evaluations[] = new Evaluation($this->fenixEdu, $evaluation);
        }
        return $evaluations;
    }
    
    /** Returns an array with this Person's completed Payments.
     */
    public function getCompletedPayments() {
        include_once("Payment.php");
        $payments = array();
        foreach($this->fenixEdu->getPersonPayments()->completed as $payment) {
            $payments[] = new Payment($this->fenixEdu, $payment);
        }
        return $payments;
    }
    
    /** Returns an array with this Person's pending Payments.
     */
    public function getPendingPayments() {
        include_once("Payment.php");
        $payments = array();
        foreach($this->fenixEdu->getPersonPayments()->pending as $payment) {
            $payments[] = new Payment($this->fenixEdu, $payment);
        }
        return $payments;
    }
}
