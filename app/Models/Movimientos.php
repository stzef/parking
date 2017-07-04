<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $cmovi
 * @property integer $sedes_id
 * @property integer $ctarifa
 * @property integer $ctimovi
 * @property integer $cusu
 * @property integer $ctipov
 * @property string $fhentrada
 * @property string $fhsalida
 * @property string $tiempo
 * @property integer $vrpagar
 * @property string $placa
 * @property boolean $cortesia
 * @property Sede $sede
 * @property Tarifa $tarifa
 * @property Timovi $timovi
 * @property User $user
 * @property Tipovehiculo $tipovehiculo
 */
class Movimientos extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['sedes_id', 'ctarifa', 'ctimovi', 'cusu', 'ctipov', 'fhentrada', 'fhsalida', 'tiempo', 'vrpagar', 'placa', 'cortesia'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sede()
    {
        return $this->belongsTo('App\Sede', 'sedes_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tarifa()
    {
        return $this->belongsTo('App\Tarifa', 'ctarifa', 'ctarifa');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timovi()
    {
        return $this->belongsTo('App\Timovi', 'ctimovi', 'ctimovi');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'cusu');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipovehiculo()
    {
        return $this->belongsTo('App\Tipovehiculo', 'ctipov', 'ctipov');
    }
}