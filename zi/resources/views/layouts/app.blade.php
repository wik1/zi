@extends( 'layouts.appCmn' )

@section('mobileContent')
<style>
	.navbar-nav > li > a, .navbar-brand {padding-top:0px !important; padding-bottom:0 !important; height: 25px; } 
	.navbar {min-height:25px !important;} 
</style>

<nav id="app-header">
	<div class="container">
		<!-- class="navbar-header" --> 
		<div class="nav">
			<div class="pull-left">
				<a id="app-name-m" href="{{ url('/') }}"> ZAMÓWIENIA </a>
			</div> 
			@if (Auth::check())
				<div class="pull-right">
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
								{{ Auth::user()->name }}<span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								@if ($guestEnabled)
									<li>
										@if (Auth::user()->loged())									
											<a  class="link-disabled" href="/user/login">Login</a>									
											<a  class="link-disabled" href="/user/login">Załóż konto</a>									
										@else
											<a href="/user/login">Login</a>									
											<a href="/user/register">Załóż konto</a>									
										@endif
									</li>																
									<li role="presentation" class="divider"></li>
								@endif
								<li>
									<a href="{{ route('logout') }}"
									   onclick="event.preventDefault();
												 document.getElementById('logout-form').submit();">
										Wyloguj
									</a>

									<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
										{{ csrf_field() }}
									</form>
								</li>
								<li role="presentation" class="divider"></li>
								<li>
									@if(priceVisible())
										<a href="#" onclick="priceOff();">Ukryj ceny</a>											
									@else
										<a href="#" onclick="priceOn();">Pokaż ceny</a>
									@endif
								</li>
								
							</ul>
						</li>
					</ul>
				</div>
			@endif
		
		</div>
	</div>
	<div class="container">			
		<div id="toolbarMobile"></div>	
		<div class="container">
			@foreach ($adFiles as $adFile)						
				@if ($adFile[2]=='pdf')
					<a href="{{asset($adFile[0])}}">&nbsp;&nbsp;{{$adFile[1]}}&nbsp;&nbsp;</a>
				@else
					<a href="{{$adFile[0]}}">&nbsp;&nbsp;{{$adFile[1]}}&nbsp;&nbsp;</a>
				@endif
			@endforeach
		</div>
		
	</div> 

	@if (Auth::check())
		@yield('subMenu')
	@endif
</nav>
	
<script>
	var mobileTBItems = [{
			location: 'before',
			widget: 'dxButton',
			locateInMenu: 'auto',
			options: { icon: "favorites" },
			onClick: function() { window.location = "/discounts"; }
		}, {
			location: 'before',
			widget: 'dxButton',
			locateInMenu: 'auto',
			options: { icon: "trash" },
			onClick: function() { window.location = "/sale"; }
		}, {
			location: 'after',
			widget: 'dxButton',			
			options: { icon: "find" },
			onClick: function() { window.location = "/m/search"; }
		}, {
			location: 'after',
			widget: 'dxButton',			
			options: { icon: "cart" },
			onClick: function() { window.location = "/cart"; }
		}, {
			location: 'after',
			widget: 'dxButton',			
			options: { icon: "filter" },
			onClick: function() {	
				x= common.sessionGet('priceFilterOff');
				
				if(x!=0 && x!=1) { //default filter On
					x=0; 		
					
				} else {		
					if(x==1) 	{x=0}
					else		{x=1};
				};	
				common.sessionPut('priceFilterOff', x);
//				DevExpress.ui.notify( 'default=>'+x);	
				
				window.location.reload();
			}
		}, {
			location: 'after',
			widget: 'dxButton',						
			options: { icon: "user" },
			onClick: function() {	
				window.location="/user/m/choice";
			}
		}, {
			location: 'after',
			widget: 'dxButton',
			locateInMenu: 'auto',
			options: {				
				icon: "preferences",
				hint: "konfiguracja kolumn",
				onClick: function() {
					dataGrid.showColumnChooser();                        
				}
			}
		}


	];
	
	dataTBMobile=$("#toolbarMobile").dxToolbar({
		items: mobileTBItems
	}).dxToolbar("instance");
	dataTBMobile.option('items[5].visible', <?php echo json_encode($guestEnabled );?>);	
	dataTBMobile.option('items[6].visible', false);	



