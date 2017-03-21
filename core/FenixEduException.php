<?php

class FenixEduException extends Exception {

    private $error;
    private $errorDescription;

    public function __construct($error, $errorDescription = "") {
        $this->error = $error;
        $this->errorDescription = $errorDescription;
    }

    public function getError() {
        return $this->error;
    }

    public function getErrorDescription() {
        return $this->errorDescription;
    }
}
