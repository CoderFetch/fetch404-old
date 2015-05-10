<?php namespace Fetch404\Core\Traits;

trait CreatesNotifications {

    /*
     * Get any notifications related to this object.
     *
     * @return App\Notification
     */
    public function notifications()
    {
        return $this->hasMany('Fetch404\Core\Models\Notification', 'subject_id')
            ->where('subject_type', '=', get_class($this));
    }

    /*
     * Create a notification.
     *
     * @param array $data
     * @return mixed
     */
    public function notify(array $data = array())
    {
        return $this->notifications()->create($data);
    }
}