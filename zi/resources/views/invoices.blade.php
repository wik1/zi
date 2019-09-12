@extends('layouts.myAccountLayout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                @if(count($invoices) > 0)
                    <table class="table">
                        <thead>
                        <tr>
                            <th>{{__('messages.teksty.faktury.dokument')}}</th>
                            <th>{{__('messages.teksty.faktury.data')}}</th>
                            <th>{{__('messages.teksty.faktury.dataPlatnosci')}}</th>
                            <th>{{__('messages.teksty.faktury.netto')}}</th>
                            <th>{{__('messages.teksty.faktury.vat')}}</th>
                            <th>{{__('messages.teksty.faktury.brutto')}}</th>
                            <th>{{__('messages.teksty.faktury.status')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($invoices as $invoice)
                            <tr class='clickable-row' data-id='{{ $invoice->ID }}'>
                                {{--<td><a href="/account/invoices/{{ $invoice->ID }}">{{ $invoice->NR }}</a></td>--}}
                                <td>{{ $invoice->DOC_NAME }}</td>
                                <td>{{ date('d-m-Y', strtotime($invoice->CREATE_DATE)) }}</td>
                                <td>{{ date('d-m-Y', strtotime($invoice->PAYMENT_DATE)) }}</td>
                                <td class="zi-column-number-right">{{ number_format($invoice->NETTO, 2, ',', ' ') }}</td>
                                <td class="zi-column-number-right">{{ number_format($invoice->VAT, 2, ',', ' ') }}</td>
                                <td class="zi-column-number-right">{{ number_format($invoice->BRUTTO, 2, ',', ' ') }}</td>
                                <td>{{ $invoice->STATUS }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p>{{__('messages.teksty.faktury.brak')}}</p>
                @endif

            </div>
        </div>

    </div>
    <script>
        $(function () {
            $(".clickable-row").click(function() {
                window.location = "/account/invoices/" + $(this).data("id");
            });
        });
    </script>
@endsection
