@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (isset($twitter_connected))
                        <div>Connected with Twitter</div>
                        <a href="{{ route('twitter.login') }}" class="btn btn-default">Force Reconnect with Twitter</a>
                    @else
                        <a href="{{ route('twitter.login') }}" class="btn btn-default">Connect Twitter</a>
                    @endif

                    <div class='tweets'>
                        @foreach ($tweets as $tweet)
                            {!! $tweet->html !!}
                        @endforeach
                    </div>

                    @if (isset($error))
                        <div class='error'>We're sorry, we seem to be having difficulty at the moment. Please try again later.</div>
                        <div class='error_message'>{{ $error }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
