<?php

namespace WebModularity\LaravelLog;

use Illuminate\Database\Eloquent\Model;

/**
 * WebModularity\LaravelUser\LogRequestMethod
 *
 * @property int $id
 * @property string $method
 */

class LogRequestMethod extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['method'];
}