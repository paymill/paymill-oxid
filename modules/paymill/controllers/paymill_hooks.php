<?php

class paymill_hooks extends oxUBase
{
    public function render()
    {       
        $data = $this->getInput('php://input');
        $fh = fopen("paymill.log", 'w');
        fwrite($fh, print_r($data, true));
        fclose($fh);
        
        $this->setHeader("HTTP/1.1 200 OK");
        exit();
    }
    
    /**
     * Return the data from 
     * the given stream source
     * 
     * @param string $source
     * @return string
     */
    public function getInput($source)
    {
        return file_get_contents($source);
    }
    
    /**
     * Set the given http header
     * 
     * @param string $header
     */
    public function setHeader($header)
    {
        header($header);
    }
}