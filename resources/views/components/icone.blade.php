@props([
    'nome' => 'ponto',
    'cor' => 'marca',
    'tamanho' => 'md',  // sm | md | lg
])

@php
    // Cores base (clara -> escura) para o degrade 3D.
    $cores = [
        'marca'     => ['#2BAE7A', '#0F3326'],
        'terracota' => ['#EBA07E', '#A8562F'],
        'manteiga'  => ['#FBE08C', '#D8A83E'],
        'verde'     => ['#5EE89A', '#15803D'],
        'azul'      => ['#7CC0FF', '#1D4ED8'],
        'vermelho'  => ['#FF8FA3', '#BE123C'],
        'laranja'   => ['#FFC487', '#C2410C'],
        'roxo'      => ['#CFA0FF', '#7E22CE'],
        'rosa'      => ['#FFB6DC', '#BE185D'],
        'cinza'     => ['#C3CAD3', '#4B5563'],
    ];
    [$claro, $escuro] = $cores[$cor] ?? $cores['marca'];

    $dim = ['sm' => '2.25rem', 'md' => '2.75rem', 'lg' => '4rem'][$tamanho] ?? '2.75rem';
    $radius = ['sm' => '.7rem', 'md' => '.9rem', 'lg' => '1.3rem'][$tamanho] ?? '.9rem';
    $svgTam = ['sm' => 'h-5 w-5', 'md' => 'h-6 w-6', 'lg' => 'h-9 w-9'][$tamanho] ?? 'h-6 w-6';

    $paths = [
        'painel'       => '<path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>',
        'relatorios'   => '<path d="M3 3v18h18"/><path d="M7 16l4-4 3 3 5-6"/>',
        'cadastros'    => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'cursos'       => '<path d="M22 10L12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>',
        'doacoes'      => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"/>',
        'despesas'     => '<rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/>',
        'campanhas'    => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>',
        'necessidades' => '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
        'estoque'      => '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>',
        'aprovacoes'   => '<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>',
        'equipe'       => '<path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'voluntarios'  => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>',
        'parceiros'    => '<path d="M11 17a4 4 0 0 1-8 0V5a2 2 0 0 1 2-2h4v14z"/><path d="M13 7h6a2 2 0 0 1 2 2v8a4 4 0 0 1-8 0V7z"/>',
        'termos'       => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>',
        'config'       => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>',
        'site'         => '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
        'sair'         => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>',
        'pessoa'       => '<circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/>',
        'dinheiro'     => '<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
        'caixa'        => '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>',
        'ponto'        => '<circle cx="12" cy="12" r="4"/>',
    ];
    $svg = $paths[$nome] ?? $paths['ponto'];
    $uid = 'ic'.substr(md5($nome.$cor.$tamanho.uniqid()), 0, 6);
@endphp

{{-- Icone 3D GLASS: base em degrade, camada de vidro, reflexo especular e brilho --}}
<span {{ $attributes->merge(['class' => 'ag-icone shrink-0']) }}
      style="--w:{{ $dim }}; --r:{{ $radius }}; --c1:{{ $claro }}; --c2:{{ $escuro }};">
    <span class="ag-icone__reflexo"></span>
    <svg class="{{ $svgTam }} ag-icone__svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
         stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
        {!! $svg !!}
    </svg>
</span>

@once
@push('estilos')
<style>
    .ag-icone{
        position:relative; display:inline-flex; align-items:center; justify-content:center;
        width:var(--w); height:var(--w); border-radius:var(--r);
        background:
            radial-gradient(120% 120% at 30% 20%, color-mix(in srgb, var(--c1) 92%, #fff) 0%, var(--c1) 35%, var(--c2) 100%);
        box-shadow:
            0 6px 14px -4px color-mix(in srgb, var(--c2) 70%, transparent),
            0 2px 4px rgba(0,0,0,.18),
            inset 0 1.5px 1px rgba(255,255,255,.55),
            inset 0 -3px 6px rgba(0,0,0,.28),
            inset 0 0 0 1px rgba(255,255,255,.12);
        overflow:hidden;
    }
    /* reflexo de vidro (brilho especular diagonal no topo) */
    .ag-icone__reflexo{
        position:absolute; inset:0; border-radius:inherit; pointer-events:none;
        background:linear-gradient(135deg, rgba(255,255,255,.65) 0%, rgba(255,255,255,.18) 28%, rgba(255,255,255,0) 52%);
        mix-blend-mode:screen;
    }
    .ag-icone__svg{
        position:relative; z-index:1; color:#fff;
        filter: drop-shadow(0 1.5px 1.5px rgba(0,0,0,.35));
    }
    /* fallback p/ navegadores sem color-mix */
    @supports not (background: color-mix(in srgb, red, blue)){
        .ag-icone{ background:linear-gradient(145deg, var(--c1), var(--c2)); }
    }
</style>
@endpush
@endonce
