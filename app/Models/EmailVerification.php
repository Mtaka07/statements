<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    use HasFactory;

    protected $guarded = [self::ID];
    protected $table = 'email_verification';

    const ID = 'id';
    const EMAIL = 'email';
    const TOKEN = 'token';
    const STATUS = 'status';
    const EXPIRATION_DATETIME = 'expiration_datetime';
    const EMAIL_VERIFIED_AT = 'email_verified_at';
}
