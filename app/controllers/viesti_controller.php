<?php

class ViestiController extends BaseController {
    
    public static function allMessages() {
        $lahetetyt = Viesti::sentMessages();
        $vastaanotetut = Viesti::receivedMessages();
        View::make('asiakasviews/messages.html', array('lahetetyt' => $lahetetyt, 'vastaanotetut' => $vastaanotetut));
    }
    
    public static function showMessage($viestiid) {
        $viesti = Viesti::findByID($viestiid);
        
        if ($_SESSION['asiakasid'] == $viesti->vastaanottavaid) {
            
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