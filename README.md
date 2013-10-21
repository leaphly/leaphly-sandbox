Leaphly Sandbox
================

## Help, re-tweet and stay tuned.

We have worked a lot in order to provide this shopping cart.

We have tried to get things decoupled, in order to have a better maintenance and flexibility.

We have used some design patterns, that allow you to give value to the specific domain of your company and products.

There is a lot to do: [@leaphly](http://twitter.com/leaphly).

[![Build Status](https://secure.travis-ci.org/leaphly/leaphly-sandbox.png?branch=master)](http://travis-ci.org/leaphly/leaphly-sandbox)
[![Latest Stable Version](https://poser.pugx.org/leaphly/leaphly-sandbox/v/stable.png)](https://packagist.org/packages/leaphly/leaphly-sandbox)
[![Total Downloads](https://poser.pugx.org/leaphly/leaphly-sandbox/downloads.png)](https://packagist.org/packages/leaphly/leaphly-sandbox)
[![Latest Unstable Version](https://poser.pugx.org/leaphly/leaphly-sandbox/v/unstable.png)](https://packagist.org/packages/leaphly/leaphly-sandbox)

## Welcome

Welcome to the Leaphly Sandbox - a fully-functional Symfony2
application that you can use to play with the leaphly concept.

This document contains information on how to download, install, and start
using Leaphly. For a more detailed explanation, see the [Installation][1]
chapter of the Symfony Documentation.

1) Installing the Standard Edition
----------------------------------

When it comes to installing the Symfony Standard Edition, you have the
following options.

### Use Composer

As Leaphly and Symfony uses [Composer][2] to manage its dependencies, the recommended way
to create a new project is to use it.

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

    curl -s http://getcomposer.org/installer | php

Then, use the `create-project` command to generate a new Leaphly application:

    php composer.phar create-project leaphly/leaphly-sandbox leaphly-sandbox -sdev

Composer will install Symfony and all its dependencies under the
`leaphly-sandbox` directory.


2) Checking your System Configuration
-------------------------------------

Before starting coding, make sure that your local system is properly
configured for Leaphly and Symfony.

Execute the `check.php` script from the command line:

    php app/check.php


3) Choose your Database Driver
------------------------------

Leaphly permits to use orm and odm for storage

### A) - Init Mongodb ODM

    app/console doctrine:mongodb:schema:create --index
    app/console doctrine:mongodb:fixtures:load

### B) - Init ORM - UNSTABLE NOW- try the mongo version please.

    git init
    mv app/AppKernel.orm.php.dist app/AppKernel.php
    mv /app/config/leaphly/dbdriver.orm.yml.dist /app/config/leaphly/dbdriver.yml
    app/console doctrine:schema:update --force
    app/console doctrine:fixtures:load

4) Load fixtures and assets
---------------------------

    app/console fos:js:dump --env=dev
    app/console assets:install web --symlink --env=dev
    app/console assetic:dump --env=dev

5) Create the Virtual Host
--------------------------

Please look at the documentation link form more detail:
[Create the virtual Host][3]

3) Browsing the Demo Application
--------------------------------

Congratulations! You're now ready to use leaphly.

To see a real-live Leaphly page in action, access the following page:

    http://cart.local

What's inside?
---------------

The Leaphly sandbox is configured with the following defaults:

Enjoy!

[1]:  http://doc.leaphly.org/
[2]:  http://getcomposer.org/
[3]:  http://doc.leaphly.org/book/sandbox.html