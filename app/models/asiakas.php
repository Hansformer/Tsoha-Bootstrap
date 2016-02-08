<?php

Class Asiakas extends BaseModel {

    public $asiakasid, $nimimerkki, $salasana, $email, $syntymapaiva, $sukupuoli,
            $paikkakunta, $yllapitaja, $paritele;
    

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Asiakas');
        $query->execute();
        $rows = $query->fetchAll();
        $asiakkaat = array();

        foreach ($rows as $row) {
            $asiakkaat[] = new Asiakas(array(
                'asiakasid' => $row['asiakasid'],
                'nimimerkki' => $row['nimimerkki'],
                'salasana' => $row['salasana'],
                'email' => $row['email'],
                'syntymapaiva' => $row['syntymapaiva'],
                'sukupuoli' => $row['sukupuoli'],
                'paikkakunta' => $row['paikkakunta'],
                'yllapitaja' => $row['yllapitaja'],
                'paritele' => $row['paritele']
            ));
        }
        return $asiakkaat;
    }

    public static function findByID($asiakasid) {
        $query = DB::connection()->prepare('SELECT * FROM Asiakas WHERE asiakasid = :asiakasid LIMIT 1');
        $query->execute(array('asiakasid' => $asiakasid));
        $row = $query->fetch();

        if ($row) {
            $asiakas = new Asiakas(array(
                'asiakasid' => $row['asiakasid'],
                'nimimerkki' => $row['nimimerkki'],
                'salasana' => $row['salasana'],
                'email' => $row['email'],
                'syntymapaiva' => $row['syntymapaiva'],
                'sukupuoli' => $row['sukupuoli'],
                'paikkakunta' => $row['paikkakunta'],
                'yllapitaja' => $row['yllapitaja'],
                'paritele' => $row['paritele']
            ));
            return $asiakas;
        }
        return null;
    }
    
    public function save(){
        // Lisätään RETURNING id tietokantakyselymme loppuun, niin saamme lisätyn rivin id-sarakkeen arvon
    $query = DB::connection()->prepare('INSERT INTO Asiakas (nimimerkki, salasana, email, syntymapaiva, sukupuoli, paikkakunta) VALUES (:nimimerkki, :salasana, :email, :syntymapaiva, :sukupuoli, :paikkakunta) RETURNING asiakasid');
    // Muistathan, että olion attribuuttiin pääse syntaksilla $this->attribuutin_nimi
    $query->execute(array('nimimerkki' => $this->nimimerkki, 'salasana' => $this->salasana, 'email' => $this->email, 'syntymapaiva' => $this->syntymapaiva, 'sukupuoli' => $this->sukupuoli, 'paikkakunta' => $this->paikkakunta));
    // Haetaan kyselyn tuottama rivi, joka sisältää lisätyn rivin id-sarakkeen arvon
    $row = $query->fetch();
    // Asetetaan lisätyn rivin id-sarakkeen arvo oliomme id-attribuutin arvoksi
    $this->id = $row['asiakasid'];
    }

}
