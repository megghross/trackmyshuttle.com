<?php
/**
 * Created by PhpStorm.
 * User: Arsla
 * Date: 5/11/2018
 * Time: 11:30 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Device extends  Model
{
    protected $table = "devices";
    public $timestamps = false;
    protected $fillable = ['serialNumber', "deviceToken","shuttleName", "routeId", "driverId"];
}