<?php
include '../source/Boleto.php';

//Config::setProduction();
//Config::setSandbox();
//Config::setAccountCredentials('email@example.org', '5179DCD806314BD6A77B774DF6148CA9', false);


$boleto = new Boleto();
/*
 * Campos obrigatórios
 */
//Valor de cada boleto. Caso sua conta não absorver a taxa do boleto, será acrescentado 1 real no valor do boleto.
$boleto->setAmount('5.12');
//Descrição do boleto
$boleto->setDescription('Simple Service');
//O CPF do comprador
$boleto->setCustomerCPF('01234567890');//Se for CNPJ use $boleto->setCustomerCNPJ('26668547000153');
//Nome do comprador
$boleto->setCustomerName('Customer Name');
//Email do comprador
$boleto->setCustomerEmail('email@example.org');
//Telefone do comprador
$boleto->setCustomerPhone('55', '32510031');


/*
 * Campos opcionais
 */
//Data de vencimento do boleto no formato de Ano-Mês-Dia. Essa data precisa ser no futuro, e no máximo 30 dias apatir do dia atual.
$boleto->setFirstDueDate(date("Y-m-d", strtotime("+3 days", time())));
//Esse é o numero de boletos a ser gerado.
$boleto->setNumberOfPayments(2);
//Uma referência de quem é o boleto (note que terá multiplos boletos com a mesma referência)
$boleto->setReference('Simple reference');//**
//Instruções para quem irá receber o pagamento
$boleto->setInstructions('Simple instruction');
//CEP do comprador
$boleto->setCustomerAddressPostalCode('97700000');
//Endereço do comprador
$boleto->setCustomerAddressStreet('Av Julio de Castilhos');
//Numero da casa do comprador
$boleto->setCustomerAddressNumber('518');
//Bairro do comprador
$boleto->setCustomerAddressDistrict('Centro');
//Cidade do comprador
$boleto->setCustomerAddressCity('Santiago');
//Estado do comprador
$boleto->setCustomerAddressState('RS');


//Executa a conexão e captura a resposta do PagSeguro.
$data = $boleto->send();

//Você terá uma array de objeto, precisará de uma estrutura de laço para percorrer um a um.
foreach ($data->boletos as $row) {
    echo 'A transação de código ' . $row->code .
        ' que vence em ' . $row->dueDate .
        ' gerou um boleto que pode ser acessado no link ' . $row->paymentLink .
        ' ou pago com o código de barras ' . $row->barcode .
        '<hr>';
}
