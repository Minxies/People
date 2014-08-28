<?php

class APIKeyCreator
{
    protected $connect;
    protected $counter = 0;
    protected $filePath;
    protected $aPIKeyFileLength;
    
    // Attempts to initialize the database connection using the supplied info.
    public function APIKeyCreator($filePath) {
        $this->filePath = $filePath;
    }

    public function getNextKey() {

        $file = file($this->filePath);
        $this->aPIKeyFileLength = sizeof($file);

        $this->counter++;
        $aPIKeyIndex = $this->counter % $this->aPIKeyFileLength;
        echo "trying key: " . $file[$aPIKeyIndex];
        return $file[$aPIKeyIndex];
    }
}