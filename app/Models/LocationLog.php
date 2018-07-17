<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationLog extends Model
{
    protected $table = "locationlog";
    public $timestamps = true;
    protected $fillable = ['serialNumber', "lat" ,"long",'data', 'routeId'];

}
