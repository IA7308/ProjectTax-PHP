<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Login extends Authenticatable
{
    use Notifiable;

    protected $table = 'logins'; 
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'password'
    ];
}
