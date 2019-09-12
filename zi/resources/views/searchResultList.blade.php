@extends('layouts.gridLayout')


@section('gridSetup')
	<script>	  
		$(document).ready(function() {
			dataGrid.columnOption("PRICE", "visible", priceVisible())			
		});	
		/* grid */  
		dataSource = <?php echo json_encode($results['data'] );?>;								
		
		dataColumns= [
                    {
                        caption: "Nazwa",
                        cellTemplate: function (container, options) {
                            $("<div>")
                                .append($("<a>", {"href": "/item/" + options.data.ID, "text": options.value}))
                                .append(getDiscountHtmlElement(options.data))
                                .append(getSaleHtmlElement(options.data))
								.append(getNewsHtmlElement(options.data))
                                .appendTo(container);
                        },
                        dataField: "NAME",
                        width: 300
                    },
                    {
                        caption: "Z",
                        cellTemplate: function (container, options) {
                            if (options.value && options.value === "1") {
                                var icon = $('<div data-toggle="tooltip" title="Image tooltip">')
                                    .append('<i class="fa fa-eye" aria-hidden="true"></i>')
                                    .appendTo(container);
//                                icon.tooltip({
//                                    template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><img src="/api/sales/items/' + options.data.ID +'/picture"/></div>'
//                                });
                            }
                        },
                        dataField: "IS_PICTURE",
                        width: 40
                    },
                    {
                        caption: "O",
                        cellTemplate: function (container, options) {
                            if (options.value && options.value.length === "1") {
                                $("<div>")
                                    .append('<i class="fa fa-file-text-o" aria-hidden="true"></i>')
                                    .appendTo(container);
                            }
                        },
                        dataField: "IS_DESCRIPTION",
                        width: 40},
                    {caption: "Indeks", dataField: "INDEKS"},
                    {caption: "K.Prod.", dataField: "PRODUCER_CODE", width: 60},
                    {
                        @if ($results['arePricesNet'])
                        caption: "Cena (netto)",
                        @else
                        caption: "Cena (brutto)",
                        @endif
                        dataField: "PRICE", dataType: 'number', width: 100, cssClass: 'no-wrap', allowSorting: true,
                        cellTemplate: function (container, options) {
                            container.text(common.dataGridCustomizeNumber(options.data.PRICE));
                        }
                    },
                    {
                        caption: "Ilość", dataField: "QUANTITY", dataType: 'number', width: 70,
                        cellTemplate: function (container, options) {
                            container.text(common.dataGridCustomizeNumber(options.data.QUANTITY, options.data.QUANTITY_PRECISION));
                        }
                    },
                    {caption: "J.m.", dataField: "QUANTITY_UNIT", width: 60},
                    {caption: "Koszyk", dataField: "CART_QUANTITY", dataType: 'number', width: 60},
                    {
                        caption: "Biorę",
                        cellTemplate: function (container, options) {
                            $('<form class="add-basket-form form-inline">')
                                .append('<div class="form-group"><input type="number" class="form-control" name="quantity" style="width: 70px" value="' + DEFAULT_NUMBER_OF_ITEMS_TO_BUY + '"></input></div>')
                                .append('<input type="hidden" name="product_id" value="' + options.data.ID + '"></input>')
                                .append('<i onclick="addToBasket(this)" class="fa fa-cart-plus" aria-hidden="true"></i>')
                                .appendTo(container);
                        },
                        width: 130
                    }
                ];
				
	
		function gridStorageKey()  {			
			return	"storage-searchResult";
		};
	
		/* context menu */
		dataPMItems =	[ { id: 5, text: 'Konfiguruj' }
					];					
	</script>	  
@endsection	


