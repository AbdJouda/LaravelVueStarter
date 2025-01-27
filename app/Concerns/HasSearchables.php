<?php

namespace App\Concerns;

use Exception;
use Illuminate\Database\Eloquent\Builder;

trait HasSearchables
{
    /**
     * IN BOOLEAN MODE constant for full-text searches.
     */
    const BOOLEAN_MODE = 'IN BOOLEAN MODE';
    /**
     * IN NATURAL LANGUAGE MODE constant for full-text searches.
     */
    const NATURAL_LANGUAGE_MODE = 'IN NATURAL LANGUAGE MODE';
    /**
     * WITH QUERY EXPANSION MODE constant for full-text searches.
     */
    const QUERY_EXPANSION_MODE = 'IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION';

    /**
     * Search model
     *
     * @param Builder $query
     * @param string|null $userValue
     * @param string $fullTextMode
     * @return Builder
     * @throws Exception
     */
    public function scopeSearch(Builder $query, ?string $userValue, string $fullTextMode = self::BOOLEAN_MODE): Builder
    {

        if (!trim($userValue)) {
            return $query;
        }

        $mode = strtoupper($fullTextMode);

        $columns = $this->buildSearchColumns();

        $this->applySearchable($query, $columns, $userValue, $mode);

        return $query;
    }

    /**
     * Build the search columns based on the searchables defined in the subclass.
     *
     * @return array
     */
    private function buildSearchColumns(): array
    {
        $columns = [];
        foreach ($this->getSearchables() as $index => $column) {
            if (is_numeric($index)) {
                $columns[] = $this->getColumnDetails($column);
            } else {
                $relatedModel = $this->{$index}()->getModel();
                $relationTableName = $relatedModel->getTable();
                foreach ($column as $relationColumnIndex => $relationColumnName) {
                    $columns[$index][] = $this->getRelationColumnDetails($relationTableName, $relationColumnIndex, $relationColumnName);
                }
            }
        }
        return $columns;
    }

    /**
     * Define searchables property to be implemented in the subclass.
     *
     * @return array
     */
    abstract protected function getSearchables(): array;

    /**
     * Get column details for a searchable field.
     *
     * @param mixed $column
     * @return array
     */
    private function getColumnDetails(mixed $column): array
    {
        if (is_array($column)) {
            $columnName = key($column);
            $indexType = end($column);
        } else {
            $columnName = $column;
            $indexType = 'default';
        }

        return [$columnName => $indexType];
    }

    /**
     * Get the details for a relation column in a searchable field.
     *
     * @param string $relationTableName
     * @param mixed $relationColumnIndex
     * @param string $relationColumnName
     * @return array
     */
    private function getRelationColumnDetails(string $relationTableName, mixed $relationColumnIndex, string $relationColumnName): array
    {
        if (is_numeric($relationColumnIndex)) {
            $columnName = "{$relationTableName}.{$relationColumnName}";
            $indexType = 'default';
        } else {
            $columnName = "{$relationTableName}.{$relationColumnIndex}";
            $indexType = $relationColumnName;
        }

        return [$columnName => $indexType];
    }

    /**
     * Apply searchable column to the query.
     *
     * @param Builder $query
     * @param array $searchable
     * @param string $value
     * @param string $mode
     * @return Builder
     * @throws Exception
     */
    protected function applySearchable(Builder $query, array $searchable, string $value, string $mode): Builder
    {
        $firstIteration = true;

        foreach ($searchable as $relation => $searchItems) {
            $method = $firstIteration ? 'where' : 'orWhere';
            if (!is_numeric($relation)) {
                $query->{$method . 'Has'}($relation, function ($relatedQuery) use ($searchItems, $value, $mode) {
                    $this->applyRelationSearch($relatedQuery, $searchItems, $value, $mode);
                });
            } else {
                $this->appendToSearchQuery($query, $searchItems, $value, $mode, $method);
            }
            $firstIteration = false;
        }

        return $query;
    }

    /**
     * Apply search conditions for the related model based on the provided search items.
     *
     * @param $relatedQuery
     * @param array $searchItems
     * @param string $value
     * @param string $mode
     * @return void
     */
    private function applyRelationSearch($relatedQuery, array $searchItems, string $value, string $mode): void
    {
        $firstCondition = true;

        foreach ($searchItems as $columnDetails) {
            $column = key($columnDetails);
            $indexType = end($columnDetails);

            if ($indexType === 'default') {
                if ($firstCondition) {
                    $relatedQuery->where($column, 'LIKE', '%' . $value . '%');
                    $firstCondition = false;
                } else {
                    $relatedQuery->orWhere($column, 'LIKE', '%' . $value . '%');
                }
            } else {
                $queryString = sprintf("MATCH (%s) AGAINST (? %s)", $column, $mode);
                $relatedQuery->whereRaw($queryString, ['"' . addcslashes($value, '%_') . '"']);
            }
        }
    }


    /**
     * Append search conditions to the query for searchable columns.
     *
     * @param $query
     * @param $searchItem
     * @param $value
     * @param $mode
     * @param string $method
     * @return mixed
     */
    protected function appendToSearchQuery($query, $searchItem, $value, $mode, string $method = 'where'): mixed
    {
        $escapedValue = addcslashes($value, '%_');

        $query->{$method}(function ($q) use ($searchItem, $escapedValue, $mode) {
            foreach ($searchItem as $key => $item) {
                $column = is_numeric($key) ? key($item) : $key;
                $indexType = is_numeric($key) ? end($item) : $item;

                if ($indexType === 'fulltext') {
                    $queryString = sprintf("MATCH (%s) AGAINST (? %s)", $column, $mode);
                    $q->whereRaw($queryString, ['"' . $escapedValue . '"']);
                } else {
                    $q->orWhere($column, 'LIKE', '%' . $escapedValue . '%');
                }
            }
        });

        return $query;
    }

}
