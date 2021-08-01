<?php

require_once "./Core/Transaction.php";

class Movimentacao
{
    public static function find($id)
    {
        if ($conn = Transaction::get()) {            
            $result = $conn->prepare("select * from movimentacoes WHERE id= :id");
            $result->execute([':id' => $id]);
            
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }

    public static function allByConta($contas_id)
    {
        if ($conn = Transaction::get()) {            
            $result = $conn->prepare("SELECT id, REPLACE(valor, '.', ',') AS valor,
            DATE_FORMAT(created_at, '%d/%m/%Y %H:%i:%s') AS created_at
            FROM movimentacoes  WHERE contas_id= :contas_id ORDER BY id desc");
            $result->execute([':contas_id' => $contas_id]);

            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }

    public static function save($movimentacao)
    {
        if ($conn = Transaction::get()) {
            foreach ($movimentacao as $key => $value) {
                $movimentacao[$key] = strip_tags(addslashes($value));
            }
            $id = isset($movimentacao['id']) ? $movimentacao['id'] : 0;
            $movimentacao['valor'] = str_replace(".","", $movimentacao['valor']);
            $movimentacao['valor'] = str_replace(",",".", $movimentacao['valor']);
            $movimentacao['valor'] = $movimentacao['operacao'] == 1 ? $movimentacao['valor'] : ($movimentacao['valor'] * -1);
            
            unset($movimentacao['id'], $movimentacao['class'], $movimentacao['method'], $movimentacao['PHPSESSID']);
            unset($movimentacao['value_max'], $movimentacao['pessoas_id'], $movimentacao['operacao']);
           
            if (empty($id)) {
                $keys_insert = implode(", ",array_keys($movimentacao));
                $values_insert = "'".implode("', '",array_values($movimentacao))."'";
                $sql = "INSERT INTO movimentacoes ($keys_insert) VALUES ($values_insert)";
            } else {
                $set = [];
                foreach ($movimentacao as $column => $value) {
                    $set[] = "$column = '$value'";
                }
                $set_update = implode(", ", $set);
                $sql = "UPDATE movimentacoes SET $set_update WHERE id = '$id'";
            }            
            $result = $conn->query($sql);            
            
            return $result;
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);            
        }
    }        
}