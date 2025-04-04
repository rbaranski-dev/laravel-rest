<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'number',
        'address',
        'city',
        'zip_code',
    ];


    /**
     * Get the employees associated with the business.
     */     
    public function employees()
    {
        return $this->belongsToMany(Employee::class, Employment::class);
    }

}
