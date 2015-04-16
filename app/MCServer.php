<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class MCServer extends Model {

	//
	protected $table = 'mc_servers';
	
	protected $fillable = ['type', 'name', 'ip', 'description', 'active', 'main', 'port'];
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function getIP()
	{
		return $this->ip . ($this->port != 25565 ? ':' . $this->port : '');
	}
	
	public function getDescription()
	{
		return $this->description;
	}
	
	public function isActive()
	{
		return $this->active == 1;
	}
}
