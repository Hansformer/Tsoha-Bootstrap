<?php

function check_logged_in() {
    BaseController::check_logged_in();
}

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/', function() {
    MainController::index();
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

$routes->get('/browse', 'check_logged_in', function() {
    AsiakasController::browse();
});

$routes->get('/profile', 'check_logged_in', function() {
    AsiakasController::viewProfile();
});

$routes->post('/profiledeletion', 'check_logged_in', function() {
    AsiakasController::deleteProfile();
});

$routes->get('/profile/edit', 'check_logged_in', function() {
    AsiakasController::editProfile();
});

$routes->post('/profile/edit', 'check_logged_in', function() {
    AsiakasController::updateProfile();
});

$routes->post('/logout', function() {
    AsiakasController::logout();
});


// Viesti routes

$routes->get('/messages', function() {
    ViestiController::allMessages();
});