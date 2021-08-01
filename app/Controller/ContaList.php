<?php

require_once 'Model/Conta.php';
require_once 'Model/Pessoa.php';

class ContaList
{
    private $html;

    public function __construct()
    {
        $this->html = file_get_contents('./View/list_conta.html');
        $this->data = [
            'id' => '',
            'pessoas_id' => '',
            'numero_conta' => '',
            'conta_list',
            'combo_pessoas'
            ];
    }

    public function delete($param)
    {
        try {
            $id = (int) $param['id'];
            Transaction::open(DATABASE);
            Conta::delete($id);
            Transaction::close();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function edit($param)
    {
        try {
            $id = (int) $param['id'];
            Transaction::open(DATABASE);
            $this->data = Conta::find($id);           
            Transaction::close();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
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

    public function load()
    {
        try {
            Transaction::open(DATABASE);
            $contas = Conta::allContaWithPessoa();
            Transaction::close();

            $items = '';
            foreach ($contas as $conta) {                     
                $item = file_get_contents('./View/item_conta.html');
                $method_delete = "home.php?class=ContaList&method=delete&id=".$conta['id'];  
                $action = "onclick=\"return confirm('Tem certeza que deseja excluir a conta numero: {$conta['numero_conta']}?');\"";
                $image = $conta['qtd_movimentacoes'] > 0 ? "proibido" : "excluir";  
                $action_delete = $conta['qtd_movimentacoes'] == 0 ?
                "<td><a href='$method_delete' $action><img src='images/$image.png' style='width:17px'></a></td>" :
                "<td><img src='images/$image.png' style='width:17px'></td>";

                $item = str_replace('{id}', $conta['id'], $item);
                $item = str_replace('{pessoas_id}', $conta['nome'], $item);
                $item = str_replace('{numero_conta}', $conta['numero_conta'], $item);
                $item = str_replace('{cpf}', $conta['cpf'], $item);                           
                $item = str_replace('{qtd_movimentacoes}', $conta['qtd_movimentacoes'], $item);
                $item = str_replace('{action_delete}', $action_delete, $item);
                $items .= $item;
            }
            $this->data['conta_list'] = $items;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function save($param)
    {
        try {
            Transaction::open(DATABASE);
            Conta::save($param);
            Transaction::close();

            $this->data = $param;
            header("Location: home.php?class=ContaList");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function show()
    {
        $this->load();
        $this->loadPessoa($this->data['pessoas_id']);
        $this->html = str_replace('{id_f}', $this->data['id'], $this->html);
        $this->html = str_replace('{pessoas_id_f}', $this->data['pessoas_id'], $this->html);
        $this->html = str_replace('{combo_pessoas}', $this->data['combo_pessoas'], $this->html);
        $this->html = str_replace('{cpf_f}', $this->data['cpf'], $this->html);
        $this->html = str_replace('{numero_conta_f}', $this->data['numero_conta'], $this->html);
        $this->html = str_replace('{items}', $this->data['conta_list'], $this->html);
        echo $this->html;
    }
}
