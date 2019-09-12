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

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <!-- DevExtreme dependencies -->
    <script type="text/javascript" src="/lib/devexpress/jquery-3.1.0.min.js"></script>
    <!-- A DevExtreme library -->
    <link rel="stylesheet" type="text/css" href="/lib/devexpress/dx.common.css">
    <link rel="stylesheet" type="text/css" href="/lib/devexpress/dx.light.css">
    <link rel="stylesheet" type="text/css" href="/lib/devexpress/dx.light.css">
    <link href="/lib/bootstrap/css/all.min.css" rel="stylesheet">
    
    <script type="text/javascript" src="/lib/bootstrap/bootstrap.min.js" async></script>    
    <script type="text/javascript" src="/lib/rtfjs/cptable.full.js" async></script>
    <script type="text/javascript" src="/lib/rtfjs/symboltable.js" async></script>
    <script type="text/javascript" src="/lib/rtfjs/rtf.js" async></script>
    <script type="text/javascript" src="/lib/devexpress/dx.web.js"></script>
    <script type="text/javascript" src="/js/custom.js?v=1.1"></script>
    <script src="/lib/noty/noty.js" type="text/javascript"></script>
    <link href="/lib/noty/noty.css" rel="stylesheet" />
	<!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
	
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
	<script>
		function isMobile()  {	
			return	<?php echo json_encode( $isMobile );?>;		 		
		}; 	
		function priceVisible(){
			return	'{{priceVisible()}}';			
		};
		function priceOn () {
			common.sessionPut( 'price_hide', 0);
			window.location.reload();			
		}
		function priceOff () {
			common.sessionPut('price_hide', 1);
			window.location.reload();			
		}		
	</script>
<!--	<div id="testy"><a>vklwek</a>	</div> -->
    <div id="app">
		@if($isMobile)
			@yield('mobileContent')
		@else
			@yield('desktopContent')
		@endif	
		@yield('gridContent')
        @yield('content')		
        <div class="zi-global-spinner">
            <i class="fa fa-refresh fa-spin fa-2x fa-fw" aria-hidden="true"></i>
        </div>
    </div>
</body>
</html>
