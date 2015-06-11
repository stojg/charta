@extends('app')

@section('content')

@if ($document->id)
<form class='edit' action="{{ action('DocumentController@postUpdate', ['id'=>$document->id]) }}" method="post" autocomplete="off">
@else
<form class='new' action="{{ action('DocumentController@postCreate') }}" method="post" autocomplete="off">
@endif
    <input type="hidden" class="token" name="_token" value="{{ csrf_token() }}">

    <div class="toolbar">
        <div class="actions">
            <button type="submit"><span class="glyphicon glyphicon-floppy-disk"></span></button>
            @if ($document->id)
                <a href="{{ action('DocumentController@getDelete', ['id'=>$document->id]) }}"><span class="glyphicon glyphicon-trash"></span></a>
            @endif
        </div>
        @if ($document->id)
        <a href="{{ action('DocumentController@getShow', ['id' => $document->id]) }}" class="back"><span class="glyphicon glyphicon-circle-arrow-left"></span></a>
        @else
            <a href="{{ action('DocumentController@index') }}" class="back"><span class="glyphicon glyphicon-circle-arrow-left"></span></a>
        @endif
        <h3>Charta</h3>
    </div>

    <div class="content box">
        <textarea class='editor content' rows="10" name="content">{!! $document->content !!}</textarea>
    </div>
</form>
@endsection
