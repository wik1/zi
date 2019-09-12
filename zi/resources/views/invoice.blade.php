@extends('layouts.myAccountLayout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if($headline != NULL)
                    <h1>{{__('messages.tytuly.faktura')}} {{ $headline->DOC_NAME }} <span
                                class="status">{{ $headline->STATUS }}</span></h1>

                    <hr/>

                    <div class="row document-headline-row">
                        <div class="col-xs-9 col-md-3 zi-text-bold">{{ __('messages.teksty.faktura.data') }}</div>
                        <div class="col-xs-3 col-md-3">{{ date('d-m-Y', strtotime($headline->CREATE_DATE)) }}</div>

                        <div class="col-xs-9 col-md-3 zi-text-bold">{{ __('messages.teksty.faktura.netto') }}</div>
                        <div class="col-xs-3 col-md-3">{{ number_format($headline->NET_PRICE, 2, ',', ' ') }}</div>
                    </div>
                    <div class="row document-headline-row">
                        <div class="col-xs-9 col-md-3 zi-text-bold">{{ __('messages.teksty.faktura.dataPlatnosci') }}</div>
                        <div class="col-xs-3 col-md-3">{{ date('d-m-Y', strtotime($headline->PAYMENT_DATE)) }}</div>

                        <div class="col-xs-9 col-md-3 zi-text-bold">{{ __('messages.teksty.faktura.brutto') }}</div>
                        <div class="col-xs-3 col-md-3">{{ number_format($headline->GROSS_PRICE, 2, ',', ' ') }}</div>
                    </div>


                    @if(count($positions) > 0)
                        <table class="table zi-margin-top-40">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{__('messages.teksty.faktura.tabela.nazwa')}}</th>
                                <th>{{__('messages.teksty.faktura.tabela.indeks')}}</th>
                                <th>{{__('messages.teksty.faktura.tabela.ilosc')}}</th>
                                <th>{{__('messages.teksty.faktura.tabela.jednostkaMiary')}}</th>
                                @if ($headline->IS_NETTO)
                                    <th>{{__('messages.teksty.faktura.tabela.cenaNetto')}}</th>
                                @else
                                    <th>{{__('messages.teksty.faktura.tabela.cenaBrutto')}}</th>
                                @endif
                                <th>{{__('messages.teksty.faktura.tabela.rabat')}}</th>
                                <th>{{__('messages.teksty.faktura.tabela.wartosc')}}</th>
                            </tr>
                            </thead>
                            <tbody>


                            @foreach ($positions as $position)
                                <tr>
                                    <th scope="row">{{ $position->NR }}</th>
                                    <td>{{ $position->NAME }}</td>
                                    <td>{{ $position->INDEKS }}</td>
                                    <td class="zi-column-number-right">{{ number_format($position->QUANTITY, $position->QUANTITY_PRECISION, ',', ' ')}}</td>
                                    <td>{{ $position->QUANTITY_UNIT }}</td>
                                    <td class="zi-column-number-right">{{ number_format($position->PRICE, 2, ',', ' ')}}</td>
                                    <td>{{ $position->DISCOUNT}}</td>
                                    <td class="zi-column-number-right">{{ number_format($position->VALUE1, 2, ',', ' ')}}</td>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <p>{{__('messages.teksty.faktura.brakPozycji')}}</p>
                    @endif
                @else
                    <p>{{__('messages.teksty.faktura.nieIstnieje')}}</p>
                @endif
            </div>
        </div>

    </div>
@endsection