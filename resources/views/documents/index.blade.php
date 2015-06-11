@extends('app')

@section('content')
    <div class="toolbar">
        <div class="actions">
            <a href="{{ action('DocumentController@getNew') }}">
                <span class="glyphicon glyphicon-plus-sign"></span>
            </a>
            <a href="{{ action('DocumentController@getTrashed') }}">
                <span class="glyphicon glyphicon-folder-open"></span>
            </a>
        </div>
        <span class="glyphicon glyphicon-home"></span> <h3>Charta</h3>
    </div>

    <div class="content box">
        <h1>Documents</h1>
        @if ($documents->count())
            <ul class="list">
                @foreach ($documents as $index => $document)
                    <li class="@if ($document->trashed())trashed @endif">
                        <a href="{{ action('DocumentController@getShow', ['id' => $document->id]) }}">
                            <div class="meta">{{ $document->updated_at->diffForHumans() }}</div>
                            {{  $document->getTitle() }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
