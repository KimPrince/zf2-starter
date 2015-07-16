Starter Application
=======================

Introduction
------------
This is a starting point for applications with complex business logic. It is a PHP MVC skeleton with a
Domain (Domain Model), Data Mapper and Service Layer. It uses Zend Framework 2 however may be ported to
other frameworks quite easily. You are welcome to use it as the foundation for your next application.

Installation
------------

1. Download and extract the files to a working directory.  On *nix systems, the easiest way to do this is:

````
    wget https://github.com/KimPrince/zf2-starter/archive/master.tar.gz
    tar xpvf master.tar.gz
    mv zf2-starter-master my-directory
    rm master.tar.gz
````

2. Run a composer update to get vendor dependencies

````
    cd my-directory
    composer self-update
    composer install
````

3. Point a virtual host to my-directory/public, and browse the host to see the welcome page

Usage
-----

For a detailed explanation of the Starter Application, please see
http://www.kimprince.com/starter/starter-application-v10