<?php

class AsiakasController extends BaseController{
    
    public static function browse(){
        $asiakkaat = Asiakas::all();
        View::make('statichtml/browse.html', array ('asiakkaat' => $asiakkaat));
    }
	
	public static function viewProfile() {
		View::make('profile.html');
	}
    
    public static function store(){
        $params = $_POST;
        
        $attributes = array('nimimerkki' => $params['nimimerkki'],
                'salasana' => $params['salasana'],
                'email' => $params['email'],
                'syntymapaiva' => $params['syntymapaiva'],
                'sukupuoli' => $params['sukupuoli'],
                'paikkakunta' => $params['paikkakunta']
				);
		
        $asikas = new Asiakas($attributes);
        $errors = $asiakas->errors();
		
		if(count($errors) == 0) {
			$asiakas->save();
			Redirect::to('statichtml/browse.html', array('message' => 'Käyttäjä luotu!'));
		} else {
			Redirect::to('statichtml/browse.html', array('message' => 'Virhe käyttäjää luodessa!'));
		}
        
    }
	
	public static function destroy($asiakasid){
		$asiakas = new Asiakas(array('asiakasid' => $asiakasid));
		$asiakas->destroy();
	}
	
	public static function edit($asiakasid){
    $Asiakas = Asiakas::findByID($asiakasid);
    View::make('editprofile.html', array('attributes' => $Asiakas));
	Redirect::to('statichtml/browse.html', array('message' => 'Peli on poistettu onnistuneesti!'));
  }
	
	
	public static function update($asiakasid){
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

    if(count($errors) > 0){
      View::make('editprofile.html', array('errors' => $errors, 'attributes' => $attributes));
    }else{
      $asiakas->update();

      Redirect::to('statichtml/browse.html' . $asiakas->asiakasid, array('message' => 'Profiilia on muokattu onnistuneesti!'));
    }
  }
	
    
    public static function signup() {
        View::make('signup.html');
    }
}
