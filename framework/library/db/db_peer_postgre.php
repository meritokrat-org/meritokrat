<?php

load::system('db/db_peer');

abstract class db_peer_postgre extends db_peer
{
    public function insert($data, $ignore_duplicate = false)
    {
        $insert_data    = [];
        $insert_columns = [];

        foreach ($data as $column => $value) {
            if (in_array($column, static::JSON_SERIALIZABLE_FIELDS, true)) {
                $data[$column] = json_encode($value);
            }

            $insert_data[]    = ":{$column}";
            $insert_columns[] = '"'.$column.'"';
        }

        $sql = sprintf('INSERT INTO %s (%s) VALUES (%s) %s', $this->table_name, implode(', ', $insert_columns), implode(', ', $insert_data), $this->primary_key ? ' RETURNING '.$this->primary_key : '');

        if ($this->primary_key) {
            $pk = db::get_scalar($sql, $data, $this->connection_name);
            $this->reset_item($pk);

            return $pk;
        }

        $statement = db::exec($sql, $data, $this->connection_name);
    }

    public function update($data, $keys = null)
    {
        if (!$keys) {
            $keys = [$this->primary_key => $data[$this->primary_key]];
        }

        $data = array_merge($data, $keys);

        $keys_data = [];
        foreach ($keys as $column => $value) {
            $keys_data[] = "\"{$column}\" = :{$column}";
        }

        $update_data = [];
        foreach ($data as $column => $value) {
            if (in_array($column, static::JSON_SERIALIZABLE_FIELDS, true)) {
                $data[$column] = json_encode($value);
            }

            if (!array_key_exists($column, $keys)) {
                $update_data[] = "\"{$column}\" = :{$column}";
            }
        }

        db::exec(
            sprintf('UPDATE %s SET %s WHERE %s', $this->table_name, implode(', ', $update_data), implode(' AND ', $keys_data)),
            $data,
            $this->connection_name
        );

        $this->reset_item($data[$this->primary_key], $data);
    }

    public function delete_item($primary_key)
    {
        $sql  = 'DELETE FROM '.$this->table_name.' WHERE '.$this->primary_key.' = :id';
        $bind = ['id' => $primary_key];

        db::exec($sql, $bind, $this->connection_name);

        $this->reset_item($primary_key);
    }
}