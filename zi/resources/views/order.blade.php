@extends('layouts.myAccountLayout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if($order != NULL)
                    <h1>{{__('messages.tytuly.zamowienie')}} {{ $order->NR }} <span class="status">{{ $order->STATUS }}</span></h1>

                    <hr/>

                    <div class="row document-headline-row">
                        <div class="col-xs-9 col-md-3 zi-text-bold">{{ __('messages.teksty.zamowienie.data') }}</div>
                        <div class="col-xs-3 col-md-3">{{ date('d-m-Y', strtotime($order->CREATE_DATE)) }}</div>

                        <div class="col-xs-9 col-md-3 zi-text-bold">{{ __('messages.teksty.zamowienie.netto') }}</div>
                        <div class="col-xs-3 col-md-3">{{ number_format($order->NET_PRICE, 2, ',', ' ') }}</div>
                    </div>
                    <div class="row document-headline-row">
                        <div class="col-xs-9 col-md-3 zi-text-bold">{{ __('messages.teksty.zamowienie.dataRealizacji') }}</div>
                        <div class="col-xs-3 col-md-3">{{ date('d-m-Y', strtotime($order->REALIZATION_DATE)) }}</div>

                        <div class="col-xs-9 col-md-3 zi-text-bold">{{ __('messages.teksty.zamowienie.brutto') }}</div>
                        <div class="col-xs-3 col-md-3">{{ number_format($order->GROSS_PRICE, 2, ',', ' ') }}</div>

                    </div>

                    @if(count($positions) > 0)
                        <table class="table zi-margin-top-20">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{__('messages.teksty.zamowienie.tabela.nazwa')}}</th>
                                <th>{{__('messages.teksty.zamowienie.tabela.indeks')}}</th>
                                <th>{{__('messages.teksty.zamowienie.tabela.ilosc')}}</th>
                                <th>{{__('messages.teksty.zamowienie.tabela.jednostkaMiary')}}</th>
                                <th>{{__('messages.teksty.zamowienie.tabela.cena')}}</th>
                                <th>{{__('messages.teksty.zamowienie.tabela.rabat')}}</th>
                                <th>{{__('messages.teksty.zamowienie.tabela.wartosc')}}</th>
                                <th>{{__('messages.teksty.zamowienie.tabela.zrealizowano')}}</th>
                                <th>{{__('messages.teksty.zamowienie.tabela.status')}}</th>
                            </tr>
                            </thead>
                            <tbody>


                            @foreach ($positions as $position)
                                <tr>
                                    <th scope="row">{{ $position->NR }}</th>
                                    <td>{{ $position->NAME }}</td>
                                    <td>{{ $position->INDEKS }}</td>
                                    <td class="zi-column-number-right">{{ number_format($position->QUANTITY, $position->QUANTITY_PRECISION, ',', ' ') }}</td>
                                    <td>{{ $position->QUANTITY_UNIT }}</td>
                                    <td class="zi-column-number-right">{{ number_format($position->PRICE, 2, ',', ' ')}}</td>
                                    <td>{{ $position->DISCOUNT}}</td>
                                    <td class="zi-column-number-right">{{ number_format($position->VALUE1, 2, ',', ' ') }}</td>
                                    <td class="zi-column-number-right">{{ number_format($position->ISSUED, 2, ',', ' ') }}</td>
                                    <td>{{ $position->STATUS}}</td>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <p>{{__('messages.teksty.zamowienie.brakPozycji')}}</p>
                    @endif
                @else
                    <p>{{__('messages.teksty.zamowienie.nieIstnieje')}}</p>
                @endif
            </div>
        </div>

    </div>
@endsection
