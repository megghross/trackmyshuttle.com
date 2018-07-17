<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShuttlesRoute extends Model
{
    protected $table = "locationlog";
    public $timestamps = true;
    protected $fillable = ['serialNumber', "routeId" ,"routeUsageCount", "data"];

}
