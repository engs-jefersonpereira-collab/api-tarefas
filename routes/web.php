<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cotacao/{moeda}', function ($moeda) {
    $moeda = strtoupper($moeda);

    $resposta = Http::get("https://economia.awesomeapi.com.br/json/last/{$moeda}-BRL");

    if ($resposta->failed() || !isset($resposta->json()[$moeda . 'BRL'])) {
        return response()->json(
            ['erro' => 'Moeda não encontrada'],
            404,
            [],
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        );
    }

    $d = $resposta->json()[$moeda . 'BRL'];

    return response()->json([
        'moeda' => $d['name'],
        'compra' => $d['bid'],
        'venda' => $d['ask'],
        'maxima' => $d['high'],
        'minima' => $d['low'],
        'atualizado_em' => $d['create_date'],
    ], 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});