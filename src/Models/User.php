<?php
namespace FifaRestfulPHP\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user_data';
    protected $primaryKey = 'user_id';
	protected $fillable = ['user_id', 'user_pwd', 'user_name'];
	
	public $timestamps = false;
}
