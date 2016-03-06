<?php

class EsittelysivuController extends BaseController {
    
    public static function newBio() {
        View::make('asiakasviews/newbio.html');
    }
    
    public static function ownBio() {
        $page = Esittelysivu::ownBio();
        $asiakas = $_SESSION['asiakasid'];
        View::make('asiakasviews/showbio.html', array('sivu' => $page, 'asiakas' => $asiakas));
    }
    
    public static function showBio($asiakasid) {
        $page = Esittelysivu::findByAsiakasID($asiakasid);
        $asiakas = Asiakas::findByID($page->asiakasid);
        View::make('asiakasviews/showBio.html', array('sivu' => $page, 'asiakas' => $asiakas));
    }
    
    public static function editBio() {
        $asiakas = $_SESSION['asiakasid'];
        $page = Esittelysivu::findByAsiakasID($asiakas->asiakasid);
        
        View::make('asiakasviews/editbio.html', array('sivu' => $page, 'asiakas' => $asiakas));
    }
    
    public static function updateBio() {
        $params = $_POST;
        
        $atttributes = array(
            'sivuid' => $params['sivuid'],
            'asiakasid' => $params['asiakasid'],
            'sisalto' => $params['sisalto']
        );
        
        $page = new Esittelysivu($attributes);
        if ($params['action'] == 'update') {
            if ($_SESSION['asiakasid'] == $page->asiakasid) {
                $errors = $page->errors();
            }
        }
        if (count($errors) == 0) {
            $page->update();
            
            Redirect::to('/profile/bio', array('message' => 'Muutos tallennettu'));
        } else {
            Redirect::to('/profile/editbio', array('errors' => $errors));
        }
    }

    public static function store() {
        $params = $_POST;

        $attributes = array(
            'asiakasid' => $_SESSION['asiakasid'],
            'sisalto' => $params['sisalto']
        );

        $page = new Esittelysivu($attributes);

        $errors = $page->errors();
        if (count($errors) == 0) {
            $page->save();

            Redirect::to('/profile/bio', array('message' => 'Esittelysivu tallennettu'));
        } else {
            View::make('asiakasviews/newbio.html', array('errors' => $errors, 'page' => $page));
        }
    }

}
