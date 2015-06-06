@extends('app')

@section('content')

@if ($document->id)
<form action="{{ action('DocumentController@postUpdate', ['id'=>$document->id]) }}" method="post" autocomplete="off">
@else
<form action="{{ action('DocumentController@postCreate') }}" method="post" autocomplete="off">
@endif
    <div class="pure-u-1 pure-u-lg-3-5 pure-u-md-4-5">
        <div class="content box">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <fieldset>
                <input type="text" name="title" value="{{ $document->title }}" placeholder="Title">
                <textarea rows="20" cols="" name="content">{!! $document->content !!}</textarea>
            </fieldset>
        </div>
    </div>
    <div class="pure-u-1 pure-u-lg-2-5 pure-u-md-1-5">
        <div class="right-menu">
            <div class="list lvl-0">
                <div class="row">
                    <button type="submit" class="flat">Save</button>
                </div>

                @if ($document->id)
                <a href="{{ action('DocumentController@getShow', ['id' => $document->id]) }}">
                    <div class="row">View</div>
                </a>
                <a href="{{ action('DocumentController@getEdit', ['id'=>$document->id]) }}">
                    <div class="row">Delete</div>
                </a>
                @else
                <a href="{{ action('DocumentController@index') }}">
                    <div class="row">Cancel</div>
                </a>
                @endif
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
