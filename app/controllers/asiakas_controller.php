<?php

class AsiakasController extends BaseController {

    public static function browse() {
        $asiakkaat = Asiakas::all();
        View::make('statichtml/browse.html', array('asiakkaat' => $asiakkaat));
    }

    public static function viewProfile($id) {
        $asiakas = Asiakas::findByID($asiakasid);

        View::make('profile.html', array('nimimerkki' => $asiakas));
        
    }

    public static function store() {
        $params = $_POST;

        $attributes = array('nimimerkki' => $params['nimimerkki'],
            'salasana' => $params['salasana'],
            'email' => $params['email'],
            'syntymapaiva' => $params['syntymapaiva'],
            'sukupuoli' => $params['sukupuoli'],
            'paikkakunta' => $params['paikkakunta']
        );

        $asiakas = new Asiakas($attributes);
        $errors = $asiakas->errors();

        if (count($errors) == 0) {
            $asiakas->save();
            Redirect::to('/browse', array('message' => 'Käyttäjä luotu!'));
        } else {
            Redirect::to('/browse', array('message' => 'Virhe käyttäjää luodessa!'));
        }
    }

    public static function destroy($asiakasid) {
        $asiakas = new Asiakas(array('asiakasid' => $asiakasid));
        $asiakas->destroy();
    }

    public static function edit($asiakasid) {
        $Asiakas = Asiakas::findByID($asiakasid);
        View::make('editprofile.html', array('attributes' => $Asiakas));
        Redirect::to('browse.html', array('message' => 'Muokattu jees'));
    }

    public static function update($asiakasid) {
        $params = $_POST;

        $attributes = array(
            'asiakasid' => $asiakasid,
            'nimimerkki' => $params['nimimerkki'],
            'salasana' => $params['salasana'],
            'email' => $params['email'],
            'syntymapaiva' => $params['syntymapaiva'],
            'sukupuoli' => $params['sukupuoli'],
            'paikkakunta' => $params['paikkakunta']
        );

        $asiakas = new Asiakas($attributes);
        $errors = $asiakas->errors();

        if (count($errors) > 0) {
            View::make('/editprofile.html', array('errors' => $errors, 'attributes' => $attributes));
        } else {
            Redirect::to('/browse' . $asiakas->asiakasid, array('message' => 'Profiilia on muokattu onnistuneesti!'));
        }
    }

    public static function signup() {
        View::make('signup.html');
    }

    public static function login() {
        View::make('login.html');
    }

    public static function handleLogin() {
        $params = $_POST;

        $asiakas = Asiakas::authenticate($params['nimimerkki'], $params['salasana']);

        if (!$asiakas) {
            View::make('login.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'nimimerkki' => $params['nimimerkki']));
        } else {
            $_SESSION['asiakas'] = $asiakas->asiakasid;

            Redirect::to('/', array('message' => 'Tervetuloa ' . $asiakas->nimimerkki . '!'));
        }
    }

    public static function getLoggedIn() {
        self::check_logged_in();
        $asiakas = self::get_user_logged_in();
        
        View::make('profile.html', array('nimimerkki' => $asiakas));
    }
}
