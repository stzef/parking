<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $rosocial
 * @property string $nombre
 * @property string $direccion
 * @property string $telefono
 * @property string $nit
 * @property string $dv
 * @property Sede[] $sedes
 */
class Empresas extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['rosocial', 'nombre', 'direccion', 'telefono', 'nit', 'dv'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sedes()
    {
        return $this->hasMany('App\Models\Sede');
    }
}
