<?php namespace Fetch404\Core\Repositories;

use Fetch404\Core\Models\Setting;

class SettingsRepository extends BaseRepository {

    public function __construct(Setting $model)
    {
        $this->model = $model;
        $this->itemsPerPage = 0;
    }

    /**
     * Get a setting by its name.
     * Returns the default value if nothing is found.
     *
     * @param $name
     * @param null $default
     * @param bool $returnValue
     * @return mixed
     */
    public function getByName($name, $default = null, $returnValue = false)
    {
        $model = $this->model->where('name', '=', $name)->first();

        if ($model)
        {
            return ($returnValue == true ? $model->value : $model);
        }
        else
        {
            return $default;
        }
    }

    /**
     * Update a setting record in the database.
     * Will create a new record if the requested one was not found.
     *
     * @param $name
     * @param $value
     * @return boolean
     */
    public function setSetting($name, $value)
    {
        $setting = $this->getByName($name, null, false);

        if ($setting)
        {
            return $setting->update(array(
                'value' => $value
            ));
        }
        else
        {
            return $this->model->create(array(
                'name' => $name,
                'value' => $value
            ));
        }

        return false;
    }
}