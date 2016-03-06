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

$routes->get('/browse/:asiakasid', 'check_logged_in', function($asiakasid) {
AsiakasController::showProfile($asiakasid); 
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

$routes->get('/messages', 'check_logged_in', function() {
    ViestiController::allMessages();
});

$routes->get('/messages/:viestiid', 'check_logged_in', function($viestiid){
    ViestiController::showMessage($viestiid);
});


$routes->get('/messages/newmessage', 'check_logged_in', function() {
    ViestiController::newMessage();
});

$routes->post('/messages/newmessage', 'check_logged_in', function() {
    ViestiController::store();
});

$routes->get('/messages/editmessage/:viestiid', 'check_logged_in', function($viestiid) {
    ViestiController::editMessage($viestiid);
});

$routes->post('/messages/updatemessage', 'check_logged_in', function() {
    ViestiController::updateMessage();
});


// esittelysivucontroller

$routes->get('/profile/bio', 'check_logged_in', function() {
    EsittelysivuController::ownBio();
});

$routes->get('/browse/bio/:asiakasid', 'check_logged_in', function($asiakasid) {
    EsittelysivuController::showBio($asiakasid);
});

$routes->get('profile/editbio', 'check_logged_in', function() {
    EsittelysivuController::editBio();
});

$routes->post('profile/updatebio', 'check_logged_in', function() {
    EsittelysivuController::updateBio();
});
