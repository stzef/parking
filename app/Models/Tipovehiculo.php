<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $ctipov
 * @property string $detalle
 * @property Movimiento[] $movimientos
 */
class Tipovehiculo extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'tipovehiculo';

    /**
     * @var array
     */
    protected $fillable = ['detalle'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movimientos()
    {
        return $this->hasMany('App\Movimiento', 'ctipov', 'ctipov');
    }
}
