@extends('app')

@section('content')
    <div class="toolbar">
        <a href="/" class="back"><span class="glyphicon glyphicon-home"></span> <h3 class="logo">Charta</h3></a>
    </div>
    <div class="content box">
        <p>
            <a href="{{ url('/signin') }}">Sign in</a> to view, create and edit your private markdown notes.
        </p>
        <p><strong>alpha version, do not use for sensitive or important data.</strong></p>
        <p>
            <h2>Screenshots</h2>
            <h4>Edit a note</h4>
            <p>After an document have been saved for the first time, all changes to it will be saved automatically.</p>
            <img src="/images/edit.png">
            <h4>See all saved notes</h4>
            <p>Infinity list, if you have a giant list of documents, search via the browsers build in search tool (CTRL + f
            or CMD + f).</p>
            <img src="/images/list.png">
            <h4>View the note</h4>
            <p>Markdown parsed is parsed as HTML.</p>
            <img src="/images/view.png">

        </p>
        <p>source code at <a href="https://github.com/stojg/charta">https://github.com/stojg/charta</a></p>
    </div>
@endsection
