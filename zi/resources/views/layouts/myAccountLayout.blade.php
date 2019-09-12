@extends('layouts.app')

@section('subMenu')
    <div class="container">
        <ul class="nav nav-tabs submenu-container" role="tablist">
            <li role="presentation"><a href="/account/finances" role="tab">Finanse</a></li>
            <li role="presentation"><a href="/account/orders" role="tab">Zamówienia</a></li>
            <li role="presentation"><a href="/account/invoices" role="tab">Faktury</a></li>
        </ul>
    </div>
@endsection

@section('content')
    @yield('content')
@endsection
