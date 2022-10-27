<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fichero extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
	protected $table = 'ficheros';
	public $timestamps = false;
	protected $fillable = ['name','path','length','status'];
}
