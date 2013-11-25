<?php

error_reporting(E_ALL);

if (!function_exists('curl_init')) {
  throw new Exception('Abstract OAuth Client needs the CURL PHP extension.');
}

if (!function_exists('json_decode')) {
  throw new Exception('Abstract OAuth Client needs the JSON PHP extension.');
}

class FenixEduException extends Exception {

	private $error;
	private $errorDescription;

	public function __construct($result) {
		$this->error = $result->{'error'};
		$this->errorDescription = $result->{'error_description'};
	}

	public function getError() {
		return $this->error;
	}

	public function getErrorDescription() {
		return $this->errorDescription;
	}

}

abstract class AbstractOAuthClient {

	private $accessKey;
	private $secretKey;

	private $user;

	private $code;

	private $accessToken;
	private $refreshToken;

	// WE DON'T WANT OUR SESSION SCOPE TO HOLD TRASH, AND WE WANT TO CLEAN EVERYTHING
	private static $SESSION_KEYS = array("code", "access_token", "refresh_token", "user");

	protected function __construct($config) {
		if(!session_id()) {
			session_start();
		}
		$this->accessKey = $config["access_key"];
		$this->secretKey = $config["secret_key"];
		$this->accessToken = isset($config["access_token"]) ? $config["access_token"] : null;
		$this->refreshToken = isset($config["refresh_token"]) ? $config["access_token"] : null;
		$this->callbackUrl = isset($config["callback_url"]) ? $config["callback_url"] : $this->getCurrentUrl();
		$this->apiBaseUrl = isset($config["api_base_url"]) ? $config["api_base_url"] : "http://fenix.ist.utl.pt";
	}

	public function getAccessKey() {
		return $this->accessKey;
	}

	public function getSecretKey() {
		return $this->secretKey;
	}

	public function getApiBaseUrl() {
		return $this->apiBaseUrl;
	}

	public function getCallbackUrl() {
		return $this->callbackUrl;
	}

	public function getAccessToken() {
		return $this->accessToken;
	}

	public function getRefreshToken() {
		return $this->refreshToken;
	}

	private function getCode() {
		if($this->code == null) {
			$this->code = $this->getSessionData('code', null);
		}
		return $this->code;
	}

	private function getCodeFromQueryParam() {
		if (isset($_GET['code'])) {
			$code = $_GET['code'];
			$this->code = $code;
			$this->setSessionData('code', $code);
			return $code;
		} else {
			self::error('Code was not found in query param.');
		}
		return false;
	}

	/**
	 * Returns the current user. If the user has not been fetched yet,
	 * the method attempts to fetch him from the available data.
	 **/
	public function getUser() {
		if ($this->user == null) {
			$this->user = $this->getUserFromAvailableData();
		}
		return $this->user;
	}

	/**
	 * Attempts to fetch user information from available data:
	 * - Cached Value
	 * - Access Token
	 * - Code
	 **/
	private function getUserFromAvailableData() {
		$user = $this->getSessionData("user", $default = null);
		if ($user) {
			$this->user = $user;
			return $user;
		} else {
			$accessToken = $this->getAccessToken();
			if ($accessToken) {
				$user = $this->getUserFromAccessToken();
				if ($user) {
					$this->setSessionData("user", $user);
				}
				return $user;
			} else {
				$code = $this->getCode();
				if (!$code) {
					$code = $this->getCodeFromQueryParam();
				}
				$this->setSessionData('code', $code);
				$this->getAccessTokenFromCode($code);
				$user = $this->getUserFromAccessToken();
				if ($user) {
					$this->setSessionData("user", $user);
					return $user;
				} else {
					self::error("Cannot retrieve user from available data");
				}
			}
		}
	}

	private function getUserFromAccessToken() {
		$user = $this->get("person");
		$this->user = $user;
		$this->setSessionData('user', $user);
		return $user;
	}

	protected function get($endpoint, $params = array()) {
		$url = $this->getApiBaseUrl() . "/api/v1/" . $endpoint;
		$apiParams = array(
			'access_token'	=> $this->getAccessToken());

		$result = $this->makeRequest($url, array_merge($apiParams, $params));
		return $result;
	}

