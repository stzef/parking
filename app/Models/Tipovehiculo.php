<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $ctipov
 * @property string $ntipov
 * @property int $vrtipov
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
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'ctipov';

    /**
     * @var array
     */
    protected $fillable = ['ntipov', 'vrtipov'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movimientos()
    {
        return $this->hasMany('App\Movimiento', 'ctipov', 'ctipov');
    }
}
