<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fichero extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $primaryKey = 'id';
	protected $table = 'ficheros';
	public $timestamps = false;
	protected $fillable = ['name','path','length','status'];
}
