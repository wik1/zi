@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{$item->NAME}}</h1>
                <hr/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                @if($item->IS_PICTURE)
                    <img src="/api/sales/items/{{$item->ID}}/picture" alt="">
                @else
                    <img src="/img/brak_obrazka.jpg" alt="">
                @endif
            </div>
            <div class="col-md-6 product-price-container">
                <div class="row">
                    <div class="col-md-12 product-price">                       
						@if(priceVisible())
							@if ($item->arePricesNet)
								<h1>{{ number_format($item->NET_PRICE, 2, ',', ' ') }} zł</h1> 
								<span>netto</span>
							@else
								<h1>{{ number_format($item->GROSS_PRICE, 2, ',', ' ') }} zł</h1> 
								<span>brutto</span>
							@endif 
						@else
							<h1>&nbsp;</h1> 
							<span>&nbsp;</span>
						@endif 
                        <span class="labels">
                            @if($item->IS_DISCOUNT)
                                <span class="label label-discount">{{__('messages.teksty.listaProduktow.promocja')}}</span>
                            @endif
                            @if($item->IS_SALE)
                                <span class="label label-sale">{{__('messages.teksty.listaProduktow.wyprzedaz')}}</span>
                            @endif
							@if($item->IS_NEWS)
                                <span class="label label-news">{{__('messages.teksty.listaProduktow.nowosc')}}</span>
                            @endif
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <form class="add-basket-form form-inline">
                            <div class="form-group">
                                {{__('messages.teksty.produkt.iloscFormularz')}} <input type="number"
                                                                                        class="form-control"
                                                                                        name="quantity"
                                                                                        style="width: 70px" value="1"/>
                                <input type="hidden" name="product_id" value="{{$item->ID}}"></input>
                            </div>
                            <button onclick="addToCart(this)" class="btn btn-primary" type="button">
                                {{__('messages.przyciski.dodajDoKoszyka')}}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="description"></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h2>{{__('messages.teksty.produkt.daneNaglowek')}}</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <tbody>
                    <tr>
                        <td>{{__('messages.teksty.produkt.indeks')}}</td>
                        <td>{{$item->INDEKS}}</td>
                    </tr>
					@if(priceVisible())
                    <tr>
                        <td>{{__('messages.teksty.produkt.netto')}}</td>
                        <td>{{ number_format($item->NET_PRICE, 2, ',', ' ')}}</td>
                    </tr>
                    <tr>
                        <td>{{__('messages.teksty.produkt.brutto')}}</td>
                        <td>{{ number_format($item->GROSS_PRICE, 2, ',', ' ') }}</td>
                    </tr>
					@endif
                    <tr>
                        <td>{{__('messages.teksty.produkt.ilosc')}}</td>
                        <td>{{  number_format($item->QUANTITY, $item->QUANTITY_PRECISION, ',', ' ') }}</td>
                    </tr>
                    <tr>
                        <td>{{__('messages.teksty.produkt.iloscWJednostceZbiorczej')}}</td>
                        <td>{{ number_format($item->QUANTITY_IN_UNIT, $item->QUANTITY_IN_UNIT_PRECISION, ',', ' ') }}</td>
                    </tr>
                    <tr>
                        <td>{{__('messages.teksty.produkt.waga')}}</td>
                        <td>{{ number_format($item->WEIGHT, 2, ',', ' ') }} {{$item->WEIGHT_UNIT}}</td>
                    </tr>
                    <tr>
                        <td>{{__('messages.teksty.produkt.kodProducenta')}}</td>
                        <td>{{$item->PRODUCER_CODE}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        var addToCart = function (addToCartButtonElement) {
            common.changeToSpinnerIcon(addToCartButtonElement);
            common.addProductToCart(
                addToCartButtonElement,
                "{{__('messages.alerty.koszyk.dodano')}}",
                function () {
                    common.changeHtmlTo(addToCartButtonElement, "{{__('messages.przyciski.dodajDoKoszyka')}}");
                }
            );
        };

        $(function () {
            function stringToBinaryArray(string) {
                var buffer = new ArrayBuffer(string.length);
                var bufferView = new Uint8Array(buffer);
                for (var i=0; i<string.length; i++) {
                    bufferView[i] = string.charCodeAt(i);
                }
                return buffer;
            }
            var binaryArray = stringToBinaryArray(<?php echo json_encode($item->DESCRIPTION ); ?>);
            var doc = new RTFJS.Document(binaryArray);
            $(".description").empty().append(doc.render());
            //debugger;
        });
    </script>
@endsection
