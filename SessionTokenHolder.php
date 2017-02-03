<?php
require_once("core/TokenHolder.php");

/* This TokenHolder uses the PHP $_SESSION to store each user's tokens.
 */
class SessionTokenHolder extends TokenHolder {
	public function __construct() {
		global $_SESSION;
		if(session_id() === "") {
			session_start();
		}
	}
	
	public function hasAccessToken() {
		global $_SESSION;
		return isset($_SESSION['fenix_access_token']);
	}
	
	public function getAccessToken() {
		global $_SESSION;
		return $_SESSION['fenix_access_token'];
	}
	
	public function setAccessToken($token) {
		global $_SESSION;
		$_SESSION['fenix_access_token'] = $token;
	}
	
	public function getRefreshToken() {
		global $_SESSION;
		return $_SESSION['fenix_refresh_token'];
	}
	
	public function setRefreshToken($token) {
		global $_SESSION;
		$_SESSION['fenix_refresh_token'] = $token;
	}
	
	public function getTokenExpiry() {
		global $_SESSION;
		return $_SESSION['fenix_expires'];
	}
	
	public function setTokenExpiry($expiry) {
		global $_SESSION;
		return $_SESSION['fenix_expires'] = $expiry;
	}
	
	public function drop() {
		global $_SESSION;
		unset($_SESSION['fenix_access_token']);
		unset($_SESSION['fenix_refresh_token']);
		unset($_SESSION['fenix_expires']);
	}
}
