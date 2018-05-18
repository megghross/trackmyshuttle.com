<?php
/**
 * Created by PhpStorm.
 * User: Arsla
 * Date: 5/11/2018
 * Time: 11:30 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Route extends  Model
{
    protected $table = "routes";
    public $timestamps = false;
    protected $fillable = ['name', "color" ,"length", "coordinates",'last_modified_datetime', "shuttleName"];

}