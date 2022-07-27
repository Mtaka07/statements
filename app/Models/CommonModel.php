<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;

class CommonModel extends Model
{
    use softDeletes, HasFactory;

    protected $guarded = ['id'];

    protected $primarykey = 'id';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
