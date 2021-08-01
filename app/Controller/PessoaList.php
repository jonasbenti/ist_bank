<?php

require_once 'Model/Pessoa.php';

class PessoaList
{
    private $html;

    public function __construct()
    {
        $this->html = file_get_contents('./View/list_pessoa.html');
        $this->data = [
            'id_f' => '',
            'nome_f' => '',
            'cpf_f' => '',
            'endereco_f' => '',
            'pessoa_list'
        ];
    }

    public function delete($param)
    {
        try {
            $id = (int) $param['id'];
            Transaction::open(DATABASE);
            Pessoa::delete($id);
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
            $this->data = Pessoa::find($id);           
            Transaction::close();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function load()
    {
        try {
            Transaction::open(DATABASE);
            $pessoas = Pessoa::all();
            Transaction::close();

            $items = '';
            foreach ($pessoas as $pessoa) {     
                $item = file_get_contents('./View/item_pessoa.html');
                $method_delete = "home.php?class=PessoaList&method=delete&id=".$pessoa['id'];  
                $action = "onclick=\"return confirm('Tem certeza que deseja excluir a pessoa id: {$pessoa['id']}?');\"";
                $image = $pessoa['total_contas'] > 0 ? "proibido" : "excluir";  
                $action_delete = $pessoa['total_contas'] == 0 ?
                "<td><a href='$method_delete' $action><img src='images/$image.png' style='width:17px'></a></td>" :
                "<td><img src='images/$image.png' style='width:17px'></td>";
                $item = str_replace('{id}', $pessoa['id'], $item);
                $item = str_replace('{nome}', $pessoa['nome'], $item);
                $item = str_replace('{cpf}', $pessoa['cpf'], $item);
                $item = str_replace('{endereco}', $pessoa['endereco'], $item);                           
                $item = str_replace('{total_contas}', $pessoa['total_contas'], $item);                           
                $item = str_replace('{action_delete}', $action_delete, $item);                           
                $items .= $item;
            }
            $this->data['pessoa_list'] = $items;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function save($param)
    {
        try {
            Transaction::open(DATABASE);
            Pessoa::save($param);
            Transaction::close();

            $this->data = $param;
            header("Location: home.php?class=PessoaList");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function show()
    {
        $this->load();
        $this->html = str_replace('{id_f}', $this->data['id'], $this->html);
        $this->html = str_replace('{nome_f}', $this->data['nome'], $this->html);
        $this->html = str_replace('{cpf_f}', $this->data['cpf'], $this->html);
        $this->html = str_replace('{endereco_f}', $this->data['endereco'], $this->html);
        $this->html = str_replace('{items}', $this->data['pessoa_list'], $this->html);
        echo $this->html;
    }
}
