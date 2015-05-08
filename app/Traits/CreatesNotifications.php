<?php namespace App\Traits;

use App\Notification;

trait CreatesNotifications {

    /*
     * Get any notifications related to this object.
     *
     * @return App\Notification
     */
    public function notifications()
    {
        return Notification::where(
            'subject_id',
            '=',
            $this->id
        )->where(
            'subject_type',
            '=',
            get_class($this)
        );
    }

    /*
     * Create a notification.
     *
     * @param array $data
     * @return mixed
     */
    public function notify(array $data)
    {
        return Notification::create($data);
    }
}