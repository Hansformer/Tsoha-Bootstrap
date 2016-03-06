<?php

class ViestiController extends BaseController {
    
    public static function allMessages() {
        $lahetetyt = Viesti::sentMessages();
        $vastaanotetut = Viesti::receivedMessages();
        View::make('asiakasviews/messages.html', array('lahetetyt' => $lahetetyt, 'vastaanotetut' => $vastaanotetut));
    }
    
    public static function showMessage($viestiid) {
        $viesti = Viesti::findByID($viestiid);
        
        if ($_SESSION['asiakasid'] == $viesti->vastaanottavaid || $_SESSION['asiakasid'] == $viesti->lahettavaid) {
            
            $lahettaja = Asiakas::findByID($viesti->lahettavaid);
            $vastaanottaja = Asiakas::findByID($viesti->vastaanottavaid);
            View::make('asiakasviews/message.html', array('sessionid' => $_SESSION['asiakasid'], 'lahettaja' => $lahettaja, 'vastaanottaja' => $vastaanottaja, 'viesti' => $viesti));
        }
        
    }
    
    public static function store() {
        $params = $_POST;
        
        $attributes = array(
            'lahettavaid' => $_SESSION['asiakasid'],
            'vastaanottavaid' => intval($params['vastaanottavaid']),
            'sisalto' => $params['sisalto']
        );
        
        $viesti = new Viesti($attributes);
        $errors = $viesti->errors();
        
        if (count($errors) == 0) {
            $viesti->save();
            
            Redirect::to('/messages', array('message' => 'Viesti on lähetetty!'));
        } else {
            Redirect::to('/messages', array('errors' => $errors));
        }
        
    }
    
    public static function editMessage($viestiid) {
        $viesti = Viesti::findByID($viestiid);
        
        if ($_SESSION['asiakasid'] == $viesti->lahettavaid) {
            $vastaanottaja = Asiakas::findByID($viesti->vastaanottavaid);
            
            View::make('asiakasviews/editmessage.html', array('viesti' => $viesti, 'vastaanottaja' => $vastaanottaja));
        } else {
            Redirect::to('/messages', array('message' => 'Et ole viestin lähettäjä'));
        }
    }
    
    public static function updateMessage() {
        $params = $_POST;
        
        if ($params['action'] == 'destroy') {
            $viesti = Viesti::findByID($params['viestiid']);
            $viesti->destroy();
            Redirect::to('/messages', array('message' => 'Viesti poistettu!'));
        }
        
        if ($params['action'] == 'update') {
            $attributes = array(
                'viestiid' => intval($params['viestiid']),
                'lahettavaid' => $_SESSION['asiakasid'],
                'vastaanottavaid' => intval($params['vastaanottavaid']),
                'sisalto' => $params['sisalto']
            );
            
            $viesti = new Viesti($attributes);
            $errors = $viesti->errors();
            
            if (count($errors) == 0) {
                $viesti->update();
                Redirect::to('/messages', array('message' => 'Muutokset tallennettu viestiin'));
            } else {
                Redirect::to('/messages', array('errors' => $errors, 'attributes' => $attributes));
            }
        }
    }
    
    
//    public static function sentMessages() {
//        
//    }
//    
//    public static function receivedMessages() {
//        
//    }
    
}