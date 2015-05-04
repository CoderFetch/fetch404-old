<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportComment extends Model {

    //
    protected $table = 'report_comments';
    protected $fillable = ['report_id', 'user_id', 'body'];

    /**
     * Report relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function report()
    {
        return $this->belongsTo('App\Report');
    }

    /**
     * User relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
