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
                <textarea class='editor' rows="20" cols="" name="content">{!! $document->content !!}</textarea>
            </fieldset>
        </div>
    </div>
    <div class="pure-u-1 pure-u-lg-2-5 pure-u-md-1-5">
        <div class="right-menu">
            <ul class="list lvl-0">
                <li>
                    <button type="submit" class="flat">Save</button>
                </li>

                @if ($document->id)
                    <li>
                <a href="{{ action('DocumentController@getShow', ['id' => $document->id]) }}">
                    View
                </a>
                    </li>
                    <li>
                <a href="{{ action('DocumentController@getDelete', ['id'=>$document->id]) }}">
                    Delete
                </a>
                    </li>
                @else
                    <li>
                <a href="{{ action('DocumentController@index') }}">
                    Cancel
                </a>
                    </li>
                @endif
                </div>
            </div>
        </ul>
    </div>
</form>
@endsection
