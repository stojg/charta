<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charta</title>
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/grids-responsive-old-ie-min.css">
    <![endif]-->
    <!--[if gt IE 8]><!-->
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/grids-responsive-min.css">
    <!--<![endif]-->
    <link rel="stylesheet" href="{{ elixir("css/app.css") }}">
</head>

<body>
<div id="layout">
    <div class="sidebar">
        <div class="left-menu">
            <h1 class="logo">Charta</h1>
                @if (Auth::guest())
                <ul>
                    <li><a href="{{ url('/') }}">Home</a></li>
                </ul>
                @else
                <ul>
                    <li><a href="{{ url('/home') }}">Documents</a></li></li>
                </ul>
                <ul>
                    <li><a href="{{ action('DocumentController@getNew') }}">New</a></li>
                    <li><a href="{{ url('/auth/logout') }}">Logout</a></li>
                </ul>
                @endif
        </div>
    </div>

    <div class="main pure-g">
        @yield('content')
    </div>

</div>

<script>
    if (!window.jQuery) {
        document.write('<script src="http://code.jquery.com/jquery-2.1.4.min.js"><\/script>');
    }
</script>

<script src="/js/codemirror.js"></script>
<script src="/js/markdown/markdown.js"></script>
<script src="/js/highlight.min.js"></script>
<script src="/react-0.13.3/build/react.js"></script>
<script src="{{ elixir("js/app.js") }}"></script>

{{--<script src="/js/comments.js">--}}

</script>
</body>
</html>