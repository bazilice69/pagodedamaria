<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class CepController extends Controller
{
    /**
     * Busca endereco pelo CEP (ViaCEP). Proxy no servidor para funcionar mesmo
     * quando o navegador do usuario nao chama APIs externas direto.
     */
    public function buscar(string $cep): JsonResponse
    {
        $cep = preg_replace('/\D/', '', $cep);

        if (strlen($cep) !== 8) {
            return response()->json(['erro' => 'CEP inválido'], 422);
        }

        try {
            $resp = Http::timeout(8)->get("https://viacep.com.br/ws/{$cep}/json/");

            if ($resp->failed() || ($resp->json('erro') ?? false)) {
                return response()->json(['erro' => 'CEP não encontrado'], 404);
            }

            $d = $resp->json();

            return response()->json([
                'logradouro' => $d['logradouro'] ?? '',
                'bairro' => $d['bairro'] ?? '',
                'cidade' => $d['localidade'] ?? '',
                'estado' => $d['uf'] ?? '',
            ]);
        } catch (\Throwable $e) {
            return response()->json(['erro' => 'Não foi possível consultar o CEP agora'], 503);
        }
    }
}
