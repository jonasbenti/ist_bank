# IST Bank

## Projeto Realizado para simular transações financeiras em um sistema bancário;

## Configurações de ambiente:
- Docker (PHP 7.1, Banco de dados Mysql 5.7, Nginx);

## Tecnologias:
- PHP;
- Mysql;
- Javascript;
- Jquery;
- HTML5;
- Bootstrap;

## Iniciando a instalação do Projeto:
- Instalar o docker;
- Clonar este projeto;
- Iniciar via terminal na pasta extraida e executar o comando do docker: `docker-compose up`
- Dados de conexão do Banco de dados (`host: localhost`, `port: 3306`, `database: ist`, `user: root`, `password: 123.456`);

## Funcionalidades:
- Utilização de MVC;
- Cadatro de pessoa;
- Cadastro de conta bancaria;
- Tela de movimentação financeira(Depositar/Retirar);
- Trigger na tabela de movimentações, para sempre que ocorrer uma movimentação atualizar o saldo da conta e a quantidade de transações;
- Validações dos campos nos inputs;

## Melhorias Futuras
- Criar uma tela para visualizar o extrato das contas (com layout melhorado);
- Criar conta com numero da conta aleatório no cadastro de pessoa de forma automatica;
