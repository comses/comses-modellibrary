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

    // follows JavaBean conventions of properties getters/setters
    function __call($method, $arguments) {
        $prefix = strtolower(substr($method, 0, 3));
        $property = strtolower(substr($method, 3));

        if (empty($prefix) || empty($property)) {
            return;
        }

        if ($prefix == "get" && isset($this->$property)) {
            return $this->$property;
        }

        if ($prefix == "set") {
            $this->$property = $arguments[0];
        }
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
        $db_result = executeLoad('SELECT m.name, m.title, m.owner_uid, m.replicators, m.replicatedModel, m.reference 
                               FROM openabm_model where id=%d', $id);
        $fetch_object = $db_fetch_object($db_result);
        $this->id=$id;
        $this->name=$fetch_object->name;
        $this->title=$fetch_object->title;
        $this->ownerUid=$fetch_object->owner_uid;
        $this->isReplicated=$fetch_object->replicatedModel;
        $this->replicators=$fetch_object->replicators;
        $this->references = $fetch_object->reference;
    }

    public function save() {
        $query = "INSERT INTO openabm_model (owner_uid, name, title, replicators, replicatedModel, reference) VALUES (%d, '%s', '%s', '%s', %d, '%s')";
        parent::executeSave($query, $this->owner_uid, $this->name, $this->title, $this->replicators, $this->is_replicated, $this->references);
    }

}
