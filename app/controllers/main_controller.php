<?php

Class MainController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('statichtml/index.html');
    }

}
