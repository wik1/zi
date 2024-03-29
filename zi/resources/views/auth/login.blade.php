@if ($silentAuth)
	<script type="text/javascript">
		window.location = "{{ url('/user/login/guest') }}";//here double curly bracket
	</script>		
@else

	
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Logowanie {{$silentAuth}}</div>				
                
				@if ($silentAuth and Auth::check())
					<script type="text/javascript">
						window.location = "{{ url('/discounts') }}";//here double curly bracket
					</script>                        
				@else
                <div class="panel-body">
			<!-- action="{{ route('login') }}"> -->
				@if ($stdAuth)
					<form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
				@else
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/user/login') }}">
				@endif
						{{ csrf_field() }}						
                        
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Adres email</label>
                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
								<input id="urlPrevious" name="urlPrevious" type="hidden"  value="{{ URL::previous() }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Hasło</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{--<div class="form-group">--}}
                            {{--<div class="col-md-6 col-md-offset-4">--}}
                                {{--<div class="checkbox">--}}
                                    {{--<label>--}}
                                        {{--<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Zapamiętaj logowanie--}}
                                    {{--</label>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Zaloguj
                                </button>
                            </div>
                        </div>						
                    </form>
                </div>
				@endif
            </div>
        </div>
    </div>
</div>
@endsection
@endif

