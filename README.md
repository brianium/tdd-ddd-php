Test Driven PHP With PHPUnit
============================

This is a simple PHP application that was built using TDD and the principles of Domain Driven Design.

Description of the application
------------------------------
This application is an exciting console app where a lone fisherman on a lone pond catches fish on every third cast.

The fish are persisted in the database. The fisherman plays for keeps, so everytime the fisherman catches a fish, it is
deleted from the database. A domain service is in charge of stocking the pond with fish when the application starts.

Domain
------
The domain holds the fish entity, the fish repository interface, the service responsible for stocking the pond, and the value objects - that is our lone fisherman and pond.

Infrastructure
--------------
The infrastructure layer contains our fish repository implementation and a Vendors directory that contains the Doctrine2 library.

Test
----
The test directory is broken up into two groups: unit tests and integration tests. 

The unit tests were used to drive the design for everything in the Domain layer. These tests make heavy use of PHPUnit mock objects and assertions.

The integration tests test the implementation of the FishRepository. These tests make use of the PHPUnit database extension to setup tables and actual rows to test against.


app.php
-------
Rather than use a full blown presentation layer, this application has one presentation, and it is represented by the app.php file. This is the application that can be run using the command:
    php app.php

Running the tests
-----------------
In order to run all the tests you will need mysql and phpunit installed. The tests assume a database called "letsgofishing", but you can name it something else if you update the appropriate config files. 

If you plan on running the app before the tests, you will also need to create a table called "fish". If you run the tests first, the table will be created for you.

To run the tests you will need to invoke the test runner with the bootstrap file and the configuration file:
    phpunit --bootstrap Test/bootstrap.php --configuration Test/phpunit.xml Test/