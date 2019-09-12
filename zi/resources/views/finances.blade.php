@extends('layouts.myAccountLayout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="row document-headline-row">
                    <div class="col-xs-9 col-md-3 zi-text-bold">{{ __('messages.teksty.finanse.naglowek.saldo') }}</div>
                    <div class="col-xs-3 col-md-3">{{ number_format($headline->SALDO, 2, ',', ' ') }}</div>

                    <div class="col-xs-9 col-md-3 zi-text-bold">{{ __('messages.teksty.finanse.naglowek.limit') }}</div>
                    <div class="col-xs-3 col-md-3">{{ number_format($headline->LIMIT, 2, ',', ' ') }}</div>
                </div>
                <div class="row document-headline-row">
                    <div class="col-xs-9 col-md-3 zi-text-bold">{{ __('messages.teksty.finanse.naglowek.przeterminowanePlatnosci') }}</div>
                    <div class="col-xs-3 col-md-3">{{ number_format($headline->OVERDUE_PAYMENTS, 2, ',', ' ') }}</div>

                    <div class="col-xs-9 col-md-3 zi-text-bold">{{ __('messages.teksty.finanse.naglowek.limitDoWykorzystania') }}</div>
                    <div class="col-xs-3 col-md-3">{{ number_format($headline->LIMIT_LEFT, 2, ',', ' ') }}</div>

                </div>

                <div class="row zi-margin-top-40">
                    @if(count($overdueDocuments) > 0)
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{__('messages.teksty.finanse.tabela.dokument')}}</th>
                                <th>{{__('messages.teksty.finanse.tabela.data')}}</th>
                                <th>{{__('messages.teksty.finanse.tabela.brutto')}}</th>
                                <th>{{__('messages.teksty.finanse.tabela.zaplacono')}}</th>
                                <th class="min-width-95">{{__('messages.teksty.finanse.tabela.doZaplaty')}}</th>
                                <th class="min-width-95">{{__('messages.teksty.finanse.tabela.poTerminie')}}</th>
                                <th>{{__('messages.teksty.finanse.tabela.dataPlatnosci')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($overdueDocuments as $doc)
                                <tr>
                                    <td>{{ $doc->DOCUMENT }}</td>
                                    <td class="zi-date-column">{{ date('d-m-Y', strtotime($doc->DATE1)) }}</td>
                                    <td class="zi-column-number-right">{{ number_format($doc->VALUE1, 2, ',', ' ') }}</td>
                                    <td class="zi-column-number-right">{{ number_format($doc->PAYED, 2, ',', ' ') }}</td>
                                    <td class="zi-column-number-right">{{ number_format($doc->TO_PAY, 2, ',', ' ') }}</td>
                                    <td class="zi-column-number-right">{{ number_format($doc->OVERDUE, 0, ',', ' ') }}</td>
                                    <td class="zi-date-column">{{ date('d-m-Y', strtotime($doc->PAYMENT_DATE)) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>{{__('messages.teksty.finanse.tabela.brak')}}</p>
                    @endif
                </div>

            </div>
        </div>

    </div>
@endsection
