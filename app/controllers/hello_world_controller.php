<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('statichtml/index.html');
    }
    
    public static function login() {
        View::make('statichtml/login.html');
    }
    
    public static function signup() {
        View::make('statichtml/signup.html');
    }
    
    public static function browse() {
        View::make('statichtml/browse.html');
    }

    public static function sandbox() {
        // Testaa koodiasi täällä
        $asiakas = Asiakas::findByID(1);
        $asiakkaat = Asiakas::all();
        $viesti = Viesti::findByID(1);
        $viestit = Viesti::all();
        
        Kint::dump($asiakkaat);
        Kint::dump($asiakas);
        Kint::dump($viestit);
        Kint::dump($viesti);
    }

}
