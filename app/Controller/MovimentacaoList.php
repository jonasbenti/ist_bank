<?php

require_once 'Model/Movimentacao.php';
require_once 'Model/Pessoa.php';
require_once 'Model/Conta.php';

class MovimentacaoList
{
    private $html;

    public function __construct()
    {
        $this->html = file_get_contents('./View/list_movimentacao.html');
        $this->data = [
            'id' => '',
            'pessoas_id' => '',
            'numero_movimentacao' => '',
            'movimentacao_list',
            'combo_pessoas',
            'combo_contas'
            ];
    }

    public function loadPessoa($pessoa_id = 0)
    {
        try {
        Transaction::open(DATABASE);
        $pessoas = Pessoa::all();
        Transaction::close();
        $select_pessoas = "";
            foreach ($pessoas as $pessoa) {
                $id = $pessoa['id'];
                $nome = $pessoa['nome'];  
                $cpf = $pessoa['cpf'];  
                $check = $id == $pessoa_id ? "selected=1" : "";              
                $select_pessoas .= "<option $check value='$id'> $nome - $cpf </option>";
            }
        $this->data['combo_pessoas'] = $select_pessoas;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function loadConta($conta_id = 0)
    {
        try {
        Transaction::open(DATABASE);
        $contas = Conta::all();
        Transaction::close();
        $select_contas = "";
        
        foreach ($contas as $conta) {
            $id = $conta['id'];
            $numero_conta = $conta['numero_conta'];  
            $saldo = str_replace('.', ',', $conta['saldo']);  
            $check = $id == $conta_id ? "selected=1" : "";              
            $select_contas .= "<option $check value='$id'> $numero_conta - $saldo </option>";
        }
        $this->data['combo_contas'] = $select_contas;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function save($param)
    {
        try {
            Transaction::open(DATABASE);
            Movimentacao::save($param);
            Transaction::close();

            $this->data = $param;
            header("Location: home.php?class=MovimentacaoList");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function show()
    {
        $this->loadPessoa();
        $this->loadConta();
        $this->html = str_replace('{id_f}', $this->data['id'], $this->html);
        $this->html = str_replace('{pessoas_id_f}', $this->data['pessoas_id'], $this->html);
        $this->html = str_replace('{combo_pessoas}', $this->data['combo_pessoas'], $this->html);
        $this->html = str_replace('{combo_contas}', $this->data['combo_contas'], $this->html);
        $this->html = str_replace('{cpf_f}', $this->data['cpf'], $this->html);
        $this->html = str_replace('{numero_movimentacao_f}', $this->data['numero_movimentacao'], $this->html);
        $this->html = str_replace('{items}', $this->data['movimentacao_list'], $this->html);
        echo $this->html;
    }
}
