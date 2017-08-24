<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable implements HasRoleContract
{
    use Notifiable, HasRole;

	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'sede_id' ,'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function movimientos()
    {
        return $this->hasMany('App\Models\Movimientos', 'id', 'cusu');
    }
    public function sede()
    {
        return $this->belongsTo('App\Models\Sedes', 'sede_id', 'csede');
    }
}

