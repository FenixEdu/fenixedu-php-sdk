<?php

/** This class defines the public interface of all TokenHolder implementations.
 */
abstract class TokenHolder {
    
    /** Checks if the TokenHolder has an access token.
     * It does not check whether the access token is still valid.
     */
    abstract public function hasAccessToken();
    
    /** Gets the stored access token.
     */
    abstract public function getAccessToken();
    
    /** Stores a new access token.
     * If the TokenHolder already had an access token, the old one is discarded.
     */
    abstract public function setAccessToken($token);
    
    /** Gets the stored refresh token.
     */
    abstract public function getRefreshToken();
    
    /** Stores a new refresh token.
     * If the TokenHolder already had a refresh token, the old one is discarded.
     */
    abstract public function setRefreshToken($token);
    
    /** Gets the stored token expiration time.
     */
    abstract public function getTokenExpiry();
    
    /** Stores a new token expiration time.
     */
    abstract public function setTokenExpiry($expiry);
    
    /** Destroys the data stored in this TokenHolder.
     */
    abstract public function drop();
}
