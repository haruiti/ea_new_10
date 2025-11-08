@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h4 class="mb-4 fw-bold">📊 Painel de Controle Financeiro</h4>

    {{-- DADOS MENSAIS --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Análise Mensal</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0 text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mês/Ano</th>
                        <th>Entrada (R$)</th>
                        <th>Saída (R$)</th>
                        <th>Saldo (R$)</th>
                        <th>Consultas</th>
                        <th>Tratamentos</th>
                        <th>Hipnoses</th>
                        <th>Psicanálises</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dadosMensais as $item)
                        <tr>
                            <td><strong>{{ $item->data }}</strong></td>
                            <td class="text-success">R$ {{ number_format($item->entrada, 2, ',', '.') }}</td>
                            <td class="text-danger">R$ {{ number_format($item->saida, 2, ',', '.') }}</td>
                            <td class="{{ $item->saldo >= 0 ? 'text-success' : 'text-danger' }}">
                                R$ {{ number_format($item->saldo, 2, ',', '.') }}
                            </td>
                            <td>{{ $item->consulta }}</td>
                            <td>{{ $item->tratamento }}</td>
                            <td>{{ $item->sessaohipnose }}</td>
                            <td>{{ $item->sessaopsicanalise }}</td>
                            <td><strong>{{ $item->total_atendimentos }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- COMPARATIVO SEMANAL DE FATURAMENTO --}}
    <div class="card mt-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">📅 Comparativo Semanal de Faturamento (Últimos 3 meses)</h5>
        </div>
        <div class="card-body p-0">
            @php
                $agrupado = [];
                foreach ($dadosSemanais as $dado) {
                    $key = $dado->semana_do_mes;
                    $agrupado[$key][$dado->mes] = $dado;
                }

                // Pegar os 3 meses mais recentes
                $meses = collect($dadosSemanais)->pluck('mes')->unique()->sortDesc()->take(3)->values();
            @endphp

            <table class="table table-striped table-hover mb-0 text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Semana</th>
                        @foreach ($meses as $mes)
                            <th>{{ str_pad($mes, 2, '0', STR_PAD_LEFT) }}/{{ $dadosSemanais[0]->ano }}</th>
                        @endforeach
                        <th>Diferença (R$)</th>
                        <th>Variação (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($agrupado as $semana => $valores)
                        @php
                            $valoresMes = [];
                            foreach ($meses as $mes) {
                                $valoresMes[$mes] = isset($valores[$mes]) ? (float) $valores[$mes]->faturamento : 0;
                            }

                            // Comparar o mês mais recente com o anterior
                            $mesAtual = $meses[0];
                            $mesAnterior = $meses[1] ?? null;

                            $valorAtual = $valoresMes[$mesAtual] ?? 0;
                            $valorAnterior = $mesAnterior ? $valoresMes[$mesAnterior] : 0;

                            $diferenca = $valorAtual - $valorAnterior;
                            $variacao = $valorAnterior > 0 ? ($diferenca / $valorAnterior) * 100 : 0;
                        @endphp

                        <tr>
                            <td><strong>Semana {{ $semana }}</strong></td>
                            @foreach ($meses as $mes)
                                <td>
                                    @if ($valoresMes[$mes] > 0)
                                        R$ {{ number_format($valoresMes[$mes], 2, ',', '.') }}
                                    @else
                                        —
                                    @endif
                                </td>
                            @endforeach
                            <td class="{{ $diferenca >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $diferenca >= 0 ? '+' : '' }}R$ {{ number_format($diferenca, 2, ',', '.') }}
                            </td>
                            <td class="{{ $variacao >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $variacao >= 0 ? '+' : '' }}{{ number_format($variacao, 1, ',', '.') }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
