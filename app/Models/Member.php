<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Member extends Authenticatable
{
    use SoftDeletes, HasFactory, Notifiable;

    protected $guarded = ['id'];

    protected $primarykey = ['id'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    const ID = 'id';
    const LINE_USER_ID = 'line_user_id';
    const NAME = 'name';
    const MAIL = 'mail';
    const PASSWORD = 'password';
    const STATUS = 'status';
}
