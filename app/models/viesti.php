<?php

class Viesti extends BaseModel {

    public $viestiid, $lahettavaid, $vastaanottavaid, $sisalto, $lahetysaika;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_content');
    }

    public static function all() {
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

    public static function sentMessages() {
        $query = DB::connection()->prepare('SELECT v.viestiid AS viestiid, v.lahettavaid AS lahettavaid, a.nimimerkki AS vastaanottavaid, v.sisalto AS sisalto, v.lahetysaika AS lahetysaika FROM Viesti AS v, Asiakas AS a WHERE v.lahettavaid = :lahettavaid AND v.vastaanottavaid = a.asiakasid ORDER BY lahetysaika DESC');
        $query->execute(array('lahettavaid' => $_SESSION['asiakasid']));
        $rows = $query->fetchAll();

        $viestit = array();
        $i = 0;
        foreach ($rows as $row) {
            $viestit[$i] = array(
                'viestiid' => $row['viestiid'],
                'lahettavaid' => $row['lahettavaid'],
                'vastaanottavaid' => $row['vastaanottavaid'],
                'sisalto' => $row['sisalto'],
                'lahetysaika' => $row['lahetysaika']
            );
            $i++;
        }
        return $viestit;
    }

    public static function receivedMessages() {
        $query = DB::connection()->prepare('SELECT v.viestiid AS viestiid, a.nimimerkki AS lahettavaid, v.vastaanottavaid AS vastaanottavaid, v.sisalto AS sisalto, v.lahetysaika AS lahetysaika FROM Viesti AS v, Asiakas AS a WHERE v.vastaanottavaid = :vastaanottavaid AND v.lahettavaid = a.asiakasid ORDER BY lahetysaika DESC');
        $query->execute(array('vastaanottavaid' => $_SESSION['asiakasid']));
        $rows = $query->fetchAll();

        $viestit = array();
        $i = 0;
        foreach ($rows as $row) {
            $viestit[$i] = array(
                'viestiid' => $row['viestiid'],
                'lahettavaid' => $row['lahettavaid'],
                'vastaanottavaid' => $row['vastaanottavaid'],
                'sisalto' => $row['sisalto'],
                'lahetysaika' => $row['lahetysaika']
            );
            $i++;
        }
        return $viestit;
    }

    public static function findByID($viestiid) {
        $query = DB::connection()->prepare('SELECT * FROM Viesti, Asiakas WHERE viestiid = :viestiid');
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
        $query = DB::connection()->prepare('INSERT INTO Viesti (lahettavaid, vastaanottavaid, sisalto, lahetysaika) VALUES (:lahettavaid, :vastaanottavaid, :sisalto, now()::timestamp(0)) RETURNING viestiid, lahetysaika');
        $query->execute(array('lahettavaid' => $this->lahettavaid, 'vastaanottavaid' => $this->vastaanottavaid, 'sisalto' => $this->sisalto));
        $row = $query->fetch();
        
        $this->viestiid = $row['viestiid'];
        $this->lahetysaika = $row['lahetysaika'];
    }
    
    public function update() {
        $query = DB::connection()->prepare('UPDATE Viesti SET sisalto = :sisalto, lahetysaika = now()::timestamp(0) WHERE viestiid = :viestiid');
        $query->execute(array('sisalto' => $this->sisalto, 'viestiid' => $this->viestiid));
        $row = $query->fetch();
    }
    
    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM Viesti where viestiid = :viestiid');
        $query->execute(array('viestiid' => $this->viestiid));
    }

    public function validate_content() {
        $errors = array();

        if ($this->sisalto == '' || $this->sisalto == null) {
            $errors[] = 'Viesti ei saa olla tyhjä!';
        }
        
        if (!$this->stringLength(trim($this->sisalto), 0, 512)) {
            $errors[] = 'Viesti saa olla korkeintaan 512 merkkiä pitkä!';
        }

        return $errors;
    }

}
