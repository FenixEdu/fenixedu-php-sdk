<?php
require_once("core/TokenHolder.php");

/* This TokenHolder uses the PHP $_SESSION to store each user's tokens.
 */
class SessionTokenHolder extends TokenHolder {
    public function __construct() {
        if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    }
    
    public function hasAccessToken() {
        return isset($_SESSION['fenix_access_token']);
    }
    
    public function getAccessToken() {
        return $_SESSION['fenix_access_token'];
    }
    
    public function setAccessToken($token) {
        $_SESSION['fenix_access_token'] = $token;
    }
    
    public function getRefreshToken() {
        return $_SESSION['fenix_refresh_token'];
    }
    
    public function setRefreshToken($token) {
        $_SESSION['fenix_refresh_token'] = $token;
    }
    
    public function getTokenExpiry() {
        return $_SESSION['fenix_expires'];
    }
    
    public function setTokenExpiry($expiry) {
        return $_SESSION['fenix_expires'] = $expiry;
    }
    
    public function drop() {
        unset($_SESSION['fenix_access_token']);
        unset($_SESSION['fenix_refresh_token']);
        unset($_SESSION['fenix_expires']);
    }
}
