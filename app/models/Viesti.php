<?php

class Viesti extends BaseModel{
    public $viestiid, $lahettavaid, $vastaanottavaid, $sisalto, $lahetysaika;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all(){
        $query = DB::connection()->prepare('SELECT * FROM Viesti');
        $query->execute();
        $rows = $query->fetchAll();
        $viestit = array();
        
        foreach ($rows as $row) {
            $viestit[] = new Viesti(array(
                'viestiid' => $row['viestiid'],
                'lahettavaid' => $row['lahettavaid'],
                'vastaanottavaid' => $row['vastaanottavaid'],
                'sisalto' => $row['sisalto'],
                'lahetysaika' => $row['lahetysaika']
            ));
        }
        return $viestit;
    }
    
    public static function findByID($viestiid){
        $query = DB::connection()->prepare('SELECT * FROM Viesti WHERE viestiid = :viestiid LIMIT 1');
        $query->execute(array('viestiid' => $viestiid));
        $row = $query->fetch();

        if ($row) {
            $viesti = new Viesti(array(
                'viestiid' => $row['viestiid'],
                'lahettavaid' => $row['lahettavaid'],
                'vastaanottavaid' => $row['vastaanottavaid'],
                'sisalto' => $row['sisalto'],
                'lahetysaika' => $row['lahetysaika']
            ));
            return $viesti;
        }
        return null;
    }
    
        public function save() {
        // Lisätään RETURNING id tietokantakyselymme loppuun, niin saamme lisätyn rivin id-sarakkeen arvon
        $query = DB::connection()->prepare('INSERT INTO Viesti (nimimerkki, salasana, email, syntymapaiva, sukupuoli, paikkakunta) VALUES (:nimimerkki, :salasana, :email, :syntymapaiva, :sukupuoli, :paikkakunta) RETURNING asiakasid');
        // Muistathan, että olion attribuuttiin pääse syntaksilla $this->attribuutin_nimi
        $query->execute(array('nimimerkki' => $this->nimimerkki, 'salasana' => $this->salasana, 'email' => $this->email, 'syntymapaiva' => $this->syntymapaiva, 'sukupuoli' => $this->sukupuoli, 'paikkakunta' => $this->paikkakunta));
        // Haetaan kyselyn tuottama rivi, joka sisältää lisätyn rivin id-sarakkeen arvon
        $row = $query->fetch();
        // Asetetaan lisätyn rivin id-sarakkeen arvo oliomme id-attribuutin arvoksi
        $this->id = $row['asiakasid'];
    }
}