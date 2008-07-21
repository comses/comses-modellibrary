<?php
// :vim:sts=2:sw=2:filetype=php

interface Persistable {
    public function getId();
    public function save();
}

abstract class AbstractPersistable implements Persistable {
    private $id = -1;

    public function __construct($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public static function executeLoad($query) {
        $args = func_get_args();
        array_shift($args);
        return db_query($query, $args);
    }

    // provides encapsulation for drupal dependency
    public function executeSave($query) {
        $args = func_get_args();
        // get rid of first query argument.
        array_shift($args);
        db_query($query, $args);
    }
}

class Model extends AbstractPersistable {

    private $id;
    private $name;
    private $title;
    private $ownerUid;
    private $replicators;
    private $isReplicated;
    private $references;

    public static function load($id) {
        $result = executeLoad('SELECT m.name, m.title, m.owner_uid, m.replicators, m.replicatedModel, m.reference 
                               FROM openabm_model where id=%d', $id);
        print_r($result);
    }

    public function save() {
        $query = "INSERT INTO openabm_model (owner_uid, name, title, replicators, replicatedModel, reference) VALUES (%d, '%s', '%s', '%s', %d, '%s')";
        parent::executeSave($query, $this->owner_uid, $this->name, $this->title, $this->replicators, $this->is_replicated, $this->references);
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }
}
