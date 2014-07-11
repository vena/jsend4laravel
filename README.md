# JSend4Laravel

This is just a simple package to add a few macros to [Laravel 4](http://laravel.com)'s
`Response` class and extend its json method for JSend-formatted responses.

* [JSend Specification](http://labs.omniti.com/labs/jsend)

## Installation

### Load the package with composer

Add the package in the `require` key of your `composer.json`:

    "vena/jsend4laravel": "dev-master"

Update composer to grab the package:

    composer update

Add the service provider to the end of your `providers` array in `app/config/app.php`:

    'providers' => array(
        ...
        'Vena\JSend4Laravel\JSend4LaravelServiceProvider',
    );

That's it, no further configuration is necessary.

## Usage

All macros return a `Response` object, allowing you to override anything about the response 
you require. Default HTTP status codes are included and everything eventually goes through 
the `Response::json()` method to set the appropriate Content-Type header, but what you do 
with the `Response` object is up to you.

### Response::jsend($data, $httpStatus, $headers)

Simply a wrapper around Response::json(). The response will be sent as JSONP if a 'callback' 
value is specified in the request input.

It's not especially useful to call this by itself, as it will not format the data whatsoever.
However, all other JSend4Laravel methods ultimate route through this.

* `$data`:       Data to include in the response, defaults to NULL.
* `$httpStatus`: HTTP status code for the response, defaults to 200.
* `$headers`:    Response headers for the response. JSON responses are always sent
                 with Content-Type: application/json


### Response::jsendSuccess($data, $httpStatus, $headers)

Formats a JSend **success** response with the supplied data. All parameters are
optional.

* `$data`:       Data to include in the response, defaults to NULL.
* `$httpStatus`: HTTP status code for the response, defaults to 200.
* `$headers`:    Response headers for the response.


### Response::jsendFail($data, $httpStatus, $headers)

Formats a JSend **fail** response. All parameters are optional.

* `$data`:       Data to include in the response, defaults to NULL.
* `$httpStatus`: HTTP status code for the response, defaults to 400.
* `$headers`:    Response headers for the response.


### Response::jsendError($message, $code, $data, $httpStatus, $headers)

Formats a JSend error response with the supplied data.

* `$message`: **Required**. A meangingful, human-readable message explaining the error.

All other parameters are optional:

* `$code`:       A numeric reference code for the error, if applicable.
* `$data`:       Additional error data to send with the request (stack traces, other
                 debug info). Not included in response if set to NULL (default).
* `$httpStatus`: HTTP status code for the response, defaults to 400.
* `$headers`:    Response headers for the response.