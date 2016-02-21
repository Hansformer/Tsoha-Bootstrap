<?php

class ViestiController extends BaseController {
    
    public static function allMessages() {
        $viestit = Viesti::all();
        View::make('asiakasviews/messages.html', array('viestit' => $viestit));
    }
    
}