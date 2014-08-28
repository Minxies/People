<?php

class PersonVO {
    protected $id;
    protected $firstName;
    protected $emailAddress;
    protected $fullContactDumpJson;
    protected $fullContactDumpJsonStatus;
    
    public function setId($id) {
        $this->id = $id;
    }
    
    public function getId() {
        return $this->id;
    }

    public function setEmailAddress($emailAddress){
         $this->emailAddress = $emailAddress;
    }

    public function getEmailAddress() {
         return $this->emailAddress;
    }

    public function setFirstName($firstName){
         $this->firstName = $firstName;
    }

    public function getFirstName() {
         return $this->firstName;
    }

    public function setFullContactDumpJson($fullContactDumpJson){
         $this->fullContactDumpJson = $fullContactDumpJson;
    }

    public function getFullContactDumpJson() {
         return $this->fullContactDumpJson;
    }

    public function setFullContactDumpJsonStatus($fullContactDumpJson){
         $this->fullContactDumpJsonStatus = $fullContactDumpJsonStatus;
    }

    public function getFullContactDumpJsonStatus() {
        if($this->fullContactDumpJson){
            return json_decode($this->fullContactDumpJson)->status;
        } else {
            return null;
        }
    }
}