<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $ctimovi
 * @property string $ntimovi
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
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'ctimovi';

    /**
     * @var array
     */
    protected $fillable = ['ntimovi'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movimientos()
    {
        return $this->hasMany('App\Movimiento', 'ctimovi', 'ctimovi');
    }
}
