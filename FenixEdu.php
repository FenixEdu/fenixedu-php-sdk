<?php
require_once("core/FenixEduConnector.php");

/** A wrapper class for the FenixEdu client library.
 */
class FenixEdu {
    private $services;
        
    public function __construct($config, $tokenHolder = NULL, $stateGenerator = NULL) {
        if($tokenHolder === NULL) {
            require_once("SessionTokenHolder.php");
            $tokenHolder = new SessionTokenHolder();
        }
        if($stateGenerator === NULL) {
            require_once("RandomHashStateGenerator.php");
            $stateGenerator = new RandomHashStateGenerator();
        }
        $connector = new FenixEduConnector($config, $tokenHolder, $stateGenerator);
        $this->services = new FenixEduServices($connector);
    }
    
    /** Returns an interface for all of FenixEDU's endpoints.
     */
    public function getServices() {
        return $this->services;
    }
    
    /** Returns the Institution of the API.
     */
    public function getInstitution() {
        require_once("domain/Institution.php");
        return new Institution($this->services);
    }
    
    /** Returns the authenticated user.
     * If no user is logged in, it will prompt authentication.
     */
    public function getPerson() {
        $this->services->login();
    	require_once("domain/Person.php");
    	return new Person($this->services, $this->services->getPerson());
    }
    
    /** Athenticate the user.
     */
    public function login() {
        $this->services->login();
    }
    
    /** Terminate the user's session.
     */
    public function logout() {
        $this->services->logout();
    }
}


/** This class implements all calls to the API's endpoints.
 */
class FenixEduServices {
    private $connector;

    public function __construct($connector) {
        $this->connector = $connector;
    }

    /** Athenticate the user.
     */
    public function login() {
        $this->connector->login();
    }
    
    /** Terminate the user's session.
     */
    public function logout() {
        $this->connector->logout();
    }
    
    /** This endpoint returns some basic information about the institution where
     * the application is deployed. It also returns a list of RSS feeds, the
     * current academic term, available languages and default language.
     */
    public function getAbout() {
        return $this->connector->get("about");
    }

    /** This endpoint returns all the academic terms available to be used in
     * other endpoints as academicTerm query parameter. The returned object
     * keys are not ordered in any particular way.
     */
    public function getAcademicTerms() {
        return $this->connector->get("academicterms");
    }

    /** This endpoint returns the menu information of Alameda's canteen.
     */
    public function getCanteen() {
        return $this->connector->get("canteen");
    }

    /** This endpoint returns the contact information of the institution where
     * the application is deployed.
     */
    public function getContacts() {
        return $this->connector->get("contacts");
    }

    /** A course is a concrete unit of teaching that typically lasts one
     * academic term. This endpoint shows some information regarding a
     * particular course. The same course may be lectured simultaneously in
     * multiple degrees during the same academic term.
     * The "competences" field holds curricular information for each set of
     * degrees in which the course is lectured. Usually this information is the
     * same for all the associated degrees.
     */
    public function getCourse($id) {
        $course = $this->connector->get("courses/" . $id);
        if($course === NULL) return NULL;
        if(!property_exists($course, 'id')) $course->id = $id;
        return $course;
    }

    /** An evaluation is a component of a course in which the teacher determines
     * the extent of the students understanding of the program. Current known
     * implementations of evaluations are: tests, exams, projects, online
     * tests and ad-hoc evaluations.
     */
    public function getCourseEvaluations($id) {
        return $this->connector->get("courses/" . $id . "/evaluations");
    }

    /** Groups are used in courses for a wide range of purposes. The most
     * typical are for creating teams of students for laboratories or projects.
     * Some groups are shared among different courses. The enrolment of student
     * groups may be atomic or individual, and may be restricted to an
     * enrolment period.
     */
    public function getCourseGroups($id) {
        return $this->connector->get("courses/" . $id . "/groups");
    }

    /** Each course is lectured during a specific set of intervals. These
     * intervals make up the lesson period for that course. Each course also
     * has a curricular load that specifies the time each student will expend
     * with the course. Each shift is the possible schedule in which a student
     * should enrol.
     */
    public function getCourseSchedule($id) {
        return $this->connector->get("courses/" . $id . "/schedule");
    }
    /** This endpoint lists all the students attending the specified course. For
     * each student it indicates the corresponding degree. The endpoint also
     * returns the number of students officially enroled in the course.
     */
    public function getCourseStudents($id) {
        return $this->connector->get("courses/" . $id . "/students");
    }

