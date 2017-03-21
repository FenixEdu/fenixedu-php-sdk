<?php
require_once("core/StateGenerator.php");
class RandomHashStateGenerator extends StateGenerator {
    public function generate() {
        return hash("md5", openssl_random_pseudo_bytes(rand(32, 128)), false);
    }
}