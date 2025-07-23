<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    
    protected $fillable = ['employee_id', 'name', 'address', 'departement_id'];

    public function department()
    {
        return $this->belongsTo(Department::class, 'departement_id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
}
