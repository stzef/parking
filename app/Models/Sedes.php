<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $empresa_id
 * @property string $nombre
 * @property integer $capacidad
 * @property Empresa $empresa
 * @property Movimiento[] $movimientos
 * @property User[] $users
 */
class Sedes extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['empresa_id', 'nombre', 'capacidad'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empresa()
    {
        return $this->belongsTo('App\Models\Empresa');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movimientos()
    {
        return $this->hasMany('App\Models\Movimiento', 'sedes_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\User');
    }
}
