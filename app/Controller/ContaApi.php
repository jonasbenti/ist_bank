<?php

require_once 'Model/Conta.php';

class ContaApi
{
    public function __construct()
    {
        $this->data = [];
    }

    public function verifyConta($param)
    {
        try {
            Transaction::open(DATABASE);
            $this->data = Conta::findByNumeroConta($param['numero_conta']);           
            Transaction::close();

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function verifyContasByPessoa($param)
    {
        try {
            Transaction::open(DATABASE);
            $this->data = Conta::findByPessoa($param['pessoas_id']);           
            Transaction::close();

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function find($param)
    {
        try {
            Transaction::open(DATABASE);
            $this->data = Conta::find($param['id']);           
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
