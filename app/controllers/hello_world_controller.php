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
        
        
         $asiakas2 = new Asiakas(array('nimimerkki' => 'as', 'salasana' => '1234', 'email' => 'a@a.fi', 'syntymapaiva' => '1992-01-01', 'sukupuoli' => true, 'paikkakunta' => 'Bönde', 'yllapitaja' => false, 'paritele' => false));
         $errors = $asiakas2->errors();
         
         Kint::dump($errors);
    }

}
