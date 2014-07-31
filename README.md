# laravel-acs

[![Build Status](https://travis-ci.org/h3r2on/laravel-acs.svg?branch=master)](https://travis-ci.org/h3r2on/laravel-acs)

A package to interface with Appcelerator Cloud Services

## Installation

To install the following to your composer.json

    "h3r2on/acs" : "*"

To set your api key run the following artisan command

    php artisan php config:publish h3r2on/acs

Then navigate to `app/config/packages/h3r2on/acs` and edit the `config.php` file to add your ACS app key.

Setup the provider and aliases: Add the following to `app/config/app.php`
	
	'providers' => array(

	   ...
       'H3r2on\Acs\AcsServiceProvider',

and to the aliases

    'aliases' => array(

        ...
        'Acs'             => 'H3r2on\Acs\Facades\Acs',
    )     

## Usage
