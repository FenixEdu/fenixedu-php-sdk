# FenixEdu API PHP SDK

A PHP SDK for the [FenixEdu API](https://fenixedu.org/dev/api/).

## Overview

This SDK allows using the FenixEdu API.

In the core folder are the essencial classes of the SDK. You can use the entire API using only the classes in the core folder.
In order to make your work easier, this SDK provides the FenixEdu class, which acts as a starting point for the SDK, and a FenixEduServices class that already implements the interfaces to use every endpoint in the FenixEdu API.
If you wish to have some domain logic handled for you, so you can access the data you need without having to worry about connections and endpoints, you can use the classes defined in domain. The SDK automatically loads these classes whenever you call a method that would instance them, so you dont even need to clutter your application with requires.

## Getting Started

First register the application as described in this [tutorial](https://fenixedu.org/dev/tutorials/use-fenixedu-api-in-your-application/).
After having your application registered, download this SDK and include FenixEdu.php into your application.
You may then use the API in anonymous mode or call the login() method to force authentication.

The following example shows how to instance FenixEdu:
```
require_once("FenixEdu.php");

//Set the configuration data for your application
//you can get this data from your application page in Fenix
$config = array(
    'access_key' => "################",
    'secret_key' => "#####################################",
    'callback_url' => "https://your_website_url/",
    'api_base_url' => "https://api_url");

$fenixEdu = new FenixEdu($config);
```

This example shows how to get the username using the domain approach:
```
$person = $fenixEdu->getPerson();
$username = $person->getIstId();
```
Notice that when using the domain approach, logging in is done automatically if necessary.
You may still force the user to login at any point you wish when using this approach, for example, to ensure only authenticated users can access your application.

The next example shows how to get the username using the FenixEduServices interface:
```
$services = $fenixEdu->getServices();
$services->login();
$person = $services->getPerson();
$username = $person->username;
```
When using FenixEduServices, all methods that get data from the API's endpoints return generic PHP objects representing the JSON data as it is given by the endpoints.

When you wish to explicitly define when the user must login or logout, these are the ways you may do so:
```
//Login directly from the FenixEdu instance
$fenixEdu->login();

//Logout directly from the FenixEdu instance
$fenixEdu->logout();

//Login from the FenixEduServices instance
$services->login();

//Logout from the FenixEduServices instance
$services->logout();
```
Logging in or out from either instance is identical.
Be aware that in a multi-page application the login process may cause the user to go back to the application's root page.
