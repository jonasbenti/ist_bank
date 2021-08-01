<?php

require_once "./Core/Transaction.php";

class Conta
{
    public static function find($id)
    {
        if ($conn = Transaction::get()) {            
            $result = $conn->prepare("select * from contas WHERE id= :id");
            $result->execute([':id' => $id]);
            
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }

    public static function findByNumeroConta($numero_conta)
    {
        if ($conn = Transaction::get()) {            
            $result = $conn->prepare("select count(*) as total, numero_conta from contas WHERE numero_conta = :numero_conta");
            $result->execute([':numero_conta' => $numero_conta]);
            
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }

    public static function findByPessoa($pessoas_id)
    {
        if ($conn = Transaction::get()) {            
            $result = $conn->prepare("select * from contas WHERE pessoas_id= :pessoas_id");
            $result->execute([':pessoas_id' => $pessoas_id]);

            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }

    public static function delete($id)
    {
        if ($conn = Transaction::get()) {            
            $result = $conn->prepare("DELETE from contas WHERE id= :id");
            $result->execute([':id' => $id]);
            
            return $result;
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }        
    }

    public static function all()
    {
        if ($conn = Transaction::get()) {            
            $result = $conn->query("select * from contas ORDER BY id desc");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }          
    }

    public static function allContaWithPessoa()
    {
        if ($conn = Transaction::get()) {            
            $result = $conn->query("select c.*, p.cpf as cpf, p.nome as nome
            from contas c 
            inner join pessoas p on (c.pessoas_id = p.id)
            ORDER BY id desc");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }          
    }

    public static function save($conta)
    {
        if ($conn = Transaction::get()) {
            foreach ($conta as $key => $value) {
                $conta[$key] = strip_tags(addslashes($value));
            }
            $id = isset($conta['id']) ? $conta['id'] : 0;

            unset($conta['id'], $conta['class'], $conta['method'], $conta['PHPSESSID']);
           
            if (empty($id)) {
                $keys_insert = implode(", ",array_keys($conta));
                $values_insert = "'".implode("', '",array_values($conta))."'";
                $sql = "INSERT INTO contas ($keys_insert) VALUES ($values_insert)";
            } else {
                $set = [];
                foreach ($conta as $column => $value) {
                    $set[] = "$column = '$value'";
                }
                $set_update = implode(", ", $set);
                $sql = "UPDATE contas SET $set_update WHERE id = '$id'";
            }            
            $result = $conn->query($sql);            
            
            return $result;
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);            
        }
    }    

    
}