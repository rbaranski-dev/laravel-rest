<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employment extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'employee_id',
    ];
    
    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
    
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
