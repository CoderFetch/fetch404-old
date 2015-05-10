<?php namespace Fetch404\Core\Repositories;

abstract class BaseRepository {

    protected $model;
    protected $itemsPerPage = 0;

    protected function getFirstBy($index, $value, $with = array())
    {
        $model = $this->model->where($index, '=', $value)->with($with)->first();
        return $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getByID($id, $with = array())
    {
        return $this->getFirstBy('id', $id, $with);
    }

    public function create($data = array())
    {
        $model = $this->model->create($data);

        return $model;
    }

    public function update($data = array())
    {
        $model = $this->model->find($data['id']);

        $model->fill($data);
        $model->save();

        return $model;
    }

    public function delete($id)
    {
        $model = $this->model->find($id);

        return $model->delete();
    }
}