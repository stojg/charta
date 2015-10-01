@extends('app')

@section('content')

    <div class="toolbar">
        <a href="/" class="back"><span class="glyphicon glyphicon-home"></span> <h3 class="logo">Charta</h3></a>
    </div>

    <div class="content box">
        <h1>Trashed Documents</h1>
        @if ($documents->count())
        <ul class="list">
        @foreach ($documents as $index => $document)
            <li class="@if ($document->trashed())trashed @endif">
                <a href="{{ action('DocumentController@getRestore', ['id' => $document->id]) }}">
                    <div class="meta">{{ $document->updated_at->diffForHumans() }}</div>
                    {{  $document->getTitle() }}
                </a>
            </li>
            @endforeach
        </ul>
        @endif
    </div>

@endsection
