<!DOCTYPE html>
<html>
    <head>
        <title>Fetch404 Installer | Success</title>
        <meta charset='utf-8'>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        {!! HTML::script('assets/js/jquery-1.11.2.min.js') !!}
        <link type="text/css" rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,600">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <style>
            body {
                margin: 0;
                color: #666;
                line-height: 1.5em;
            }
            body {
                font-size: 16px;
                font-family: open sans, helvetica, arial, sans-serif;
            }
            a {
                text-decoration: none;
                color: #354059;
            }
            a:hover {
                color: #000;
            }
            p, ul, ol, h1, h2, h3 {
                margin: 0 0 1em;
            }
            h1 {
                font-size: 200%;
                font-weight: 300;
                color: #354059;
                margin-top: 30px;
            }
            h2 {
                font-size: 120%;
                font-weight: 300;
                margin-bottom: 50px;
                line-height: 1.5em;
            }
            h3 {
                font-size: 100%;
                font-weight: normal;
                margin: 1.5em 0 0.5em;
            }
            hr {
                border: solid #eee;
                border-width: 2px 0 0;
                margin: 15px 0;
            }
            .clear {
                clear: both;
            }

            #container {
                margin: 50px auto;
                max-width: 700px;
                text-align: center;
            }
            .details {
                text-align: left;
                margin: 0 auto;
            }

            .button {
                cursor: pointer;
                border-radius: 3px;
                background: #f6f6f6;
                font-weight: 600;
                border: 1px solid transparent;
                white-space: nowrap;
                font-size: 120%;
                padding: 15px 25px;
                -webkit-appearance: none;
            }
            .button:hover,
            .button:active {
                border-color: #ddd;
            }
            .button:active {
                background-color: #ddd;
            }
            a.button {
                display: inline-block;
            }

            .button.submit {
                border-color: #000;
                color: #fff;
                background-color: #354059;
            }
            .button.submit:hover {
                border-color: #000;
                color: #fff;
            }
        </style>
    </head>
    <body>
        <div id='container'>
            <span class="fa-stack fa-3x">
                <i class="fa fa-square fa-stack-2x"></i>
                <i class="fa fa-check fa-stack-1x fa-inverse"></i>
            </span>
            <br>
            <h1>Installation Succeeded</h1>
            <h2>
                <small style="color: #ccc">
                    Fetch404 has been installed! You can now use your site.
                </small>
                <br />
                <div style="text-align: center;">
                    <a class="submit button" href="{{{ route('home.show') }}}">
                        Proceed to Site &#155;
                    </a>
                    <br />
                    <small style="color: lightgray;">* Need help? Visit the <a href="http://fetch404.com">Fetch404 support forum</a>.</small>
                </div>
            </h2>
        </div>
    </body>
</html>