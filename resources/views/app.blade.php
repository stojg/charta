<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charta</title>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/g/pure@0.6.0(pure-min.css+grids-responsive-min.css)">
    <link rel="stylesheet" href="/css/app.css">
</head>

<body>
    <div id="layout">
        <div class="main pure-g">
            <div class="pure-u-md-1-5"></div>
            <div class="pure-u-1 pure-u-md-3-5">@yield('content')</div>
            <div class="pure-u-md-1-5"></div>
        </div>
    </div>

    <script>
        if (!window.jQuery) {
            document.write('<script src="//code.jquery.com/jquery-2.1.4.min.js"><\/script>');
        }
    </script>
    <script src="/js/all.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/font-hack/2.015/css/hack-extended.min.css">
</body>
</html>