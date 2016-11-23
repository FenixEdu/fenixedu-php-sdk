# FenixEdu API PHP SDK

The PHP SDK for the FenixEdu project.

## Setup

Before you start using the SDK, you need to setup the fenixedu.config.php file with your application's credentials.

Just copy the sample provided in fenixedu.config.php.sample to fenixedu.config.php and fill it with your credentials.

## Usage

```php
# Include the SDK
require_once("FenixEdu.class.php");

# Get the singleton instance
$sdk = FenixEdu::getSingleton();

# Get the authentication URL and redirect the user to the login prompt
$authURL = $sdk->getAuthURL();
header('Location: '.$authURL);

# When the user is redirected back to your redirect_uri, get the code and exchange it for the OAuth tokens
$token = $sdk->getAccessTokenFromCode($code);

# You can now make public and private API calls like:
$person = $sdk->getPerson()
$about = $sdk->getAboutInfo()

```
