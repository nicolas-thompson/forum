<?php

namespace App\Inspections;

class Spam 
{
    protected $inspections = [
        InvalidKeywords::class
    ];

    public function detect($body)
    {
        foreach($this->inspections as $inspection) {
        
            app($inspection)->detect($body);
        }

        // $this->detectInvalidKeywords($body);
        $this->detectKeyHeldDown($body);

        return false;
    }

    protected function detectKeyHeldDown($body) 
    {
        if(preg_match('/(.)\\1{4,}/', $body)) {
            throw new \Exception('Your reply contains spam.');
        }
    }
}