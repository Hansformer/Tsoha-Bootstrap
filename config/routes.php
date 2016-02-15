<?php

function check_logged_in() {
    BaseController::check_logged_in();
}

$routes->get('/', function() {
    HelloWorldController::index();
});

$routes->get('/login', function() {
    AsiakasController::login();
});

$routes->post('/login', function() {
    AsiakasController::handleLogin();
});

$routes->get('/signup', function() {
    AsiakasController::signup();
});

$routes->post('/signup', function() {
    AsiakasController::store();
});

$routes->get('/browse', function() {
    AsiakasController::browse();
});

$routes->get('/profile/:id', 'check_logged_in', function($id) {
    AsiakasController::viewProfile($id);
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});
