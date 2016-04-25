@extends('app')

@section('content')
    <div class="toolbar">
        <a href="/" class="back"><span class="glyphicon glyphicon-home"></span> <h3 class="logo">Charta</h3></a>
    </div>
    <div class="content box">
        <h1>Welcome</h1>
        <p>
            Please <a href="{{ url('/signin') }}">sign in</a> to see and edit content on this site
        </p>
    </div>
@endsection
