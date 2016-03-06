<?php

class Esittelysivu extends BaseModel {

    public $sivuid, $asiakasid, $sisalto;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_sisalto');
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Esittelysivujulkinen');
        $query->execute();

        $rows = $query->fetchAll();

        $pages = array();
        foreach ($rows as $row) {
            $pages[] = New Esittelysivu(array(
                'sivuid' => $row['sivuid'],
                'asiakasid' => $row['asiakasid'],
                'sisalto' => $row['sisalto']
            ));
        }
    }

    public function findByID($sivuid) {
        $query = DB::connection()->prepare('SELECT * FROM Esittelysivujulkinen WHERE sivuid = :sivuid');
        $query->execute(array('sivuid' => $sivuid));
        $row = $query->fetch();

        $page = new Esittelysivu(array(
            'sivuid' => $row['sivuid'],
            'asiakasid' => $row['asiakasid'],
            'sisalto' => $row['sisalto']
        ));

        return $page;
    }

    public function findByAsiakasID($asiakasid) {
        $query = DB::connection()->prepare('SELECT * FROM Esittelysivujulkinen WHERE asiakasid = :asiakasid');
        $query->execute(array('asiakasid' => $asiakasid));
        $row = $query->fetch();

        $page = new Esittelysivu(array(
            'sivuid' => $row['sivuid'],
            'asiakasid' => $row['asiakasid'],
            'sisalto' => $row['sisalto']
        ));

        return $page;
    }

    public function ownBio() {
        $query = DB::connection()->prepare('SELECT * FROM Esittelysivujulkinen WHERE asiakasid = :asiakasid');
        $query->execute(array('asiakasid' => $_SESSION['asiakasid']));

        $row = $query->fetch();
        
        $page = new Esittelysivu(array(
            'sivuid' => $row['sivuid'],
            'asiakasid' => $row['asiakasid'],
            'sisalto' => $row['sisalto']
        ));

        return $page;
    }

    public function validate_sisalto() {
        $errors = array();

        if (!$this->stringLength($sisalto, 0, 1024)) {
            $errors[] = 'Esittelyteksti saa olla korkeintaan 1024 merkkiä pitkä';
        }

        return $errors;
    }

    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM Esittelysivujulkinen WHERE sivuid = :sivuid');
        $query->execute(array('sivuid' => $this->sivuid));
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Esittelysivujulkinen SET sisalto = :sisalto WHERE sivuid = :sivuid');
        $query->execute(array('sisalto' => $this->sisalto, 'sivuid' => $this->sivuid));
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Esittelysivu (asiakasid, sisalto) VALUES (:asiakasid, :sisalto) RETURNING sivuid');
        $query->execute(array('asiakasid' => $this->asiakasid, 'sisalto' => $this->sisalto));

        $row = $query->fetch();
        $this->sivuid = $row['sivuid'];
    }

}
