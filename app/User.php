<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'org_key', 'visits', 'role', 'status', 'notify', 'phone_number', 'first_name', 'last_name', 'salt', 'is_bounces', 'is_complaints', 'user_key'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];



    public function RegisterVisit(){
    	$this->visits = $this->visits + 1;
    	$this->update();
	}
}
