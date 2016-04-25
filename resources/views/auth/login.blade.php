@extends('app')

@section('content')
    <div class="toolbar">
        <a href="/" class="back"><span class="glyphicon glyphicon-home"></span> <h3 class="logo">Charta</h3></a>
    </div>
    <div class="content box">
        <h1>Sign in</h1>
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <a href="/login/github">Sign in with github</a>
    </div>
@endsection
