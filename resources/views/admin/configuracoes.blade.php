@extends('layouts.admin')
@section('titulo', 'Configurações')

@section('conteudo')
<div class="max-w-3xl">
    <h1 class="text-2xl md:text-3xl font-extrabold text-marca mb-1">⚙️ Configurações da Associação</h1>
    <p class="text-cafe/60 text-sm mb-6">Esses dados aparecem no site público (header, rodapé, Pix, contato).</p>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 rounded-xl p-4 text-sm">
            <ul class="list-disc list-inside">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.configuracoes.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Identidade --}}
        <div class="bg-white rounded-xl2 shadow-sm p-6 space-y-4">
            <h2 class="font-bold text-marca">Identidade</h2>
            <div>
                <label class="block text-sm font-semibold mb-1">Nome da associação *</label>
                <input name="nome" value="{{ old('nome', $config->nome) }}" required class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Logo</label>
                <div class="flex items-center gap-4">
                    @php $logoAtual = $config->logo ? asset('storage/'.$config->logo) : (file_exists(public_path('img/logo.png')) ? asset('img/logo.png') : null); @endphp
                    @if ($logoAtual)<img src="{{ $logoAtual }}" class="h-16 w-16 object-contain rounded-lg bg-creme p-1">@endif
                    <input type="file" name="logo" accept="image/*" class="text-sm">
                </div>
                <p class="text-xs text-cafe/50 mt-1">PNG com fundo transparente até 2 MB.</p>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Sobre o trabalho (frase curta)</label>
                <textarea name="sobre_trabalho" rows="2" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">{{ old('sobre_trabalho', $config->sobre_trabalho) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">História</label>
                <textarea name="historia" rows="4" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">{{ old('historia', $config->historia) }}</textarea>
            </div>
        </div>

        {{-- Contato / redes --}}
        <div class="bg-white rounded-xl2 shadow-sm p-6 space-y-4">
            <h2 class="font-bold text-marca">Contato e redes</h2>
            <div class="grid sm:grid-cols-2 gap-4">
                <div><label class="block text-sm font-semibold mb-1">Telefone</label><input name="telefone" value="{{ old('telefone', $config->telefone) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
                <div><label class="block text-sm font-semibold mb-1">WhatsApp</label><input name="whatsapp" value="{{ old('whatsapp', $config->whatsapp) }}" placeholder="5511999999999" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
                <div><label class="block text-sm font-semibold mb-1">E-mail</label><input type="email" name="email" value="{{ old('email', $config->email) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
                <div><label class="block text-sm font-semibold mb-1">Horário de atendimento</label><input name="horario_atendimento" value="{{ old('horario_atendimento', $config->horario_atendimento) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
                <div><label class="block text-sm font-semibold mb-1">Instagram</label><input name="instagram" value="{{ old('instagram', $config->instagram) }}" placeholder="@usuario" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
                <div><label class="block text-sm font-semibold mb-1">Facebook (link)</label><input name="facebook" value="{{ old('facebook', $config->facebook) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
            </div>
            <div class="grid sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2"><label class="block text-sm font-semibold mb-1">Endereço</label><input name="endereco" value="{{ old('endereco', $config->endereco) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
                <div><label class="block text-sm font-semibold mb-1">Cidade/UF</label>
                    <div class="flex gap-2">
                        <input name="cidade" value="{{ old('cidade', $config->cidade) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                        <input name="estado" maxlength="2" value="{{ old('estado', $config->estado) }}" placeholder="SP" class="w-16 rounded-lg border-cafe/20 focus:border-marca focus:ring-marca uppercase">
                    </div>
                </div>
            </div>
        </div>

        {{-- Pix --}}
        <div class="bg-white rounded-xl2 shadow-sm p-6 space-y-4">
            <h2 class="font-bold text-marca">💰 Pix para doações</h2>
            <div class="grid sm:grid-cols-2 gap-4">
                <div><label class="block text-sm font-semibold mb-1">Chave Pix</label><input name="pix_chave" value="{{ old('pix_chave', $config->pix_chave) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
                <div><label class="block text-sm font-semibold mb-1">Tipo da chave</label>
                    <select name="pix_tipo" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                        @foreach (['', 'cpf','cnpj','email','telefone','aleatoria'] as $t)
                            <option value="{{ $t }}" @selected(old('pix_tipo', $config->pix_tipo) === $t)>{{ $t === '' ? '—' : ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-semibold mb-1">Nome do recebedor</label><input name="pix_nome_recebedor" value="{{ old('pix_nome_recebedor', $config->pix_nome_recebedor) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
                <div><label class="block text-sm font-semibold mb-1">Cidade do recebedor</label><input name="pix_cidade" value="{{ old('pix_cidade', $config->pix_cidade) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
            </div>
        </div>

        {{-- Impacto --}}
        <div class="bg-white rounded-xl2 shadow-sm p-6 space-y-4">
            <h2 class="font-bold text-marca">📊 Impacto (números públicos)</h2>
            <p class="text-xs text-cafe/50">Apenas totais — nunca exponha dados pessoais das crianças.</p>
            <div class="grid sm:grid-cols-2 gap-4">
                <div><label class="block text-sm font-semibold mb-1">Crianças atendidas</label><input type="number" min="0" name="criancas_atendidas" value="{{ old('criancas_atendidas', $config->criancas_atendidas) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
                <div><label class="block text-sm font-semibold mb-1">Famílias atendidas</label><input type="number" min="0" name="familias_atendidas" value="{{ old('familias_atendidas', $config->familias_atendidas) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
            </div>
        </div>

        <div class="flex gap-3">
            <button class="btn-marca">💾 Salvar configurações</button>
        </div>
    </form>
</div>
@endsection
