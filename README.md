Hush-Hush For Laravel
=======================

[![Latest Stable Version](https://poser.pugx.org/trenaldas/hush-hush/v/stable)](https://packagist.org/packages/trenaldas/hush-hush) 
[![Total Downloads](https://poser.pugx.org/trenaldas/hush-hush/downloads)](https://packagist.org/packages/trenaldas/hush-hush) 
[![Latest Unstable Version](https://poser.pugx.org/trenaldas/hush-hush/v/unstable)](https://packagist.org/packages/trenaldas/hush-hush)
[![License](https://poser.pugx.org/trenaldas/hush-hush/license)](https://packagist.org/packages/trenaldas/hush-hush)
[![Monthly Downloads](https://poser.pugx.org/trenaldas/hush-hush/d/monthly)](https://packagist.org/packages/trenaldas/hush-hush)

Package to help with AWS Secrets Manager


Requirements
============

* PHP >= TBA

Installation
============

    php artisan hush-hush:install
This will publish config file to config/hushhush.php


Usage
=====

#### For database login details

To use login details from AWS secret manager create following variables in your .env file:
    
    HH_DB_CONNECTION = 
    HH_DB_SECRET = 
    
HH_DB_CONNECTION - the connection you want to use the secret for (mysql, sqlite...)

HH_DB_SECRET - AWS secret name

#### For any other secret to store

In order to retrieve and store secret for future use you have to insert it to config file config/hushhush.php


.yml file example

    database:
      name: mysql
      environemts:
          local: hush-hush-secret-local
          staging: hush-hush-secret-staging
          production: hush-hush-secret-production
    secrets:
      secret_name:
        local_name: app-secret
        environments:
          local: hush-hush-local
          staging: hush-hush-staging
          production: hush-hush-production
      super_secret_name:
        local_name: my-api-login
        environments:
          local: hush-hush-super-local
          staging: hush-hush-super-staging
          production: hush-hush-super-production
