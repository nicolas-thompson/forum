<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
    protected $request, $builder;
    protected $filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;    
    }

    public function apply($builder)
    {
        $this->builder = $builder;


        collect($this->getFilters())
            ->filter(function ($value, $filter){
                return method_exists($this, $filter);
            })
            ->each(function ($value, $filter){
                $this->$filter($value);
            });
        
        return $this->builder;
    }

    protected function getFilters()
    {
        return $this->request->only($this->filters);
    }

}