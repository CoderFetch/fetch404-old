<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model {

	//
    protected $table = 'reports';
    protected $fillable = ['subject_id', 'subject_type', 'user_id', 'closed', 'reason', 'reported_id'];

    public function open()
    {
        return $this->update(array(
            'closed' => 0
        ));
    }

    public function close()
    {
        return $this->update(array(
            'closed' => 1
        ));
    }

    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function reported()
    {
        return $this->belongsTo('App\User', 'reported_id');
    }

    public function subject()
    {
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->hasMany('App\ReportComment');
    }

    public function isClosed()
    {
        return $this->closed == 1;
    }

    public function getContentURL()
    {
        if ($this->subject instanceof Post || $this->subject instanceof Topic)
        {
            return ($this->subject->Route);
        }

        if ($this->subject instanceof User)
        {
            return ($this->subject->profileURL);
        }

        return config('app.url');
    }
}
