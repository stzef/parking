<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $ctarifa
 * @property string $ntarifa
 * @property integer $vrtarifa
 * @property Movimiento[] $movimientos
 */
class Tarifas extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['ntarifa', 'vrtarifa'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movimientos()
    {
        return $this->hasMany('App\Movimiento', 'ctarifa', 'ctarifa');
    }
}
