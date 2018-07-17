<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleAPIData extends Model
{
    protected $table = "google_api_data";
    public $timestamps = true;
    protected $fillable = ['url', "data" ,"details"];

}
