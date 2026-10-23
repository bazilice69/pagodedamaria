<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pessoa;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CarteirinhaController extends Controller
{
    /** Pagina da carteirinha, pronta pra imprimir (A frente com foto + QR). */
    public function show(Pessoa $pessoa): View
    {
        // QR aponta para a verificacao publica da carteirinha (pelo codigo).
        $url = route('carteirinha.verificar', $pessoa->codigo ?: $pessoa->id);

        $qr = base64_encode(
            QrCode::format('svg')->size(140)->margin(0)->generate($url)
        );

        return view('admin.pessoas.carteirinha', [
            'pessoa' => $pessoa,
            'qrSvg' => $qr,
            'urlVerificacao' => $url,
        ]);
    }
}
