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
    <link rel="stylesheet" href="/css/codemirror.css">
    <link rel="stylesheet" href="/css/icomoon.css">
    <link rel="stylesheet" href="/css/app.css">
</head>

<body>
<div id="layout">
    <div class="sidebar">
        <div class="box">
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
<link rel="stylesheet" href="//cdn.jsdelivr.net/editor/0.1.0/editor.css">
<script src="/js/codemirror.js"></script>
<script src="/js/app.js"></script>

</body>
</html>