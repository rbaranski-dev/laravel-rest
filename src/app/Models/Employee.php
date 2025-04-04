<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'email',
        'phone',
    ];

    public function businesses()
    {
        return $this->hasManyThrough(
            Business::class,
            Employment::class,
            'employee_id', // Foreign key on the Employment table
            'id', // Foreign key on the Business table
            'id', // Local key on the Employee table
            'business_id' // Local key on the Employment table
        );
    }

}
