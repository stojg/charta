@extends('app')

@section('content')
<div class="pure-u-1 pure-u-lg-3-5 pure-u-md-4-5">
    <div class="content box">
        <h1>Documents</h1>
        <ul class="list lvl-0">
        @foreach ($documents as $index => $document)
            <li>
                <a href="{{ action('DocumentController@getShow', ['id' => $document->id]) }}">
                    <div class="meta">{{ $document->updated_at->diffForHumans() }}</div>
                    {{  $document->title }}
                </a>
            </li>

                {{--<div class="row @if($index == 0)first @elseif ($index+1==count($documents))last @endif">--}}


                    {{--<div class="action"><a href="{{ action('DocumentController@getEdit', ['id'=>$document->id]) }}"><span class="icon-pencil"></span> </a></div>--}}


                {{--</div>--}}
            @endforeach
        </ul>
    </div>
</div>
@endsection
