<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $cmovi
 * @property int $ctarifa
 * @property int $ctimovi
 * @property int $cusu
 * @property int $ctipov
 * @property int $sedes_id
 * @property string $fhentrada
 * @property string $fhsalida
 * @property string $tiempo
 * @property int $vrpagar
 * @property int $vrdescuento
 * @property string $placa
 * @property boolean $cortesia
 * @property string $updated_at
 * @property string $created_at
 * @property Tarifa $tarifa
 * @property Timovi $timovi
 * @property User $user
 * @property Tipovehiculo $tipovehiculo
 * @property Sede $sede
 */
class Movimientos extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'cmovi';

    /**
     * @var array
     */
    protected $fillable = ['ctarifa', 'ctimovi', 'cusu', 'ctipov', 'sedes_id', 'fhentrada', 'fhsalida', 'tiempo', 'vrpagar', 'vrdescuento', 'placa', 'cortesia', 'updated_at', 'created_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tarifa()
    {
        return $this->belongsTo('App\Models\Tarifas', 'ctarifa', 'ctarifa');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timovi()
    {
        return $this->belongsTo('App\Models\Timovi', 'ctimovi', 'ctimovi');
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
        return $this->belongsTo('App\Models\Tipovehiculo', 'ctipov', 'ctipov');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sede()
    {
        return $this->belongsTo('App\Models\Sede', 'sedes_id', 'csede');
    }
}
