<?php

namespace App\Filters;

use \App\User;

class ThreadFilters extends Filters
{
    protected $filters = ['by', 'popular'];

    /**
     * Filter the query by given username.
     * 
     * @param string $username
     * @return Builder
     */
    public function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();
        
        return $this->builder->where('user_id', $user->id);
    }

    /**
     * Filter the query according to most popluar threads
     * 
     * @return $this
     */
    public function popular()
    {
        $this->builder->getQuery()->orders = [];
        return $this->builder->orderBy('replies_count', 'desc');
    }
}