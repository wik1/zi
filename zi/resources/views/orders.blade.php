@extends('layouts.myAccountLayout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                @if(count($orders) > 0)
                    <table class="table table-fixed">
                        <thead>
                        <tr>
                            <th>{{__('messages.teksty.zamowienia.dokument')}}</th>
                            <th>{{__('messages.teksty.zamowienia.data')}}</th>
                            <th>{{__('messages.teksty.zamowienia.dataRealizacji')}}</th>
                            <th>{{__('messages.teksty.zamowienia.netto')}}</th>
                            <th>{{__('messages.teksty.zamowienia.brutto')}}</th>
                            <th>{{__('messages.teksty.zamowienia.status')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($orders as $order)
                            <tr class='clickable-row' data-id='{{ $order->ID }}'>
                                <td>{{ $order->NR }}</td>
                                <td>{{ date('d-m-Y', strtotime($order->CREATE_DATE)) }}</td>
                                <td>{{ date('d-m-Y', strtotime($order->REALIZATION_DATE)) }}</td>
                                <td class="zi-column-number-right">{{ number_format($order->NET_PRICE, 2, ',', ' ') }}</td>
                                <td class="zi-column-number-right">{{ number_format($order->GROSS_PRICE, 2, ',', ' ') }}</td>
                                <td>{{ $order->STATUS }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p>{{__('messages.teksty.zamowienia.brak')}}</p>
                @endif

            </div>
        </div>

    </div>
    <script>
        $(function () {
            $(".clickable-row").click(function() {
                window.location = "/account/orders/" + $(this).data("id");
            });
        });
    </script>
@endsection
