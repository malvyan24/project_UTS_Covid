<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    protected $table = 'patients';
    protected $fillable = [
        'name',
        'phone',
        'address',
        'status',
        'in_date',
        'out_date',
        'created_at',
        'updated_at'

    ];
    protected $primarykey = 'id';
}
