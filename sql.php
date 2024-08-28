<?php
class SQL {
    private Database $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function select(string $table, string $columns = '*', string $where = '', array $params = []): array {
        $sql = "SELECT {$columns} FROM {$table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        return $this->db->fetchAll($sql, $params);
    }

    public function insert(string $table, array $data): string {
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";
        $this->db->query($sql, array_values($data));
        return $this->db->lastInsertId();
    }

    public function update(string $table, array $data, string $where, array $params = []): void {
        $columns = [];
        foreach ($data as $column => $value) {
            $columns[] = "{$column} = ?";
        }
        $sql = "UPDATE {$table} SET " . implode(', ', $columns) . " WHERE {$where}";
        $this->db->query($sql, array_merge(array_values($data), $params));
    }

    public function delete(string $table, string $where, array $params = []): void {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $this->db->query($sql, $params);
    }
}

?>
