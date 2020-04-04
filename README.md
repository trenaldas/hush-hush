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

Use Composer to install Hush-Huhs to your Laracel project

    composer require trenaldas/hush-hush

After installing Hush-Hush Composer package, use command below:

    php artisan hush-hush:install

This will publish config file to `config/hush-hush.php` and will create empty `hush-hush.yml` file in your root directory.

Usage
=====

#### For database login details

Use command:
    
    php artisan hush-hush:database

#### For any other secret to store

Use command: 
    
    php artisan hush-hush:create_secret

#### .yml file example

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
          