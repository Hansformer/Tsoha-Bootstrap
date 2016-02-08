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
}