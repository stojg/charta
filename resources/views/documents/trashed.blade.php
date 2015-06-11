@extends('app')

@section('content')

    <div class="toolbar">

        <a href="/"><i class="glyphicon glyphicon-circle-arrow-left"></i></a> <h3>Charta</h3>
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
