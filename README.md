Hush-Hush For Laravel
=======================

[![Latest Stable Version](https://poser.pugx.org/trenaldas/hush-hush/v/stable)](https://packagist.org/packages/trenaldas/hush-hush)
[![Total Downloads](https://poser.pugx.org/trenaldas/hush-hush/downloads)](https://packagist.org/packages/trenaldas/hush-hush)
[![Latest Unstable Version](https://poser.pugx.org/trenaldas/hush-hush/v/unstable)](https://packagist.org/packages/trenaldas/hush-hush)
[![License](https://poser.pugx.org/trenaldas/hush-hush/license)](https://packagist.org/packages/trenaldas/hush-hush)
[![Monthly Downloads](https://poser.pugx.org/trenaldas/hush-hush/d/monthly)](https://packagist.org/packages/trenaldas/hush-hush)

Composer package to help with AWS Secrets Manager service secrets (hush-hushes). This package will automatically
update your database credentials from AWS Secrets Manager secret.

Installation
============

Use Composer to install Hush-Huhs to your Laravel project

    composer require trenaldas/hush-hush

After installing Hush-Hush Composer package, use command below:

    php artisan hush-hush:install

This will publish config file to `config/hush-hush.php` and will create empty `hush-hush.yml`
file in your root directory.


Config File
===========

Config file has three main configs:

    environments
    exception_throw
    every_request

'environment' config describes the environments you want your secrets to be retrieved at.
It's set to have two most common environments by default: `staging` and `production`.
You should amend these accordingly with your project environment names
(best do it before running database and secret commands).

'exception_throw' config is set to false by default. This will log errors in case of any failure
(fore example while running composer install in the environment that does not have access to AWS SM)

'every_request' config is set to true by default. Calls SM with every request, if set to false - checks
if connection is okay, and if not, calls SM to update db credentials.

Usage
=====

#### For database login details

Use command:

    php artisan hush-hush:database

#### For any other secret to store

Use command:

    php artisan hush-hush:create-secret

All secrets created can be manually edited, deleted or added in `hush-hush.yml` file.


#### To get secrets anywhere in your code

Use class HushHush function `uncover('localSecretName')` to get secret.


.yml file example
=================

```yaml
database:
  name: mysql
  environemts:
    local: hush-hush-secret-local
    staging: hush-hush-secret-staging
    production: hush-hush-secret-production
secrets:
  local_secret_name:
    local_name: app-secret
    environments:
      local: hush-hush-local
      staging: hush-hush-staging
      production: hush-hush-production
  local_super_secret_name:
    local_name: my-api-login
    environments:
      local: hush-hush-super-local
      staging: hush-hush-super-staging
      production: hush-hush-super-production       
```

## Authors

* **Renaldas Tauras** - [trenaldas](https://github.com/trenaldas)

```
  _   _           _           _   _           _
 | | | |_   _ ___| |__       | | | |_   _ ___| |__
 | |_| | | | / __| '_ \ _____| |_| | | | / __| '_ \
 |  _  | |_| \__ \ | | |_____|  _  | |_| \__ \ | | |
 |_| |_|\__,_|___/_| |_|     |_| |_|\__,_|___/_| |_|
```