</script>		
@endsection


@section('desktopContent')
        <nav class="navbar navbar-default navbar-static-top main-banner">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
<!--                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
-->
                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}"> 
                        {{ config('app.name', 'Laravel') }} 												
                    </a>					
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::check())                        
                            <li>
<!--                                <div class="main-search-container"> -->
								<div class="main-search-container">
                                    <div class="form-inline">
                                        <input id="search-input" type="text" class="form-control" placeholder="Fraza...">
                                        <select class="form-control">
                                            <option value="">wszystkie</option>
                                            @foreach ($searchProductGroups as $productGroup)
                                                <option value="{{$productGroup->id}}">{{$productGroup->name}}</option>
                                            @endforeach
                                        </select>
                                        <button id="search-button" class="btn btn-primary" onclick="searchButtonClicked(this)" type="button">Szukaj</button>
                                    </div><!-- /input-group -->
                                </div>
                            </li>
                            <li>
                                <a href="/cart" class="icon-link cart-container">
                                    <div class="cart-with-number-of-items">
                                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                        <span class="cart-size-container">
                                            <span class="cart-size">{{$itemsInCart}}</span>
                                        </span>
                                    </div>
                                    <span><span class="cart-value"><span class="cart-value-span">{{number_format($sumOfCart, 2, ',', ' ')}}</span> zł </span> (brutto)</span>
                                </a>
                            </li>							
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
									Witaj, 									
									{{ Auth::user()->name }}
									<span class="caret"></span>								
                                </a>

                                <ul class="dropdown-menu" role="menu">
									@if (Auth::user()->guestEnabled())									
										@if (Auth::user()->loged())									
											<li class="disabled">
												<a href="#">Zaloguj się</a>									
												<a href="#">Załóż konto</a>									
											</li>
										@else
											<li>
												<a href="/user/login">Zaloguj się</a>									
												<a href="/user/register">Załóż konto</a>									
											</li>
										@endif					
										@if (Auth::user()->loged())
											<li role="presentation" class="divider"></li>
										@endif
									@endif
									@if (Auth::user()->loged() or !Auth::user()->guestEnabled())
                                    <li>
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Wyloguj
                                        </a>									
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
									@endif
									<li role="presentation" class="divider"></li>
									<li>
										@if(priceVisible())
											<a href="#" onclick="priceOff();">Ukryj ceny</a>											
										@else
											<a href="#" onclick="priceOn();">Pokaż ceny</a>
										@endif
									</li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            @if (!Auth::guest())
				<div class="container">
					@foreach ($adFiles as $adFile)						
						@if ($adFile[2]=='pdf')
							<a href="{{asset($adFile[0])}}">&nbsp;&nbsp;{{$adFile[1]}}&nbsp;&nbsp;</a>
						@else
							<a href="{{$adFile[0]}}">&nbsp;&nbsp;{{$adFile[1]}}&nbsp;&nbsp;</a>
						@endif
                    @endforeach
				</div>
                <div class="menu-outer-container">

                    <div class="container">
                        <div class="menu-inner-container">
                            <a href="/discounts">{{ __('messages.menu.promocje') }}</a>
                            <a href="/sale">Wyprzedaż</a>
                            <a class="product-link product-menu-trigger">Produkty</a>
                            <a href="/cart">Koszyk</a>							
							@if (Auth::user()->loged())
                              <a href="/account">Moje konto</a>
							@endif
                        </div>
                    </div>

                    <div class="productsMenu product-menu-trigger">
                        <div class="container">
                            <div class="row zi-margin-top-20">
                            @foreach ($productTree as $productGroup)
                                <div class="col-md-3">
                                    <a class="title" href="/products/{{ $productGroup['id'] }}">{{ $productGroup['text'] }}</a>
                                    <ul>
                                        @foreach ($productGroup['items'] as $subGroup)
                                            <li><a href="/products/{{ $subGroup['id'] }}">{{ $subGroup['text'] }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @yield('subMenu')
            @endif
        </nav>       
@endsection

