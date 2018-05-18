<?php
/**
 * Created by PhpStorm.
 * User: Arsla
 * Date: 5/11/2018
 * Time: 11:30 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Driver extends  Model
{
    protected $table = "drivers";
    public $timestamps = false;
    protected $fillable = ['firstName', "lastName","contactNumber"];
}