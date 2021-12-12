<?php

class UserService {
    
    /**
     * DO NOT MODIFY THIS METHOD
     * ONLY THIS METHOD CAN READ data.txt
     */
    protected function getAllUsers(){
        $file = 'data/data.txt';
        
        $data = json_decode(file_get_contents($file));
        
        return $data;
        
    }
    
}
