<?php

namespace Voyager\Admin\Traits\Facade;

use Illuminate\Support\Facades\DB;

trait Database
{
    protected array $tables = [];

    /**
     * Get all tables in the database.
     *
     * @return array
     */
    public function getTables(): array
    {
        return DB::connection()->getDoctrineSchemaManager()->listTableNames();
    }

    /**
     * Get all columns in a given table.
     */
    public function getColumns(string $table): array
    {
        if (!array_key_exists($table, $this->tables)) {
            $builder = DB::getSchemaBuilder();
            $this->tables[$table] = $builder->getColumnListing($table);
        }

        return $this->tables[$table];
    }
}