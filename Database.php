<?php


declare(strict_types=1);


define('DB_HOST', 'localhost');
define('DB_NAME', 'myDB');
define('DB_USER', 'root');
define('DB_PASS', '');

class Database
{
    private string $host;
    private string $dbname;
    private string $username;
    private string $password;
    private ?PDO $pdo = null;

    public function __construct(
        string $host = DB_HOST,
        string $dbname = DB_NAME,
        string $username = DB_USER,
        string $password = DB_PASS
    ) {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
    }

    public function connect(): void
    {
        if ($this->pdo === null) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
                $this->pdo = new PDO($dsn, $this->username, $this->password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $this->logError($e->getMessage());
                throw new Exception("Failed to connect to database.");
            }
        }
    }

    public function disconnect(): void
    {
        $this->pdo = null;
    }

    public function query(string $sql, array $params = []): PDOStatement
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            throw new Exception("The query could not be run.");
        }
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function fetch(string $sql, array $params = []): array | false
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    public function rowCount(string $sql, array $params = []): int
    {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    private function logError(string $message): void
    {
        error_log($message, 3, 'app_errors.log');
    }
}

// $db = new Database();
// $db->connect();

?>
