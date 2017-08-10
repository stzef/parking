<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $csede
 * @property int $empresa_id
 * @property string $nsede
 * @property int $capacidad
 * @property Empresa $empresa
 * @property Movimiento[] $movimientos
 * @property User[] $users
 */
class Sedes extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'csede';

    /**
     * @var array
     */
    protected $fillable = ['empresa_id', 'nsede', 'capacidad'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empresa()
    {
        return $this->belongsTo('App\Empresa');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movimientos()
    {
        return $this->hasMany('App\Movimiento', 'sedes_id', 'csede');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\User', null, 'csede');
    }
}
