<?php

include_once 'PersonVO.php';

class PersonDAO
{
    protected $connect;
    protected $db;
    
    // Attempts to initialize the database connection using the supplied info.
    public function PersonDAO($host, $username, $password, $database) {
        $this->connect = mysql_connect($host, $username, $password);
        $this->db = mysql_select_db($database);
    }
    
    // Executes the specified query and returns an associative array of reseults.
    protected function execute($sql) {
        $res = mysql_query($sql, $this->connect) or die(mysql_error());

        $personVO = array();
        
        if(mysql_num_rows($res) > 0) {
            for($i = 0; $i < mysql_num_rows($res); $i++) {
                $row = mysql_fetch_assoc($res);
                $personVO[$i] = new PersonVO();

                $personVO[$i]->setId($row['id']);
                $personVO[$i]->setEmailAddress($row['emailaddress']);
                $personVO[$i]->setFirstName($row['firstname']);
                $personVO[$i]->setFullContactDumpJson($row['fullcontactdumpjson']);

            }
        }
        return $personVO;
    }
    
    // Retrieves the corresponding row for the specified user ID.
    public function getByPersonId($userId) {
        $sql = "SELECT * FROM persons WHERE id=".$userId;
        return $this->execute($sql);
    }
    
    // Retrieves all persons currently in the database.
    public function getPersons() {
        $sql = "SELECT * FROM persons";
        return $this->execute($sql);
    }
    
    //Saves the supplied user to the database.
    public function save($personVO) {
        $affectedRows = 0;
        $sql = '';


        
        if($personVO->getId() != "") {
            $currPersonVO = $this->getByPersonId($personVO->getId());
        }
        
        // If the query returned a row then update,
        // otherwise insert a new user.
        if(sizeof($currPersonVO) > 0) {
            $sql = "UPDATE persons SET ".
                "fullcontactdumpjson='". mysql_real_escape_string( $personVO->getFullContactDumpJson() )."' ".
                "WHERE id=".$personVO->getId();
            
            mysql_query($sql, $this->connect) or die(mysql_error());
            $affectedRows = mysql_affected_rows();
        }
        else {
            $sql = "INSERT INTO persons (fullcontactdumpjson) VALUES('".
                $personVO->getFullContactDumpJson()."', ".
            
            mysql_query($sql, $this->connect) or die(mysql_error());
            $affectedRows = mysql_affected_rows();
        }
        
        return $affectedRows;
    }
    
    // Deletes the supplied user from the database.
    public function delete($personVO) {
        $affectedRows = 0;
        
        // Check for a user ID.
        if($personVO->getId() != "") {
            $currPersonVO = $this->getByPersonId($personVO->getId());
        }
        
        // Otherwise delete a user.
        if(sizeof($currPersonVO) > 0) {
            $sql = "DELETE FROM persons WHERE id=".$personVO->getId();
            
            mysql_query($sql, $this->connect) or die(mysql_error());
            $affectedRows = mysql_affected_rows();
        }
        
        return $affectedRows;
    }
}