<?php namespace Fetch404\Core\Traits;

use Fetch404\Core\Models\User;

trait CanBeReported {

    /**
     * Get every report instance related to this object.
     *
     * @return mixed
     */
    public function reports()
    {
        return $this->hasMany(
            'Fetch404\Core\Models\Report', 'subject_id'
        )->where(
            'subject_type', '=', get_class($this)
        );
    }

    /**
     * Create a new report.
     *
     * @param User $user
     * @param string $reason
     * @param $reported_user
     * @return boolean
     */
    public function report(User $user, $reason, $reported_user)
    {
        if (is_null($user)) return;

        return $this->reports()->create(array(
            'subject_id' => $this->id,
            'subject_type' => get_class($this),
            'user_id' => $user->getId(),
            'closed' => 0,
            'reason' => $reason,
            'reported_id' => $reported_user
        ));
    }
}