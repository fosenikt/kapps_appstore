<?php
namespace Kapps\Model\Database;

use Kapps\Model\Database\Db;
use Kapps\Model\Database\Exception\DatabaseException;

/**
 * Query Builder
 *
 * @autor ChatGPT
 */
class QueryBuilder {

    protected $db;
    protected $table;
    protected $columns = '*';
    protected $where = [];
    protected $joins = [];
    protected $orderBy = [];
    protected $limit;
    protected $offset;
    protected $params = [];
    protected $debug = false;

    public function __construct() {
        $this->db = Db::getInstance();
    }

    public function getDb() {
        return $this->db;
    }

    public function table($table) {
        $this->table = $table;
        return $this;
    }

    public function select(...$columns) {
        $this->columns = implode(', ', $columns);
        return $this;
    }

    public function where($column, $operator, $value = null) {
        if (strtoupper($operator) === 'IS' || strtoupper($operator) === 'IS NOT') {
            $this->where[] = "$column $operator NULL";
        } else {
            $this->where[] = "$column $operator ?";
            $this->params[] = $value;
        }
        return $this;
    }

    public function orWhere($column, $operator, $value = null) {
        if (strtoupper($operator) === 'IS' || strtoupper($operator) === 'IS NOT') {
            $this->where[] = ["OR", "$column $operator NULL"];
        } else {
            $this->where[] = ["OR", "$column $operator ?"];
            $this->params[] = $value;
        }
        return $this;
    }

	public function whereIn($column, array $values) {
		$placeholders = implode(', ', array_fill(0, count($values), '?'));
		$this->where[] = "$column IN ($placeholders)";
		foreach ($values as $value) {
			$this->params[] = $value;
		}
		return $this;
	}

	public function whereRaw($sql, $params = []) {
		$this->where[] = $sql;
		$this->params = array_merge($this->params, $params);
		return $this;
	}


    public function join($table, $first, $operator, $second) {
        $this->joins[] = "JOIN $table ON $first $operator $second";
        return $this;
    }

    public function leftJoin($table, $first, $operator, $second) {
        $this->joins[] = "LEFT JOIN $table ON $first $operator $second";
        return $this;
    }

    public function rightJoin($table, $first, $operator, $second) {
        $this->joins[] = "RIGHT JOIN $table ON $first $operator $second";
        return $this;
    }

    public function orderBy($column, $direction = 'ASC') {
        $this->orderBy[] = "$column $direction";
        return $this;
    }

    public function limit($limit) {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset) {
        $this->offset = $offset;
        return $this;
    }

    public function debug() {
        $this->debug = true;
        return $this;
    }

    public function get() {
        $sql = "SELECT {$this->columns} FROM {$this->table}";

        if (!empty($this->joins)) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        if (!empty($this->where)) {
            $sql .= ' WHERE ' . $this->compileWheres();
        }

        if (!empty($this->orderBy)) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orderBy);
        }

        if (!empty($this->limit)) {
            $sql .= " LIMIT {$this->limit}";
        }

        if (!empty($this->offset)) {
            $sql .= " OFFSET {$this->offset}";
        }

        $stmt = $this->executeStatement($sql, $this->params);
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return !empty($rows) ? $rows : null;
    }

    protected function compileWheres() {
        $sql = '';
        foreach ($this->where as $index => $where) {
            if (is_array($where)) {
                if ($index === 0) {
                    $sql .= $where[1];
                } else {
                    $sql .= ' ' . $where[0] . ' ' . $where[1];
                }
            } else {
                if ($index === 0) {
                    $sql .= $where;
                } else {
                    $sql .= ' AND ' . $where;
                }
            }
        }
        return $sql;
    }

    public function chunk($size, $callback) {
        $offset = 0;

        do {
            $this->limit($size)->offset($offset);
            $results = $this->get();
            if (empty($results)) {
                break;
            }
            $callback($results);
            $offset += $size;
        } while (count($results) === $size);
    }

    public function insert(array $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->executeStatement($sql, array_values($data));
        if ($stmt->affected_rows > 0) {
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }

    public function update(array $data) {
        $set = implode('=?, ', array_keys($data)) . '=?';
        $sql = "UPDATE {$this->table} SET $set";

        if (!empty($this->where)) {
            $sql .= ' WHERE ' . $this->compileWheres();
        }

        $params = array_merge(array_values($data), $this->params);

        $stmt = $this->executeStatement($sql, $params);

        if ($stmt === false) {
            throw new DatabaseException("Error executing statement: " . $this->db->error);
        }

        $affectedRows = $stmt->affected_rows;
        $errno = $stmt->errno;
        $stmt->close();

        return ($affectedRows > 0 || $errno === 0);
    }

    public function updateOrInsert(array $whereData, array $updateData) {
        // Sjekk etter eksisterende rad
        $query = clone $this;
        foreach ($whereData as $column => $value) {
            $query->where($column, '=', $value);
        }
        $existing = $query->get();

        if (!empty($existing)) {
            // Hvis det finnes eksisterende data, oppdater
            $this->where = [];
            $this->params = [];
            foreach ($whereData as $column => $value) {
                $this->where($column, '=', $value);
            }
            return $this->update($updateData);
        } else {
            // Hvis ingen eksisterende data finnes, sett inn ny data
            return $this->insert(array_merge($whereData, $updateData)) !== false;
        }
    }

    public function delete() {
        $sql = "DELETE FROM {$this->table}";
        if (!empty($this->where)) {
            $sql .= ' WHERE ' . $this->compileWheres();
        } else {
            throw new DatabaseException("Delete operation requires a WHERE clause.");
        }

        $stmt = $this->executeStatement($sql, $this->params);

        if ($stmt === false) {
            throw new DatabaseException("Error executing statement: " . $this->db->error);
        }

        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        return $affectedRows > 0;
    }


	public function getInsertId() {
		return $this->db->insert_id;
	}


    private function executeStatement($sql, $params = []) {
        if ($this->debug) {
            error_log("SQL: $sql");
            error_log("Params: " . json_encode($params));
        }

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new DatabaseException($this->db->error);
        }

        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new DatabaseException($stmt->error);
        }

        return $stmt;
    }
}

