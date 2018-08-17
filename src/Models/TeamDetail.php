<?php
namespace FifaRestfulPHP\Models;

use Illuminate\Database\Eloquent\Model;

class TeamDetail extends Model
{
    protected $table = 'team_details';
    protected $primaryKey = 'fifa_code';
	protected $fillable = ['fifa_code', 'fifa_ranking', 'name', 'nickname', 'association', 'head_coach', 'captain', 'flag_url', 'logo_url'];
	
	public $timestamps = false;
}
