{{-- Botao de excluir com confirmacao. Uso: @include('admin.partials.botao-excluir', ['rota' => route(...)]) --}}
<form method="POST" action="{{ $rota }}" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir? Esta ação não pode ser desfeita.');">
    @csrf
    @method('DELETE')
    <button type="submit" class="text-red-600 hover:underline ml-2">excluir</button>
</form>
