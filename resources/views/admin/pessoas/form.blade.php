@extends('layouts.admin')
@section('titulo', $pessoa->exists ? 'Editar cadastro' : 'Novo cadastro')

@section('conteudo')
@php
    // Prepara a lista inicial de beneficios para o Alpine (evita PHP complexo no @json inline).
    $beneficiosIniciais = old('beneficios');
    if ($beneficiosIniciais === null) {
        $beneficiosIniciais = $pessoa->exists
            ? $pessoa->beneficios->map(function ($b) {
                return [
                    'tipo_beneficio_id' => $b->tipo_beneficio_id,
                    'tipo_livre' => $b->tipo_livre,
                    'valor' => $b->valor,
                    'numero_nis' => $b->numero_nis,
                ];
            })->values()->all()
            : [];
    }
@endphp
<div class="max-w-3xl" x-data="cadastroPessoa()">
    <a href="{{ route('admin.pessoas.index') }}" class="text-terracota text-sm font-semibold hover:underline">← voltar</a>
    <h1 class="text-2xl font-extrabold text-marca mt-2 mb-6">{{ $pessoa->exists ? 'Editar cadastro' : 'Novo cadastro' }}</h1>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 rounded-xl p-4 text-sm">
            <ul class="list-disc list-inside">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" enctype="multipart/form-data"
          action="{{ $pessoa->exists ? route('admin.pessoas.update', $pessoa) : route('admin.pessoas.store') }}"
          class="space-y-6">
        @csrf
        @if ($pessoa->exists) @method('PUT') @endif

        {{-- Dados pessoais --}}
        <div class="bg-white rounded-xl2 shadow-sm p-6 space-y-4">
            <h2 class="font-bold text-marca">Dados pessoais</h2>
            <div class="flex items-center gap-4">
                @if ($pessoa->foto)<img src="{{ asset('storage/'.$pessoa->foto) }}" class="h-20 w-20 rounded-full object-cover">@else<span class="h-20 w-20 rounded-full bg-creme flex items-center justify-center text-2xl">👤</span>@endif
                <div>
                    <label class="block text-sm font-semibold mb-1">Foto de perfil</label>
                    <input type="file" name="foto" accept="image/*" class="text-sm">
                    <p class="text-xs text-cafe/50 mt-1">Aparece na carteirinha. JPG/PNG até 4 MB.</p>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div><label class="block text-sm font-semibold mb-1">Nome completo *</label><input name="nome" value="{{ old('nome', $pessoa->nome) }}" required class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
                <div><label class="block text-sm font-semibold mb-1">Apelido</label><input name="apelido" value="{{ old('apelido', $pessoa->apelido) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
                <div><label class="block text-sm font-semibold mb-1">Nascimento</label><input type="date" name="nascimento" value="{{ old('nascimento', optional($pessoa->nascimento)->format('Y-m-d')) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
                <div><label class="block text-sm font-semibold mb-1">Sexo</label>
                    <select name="sexo" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                        <option value="">—</option>
                        @foreach (['Feminino','Masculino','Outro'] as $s)<option @selected(old('sexo',$pessoa->sexo)===$s)>{{ $s }}</option>@endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-semibold mb-1">CPF</label><input name="cpf" value="{{ old('cpf', $pessoa->cpf) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
                <div><label class="block text-sm font-semibold mb-1">RG</label><input name="rg" value="{{ old('rg', $pessoa->rg) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
            </div>
        </div>

        {{-- Contato --}}
        <div class="bg-white rounded-xl2 shadow-sm p-6 space-y-4">
            <h2 class="font-bold text-marca">Contato</h2>
            <div class="grid sm:grid-cols-3 gap-4">
                <div><label class="block text-sm font-semibold mb-1">Telefone</label><input name="telefone" value="{{ old('telefone', $pessoa->telefone) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
                <div><label class="block text-sm font-semibold mb-1">WhatsApp</label><input name="whatsapp" value="{{ old('whatsapp', $pessoa->whatsapp) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
                <div><label class="block text-sm font-semibold mb-1">E-mail</label><input type="email" name="email" value="{{ old('email', $pessoa->email) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
            </div>
        </div>

        {{-- Endereco com CEP --}}
        <div class="bg-white rounded-xl2 shadow-sm p-6 space-y-4">
            <h2 class="font-bold text-marca">Endereço</h2>
            <div class="grid sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">CEP</label>
                    <input name="cep" x-ref="cep" value="{{ old('cep', $pessoa->cep) }}" @blur="buscarCep()" placeholder="00000-000" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                    <p x-show="cepMsg" x-text="cepMsg" class="text-xs mt-1" :class="cepErro ? 'text-red-600' : 'text-cafe/50'"></p>
                </div>
                <div class="sm:col-span-3"><label class="block text-sm font-semibold mb-1">Logradouro</label><input name="logradouro" x-ref="logradouro" value="{{ old('logradouro', $pessoa->logradouro) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
                <div><label class="block text-sm font-semibold mb-1">Número</label><input name="numero" value="{{ old('numero', $pessoa->numero) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
                <div><label class="block text-sm font-semibold mb-1">Complemento</label><input name="complemento" value="{{ old('complemento', $pessoa->complemento) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
                <div><label class="block text-sm font-semibold mb-1">Bairro</label><input name="bairro" x-ref="bairro" value="{{ old('bairro', $pessoa->bairro) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
                <div class="grid grid-cols-3 gap-2">
                    <div class="col-span-2"><label class="block text-sm font-semibold mb-1">Cidade</label><input name="cidade" x-ref="cidade" value="{{ old('cidade', $pessoa->cidade) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
                    <div><label class="block text-sm font-semibold mb-1">UF</label><input name="estado" x-ref="estado" maxlength="2" value="{{ old('estado', $pessoa->estado) }}" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca uppercase"></div>
                </div>
            </div>
        </div>

        {{-- Papeis --}}
        <div class="bg-white rounded-xl2 shadow-sm p-6 space-y-4">
            <h2 class="font-bold text-marca">Papéis nesta associação</h2>
            <p class="text-xs text-cafe/50">A mesma pessoa pode ter vários papéis (ex: uma família que doa E recebe).</p>
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2">
                @foreach (['e_aluno'=>'🧒 Aluno(a)','e_responsavel'=>'👪 Responsável','e_voluntario'=>'🚗 Voluntário','e_doador'=>'❤️ Doador','e_beneficiario'=>'📦 Beneficiário'] as $campo => $label)
                    <label class="flex items-center gap-2 text-sm bg-creme rounded-lg px-3 py-2 cursor-pointer">
                        <input type="checkbox" name="{{ $campo }}" value="1" @checked(old($campo, $pessoa->$campo)) class="rounded text-marca focus:ring-marca"> {{ $label }}
                    </label>
                @endforeach
            </div>
            <div class="grid sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-sm font-semibold mb-1">Responsável (se for criança)</label>
                    <select name="responsavel_id" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">
                        <option value="">—</option>
                        @foreach ($responsaveis as $r)<option value="{{ $r->id }}" @selected(old('responsavel_id', $pessoa->responsavel_id) == $r->id)>{{ $r->nome }}</option>@endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-semibold mb-1">Parentesco</label><input name="parentesco" value="{{ old('parentesco', $pessoa->parentesco) }}" placeholder="mãe, pai, avó..." class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca"></div>
            </div>
        </div>

        {{-- Autorizacao de imagem --}}
        <div class="bg-white rounded-xl2 shadow-sm p-6">
            <label class="flex items-start gap-3">
                <input type="checkbox" name="autoriza_imagem" value="1" @checked(old('autoriza_imagem', $pessoa->autoriza_imagem)) class="mt-1 rounded text-marca focus:ring-marca">
                <span>
                    <span class="font-bold text-marca">📸 Autoriza uso de imagem</span>
                    <span class="block text-sm text-cafe/60">Marque apenas se houver autorização do responsável. Sem isso, a foto não será usada publicamente.</span>
                </span>
            </label>
        </div>

        {{-- Beneficios sociais --}}
        <div class="bg-white rounded-xl2 shadow-sm p-6 space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-marca">🧾 Benefícios sociais recebidos</h2>
                <button type="button" @click="addBeneficio()" class="text-sm text-terracota font-semibold">+ adicionar</button>
            </div>
            <p class="text-xs text-cafe/50">Ex: Bolsa Família, BPC/LOAS. Ajuda a entender a situação da família.</p>

            <template x-for="(b, i) in beneficios" :key="i">
                <div class="grid sm:grid-cols-12 gap-2 items-start bg-creme/50 rounded-lg p-3">
                    <div class="sm:col-span-5">
                        <select :name="`beneficios[${i}][tipo_beneficio_id]`" x-model="b.tipo_beneficio_id" class="w-full rounded-lg border-cafe/20 text-sm focus:border-marca focus:ring-marca">
                            <option value="">— tipo —</option>
                            @foreach ($tiposBeneficio as $t)<option value="{{ $t->id }}">{{ $t->nome }}</option>@endforeach
                        </select>
                        <input :name="`beneficios[${i}][tipo_livre]`" x-model="b.tipo_livre" placeholder="ou digite outro" class="w-full mt-1 rounded-lg border-cafe/20 text-sm focus:border-marca focus:ring-marca">
                    </div>
                    <div class="sm:col-span-3"><input type="number" step="0.01" :name="`beneficios[${i}][valor]`" x-model="b.valor" placeholder="Valor R$" class="w-full rounded-lg border-cafe/20 text-sm focus:border-marca focus:ring-marca"></div>
                    <div class="sm:col-span-3"><input :name="`beneficios[${i}][numero_nis]`" x-model="b.numero_nis" placeholder="NIS (opcional)" class="w-full rounded-lg border-cafe/20 text-sm focus:border-marca focus:ring-marca"></div>
                    <div class="sm:col-span-1 flex justify-end"><button type="button" @click="beneficios.splice(i,1)" class="text-red-500 text-lg">×</button></div>
                </div>
            </template>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Observações</label>
            <textarea name="observacao" rows="2" class="w-full rounded-lg border-cafe/20 focus:border-marca focus:ring-marca">{{ old('observacao', $pessoa->observacao) }}</textarea>
        </div>

        <input type="hidden" name="ativo" value="1">
        <div class="flex gap-3">
            <button class="btn-marca">{{ $pessoa->exists ? 'Salvar cadastro' : 'Criar cadastro' }}</button>
            <a href="{{ route('admin.pessoas.index') }}" class="px-6 py-3 rounded-full font-semibold text-cafe/70 hover:bg-creme">Cancelar</a>
        </div>
    </form>
</div>

<script>
    function cadastroPessoa() {
        return {
            cepMsg: '', cepErro: false,
            beneficios: @json($beneficiosIniciais),
            addBeneficio() { this.beneficios.push({ tipo_beneficio_id: '', tipo_livre: '', valor: '', numero_nis: '' }); },
            async buscarCep() {
                const cep = (this.$refs.cep.value || '').replace(/\D/g, '');
                if (cep.length !== 8) return;
                this.cepMsg = 'Buscando...'; this.cepErro = false;
                try {
                    const r = await fetch(`{{ url('painel/cep') }}/${cep}`, { headers: { 'Accept': 'application/json' } });
                    if (!r.ok) throw new Error();
                    const d = await r.json();
                    if (!this.$refs.logradouro.value) this.$refs.logradouro.value = d.logradouro || '';
                    if (!this.$refs.bairro.value) this.$refs.bairro.value = d.bairro || '';
                    this.$refs.cidade.value = d.cidade || this.$refs.cidade.value;
                    this.$refs.estado.value = d.estado || this.$refs.estado.value;
                    this.cepMsg = '✓ endereço preenchido';
                } catch (e) {
                    this.cepErro = true; this.cepMsg = 'Não encontrei esse CEP (preencha à mão)';
                }
            },
        }
    }
</script>
@endsection
