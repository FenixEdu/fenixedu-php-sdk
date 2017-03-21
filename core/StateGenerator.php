<?php

/** This class defines the public interface of all StateGenerator implementations.
 */
abstract class StateGenerator {
    
    /** Generates a state string
     */
    abstract public function generate();    
}