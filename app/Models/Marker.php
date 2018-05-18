<?php
/**
 * Created by PhpStorm.
 * User: Arsla
 * Date: 5/11/2018
 * Time: 11:30 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Marker extends  Model
{
    protected $table = "marker";
    public $timestamps = false;
    protected $fillable = ['name', "type","route" ,"address", "lat",'lng', "last_modified_datetime"];
}