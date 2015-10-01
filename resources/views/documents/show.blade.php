@extends('app')

@section('content')
    <div class="toolbar">
        <div class="actions">
            <a href="{{ action('DocumentController@getEdit', ['id'=>$document->id]) }}" class="edit">
                <span class="glyphicon glyphicon-pencil"></span>
            </a>
        </div>
        <a href="/" class="back"><span class="glyphicon glyphicon-home"></span> <h3 class="logo">Charta</h3></a>
    </div>

    <div class="content box">
        {!! $document->asHTML() !!}
    </div>
@endsection