@section('gridHTML')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
				@if($isMobile)
					<h5>{{$header}} dla "{{$_GET['q']}}"</h5>
				@else
					<h1>{{$header}} dla "{{$_GET['q']}}"</h1>
				@endif
                <hr/>

                <div class="row">
					@if(!$results['priceFilterOff']) 
						<div 
							@if($isMobile)
								class="col-md-12" 
							@else
								class="col-md-1 no-padding-left-right"
							@endif												
						>
							<div class="form-group">
								<label for="priceFrom">Cena</label>
								<input type="number" class="form-control" id="priceFrom" placeholder="Od">
								<input type="number" class="form-control" id="priceTo" placeholder="Do">
							</div>
							<button id="apply-filter-button" class="btn btn-primary btn-block" onclick="applyFilters()" type="button">Filtruj</button>
							<button id="reset-filter-button" class="btn btn-danger btn-block" onclick="clearFilters()" type="button">Resetuj</button>
						</div>
					@endif
                    <div 
						@if(!$results['priceFilterOff']) 
							@if($isMobile)
								class="col-md-12" 
							@else
								class="col-md-11" 
							@endif						
						@endif
					>
						<div id="toolbarContainer" style="display: none"></div>
                        <div id="gridContainer"></div>
						<div id="menuContainer"></div>		
                    </div>
                </div>

            </div>
        </div>
    </div>
	
    <script>
        var DEFAULT_NUMBER_OF_ITEMS_TO_BUY = 1;

        var addToBasket = function(cartIconElement) {
            changeIconToSpinner(cartIconElement);
            common.addProductToCart(
                cartIconElement,
                "{{__('messages.alerty.koszyk.dodano')}}",
                function() {
                    changeIconBackFromSpinner(cartIconElement);
                }
            );
        };

        var changeIconToSpinner = function(cartIconElement) {
            $(cartIconElement).removeClass('fa-cart-plus').addClass('fa-refresh fa-spin fa-3x fa-fw');
        };

        var changeIconBackFromSpinner = function(cartIconElement) {
            $(cartIconElement).removeClass('fa-refresh fa-spin fa-3x fa-fw').addClass('fa-cart-plus');
        };

        var getDiscountHtmlElement = function (data) {
            if (data.IS_DISCOUNT === 1) {
                return '<span class="label label-discount">{{__('messages.teksty.listaProduktow.promocja')}}</span>';
            } else {
                return '';
            }
        }

        var getSaleHtmlElement = function (data) {
            if (data.IS_SALE === 1) {
                return '<span class="label label-sale">{{__('messages.teksty.listaProduktow.wyprzedaz')}}</span>';
            } else {
                return '';
            }
        }
		
		var getNewsHtmlElement = function (data) {
            if (data.IS_NEWS === 1) {
                return '<span class="label label-news">{{__('messages.teksty.listaProduktow.nowosc')}}</span>';
            } else {
                return '';
            }
        }


        var applyFilters = function() {
            var priceFrom = $('#priceFrom').val();
            var priceTo = $('#priceTo').val();

            if ((!priceFrom || priceFrom.length === 0) && (!priceTo || priceTo.length === 0)) {
                dataGrid.clearFilter();
            } else if ((priceFrom && priceFrom.length > 0) && (priceTo && priceTo.length > 0)) {
                dataGrid.filter([ "PRICE", '>=', parseInt(priceFrom) ],
                    "and",
                    [ "PRICE", '<=', parseInt(priceTo) ])
            } else {
                if (priceFrom && priceFrom.length > 0) {
                    dataGrid.filter(['PRICE', '>=', parseInt(priceFrom)])
                }
                if (priceTo && priceTo.length > 0) {
                    dataGrid.filter(['PRICE', '<=', parseInt(priceTo)])
                }
            }
        };

        var clearFilters = function() {
            dataGrid.clearFilter();
            $('#priceFrom').val('');
            $('#priceTo').val('');
        }

        function qs(key) {
            key = key.replace(/[*+?^$.\[\]{}()|\\\/]/g, "\\$&"); // escape RegEx meta chars
            var match = location.search.match(new RegExp("[?&]"+key+"=([^&]+)(&|$)"));
            return match && decodeURIComponent(match[1].replace(/\+/g, " "));
        }

        function registerKeyUps() {
            $("#priceFrom").keyup(function(event){
                if(event.keyCode == 13){
                    $("#apply-filter-button").click();
                }
            });
            $("#priceTo").keyup(function(event){
                if(event.keyCode == 13){
                    $("#apply-filter-button").click();
                }
            });
        }

//        var updateFilterSearchQuery = function () {
//            var filterSearchQuery = qs('q');
//            $('#filterQuery').val(filterSearchQuery);
//        }

        $(function () {
//            updateFilterSearchQuery();
            registerKeyUps();           
        });
    </script>
@endsection