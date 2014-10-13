# laravel-acs

[![Build Status](https://travis-ci.org/h3r2on/laravel-acs.svg?branch=master)](https://travis-ci.org/h3r2on/laravel-acs)

A package to interface with Appcelerator Cloud Services

## Installation

To install the following to your composer.json

    "h3r2on/acs" : "*"

Add the service provider and alias to app/config/app.php:

	'H3r2on\Acs\AcsServiceProvider',

  'Acs' => 'H3r2on\Acs\Facades\Acs',

To publish the configuration file you'll have to:

   artisan config:publish h3r2on/acs


## Basic Usage

In your contoller or route:

    $result = Acs::get('user/search.json');

    //do something with the returned object

### Using Authenticated API's or as an Authentication provider for ACS Users

To use API's that require user authentication you will need to use a third party authentication provider. I've chosen [Sentry](https://cartalyst.com/manual/sentry), if you'd like to use this and use a different Auth manager create a issue.

TODO: Complete usage
