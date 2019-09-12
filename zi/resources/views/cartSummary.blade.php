@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('messages.teksty.koszyk.podsumowanieTytul')}}</h1>

                <hr/>

                <form id="cartSummaryForm" action="/cart/summary/update" class="form-inline" method="post">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>{{__('messages.teksty.koszyk.nazwa')}}</th>
                            <th>{{__('messages.teksty.koszyk.wybor')}}</th>
                            <th>{{__('messages.teksty.koszyk.wartosc')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($cartSummary as $cartSummaryItem)
                            <tr>
                                <td>{{ $cartSummaryItem->NAZWA }}</td>
                                <td>
                                    @if ($cartSummaryItem->ID == 'PLAT')
                                        <select class="form-control" name="paymentType">
                                            @foreach ($paymentTypes as $paymentType)
                                                <option value="{{$paymentType->KOD}}"
                                                    @if ($paymentType->KOD == $cartSummaryItem->KOD)
                                                        selected
                                                    @endif
                                                >{{$paymentType->NAZWA_TERMIN}}</option>
                                            @endforeach
                                        </select>
                                    @elseif ($cartSummaryItem->ID == 'SRTRANS')
                                        <select class="form-control" name="transportType">
                                            @foreach ($transportTypes as $transportType)
                                                <option value="{{$transportType->KOD}}"
                                                    @if ($transportType->KOD == $cartSummaryItem->KOD)
                                                        selected
                                                    @endif
                                                >{{$transportType->NAZWA}}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        {{ $cartSummaryItem->TRESC }}
                                    @endif
                                </td>
                                <td>{{ number_format($cartSummaryItem->WARTOSC, 2, ',', ' ') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {!! csrf_field() !!}
                </form>
            </div>
        </div>
        <div class="row zi-margin-top-20">
            <div class="col-md-6">
                <a class="btn btn-primary" href="/cart">Powrót</a>
            </div>
            <div class="col-md-6 text-right">
                <form method="post" action="/cart/submit">
                    {!! csrf_field() !!}
                    <button class="btn btn-primary" type="submit">{{__('messages.teksty.koszyk.realizuj')}}</button>
                </form>

            </div>
        </div>
    </div>
    <script>
        $(function () {
            $('.form-control').focusout(function (event) {
                common.showGlobalSpinner();
                document.getElementById("cartSummaryForm").submit();
            });
            $('.form-control').change(function (event) {
                common.showGlobalSpinner();
                document.getElementById("cartSummaryForm").submit();
            });
        });
    </script>
@endsection
