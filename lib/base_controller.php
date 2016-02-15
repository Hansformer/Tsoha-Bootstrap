<?php

  class BaseController{

    public static function get_user_logged_in(){
      if(isset($_SESSION['asiakasid'])) {
          $asiakasid = $_SESSION['asiakasid'];
          $asiakas = Asiakas::findByID($asiakasid);
          return $asiakas;
      }
      return null;
    }

    public static function check_logged_in(){
      // Toteuta kirjautumisen tarkistus tähän.
      // Jos käyttäjä ei ole kirjautunut sisään, ohjaa hänet toiselle sivulle (esim. kirjautumissivulle).
        if (!isset($_SESSION['asiakasid'])) {
            Redirect::to('/', array('error' => 'Kirjaudu sisään ensin!'));
        }
    }

  }
