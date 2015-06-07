@extends('app')

@section('content')
<div class="pure-u-1 pure-u-lg-3-5 pure-u-md-4-5">
    <div class="content box">
        <div>
            {!! $document->asHTML() !!}
        </div>
    </div>
</div>
<div class="pure-u-1 pure-u-lg-2-5 pure-u-md-1-5">
    <div class="right-menu">
        <ul class="list lvl-0">
            <li>
            <a href="{{ action('DocumentController@getEdit', ['id'=>$document->id]) }}">
                Edit
            </a>
            </li>
        </ul>
    </div>

</div>
@endsection
