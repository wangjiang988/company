<?php
namespace App\Repositories\Eloquent;
/**
 * Abstract class for eloquent repositories.
 */
abstract class Repository
{
    /**
     * An instance of the model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;
    /**
     * Get's all records from the model.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->model->all();
    }
    /**
     * Get's an item from the repository.
     *
     * @param int $model_id
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getById($model_id)
    {
        return $this->model->findOrFail($model_id);
    }

    /**
     * Get's an item from the repository.
     *
     * @param int $model_id
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getByOderId($model_id)
    {
        return $this->model->where('order_id','=',$model_id)->first();
    }

    /**
     * Creates a new instance of the model.
     *
     * @param array $fields
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $fields)
    {
        return $this->model->create($fields);
    }
    /**
     * Updates an instance by it's ID.
     *
     * @param array $fields
     * @param int   $model_id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateById(array $fields, $model_id)
    {
        $model = $this->getById($model_id);
        $model->update($fields);
        return $model;
    }
    /**
     * Delete an instance by it's ID.
     *
     * @param int $model_id
     *
     * @throws \Exception
     * @return bool
     */
    public function deleteById($model_id)
    {
        $model = $this->getById($model_id);
        return $model->delete();
    }
    /**
     * Chunk the results of the query.
     *
     * @param int      $count
     * @param callable $callback
     *
     * @return bool
     */
    public function chunk($count, callable $callback)
    {
        return $this->model->chunk($count, $callback);
    }
    /**
     * Runs where query in chunk.
     *
     * @param string   $field
     * @param array    $values
     * @param int      $count
     * @param callable $callback
     *
     * @return bool
     */
    public function chunkWhereIn($field, array $values, $count, callable $callback)
    {
        return $this->model->whereIn($field, $values, 'and', false)
            ->chunk($count, $callback);
    }
    /**
     * Updates all instances with a specific status.
     *
     * @param int $original
     * @param int $updated
     *
     * @return bool
     */
    public function updateStatusAll($original, $updated)
    {
        return $this->model->where('status', '=', $original)
            ->update(['status' => $updated]);
    }

    /**
     * Retrieve first data of repository
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function first($columns = ['*'])
    {
        return $results = $this->model->where($columns)->first();
    }

    public function firstOrCreate($columns=['*'])
    {
        return $results = $this->model->firstOrCreate($columns);
    }

    /**
     * 查询记录总数
     * @param array $columns
     * @return mixed
     */
    public function count($columns = ['*'])
    {
        return $results = $this->model->where($columns)->count();
    }

    //pubf
}