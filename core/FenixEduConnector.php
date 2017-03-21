<?php
require_once("RestRequest.php");
require_once("FenixEduException.php");
require_once("TokenHolder.php");
require_once("StateGenerator.php");

class FenixEduConnector {
    private $accessKey;
    private $secretKey;
    private $callbackUrl;
    private $apiBaseUrl;

    private $code;

    private $tokenHolder;
    private $stateGenerator;
    
    //The TokenHolder doesn't necessarily make these attributes redundant!
    //remember: reading from a file or database is a lot slower than reading from RAM
    private $accessToken;
    private $refreshToken;
    private $expirationTime;
    
    public function __construct($config, $tokenHolder, $stateGenerator) {
        $this->tokenHolder = $tokenHolder;
        $this->stateGenerator = $stateGenerator;
        $this->accessKey = $config["access_key"];
        $this->secretKey = $config["secret_key"];
        if($tokenHolder->hasAccessToken()) {
            $this->accessToken = $tokenHolder->getAccessToken();
            $this->refreshToken = $tokenHolder->getRefreshToken();
            $this->expirationTime = $tokenHolder->getTokenExpiry();
            if($this->expirationTime <= time()) $this->refreshAccessToken();
        } else {
            $this->accessToken = NULL;
            $this->refreshToken = NULL;
        }
        if(!isset($config["callback_url"])) throw new FenixEduException(
                "Error creating FenixEduConnector.",
                "The Configuration requires a callback URL!"
                );
        if(!isset($config["api_base_url"])) throw new FenixEduException(
                "Error creating FenixEduConnector.",
                "The Configuration requires the API's URL!"
                );
        $this->callbackUrl = $config["callback_url"];
        $this->apiBaseUrl = $config["api_base_url"];
    }
    
    private function getAccessTokenFromCode($code){
        $reqbody = array( 'client_id' => $this->accessKey,
                'client_secret' => $this->secretKey,
                'redirect_uri' => $this->callbackUrl,
                'code' => $code,
                'grant_type' => 'authorization_code');
        $url = $this->apiBaseUrl . "/oauth/access_token";
        $req = new RestRequest($url, 'POST', $reqbody);
        $req->execute();
        $info = $req->getResponseInfo();
        if($info['http_code'] == 200){
            $json = json_decode($req->getResponseBody());
            $this->accessToken = $json->access_token;
            $this->refreshToken = $json->refresh_token;
            $this->expirationTime = time() + $json->expires_in;
            $this->tokenHolder->setAccessToken($this->accessToken);
            $this->tokenHolder->setRefreshToken($this->refreshToken);
            $this->tokenHolder->setTokenExpiry($this->expirationTime);
            header('Location: ' . $this->callbackUrl);
        } else {
            throw new FenixEduException("Error getting Access Token!",
                            "Connection to API failed or invalid client secret.");
        }
    }

    private function refreshAccessToken() {
        $reqbody = array('client_id' => $this->accessKey,
                'client_secret' => $this->secretKey,
                'refresh_token' => $this->refreshToken);
        $url = $this->apiBaseUrl . "/oauth/refresh_token";
        $req = new RestRequest($url, 'POST', $reqbody);
        $req->execute();
        $info = $req->getResponseInfo();
        $result = json_decode($req->getResponseBody());
        if($info['http_code'] == 200){
            $this->accessToken = $result->access_token;
            $this->expirationTime = time() + $result->expires_in;
            $this->tokenHolder->setAccessToken($this->accessToken);
            $this->tokenHolder->setTokenExpiry($this->expirationTime);
        } elseif($info['http_code'] == 401) {
            //FIXME do proper error handling for cases other than invalid token
            $this->logout();
            $this->login();
            exit();
            //throw new FenixEduException($result->error, $result->errorDescription);
        }
    }

    private function buildURL($endpoint, $public = false) {
        $url = $this->apiBaseUrl . "/api/fenix/v1/" . $endpoint;
        if(!$public){
            if($this->expirationTime <= time()) $this->refreshAccessToken();
            $url .= (strpos($url, '?') === false ? '?' : '&') . 'access_token='. urlencode($this->accessToken);
        }
        return $url;
    }
    
    private function executeRequest($req) {
        $req->execute();
        $result = json_decode($req->getResponseBody());
        $info = $req->getResponseInfo();
        if($info['http_code'] == 401) {
            if(strcmp($result->error, "accessTokenInvalidFormat") == 0) {
                $this->logout();
                $this->login();
                exit();
            } else throw new FenixEduException($result->error, $result->error_description);
        } elseif($info['http_code'] == 200) return $result;
    }
    
    /** Retrieves data from the specified endpoint.
     * The 'public' parameter specifies whether the data is requested
     * without a user's credentials.
     */
    public function get($endpoint) {
        $public = $this->accessToken === NULL;
        $url = $this->buildURL($endpoint, $public);
        $req = new RestRequest($url, 'GET');
        return $this->executeRequest($req);
    }

    /** Sends data to the specified endpoint.
     */
    public function put($endpoint, $data = "") {
        $url = $this->buildURL($endpoint);
        $req = new RestRequest($url, 'POST', $data);
        return $this->executeRequest($req);
    }

    /** Performs the login process.
     * If not yet logged in, the user is presented with the API's login interface.
     * If the user is already logged in, loads his access token.
     */
    public function login() {
        if($this->refreshToken === NULL) {
            if(isset($_GET['error'])) {
                //TODO use the actual error output
                throw new FenixEduException("Error logging in!");
            } else if(isset($_GET['code'])) {
                $state = $this->tokenHolder->getState();
                if(!isset($_GET['state']) || strcmp($_GET['state'], $state) !== 0 || $state === NULL) {
                    throw new FenixEduException("Invalid state. Possible malicious request!");
                }
                $code = $_GET['code'];
                $this->tokenHolder->setState(NULL);
                $this->getAccessTokenFromCode($code);
            } else {
                $this->tokenHolder->setState($this->stateGenerator->generate());
                $params = array('client_id' => $this->accessKey,
                        'redirect_uri' => $this->callbackUrl,
                        'state' => $this->tokenHolder->getState()
                );
                $query = http_build_query($params, '', '&');
                $authorizationUrl = $this->apiBaseUrl . "/oauth/userdialog?" . $query;
                header(sprintf("Location: %s", $authorizationUrl));
                exit();
            }
        }
    }
    
    /** Logs out the user and erases the access token.
     */
    public function logout() {
        $this->tokenHolder->drop();
        $this->accessToken = NULL;
        $this->refreshToken = NULL;
    }
}
