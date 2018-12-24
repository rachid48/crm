<?php
/**
 * Created by PhpStorm.
 * User: xhost
 * Date: 19-Dec-18
 * Time: 8:30 PM
 */

namespace Core\Table;


use Core\Database\Database;

/**
 * Class Table
 * @package Core\Table
 */
class Table
{
    /**
     * @var string
     */
    protected $table;
    /**
     * @var Database
     */
    protected $db;

    /**
     * Table constructor.
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
        if (is_null($this->table)) {
            $parts = explode('\\', get_class($this));
            $class_name = end($parts);
            $this->table = strtolower(str_replace('Table', '', $class_name));
        }
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->query("
        SELECT * 
        FROM $this->table
        ");
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE id = ?", [$id], true);
    }

    /**
     * @param $fields
     * @return mixed
     */
    public function insert($fields)
    {
        $sql_parts = [];
        $attributes = [];
        $tmp = [];
        foreach ($fields as $k => $v) {
            $sql_parts[] = "$k";
            $attributes[] = $v;
            $tmp[] = " ?";
        }
        $sql_part = implode(', ', $sql_parts);
        $tmp = implode(', ', $tmp);
        return $this->query("INSERT INTO {$this->table} ( $sql_part )  VALUES ($tmp)", $attributes);

    }

    /**
     * @param $id
     * @param $fields
     * @return mixed
     */
    public function update($id, $fields)
    {
        $sql_parts = [];
        $attributes = [];
        foreach ($fields as $k => $v) {
            $v = str_replace("'","\'", $v);
            $sql_parts[] = "`$k`='" .$v. "'";
        }
        $attributes[] = $id;
        $sql_part = implode(', ', $sql_parts);
        return $this->query("UPDATE {$this->table} SET $sql_part  WHERE id= ?", [$id], true);
    }

    /**
     * @param $statement
     * @param null $attributes
     * @param bool $one
     * @return mixed
     */
    public function query($statement, $attributes = null, $one = false)
    {
        if ($attributes) {
            return $this->db->prepare(
                $statement,
                $attributes,
                str_replace('Table', 'Entity', get_class($this)),
                $one
            );
        } else {
            return $this->db->query(
                $statement,
                str_replace('Table', 'Entity', get_class($this)),
                $one
            );
        }
    }

    /**
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        $method = 'get' . ucfirst($key);
        $this->$key = $this->$method();
        return $this->$key;
    }

    /**
     * @param $email
     * @return mixed
     */
    public function findByEmail($email)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE email = ?", [$email], true);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->query("DELETE FROM {$this->table} WHERE id=?",[$id]);
    }
}