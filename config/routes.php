<?php

$routes->get('/', function() {
    HelloWorldController::index();
});


$routes->get('/login', function() {
    HelloWorldController::login();
});

$routes->get('/signup', function() {
    AsiakasController::signup();
});

$routes->post('/signup', function(){
    AsiakasController::store();
});

$routes->get('/browse', function() {
    AsiakasController::browse();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});
