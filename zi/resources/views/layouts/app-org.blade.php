<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-112824884-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-112824884-1');
    </script>


    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <!-- DevExtreme dependencies -->
    <script type="text/javascript" src="/lib/devexpress/jquery-3.1.0.min.js"></script>
    <!-- A DevExtreme library -->
    <link rel="stylesheet" type="text/css" href="/lib/devexpress/dx.common.css" />
    <link rel="stylesheet" type="text/css" href="/lib/devexpress/dx.light.css" />
    <link rel="stylesheet" type="text/css" href="/lib/devexpress/dx.light.css" />

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <script type="text/javascript" src="/lib/rtfjs/cptable.full.js" async></script>
    <script type="text/javascript" src="/lib/rtfjs/symboltable.js" async></script>
    <script type="text/javascript" src="/lib/rtfjs/rtf.js" async></script>
    <script type="text/javascript" src="/lib/devexpress/dx.web.js"></script>
    <script type="text/javascript" src="/js/custom.js?v=1.1"></script>
    <script src="/lib/noty/noty.js" type="text/javascript"></script>
    <link href="/lib/noty/noty.css" rel="stylesheet" />
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    <div id="app">
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
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            @if (!Auth::guest())
				<div class="container">
					@if(is_file("img/zi_dokument.html"))					    
						<a href="/img/zi_dokument.html" title="Go to W3Schools HTML section">Sample HTML</a>
					@endif				
				</div>
                <div class="menu-outer-container">

                    <div class="container">
                        <div class="menu-inner-container">
                            <a href="/discounts">{{ __('messages.menu.promocje') }}</a>
                            <a href="/sale">Wyprzedaż</a>
                            <a class="product-link product-menu-trigger">Produkty</a>
                            <a href="/cart">Koszyk</a>
                            <a href="/account">Moje konto</a>
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

        @yield('content')

        <div class="zi-global-spinner">
            <i class="fa fa-refresh fa-spin fa-2x fa-fw" aria-hidden="true"></i>
        </div>
    </div>
</body>
</html>
