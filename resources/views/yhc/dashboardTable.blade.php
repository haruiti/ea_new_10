<table id="datatable-dashboard" class="table table-striped table-bordered table">
    <thead class="thead-th">
        <tr>
            <th>Data</th>
            <th>Entrada</th>
            <th>Cosulta</th>
            <th>Tratamento</th>
            <th>Sessão Hipnose</th>
            <th>Sessão Psicanálise</th>
            <th>Atendimento</th>
            <th>marketing</th>
            <th>transporte</th>
            <th>sala</th>
            <th>alimentacao</th>
            <th>material</th>
            <th>Saída</th>
            <th>Saldo</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($dados))

        @foreach($dados as $dado => $value)
        <tr>
            <td class="td" data-th="Data:&nbsp&nbsp">
                <span>
                {{$value['data']}}
                </span>
            </td>

            <td class="td" data-th="Entrada:&nbsp&nbsp">
                <span >
                {{ $value['entrada'] }}
                </span>
            </td>
            <td class="td" data-th="Consulta:&nbsp&nbsp">
                <span >
                {{ $value['consulta'] }}
                </span>
            </td>
            <td class="td" data-th="Tratamento:&nbsp&nbsp">
                <span >
                {{ $value['tratamento'] }}
                </span>
            </td>
            <td class="td" data-th="SessaoHipnose:&nbsp&nbsp">
                <span >
                {{ $value['sessaohipnose'] }}
                </span>
            </td>
            <td class="td" data-th="SessaoPsicanalise:&nbsp&nbsp">
                <span >
                {{ $value['sessaopsicanalise'] }}
                </span>
            </td>
            <td class="td" data-th="Atendimento:&nbsp&nbsp">
                <span >
       
                </span>
            </td>
            <td class="td" data-th="Entrada:&nbsp&nbsp">
                <span >
                {{ $value['marketing'] }}
                </span>
            </td>
            <td class="td" data-th="Entrada:&nbsp&nbsp">
                <span >
                {{ $value['transporte'] }}
                </span>
            </td>
            <td class="td" data-th="Entrada:&nbsp&nbsp">
                <span >
                {{ $value['sala'] }}
                </span>
            </td>
            <td class="td" data-th="Entrada:&nbsp&nbsp">
                <span >
                {{ $value['alimentacao'] }}
                </span>
            </td>
            <td class="td" data-th="Entrada:&nbsp&nbsp">
                <span >
                {{ $value['material'] }}
                </span>
            </td>
            <td class="td" data-th="saída:&nbsp&nbsp">
                <span>

                {{ $value['saida'] }}
                </span>
            </td>
            <td class="td" data-th="Saldo:&nbsp&nbsp">
                <span>
                {{ $value['saldo'] }}
                </span>
            </td>

        </tr>
        @endforeach
        @endif
    </tbody>
</table>

