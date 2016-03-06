<?php

class AsiakasController extends BaseController {

    public static function browse() {
        $asiakkaat = Asiakas::all();
        View::make('asiakasviews/browse.html', array('asiakkaat' => $asiakkaat));
    }

    public static function viewProfile() {
        $asiakas = self::get_user_logged_in();

        View::make('asiakasviews/profile.html', array('asiakas' => $asiakas));
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
            Redirect::to('/login', array('message' => 'Käyttäjä luotu!'));
        } else {
            Redirect::to('/signup', array('errors' => $errors, 'attributes' => $attributes));
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
            View::make('asiakasviews/editprofile.html', array('errors' => $errors, 'attributes' => $attributes));
        } else {
            Redirect::to('/profile' . $asiakas->asiakasid, array('message' => 'Profiilia on muokattu onnistuneesti!'));
        }
    }

    public static function signup() {
        View::make('asiakasviews/signup.html');
    }

    public static function login() {
        View::make('asiakasviews/login.html');
    }

    public static function handleLogin() {
        $params = $_POST;

        $asiakas = Asiakas::authenticate($params['nimimerkki'], $params['salasana']);

        if (!$asiakas) {
            View::make('asiakasviews/login.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'nimimerkki' => $params['nimimerkki']));
        } else {
            $_SESSION['asiakasid'] = $asiakas->asiakasid;

            Redirect::to('/', array('message' => 'Tervetuloa ' . $asiakas->nimimerkki . '!'));
        }
    }

    public static function getLoggedIn() {
        self::check_logged_in();
        $asiakas = self::get_user_logged_in();

        View::make('asiakasviews/profile.html', array('nimimerkki' => $asiakas));
    }

    public static function deleteProfile() {
        self::check_logged_in();
        $asiakas = Asiakas::findByID($_SESSION['asiakasid']);
        $asiakas->destroy();

        Redirect::to('/', array('message' => 'Profiilisi poistettu!'));
    }

    public static function editProfile() {
        $asiakas = Asiakas::findByID($_SESSION['asiakasid']);
        View::make('asiakasviews/editprofile.html', array('asiakas' => $asiakas));
    }

    public static function updateProfile() {
        $params = $_POST;
        if ($params['action'] == 'tallenna') {
            // self::saveProfileChanges($params);

            $asiakas = Asiakas::findByID($_SESSION['asiakasid']);
            $asiakas->salasana = $params['salasana'];
            $asiakas->email = $params['email'];
            $asiakas->syntymapaiva = $params['syntymapaiva'];
            $asiakas->paikkakunta = $params['paikkakunta'];
            $asiakas->sukupuoli = $params['sukupuoli'];

            $errors = $asiakas->errors();
            
            if (in_array('Nimimerkki on jo olemassa', $errors) && count($errors) == 1) {
                $asiakas->updateProfileInformation();
                Redirect::to('/profile', array('message' => 'Asiakastiedot on päivitetty.'));
            }
            
            if (count($errors) > 0) {
                View::make('asiakasviews/editprofile.html', array('errors' => $errors, 'asiakas' => $asiakas));
            } else {
                $asiakas->updateProfileInformation();
                Redirect::to('/profile', array('message' => 'Asiakastiedot on päivitetty.'));
            }
        } else {
            $asiakas = Asiakas::findByID($_SESSION['asiakasid']);
            $asiakas->destroy();
            self::logout();
        }
    }

    public static function saveProfileChanges($params) {
        $attributes = array(
            'asiakasid' => $_SESSION['asiakasid'],
            'salasana' => $params['salasana'],
            'email' => $params['email'],
            'syntymapaiva' => $params['syntymapaiva'],
            'sukupuoli' => $params['sukupuoli'],
            'paikkakunta' => $params['paikkakunta']
        );
        $asiakas = new Asiakas($attributes);
        $errors = $asiakas->errors();
        if (count($errors) > 0) {
            View::make('asiakasviews/editprofile.html', array('errors' => $errors, 'asiakas' => $asiakas));
        } else {
            $asiakas->update();
            Redirect::to('/profile', array('message' => 'Asiakastiedot on päivitetty.'));
        }
    }
    
    public static function showProfile($asiakasid) {
        $profileToShow = Asiakas::findByID($asiakasid);
        
        View::make('asiakasviews/showprofile.html', array('asiakas' => $profileToShow));
    }

    public static function logout() {
        $_SESSION['asiakasid'] = null;
        Redirect::to('/login', array('message' => 'Olet kirjautunut ulos!'));
    }

}
