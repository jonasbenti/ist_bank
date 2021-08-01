<?php

require_once "./Core/Transaction.php";

class Pessoa
{
    public static function find($id)
    {
        if ($conn = Transaction::get()) {            
            $result = $conn->prepare("select * from pessoas WHERE id= :id");
            $result->execute([':id' => $id]);
            
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }

    public static function delete($id)
    {
        if ($conn = Transaction::get()) {            
            $result = $conn->prepare("DELETE from pessoas WHERE id= :id");
            $result->execute([':id' => $id]);
            
            return $result;
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }        
    }

    public static function all()
    {
        if ($conn = Transaction::get()) {            
            $result = $conn->query("SELECT p.*, 
            (SELECT COUNT(*) FROM contas WHERE pessoas_id = p.id) AS total_contas 
            FROM pessoas p ORDER BY id DESC");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }          
    }

    public static function save($pessoa)
    {
        if ($conn = Transaction::get()) {
            foreach ($pessoa as $key => $value) {
                $pessoa[$key] = strip_tags(addslashes($value));
            }
            $id = isset($pessoa['id']) ? $pessoa['id'] : 0;
            $pessoa['cpf'] = preg_replace('/[^0-9]/', '', $pessoa['cpf']);
            unset($pessoa['id'], $pessoa['class'], $pessoa['method'], $pessoa['PHPSESSID']);
           
            if (empty($id)) {
                $keys_insert = implode(", ",array_keys($pessoa));
                $values_insert = "'".implode("', '",array_values($pessoa))."'";
                $sql = "INSERT INTO pessoas ($keys_insert) VALUES ($values_insert)";
            } else {
                $set = [];
                foreach ($pessoa as $column => $value) {
                    $set[] = "$column = '$value'";
                }
                $set_update = implode(", ", $set);
                $sql = "UPDATE pessoas SET $set_update WHERE id = '$id'";
            }            
            $result = $conn->query($sql);            
            
            return $result;
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);            
        }
    }        
}
