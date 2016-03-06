<?php

Class Asiakas extends BaseModel {

    public $asiakasid, $nimimerkki, $salasana, $email, $syntymapaiva, $sukupuoli,
            $paikkakunta, $yllapitaja, $paritele;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_password', 'validate_paikkakunta', 'validate_sex', 'validate_email', 'validate_date');
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Asiakas ORDER BY asiakasid');
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

    public function save() {
        // Lisätään RETURNING id tietokantakyselymme loppuun, niin saamme lisätyn rivin id-sarakkeen arvon
        $query = DB::connection()->prepare('INSERT INTO Asiakas (nimimerkki, salasana, email, syntymapaiva, sukupuoli, paikkakunta) VALUES (:nimimerkki, :salasana, :email, :syntymapaiva, :sukupuoli, :paikkakunta) RETURNING asiakasid');
        // Muistathan, että olion attribuuttiin pääse syntaksilla $this->attribuutin_nimi
        $query->execute(array('nimimerkki' => $this->nimimerkki, 'salasana' => $this->salasana, 'email' => $this->email, 'syntymapaiva' => $this->syntymapaiva, 'sukupuoli' => $this->sukupuoli, 'paikkakunta' => $this->paikkakunta));
        // Haetaan kyselyn tuottama rivi, joka sisältää lisätyn rivin id-sarakkeen arvon
        $row = $query->fetch();
        // Asetetaan lisätyn rivin id-sarakkeen arvo oliomme id-attribuutin arvoksi
        $this->id = $row['asiakasid'];
    }

    public function validate_name() {
        $errors = array();

        $nameflag = $this->nameAlreadyExists($this->nimimerkki);

        if ($nameflag == true) {
            $errors[] = 'Nimimerkki on jo olemassa';
        }

        if ($this->nimimerkki == '' || $this->nimimerkki == null) {
            $errors[] = 'Nimi ei saa olla tyhjä!';
        }
        if (strlen($this->nimimerkki) < 3) {
            $errors[] = 'Nimen pituuden pitää olla vähintään kolme merkkiä!';
        }

        if (strlen($this->nimimerkki) > 32) {
            $errors[] = 'Nimimerkki ei saa olla yli 32 merkkiä pitkä!';
        }
        return $errors;
    }

    public function validate_password() {
        $errors = array();
        if ($this->salasana == '' || $this->salasana == null) {
            $errors[] = 'Salasana ei saa olla tyhjä!';
        }
        if (!$this->stringLength(trim($this->salasana), 5, 64)) {
            $errors[] = 'Salasanan pituuden pitää olla vähintään 5 merkkiä ja enintään 64!';
        }
        return $errors;
    }

    public function validate_paikkakunta() {
        $errors = array();
        if ($this->paikkakunta == '' || $this->paikkakunta == null) {
            $errors[] = 'Paikkakunta ei saa olla tyhjä!';
        }
        if (!$this->stringLength(trim($this->paikkakunta), 2, 32)) {
            $errors[] = 'Paikkakunta pitää olla vähintään 2 merkkiä pitkä ja enintään 32!';
        }
        return $errors;
    }

    public function validate_sex() {
        $errors = array();
        if ($this->sukupuoli == NULL || $this->sukupuoli == '' || ($this->sukupuoli != false && $this->sukupuoli != true) || ($this->sukupuoli != 0 && $this->sukupuoli != 1)) {
            $errors[] = 'Valitse sukupuoli!';
        }
        return $errors;
    }

    public function validate_email() {
        $errors = array();

        if ($this->email == NULL || $this->email == '') {
            $errors[] = 'Sähköposti ei saa olla tyhjä!';
        }

        if (!$this->stringLength(trim($this->email), 0, 64)) {
            $errors[] = 'Sähköpostin maksimipituus on 64 merkkiä!';
        }

        return $errors;
    }

    public function validate_date() {
        $errors = array();
        
        $dateflag = $this->checkValidDateFormat($this->syntymapaiva);
        
        if ($dateflag == false) {
            $errors[] = 'Syntymäpäivä ei ole validi!';
        }

//        if (!is_numeric($dateArr[0]) || !is_numeric($dateArr[1]) || !is_numeric($dateArr[2])) {
//            $errors[] = 'Syntymäpäivä ei ole numeerisessa muodossa!';
//        }
        
        

        if ($this->syntymapaiva == NULL) {
            $errors[] = 'Syntymäpäivä ei saa olla tyhjä!';
        }


        return $errors;
    }
    
    public function checkValidDateFormat($date) {
        return (bool)strtotime($date);
    }

    public function nameAlreadyExists($nimimerkki) {
        $query = DB::connection()->prepare("SELECT * FROM Asiakas WHERE Nimimerkki = :nimimerkki");
        $query->execute(array('nimimerkki' => $nimimerkki));
        $result = $query->fetch();

        if ($result == null) {
            return false;
        } else {
            return true;
        }
    }

    public function deleteByID($asiakasid) {
        
        $query = DB::connection()->prepare('UPDATE Viesti SET lahettavaid = null WHERE lahettavaid = :asiakasid');
        $query->execute(array('asiakasid' => $asiakasid));
        
        $query = DB::connection()->prepare('UPDATE Viesti SET vastaanottavaid = null WHERE vastaanottavaid = :asiakasid');
        $query->execute(array('asiakasid' => $asiakasid));
        
        $query = DB::connection()->prepare('UPDATE Esittelysivujulkinen SET asiakasid = null WHERE asiakasid = :asiakasid');
        $query->execute(array('asiakasid' => $this->asiakasid));
        
        $query = DB::connection()->prepare("DELETE FROM Asiakas WHERE asiakasid = :asiakasid");
        $query->execute(array('asiakasid' => $asiakasid));
    }

    public function destroy() {
        $query = DB::connection()->prepare('UPDATE Viesti SET lahettavaid = null WHERE lahettavaid = :asiakasid');
        $query->execute(array('asiakasid' => $this->asiakasid));
        
        $query = DB::connection()->prepare('UPDATE Viesti SET vastaanottavaid = null WHERE vastaanottavaid = :asiakasid');
        $query->execute(array('asiakasid' => $this->asiakasid));
        
        $query = DB::connection()->prepare('UPDATE Esittelysivujulkinen SET asiakasid = null WHERE asiakasid = :asiakasid');
        $query->execute(array('asiakasid' => $this->asiakasid));
        
        $query = DB::connection()->prepare("DELETE FROM Asiakas WHERE asiakasid = :asiakasid");
        $query->execute(array('asiakasid' => $this->asiakasid));

//        $query2 = DB::connection()->prepare("DELETE FROM Viesti WHERE lahettavaid = :asiakasid OR vastaanottavaid = :asiakasid");
//        $query2->execute(array('asiakasid' => $this->asiakasid, 'asiakasid' => $this->asiakasid));
    }

    public function updateProfileInformation() {
        $query = DB::connection()->prepare("UPDATE Asiakas SET salasana = :salasana, email = :email, syntymapaiva = :syntymapaiva, sukupuoli = :sukupuoli, paikkakunta = :paikkakunta WHERE asiakasid = :asiakasid");
        $query->execute(array('salasana' => $this->salasana, 'email' => $this->email, 'syntymapaiva' => $this->syntymapaiva, 'sukupuoli' => $this->sukupuoli, 'paikkakunta' => $this->paikkakunta, 'asiakasid' => $this->asiakasid));

        $row = $query->fetch();
    }

    public function update() {
        // Lisätään RETURNING id tietokantakyselymme loppuun, niin saamme lisätyn rivin id-sarakkeen arvon
        $query = DB::connection()->prepare('UPDATE Asiakas (nimimerkki, salasana, email, syntymapaiva, sukupuoli, paikkakunta) VALUES (:nimimerkki, :salasana, :email, :syntymapaiva, :sukupuoli, :paikkakunta) RETURNING asiakasid');
        $query->execute(array('nimimerkki' => $this->nimimerkki, 'salasana' => $this->salasana, 'email' => $this->email, 'syntymapaiva' => $this->syntymapaiva, 'sukupuoli' => $this->sukupuoli, 'paikkakunta' => $this->paikkakunta));
        $row = $query->fetch();
    }

    public static function authenticate($nimimerkki, $salasana) {
        $query = DB::connection()->prepare('SELECT * FROM Asiakas WHERE nimimerkki = :nimimerkki AND salasana = :salasana LIMIT 1');
        $query->execute(array('nimimerkki' => $nimimerkki, 'salasana' => $salasana));

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

}
