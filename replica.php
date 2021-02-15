<?php

/**
*@Luis Castro
*@realizar cobro por collet y reversar inmediatamente
*@07/09/2020 Año de la pandemia del covid2
**/

    $login ='';
    $trankey = '';
    $numCard = '36545400000008';
    //4110760000000008
    $auth = new stdClass();
    $auth->login = $login;
    $auth->nonce = 'abc123toma';
    $auth->seed = date('c');
    $auth->tranKey = base64_encode(hash('sha1', $auth->nonce . $auth->seed . $trankey , true));
    $auth->nonce = base64_encode($auth->nonce);
    //tarjeta de pruebas
    $card = new stdClass();
    $card->number = $numCard;
    $card->expirationMonth = "11";
    $card->expirationYear = "22";
    $card->cvv="123";
    //tokent
    $token = new stdClass();
    $token->token = "a195a6a1d2648bd788139412056decb60de3b6083efbebf79a051afe4029d4ba";
    //informacion del pagador
    $payer = new stdClass();
    $payer->name = "Gretchen";
    $payer->surname = "Jakubowski";
    $payer->email = "pruebasp2pec@gmail.com";
    $payer->document = "1040035020";
    $payer->documentType = "CI";
    //instrmento de paso Tarjeta o token
    $instrument = new stdClass();
    $instrument->token = $token;
    //monto de el pago
    $amount = new stdClass();
    $amount->currency = "USD";
    $amount->total = 1;
    //monto de la trx
    $payment = new stdClass();
    $payment->reference = uniqid();
    $payment->description = 'A payment collect example';
    $payment->amount = $amount;
    //obj json
    $request = new stdClass();
    $request->auth = $auth;
    $request->instrument = $instrument;
    $request->payment = $payment;
    $request->payer= $payer;

    //Se realiza  el collet.
    $elJson= json_encode($request);
    $url = "https://test.placetopay.ec/redirection/api/collect";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $elJson);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=UTF-8', 'User-Agent: cUrl Testing'));
    $result = curl_exec($ch);
    $respuesta = json_decode($result,true);
    $referenciaInterna = $respuesta["payment"][0]["internalReference"];

    //reversar la transacion
    $url2 = "https://test.placetopay.ec/redirection/api/reverse";
    $request2 = new stdClass();
    $request2->auth = $auth;
    $request2->internalReference = $referenciaInterna;
    $elJson2= json_encode($request2);
    $ch = curl_init($url2);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $elJson2);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=UTF-8', 'User-Agent: cUrl Testing'));
    $result2 = curl_exec($ch);
    $respuesta2 = json_decode($result2,true);

    var_dump($respuesta2);


 ?>
