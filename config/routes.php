<?php

$routes->get('/', function() {
    HelloWorldController::index();
});


$routes->get('/login', function() {
    HelloWorldController::login();
});

$routes->get('/signup', function() {
    HelloWorldController::signup();
});

$routes->get('/browse', function() {
    HelloWorldController::browse();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});
