<?php
require_once("FenixEduEntity.php");

class Course extends FenixEduEntity {
    private $data;
    private $students;
    
    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
        $this->students = NULL;
    }
    
    private function mergeData($courseData, $degreeData) {
        $this->data = $courseData;
        $this->data->credits = $degreeData->credits;
    }
    
    private function loadDataFromCourse() {
        if(!property_exists($this->data, 'url') || !property_exists($this->data, 'competences')) {
            $this->mergeData($this->fenixEdu->getCourse($this->data->id, $this->data->academicTerm), $this->data);
        }
    }
    
    private function loadDataFromDegree() {
        if(!property_exists($this->data, 'credits')) {
            $degree = $this->fenixEdu->getDegree($this->data->degrees[0]->id, $this->data->academicTerm);
            foreach($this->fenixEdu->getDegreeCourses($degree->id, $this->data->academicTerm) as $course) {
                if(strcmp($course->id, $this->data->id) == 0) {
                    $this->mergeData($this->data, $course);
                    return;
                }
            }
        }
    }
    
    /** Returns the acronym of this Course.
     */
    public function getAcronym() {
        return $this->data->acronym;
    }
    
    /** Returns the name of this Course.
     */
    public function getName() {
        return $this->data->name;
    }
    
    /** Returns the academicTerm of this Course.
     */
    public function getAcademicTerm() {
        return $this->data->academicTerm;
    }
    
    /** Returns the <i>id</i> of this Course.
     */
    public function getId() {
        return $this->data->id;
    }
    
    /** Returns the value in credits of this Course.
     */
    public function getCredits() {
        $this->loadDataFromDegree();
        return $this->data->credits;
    }
    
    /** Returns the evaluation method of this Course.
     */
    public function getEvaluationMethod() {
        $this->loadDataFromCourse();
        return $this->data->evaluationMethod;
    }
    
    /** Returns the number of students attending this Course.
     */
    public function getNumberOfAttendingStudents() {
        $this->loadDataFromCourse();
        return $this->data->numberOfAttendingStudents;
    }
    
    /** Returns this Course's summary link.
     */
    public function getSummaryLink() {
        $this->loadDataFromCourse();
        return $this->data->summaryLink;
    }
    
    /** Returns this Course's announcement link.
     */
    public function getAnnouncementLink() {
        $this->loadDataFromCourse();
        return $this->data->announcementLink;
    }
    
    /** Returns this Course's URL.
     */
    public function getUrl() {
        $this->loadDataFromCourse();
        return $this->data->url;
    }
    
    /** Returns the program of this Course.
     */
    public function getProgram() {
        $this->loadDataFromCourse();
        return $this->data->competences[0]->program;
    }
    
    /** Returns an array with this Course's bibliographic references.
     */
    public function getBibliography() {
        require_once("Book.php");
        $this->loadDataFromCourse();
        $books = array();
        foreach($this->data->competences[0]->bibliographicReferences as $book) {
            $books[] = new Book($this->fenixEdu, $book);
        }
        return $books;
    }
    
    /** Returns an array with the Degrees containing this Course.
     */
    public function getDegrees() {
        require_once("Degree.php");
        $this->loadDataFromCourse();
        $degrees = array();
        foreach($this->data->degrees as $degree) $degrees[] = new Degree($this->fenixEdu, $degree);
        return $degrees;
    }
    
    /** Returns an array with this Course's Teachers.
     */
    public function getTeachers() {
        require_once("Teacher.php");
        $this->loadDataFromCourse();
        $teachers = array();
        foreach($this->data->teachers as $teacher) $teachers[] = new Teacher($this->fenixEdu, $teacher);
        return $teachers;
    }
    
    /** Returns an array with the Evaluations of this Course.
     */
    public function getEvaluations() {
        require_once("Evaluation.php");
        $evaluations = array();
        foreach($this->fenixEdu->getCourseEvaluations($this->getId()) as $evaluation) {
            $evaluations[] = new Evaluation($this->fenixEdu, $evaluation);
        }
        return $evaluations;
    }
    
    /** Returns an array with the GroupEnrolments of this Course.
     */
    public function getGroupEnrolments() {
        require_once("GroupEnrolment.php");
        $groupEnrolments = array();
        foreach($this->fenixEdu->getCourseGroups($this->getId()) as $groupEnrolment) {
            $groupEnrolments[] = new GroupEnrolment($this->fenixEdu, $groupEnrolment);
        }
        return $groupEnrolments;
    }
    
    public function getSchedule() {
        //TODO calendar first
    }
    
    /** Returns the number of Students enroled on this Course.
     */
    public function getEnrolmentCount() {
        if($this->students === NULL) $this->students = $this->fenixEdu->getCourseStudents($this->getId());
        return $this->students->enrolmentCount;
    }
    
    /** Returns the number of Students attending on this Course.
     */
    public function getAttendingCount() {
        if($this->students === NULL) $this->students = $this->fenixEdu->getCourseStudents($this->getId());
        return $this->students->attendingCount;
    }
    
    /** Returns an array with the Students of this Course.
     */
    public function getStudents() {
        require_once("Student.php");
        if($this->students === NULL) $this->students = $this->fenixEdu->getCourseStudents($this->getId());
        $students = array();
        foreach($this->students->students as $student) $students[] = new Student($this->fenixEdu, $student);
        return $students;
    }
    
    /** Returns the grade of the user in this Course.
     * Returns <i>NULL</i> if the user has not enroled in this Course.
     */
    public function getGrade() {
        if(property_exists($this->data, 'grade')) return $this->data->grade;
        $this->fenixEdu->login();
        foreach($this->fenixEdu->getPersonCourses($this->data->academicTerm)->enrolments as $course) {
            if(strcmp($course->id, $this->data->id) == 0) {
                $this->data->grade = $course->grade;
                return $this->data->grade;
            }
        }
        foreach($this->fenixEdu->getPersonCurriculum() as $curriculum) {
            foreach ($curriculum->approvedCourses as $course) {
                if(strcmp($course->id, $this->data->id) == 0) {
                    $this->data->grade = $course->grade;
                    return $this->data->grade;
                }
            }
        }
        return NULL;
    }
}
