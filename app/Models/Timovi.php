<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $ctimovi
 * @property string $detalle
 * @property Movimiento[] $movimientos
 */
class Timovi extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'timovi';

    /**
     * @var array
     */
    protected $fillable = ['detalle'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movimientos()
    {
        return $this->hasMany('App\Movimiento', 'ctimovi', 'ctimovi');
    }
}
