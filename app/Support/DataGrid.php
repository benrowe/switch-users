<?php

namespace App\Support;

/**
 * Helps couple a model to a datagrid component
 */
class DataGrid
{
    private $params;
    private $query;

    /**
     * Take the query builder object and the params from the datagrid component
     *
     * @param [type] $query  [description]
     * @param [type] $params [description]
     */
    public function __construct($query, $params)
    {
        $this->query = $query;
        $this->params = $params;
    }

    /**
     * Detect the columns based on the supplied params
     *
     * @return array
     */
    public function getDataColumns()
    {
        return array_filter(array_map(function ($col) {
            return $col['name'];
        }, $this->params['columns']));
    }

    /**
     * Build the response object as an array
     *
     * @return array
     */
    public function response()
    {
        $total = clone $this->query;

        $query = $this->applyQueryFilters();

        $resultSet = clone $query;


        $params = $this->params;

        // limit/offset
        $result = $resultSet
            ->take($params['length'])
            ->offset($params['start']);

        // ordering
        $columns = $this->getDataColumns();
        foreach ($params['order'] as $order) {
            if (isset($columns[$order['column']])) {
                $result->orderBy($columns[$order['column']], $order['dir']);
            }
        }
        $collection = $this->prepareData($result);

        return [
            'draw' => $this->params['draw'],
            'recordsTotal' => $total->count(),
            'recordsFiltered' => $query->count(),
            'data' => $collection->toArray(),
        ];
    }

    private function prepareData($result)
    {
        $collection = $result->get();

        $columns = $this->getDataColumns();

        return $collection->map(function ($item) use ($columns) {
            $result =  [
                'DT_RowId' => 'row_'.$item->getKey(),
                'DT_RowData' => [
                    'pkey' => $item->getKey()
                ],
            ];
            foreach ($columns as $column) {
                $result[] = $item->$column;
            }
            $result[] = '';
            return $result;
        });
    }

    private function applyQueryFilters()
    {
        $query = $this->query;
        if ($this->params['search']) {
            $search = $this->params['search']['value'];
            $columns = $this->getDataColumns();
            // apply like searching to all of the columns
            $query->where(function($query) use ($search, $columns) {
                foreach ($columns as $col) {
                    $query->orWhere($col, 'LIKE', '%'.$search.'%');
                }
            });
        }
        return $query;
    }
}
