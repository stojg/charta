@extends('app')

@section('content')
<div class="pure-u-1 pure-u-lg-3-5 pure-u-md-4-5">
    <div class="content box">
        <h1>Documentation</h1>
        <p>
            Please <a href="{{ url('/auth/login') }}">sign in</a> to see and edit content on this site
        </p>
    </div>
</div>
@endsection
