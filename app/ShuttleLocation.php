<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShuttleLocation extends Model
{
    protected $table = "shuttlelocation";
    public $timestamps = true;
    protected $fillable = ['serialNumber', "lat" ,"long", "locationdatetime",'device_text'];


}