	private function getUserAccessToken() {
		$code = $this->getCode();
		if($code && $code != $this->getSessionData('code', null)) {
			$accessToken = $this->getAccessTokenFromCode($code);
			if($accessToken) {
				$this->setSessionData('access_token', $accessToken);
				return $accessToken;
			} else {
				$this->deleteAllSessionData();
				return false;
			}
		} else {
			return $this->getSessionData('access_token');
		}
	}

	private function getAccessTokenFromCode($code) {
		if(empty($code)) {
			return false;
		}

		$endpoint = $this->getApiBaseUrl() . "/oauth/access_token";

		$params = array(
			'client_id'		=> $this->getAccessKey(),
			'client_secret'	=> $this->getSecretKey(),
			'code'			=> $code,
			'redirect_uri'	=> $this->getCallbackUrl(),
			'grant_type'	=> 'authorization_code');

		try {
			$result = $this->makeRequest($endpoint, $params);
			$json = json_decode($result);
			if(array_key_exists("error", $json)) {
				throw new FenixEduException($json);
			}
			$accessToken = $json->{'access_token'};
			$refreshToken = $json->{'refresh_token'};
			$this->setAccessToken($accessToken);
			$this->setSessionData('access_token', $accessToken);
			$this->setRefreshToken($refreshToken);
			$this->setSessionData('refresh_token', $refreshToken);
			return $accessToken;
		} catch(FenixEduException $e) {
			return false;
		}
		return false;
	}

	private function setSessionData($key, $value) {
		if(!in_array($key, self::$SESSION_KEYS)) {
			self::error("Unsupported key to save in session data");
			return;
		}
		$var_name = $this->constructSessionVariableName($key);
    	$_SESSION[$var_name] = $value;
	}

	private function getSessionData($key, $default = false) {
		if(!in_array($key, self::$SESSION_KEYS)) {
			self::error("Unsupported key to fetch from session data");
			return;
		}
		$var_name = $this->constructSessionVariableName($key);
		return isset($_SESSION[$var_name]) ? $_SESSION[$var_name] : $default;
	}

	private function deleteSessionData($key) {
		if(!in_array($key, self::$SESSION_KEYS)) {
			self::error("Unsupported key to delete from session data");
			return;
		}
		$var_name = $this->constructSessionVariableName($key);
		if(isset($_SESSION[$var_name])) {
			unset($_SESSION[$var_name]);
		}
	}

	private function deleteAllSessionData() {
		foreach(self::$SESSION_KEYS as $key) {
			$this->deleteSessionData($key);
		}
	}

	public function clean() {
		$this->deleteAllSessionData();
	}

	private static function error($msg) {
		//DO STUFF WITH MSG
	}

	private function constructSessionVariableName($key) {
		$parts = array('fb', $this->getAccessKey(), $key);
		return implode('_', $parts);
	}

	function getAuthorizationUrl() {
		$params = array(
			'client_id'	=> $this->getAccessKey(),
			'redirect_uri' => $this->getCallbackUrl()
		);
		$query = http_build_query($params, '', '&');
		return $this->getApiBaseUrl() . "/oauth/userdialog?" . $query;
	}

	public function setAccessToken($accessToken) {
		$this->accessToken = $accessToken;
		return $this;
	}

	public function setRefreshToken($refreshToken) {
		$this->refreshToken = $refreshToken;
		return $this;
	}

	private function makeRequest($url, $params, $ch = null) {
		if (!$ch) {
			$ch = curl_init();
		}

		$opts = self::$CURL_OPTS;
		$opts[CURLOPT_POSTFIELDS] = http_build_query($params, '', '&');

		$opts[CURLOPT_URL] = $url;

		curl_setopt_array($ch, $opts);
		$result = curl_exec($ch);
		$errno = curl_errno($ch);

		if($errno) {
			throw new FenixEduException(json_encode(array(
				"error"	=> "Curl Error",
				"errorDescription"	=> $result)));
		}
		return $result;
	}

	private static $CURL_OPTS = array(
		CURLOPT_CONNECTTIMEOUT	=> 10,
		CURLOPT_RETURNTRANSFER	=> true,
		CURLOPT_TIMEOUT 		=> 60
	);

}

?>