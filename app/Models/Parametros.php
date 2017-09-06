<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $cparam
 * @property string $name
 * @property string $type
 * @property string $value_text
 * @property boolean $value_bool
 * @property integer $value_int
 */
class Parametros extends Model
{
	protected $primaryKey = 'id';
    /**
     * @var array
     */
    protected $fillable = ['id','cparam', 'name', 'type', 'value_text', 'value_bool', 'value_int'];

}
