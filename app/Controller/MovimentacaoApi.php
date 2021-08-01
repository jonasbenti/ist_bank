<?php

require_once 'Model/Movimentacao.php';

class MovimentacaoApi
{
    public function __construct()
    {
        $this->data = [];
    }

    public function allMovimentacoesByConta($param)
    {
        try {
            Transaction::open(DATABASE);
            $this->data = Movimentacao::allByConta($param['contas_id']);           
            Transaction::close();

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function show()
    {
       echo json_encode($this->data);
    }
}
