<?php namespace App\Models;
/**
 * 公共模型文件，用以解析查询条件数组
 *
 * @author      技安 <php360@qq.com>
 * @link        http://www.hwache.com
 * @copyright   苏州华车网络科技有限公司
 */

trait Common {

    public function scopeParam($query, array $map) {
        foreach ($map as $k => $v) {
            if(!in_array($k,['_complex','_sub'])) {
                $query->switch($k, $v);
            } else if($k == '_sub') {
                if(strtolower($v['_logic']) == 'or') {
                    unset($v['_logic']);
                    $query->where(function($query) use($v){
                        $query->orParam($v);
                    });
                }
            }
        }
        return $query;
    }

    /**
     * @param $query
     * @param $field
     * @param array $map
     * @param bool $or
     * @return
     */
    public function scopeSwitch($query, $field, $map, $or = false) {
        if($or == true) {
            if(is_array($map)) {
                switch($map[0]) {
                    case 'eq': // 等于
                        return $query->orWhere($field, '=', $map[1]);
                        break;
                    case 'neq': // 不等于
                        return $query->orWhere($field, '<>', $map[1]);
                        break;
                    case 'gt': // 大于
                        return $query->orWhere($field, '>', $map[1]);
                        break;
                    case 'egt': // 大于等于
                        return $query->orWhere($field, '>=', $map[1]);
                        break;
                    case 'lt': // 小于
                        return $query->orWhere($field, '<', $map[1]);
                        break;
                    case 'elt': // 小于等于
                        return $query->orWhere($field, '<=', $map[1]);
                        break;
                    case 'between': // （在）区间查询
                        return $query->orWhere(function($query) use($field, $map){
                            $query->whereBetween($field, $map[1]);
                        });
                        break;
                    case 'notbetween': // （不在）区间查询
                        return $query->orWhere(function($query) use($field, $map){
                            $query->whereNotBetween($field, $map[1]);
                        });
                        break;
                    case 'in': // （在）IN 查询
                        return $query->orWhere(function($query) use($field, $map){
                            $query->whereIn($field, $map[1]);
                        });
                        break;
                    case 'notin': // （不在）IN 查询
                        return $query->orWhere(function($query) use($field, $map){
                            $query->whereNotIn($field, $map[1]);
                        });
                        break;
                    case 'null': // 为null
                        return $query->orWhere(function($query) use($field){
                            $query->whereNull($field);
                        });
                        break;
                    case 'like': // 模糊查询
                        return $query->orWhere($field, 'like', $map[1]);
                        break;
                }
            } else {
                return $query->orWhere($field, '=', $map);
            }
        } else if(is_array($map)) {
            switch($map[0]) {
                case 'eq': // 等于
                    return $query->eg($field, $map[1]);
                    break;
                case 'neq': // 不等于
                    return $query->neg($field, $map[1]);
                    break;
                case 'gt': // 大于
                    return $query->gt($field, $map[1]);
                    break;
                case 'egt': // 大于等于
                    return $query->egt($field, $map[1]);
                    break;
                case 'lt': // 小于
                    return $query->lt($field, $map[1]);
                    break;
                case 'elt': // 小于等于
                    return $query->elt($field, $map[1]);
                    break;
                case 'between': // （在）区间查询
                    return $query->between($field, $map[1]);
                    break;
                case 'notbetween': // （不在）区间查询
                    return $query->notbetween($field, $map[1]);
                    break;
                case 'in': // （在）IN 查询
                    return $query->in($field, $map[1]);
                    break;
                case 'notin': // （不在）IN 查询
                    return $query->notin($field, $map[1]);
                    break;
                case 'null': // 为null
                    return $query->null($field);
                    break;
                case 'like': // 模糊查询
                    return $query->like($field, $map[1]);
                    break;
            }
        } else {
            return $query->eg($field, $map);
        }
    }

    public function scopeOrParam($query, array $map) {
        foreach ($map as $k => $v) {
            $query->switch($k, $v, true);
        }
    }

    /**
     * 等于
     * @param $query
     * @param $field
     * @param $value
     * @return mixed
     */
    public function scopeEg($query, $field, $value) {
        return $query->where($field, '=', $value);
    }

    /**
     * 不等于
     * @param $query
     * @param $field
     * @param $value
     * @return mixed
     */
    public function scopeNeg($query, $field, $value) {
        return $query->where($field, '<>', $value);
    }

    /**
     * 大于
     * @param $query
     * @param $field
     * @param $value
     * @return mixed
     */
    public function scopeGt($query, $field, $value) {
        return $query->where($field, '>', $value);
    }

    /**
     * 大于等于
     * @param $query
     * @param $field
     * @param $value
     * @return mixed
     */
    public function scopeEgt($query, $field, $value) {
        return $query->where($field, '>=', $value);
    }

    /**
     * 小于
     * @param $query
     * @param $field
     * @param $value
     * @return mixed
     */
    public function scopeLt($query, $field, $value) {
        return $query->where($field, '<', $value);
    }

    /**
     * 小于等于
     * @param $query
     * @param $field
     * @param $value
     * @return mixed
     */
    public function scopeElt($query, $field, $value) {
        return $query->where($field, '<=', $value);
    }

    /**
     * 或者
     * @param $query
     * @param $field
     * @param $value
     * @return mixed
     */
    public function scopeOr($query, $field, $value) {
        return $query->orWhere($field, $value);
    }

    /**
     * 在数组中
     * @param $query
     * @param $field
     * @param $value
     * @return mixed
     */
    public function scopeIn($query, $field, $value) {
        return $query->whereIn($field, $value);
    }

    /**
     * 不在数组中
     * @param $query
     * @param $field
     * @param $value
     * @return mixed
     */
    public function scopeNotIn($query, $field, $value) {
        return $query->whereNotIn($field, $value);
    }

    /**
     * 为NUll
     * @param $query
     * @param $field
     * @return mixed
     */
    public function scopeNull($query, $field) {
        return $query->whereNull($field);
    }

    /**
     * 模糊查询
     * @param $query
     * @param $field
     * @param $value
     * @return mixed
     */
    public function scopeLike($query, $field, $value) {
        return $query->where($field, 'like', $value);
    }


}
