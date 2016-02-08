<?php

class AsiakasController extends BaseController{
    
    public static function browse(){
        $asiakkaat = Asiakas::all();
        View::make('statichtml/browse.html', array ('asiakkaat' => $asiakkaat));
    }
    
    public static function store(){
        $params = $_POST;
        
        
        $asiakas = new Asiakas(array(
                'nimimerkki' => $params['nimimerkki'],
                'salasana' => $params['salasana'],
                'email' => $params['email'],
                'syntymapaiva' => $params['syntymapaiva'],
                'sukupuoli' => $params['sukupuoli'],
                'paikkakunta' => $params['paikkakunta']
            ));
        
        
        $asiakas->save();
        
        Redirect::to('/browse', array('message' => 'Käyttäjä luotu!'));
    }
    
    public static function signup() {
        View::make('signup.html');
    }
}
