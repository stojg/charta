@extends('app')

@section('content')
    <div class="toolbar">
        <div class="actions">
            <a href="{{ action('DocumentController@getEdit', ['id'=>$document->id]) }}" class="edit">
                <span class="glyphicon glyphicon-pencil"></span>
            </a>
        </div>
        <a href="/" class="back"><i class="glyphicon glyphicon-circle-arrow-left"></i></a> <h3>Charta</h3>
    </div>

    <div class="content box">
        {!! $document->asHTML() !!}
    </div>
@endsection