    /** This endpoint returns the information for all degrees. If no
     * academicTerm is defined it returns the degree information for the
     * currentAcademicTerm.
     */
    public function getDegrees($academicTerm = NULL) {
        $query = $academicTerm !== NULL ? "?academicTerm=" . $academicTerm : "";
        return $this->connector->get("degrees" . $query);
    }

    /** This endpoint returns the information for the {id} degree. If no
     * academicTerm is defined it returns the degree information for the
     * currentAcademicTerm.
     */
    public function getDegree($id, $academicTerm = NULL) {
        $query = $academicTerm !== NULL ? "?academicTerm=" . $academicTerm : "";
        $degree = $this->connector->get("degrees/" . $id . $query);
        if($degree === NULL) return NULL;
        if(!property_exists($degree, 'id')) $degree->id = $id;
        return $degree;
    }

    /** This endpoint returns the informations for a degree's courses. If no
     * academicTerm is defined it returns the degree information for the
     * currentAcademicTerm.
     */
    public function getDegreeCourses($id, $academicTerm = NULL) {
        $query = $academicTerm !== NULL ? "?academicTerm=" . $academicTerm : "";
        return $this->connector->get("degrees/" . $id . "/courses" . $query);
    }

    /** This endpoint returns a representation of the domain model for the
     * application. While this information is returned in a JSON format, the
     * concepts underlying the domain model can be found on the Fenix Framework
     * site:
     * <a href="http://fenix-framework.github.io/DML.html">http://fenix-framework.github.io/DML.html</a>
     */
    public function getDomainModel() {
        return $this->connector->get("domainmodel");
    }

    /** This endpoint returns parking information.
     */
    public function getParking() {
        return $this->connector->get("parking");
    }

    /** This endpoint allows to access the current person information.
     */
    public function getPerson() {
        return $this->connector->get("person");
    }

    /** This endpoint returns the user's class information. This information can
     * be retrieved both in iCalendar and JSON formats.
     */
    public function getPersonalCalendarClasses($format = "json") {
        $query = "?format=" . $format;
        return $this->connector->get("person/calendar/classes" . $query);
    }

    /** This endpoint returns the students's evaluations information. This
     * information can be retrieved both in iCalendar and JSON formats.
     */
    public function getPersonalCalendarEvaluations($format = "json") {
        $query = "?format=" . $format;
        return $this->connector->get("person/calendar/evaluations" . $query);
    }

    /** This endpoint returns the user's course information.
     */
    public function getPersonCourses($academicTerm = NULL) {
        $query = $academicTerm !== NULL ? "?academicTerm=" . $academicTerm : "";
        return $this->connector->get("person/courses" . $query);
    }

    /** Complete curriculum (only for students)
     */
    public function getPersonCurriculum() {
        return $this->connector->get("person/curriculum");
    }

    /** This endpoint returns the student's written evaluation information.
     */
    public function getPersonEvaluations() {
        return $this->connector->get("person/evaluations");
    }

    /** This endpoint allows the student to enroll or disenroll from a written
     * evaluation.
     * Set to TRUE to enroll or FALSE to disenroll.
     */
    public function setPersonEvaluationEnrollment($id, $enrol) {
        $query = "?enrol=" . ($enrol ? "yes" : "no");
        return $this->connector->get("person/evaluations/" . $id . $query);
    }

    /** This endpoint returns user's payments information.
     */
    public function getPersonPayments() {
        return $this->connector->get("person/payments");
    }

    /** This endpoint returns the shuttle information.
     */
    public function getShuttle() {
        return $this->connector->get("shuttle");
    }

    /** This endpoint returns the information about the campi.
     */
    public function getSpaces() {
        return $this->connector->get("spaces");
    }

    /** This endpoint returns information about the space for a given {id}, its
     * contained and parent spaces. The {id} can be for any of these types:
     * "CAMPUS", "BUILDING", "FLOOR" or "ROOM".
     */
    public function getSpace($id, $day = NULL) {
        $query = $day !== NULL ? "?day=" . $day : "";
        return $this->connector->get("spaces/" . $id . $query);
    }

    /** This endpoint returns the space's blueprint in the required format.
     */
    public function getSpaceBlueprint($id, $format = "jpeg") {
        $query = "?format=" . $format;
        return $this->connector->get("spaces/" . $id . "/blueprint" . $query);
    }
}
