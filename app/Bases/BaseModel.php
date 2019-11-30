<?php
namespace App\Bases;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model
{
    public function scopeTransaction($query, $callback)
    {
        DB::beginTransaction();

        $result = $callback();

        if ($result['code'] == 200)
        {
            DB::commit();
        }
        else
        {
            DB::rollback();
        }

        return $result;
    }

    public function scopeData($query, $key = NULL, $orderBy = NULL, $direction = 'asc', $offset = 0, $limit = 0)
    {
        if (is_array($key)) {
          $key_temp = $key;
          // usage ['column'=>['value','value']] convert to whereIn
          foreach($key_temp as $k => $v){
            if(is_array($v)){
              $query->whereIn($k,$v);
              unset($key[$k]);
            }
          }
          //end
          $query->where($key);
        }

        if (!empty($offset) || !empty($limit)) {
            $query->take($limit)->skip($offset);
        }

        if (!empty($orderBy)) {
            $query->orderBy($orderBy, $direction);
        }

        return $query;
    }

    public function scopeWhereLike($query, $name, $value, $status = 'both')
    {
        switch ($status) {
            case 'left':
                $value = $value . '%';
                break;
            case 'right':
                $value = '%' . $value;
                break;
            case 'both':
                $value = '%' . $value . '%';
                break;
        }

        $query->where($name, 'like', $value);
        return $query;
    }

    public function scopeCreateOne($query, array $data, $callback = NULL)
    {
        try {
            $event = $query->create($data);

            // if contain callback
            if (is_callable($callback)) {
                $callback($query, $event);
            }

            return [
                'code'    => 200,
                'status'  => 'success',
                'message' => __('Data has been saved.'),
                'data'    => [
                    '_id' => encrypt($event->id),
                ]
            ];
        } catch (Exception $e) {
            return [
                'code'    => 500,
                'status'  => 'error',
                'message' => __('Save data failed.'),
                'data'    => $e->getMessage()
            ];
        }
    }

    public function scopeUpdateOne($query, $id, array $data, $callback = NULL)
    {
        try {
            $cursor = $query->find($id);
            if ($cursor) {
                $event = $cursor->update($data);

                // if contain callback
                if (is_callable($callback)) {
                    $callback($query, $event, $cursor);
                }

                return  [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => __('Data has been updated.'),
                    'data'    => [
                        '_id' => encrypt($id),
                    ]
                ];
            } else {
                return  [
                    'code'    => 500,
                    'status'  => 'error',
                    'message' => __('Data not found.')
                ];
            }
        } catch (Exception $e) {
            return [
                'code'    => 500,
                'status'  => 'error',
                'message' => __('Update data failed.'),
                'data'    => $e->getMessage()
            ];
        }
    }

    public function scopeDeleteOne($query, $id, $callback = NULL)
    {
        try {
            $cursor = $query->find($id);
            if ($cursor) {
                $event = $cursor->delete();

                // if contain callback
                if (is_callable($callback)) {
                    $callback($query, $event, $cursor);
                }

                return  [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => __('Data has been deleted.'),
                    'data'    => [
                        '_id' => encrypt($id),
                    ]
                ];
            } else {
                return  [
                    'code'    => 500,
                    'status'  => 'error',
                    'message' => __('Data not found.')
                ];
            }
        } catch (Exception $e) {
            return [
                'code'    => 500,
                'status'  => 'error',
                'message' => __('Delete data failed'),
                'data'    => $e->getMessage()
            ];
        }
    }

    public function scopeDeleteBatch($query, array $id, $callback = NULL)
    {
        try {
            $cursors = $query->whereIn('id', $id)->get();
            if ($cursors) {
                $deleted_id = [];

                foreach ($cursors as $cursor) {
                    $deleted_id[] = encrypt($cursor->id);
                    $event = $cursor->delete();

                    // if contain callback
                    if (is_callable($callback)) {
                        $callback($query, $event, $cursor);
                    }

                }

                return  [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => __('Data has been deleted.'),
                    'data'    => [
                        '_id' => encrypt($id),
                    ]
                ];
            } else {
                return  [
                    'code'    => 500,
                    'status'  => 'error',
                    'message' => __('Data not found.')
                ];
            }
        } catch (Exception $e) {
            return [
                'code'    => 500,
                'status'  => 'error',
                'message' => __('Delete data failed.'),
                'data'    => $e->getMessage()
            ];
        }
    }

    public function scopeIsActive($query)
    {
        $query->where('status', 1);
        return $query;
    }

}
