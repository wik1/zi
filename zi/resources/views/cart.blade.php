@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>{{__('messages.tytuly.koszyk')}}</h1>

            <hr/>
            @if(count($cart['items']) > 0)
            <form id="cartForm" action="/cart/update" class="form-inline zi-cart-form" method="post">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-2">Płatność:</div>
                            <div class="col-md-8">
                                <select class="form-control" name="paymentType" style='width: 100%'>
                                    @foreach ($paymentTypes as $paymentType)
                                    <option value="{{$paymentType->KOD}}"
                                            @if ($paymentType->KOD == $cart['selectedPaymentType'])
                                            selected
                                            @endif
                                            >{{$paymentType->NAZWA_TERMIN}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">Dostawa:</div>
                            <div class="col-md-8">
                                <select class="form-control" name="transportType" style='width: 100%'>
                                    @foreach ($transportTypes as $transportType)
                                    <option value="{{$transportType->KOD}}"
                                            @if ($transportType->KOD == $cart['selectedTransportType'])
                                            selected
                                            @endif
                                            >{{$transportType->NAZWA}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
				
                        <div class="row">
                            <div class="col-md-2">Uwagi:</div>
                            <div class="col-md-8">							
                                <input class="form-control" name="commentsText" type="text" style='width: 100%' value="{{$cart['addedCommentsText']}}">										
                            </div>
                        </div>
                        @if($useOdbiorca)
                            <div class="row">
                                <div class="col-md-2">Odbiorca:</div>
                                <div class="col-md-8">							
                                    <div id="lookup"></div>
                                </div>
                            </div>							
                        @endif
                    </div>
                    @if(priceVisible())
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-xs-6">Wartość koszyka netto:</div>
                            <div class="col-xs-6 zi-bolder-bigger">{{ number_format($nettoSumOfCart, 2, ',', ' ') }}
                                zł
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">Wartość koszyka brutto:</div>
                            <div class="col-xs-6 zi-bolder-bigger">{{ number_format($sumOfCart, 2, ',', ' ') }}
                                zł
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">Ilość ogółem:</div>
                            <div class="col-xs-6 zi-bolder-bigger">{{ $itemsInCart }}</div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="row zi-margin-top-40">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>LP.</th>
                                <th>Nazwa</th>
                                <th>Indeks</th>
                                @if(priceVisible())												   										
                                <th>Cena (netto lub brutto)</th>
                                <th>Rabat %</th>
                                @endif
                                <th class="zi-min-width-51">J. m</th>
                                <th>Ilość</th>
                                @if(priceVisible())												   									
                                <th>Wartość netto</th>
                                <th>Wartość brutto</th>
                                @endif
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach ($cart['items'] as $cartItem)
                            <tr>
                                <th scope="row">{{$loop->index+1}}</th>
                                <td><a href="/item/{{ $cartItem->IDMAG }}">{{ $cartItem->NAZWA }}</a></td>
                                <td>{{ $cartItem->INDEKS }}</td>
                                @if(priceVisible())												   										
                                <td>{{ number_format($cartItem->CENA_N, 2, ',', ' ') }}</td>
                                <td>{{ $cartItem->RABAT_TXT }}</td>
                                @endif
                                <td>{{ $cartItem->JM_N }}</td>
                                <td><input class="form-control" type="text" name="item{{ $cartItem->IDMAG }}"
                                           value="{{ $cartItem->ILOSC }}"/></td>
                                @if(priceVisible())												   
                                <td>{{ number_format($cartItem->NETTO, 2, ',', ' ') }}</td>
                                <td>{{ number_format($cartItem->BRUTTO, 2, ',', ' ') }}</td>
                                @endif
                                {{--<td class="zi-column-number-right">{{ $cartItem->QUANTITY }}</td>--}}
                                {{--<td class="zi-column-number-right">{{ $cartItem->NET_PRICE * $cartItem->QUANTITY}}</td>--}}
                                        <td>
                                    <i onclick="deleteFromCart({{ $cartItem->IDMAG }}, {{ $cartItem->ILOSC }}, this)"
                                       class="fa fa-times clickable"></i></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                @if(priceVisible())												   
                                    <td></td>
                                    <td></td>								
                                    <td></td>
                                    <td></td>
                                @endif
                                <td></td>
                                <td></td>
                                <td>
                                    <a href="/cart/removeall" onclick="common.showGlobalSpinner()">Usuń wszystko</a>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {!! csrf_field() !!}
            </form>
            @else
            <p class="zi-empty-cart">{{__('messages.teksty.koszyk.jestPusty')}}</p>
            @endif				
            <div class="row zi-margin-top-20">
                <div class="col-md-9">
                    <a class="btn btn-primary" href="/">Kontynuuj zakupy</a>
                </div>
                @if(count($cart['items']) > 0)						
                <div class="col-md-3 text-left">							
                    <form method="post" action="/cart/submit">
                        {!! csrf_field() !!}
                        <button class="btn btn-primary" type="submit" onclick="common.showGlobalSpinner()"
                                @if (Auth::user()->guestLoged())
                                    disabled = "disabled" 
                                @endif
                                href="/cart/summary">{{__('messages.przyciski.realizujZamowienie')}}
                    </button>
                </form>
            </div>
            @endif
        </div>
        @if(count($cart['items']) > 0 and Auth::user()->guestLoged())						
        <div class="row zi-margin-top-9">
            <div class="col-md-offset-9 col-md-3">
                Realizacja niemożliwa.
                <p>Brak zalogowanego użytkownika.</p>
                <a href="/user/login">Zaloguj się</a>
                &nbsp;lub&nbsp; 
                <a href="/user/register">Załóż konto</a>									

            </div>
        </div>
        @endif
    </div>
</div>

</div>

<script>
    
    $(document).ready(function () {
        errorTxt = common.sessionPull('errorTxt');
        if (errorTxt) 
            common.errorMsg( common.getErrDbText( errorTxt ) );
    });    
    
    var removeRowFromTable = function (iconElement) {
        $(iconElement).parent().parent().remove();
    };
    var updateCartSum = function (cartSum) {
        $('#cart-sum').text(cartSum);
    }

    function deleteFromCart(productId, quantity, iconElement) {
        var result = confirm("{{__('messages.alerty.koszyk.naPewnoUsunZKoszyka')}}");
        if (result == true) {
            removeRowFromTable(iconElement);
            common.showGlobalSpinner();
            document.getElementById("cartForm").submit();
        }
    }

    $(function () {
		$('#cartForm .form-control').focusout(function (event) {
			common.showGlobalSpinner();
			document.getElementById("cartForm").submit();
		});
		$('#cartForm .form-control').keyup(function (event) {
			event.preventDefault();
			if (event.keyCode == 13) {
				common.showGlobalSpinner();
				document.getElementById("cartForm").submit();
			}
		});
		$('#cartForm .form-control').change(function (event) {
			common.showGlobalSpinner();
			document.getElementById("cartForm").submit();
		});
    });
	
	var ktrhStore = new DevExpress.data.ArrayStore({
			data: <?php echo json_encode($ktrhItems['data']);?>,
			key: "KOD",
			errorHandler: function (error) {
				alert(error.message);
			}
		});	
		
	var ktrhDS = new DevExpress.data.DataSource({
		store: ktrhStore,
		postProcess: function (data) {
			data.unshift({ KONTRAHENT: "Kontrahent", NIP: "NIP", disabled: true, visible: true });
			return data;
		}
	});	
	
	
	function selectedOdbiorca()  {	
		return	parseInt (<?php echo json_encode($cart['selectedOdbiorca']);?>);		 		
	}; 	
		
	//var selectedOdbiorca=6263;
	//<?php echo json_encode($cart['selectedOdbiorca']);?>;

	$("#lookup").dxLookup({
        dataSource: ktrhDS,
//        grouped: true,        
        closeOnOutsideClick: false,
        showClearButton: true,
        showPopupTitle: false,
        searchEnabled: true,
        fullScreen: false,
        searchExpr: ["KONTRAHENT", "NIP"],
        displayExpr: function (item) {
            if (item)
                return item.KONTRAHENT + " " +item.NIP
        },
		valueExpr:"KOD",
		value: selectedOdbiorca(),
		placeholder: "wybierz kontrahenta",
        onValueChanged: function(e) {
	
			common.sessionPut( 'cartOdbiorca', e.value);	
//			  alert('hello'+common.sessionGet( 'cartOdbiorca'));
        },
        itemTemplate: function(data, index, container) {
            return getTemplate(data, index, container);
        }
    });
	
    function getTemplate(data, index, container) {
        var row =   '<div class="u-word-wrap">'+
                        '<div class="col-xs-9">'+data["KONTRAHENT"]+'</div>'+
                        '<div class="col-xs-3">'+data["NIP"]+'</div>'
                    '</div>';

        container.append(row);
    };	
	

	
	
</script>
@endsection
