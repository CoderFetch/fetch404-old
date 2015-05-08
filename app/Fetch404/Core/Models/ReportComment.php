<?php namespace Fetch404\Core\Models;

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
        return $this->belongsTo('Fetch404\Core\Models\Report');
    }

    /**
     * User relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Fetch404\Core\Models\User');
    }

}
