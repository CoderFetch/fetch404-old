<!DOCTYPE html>
<html>
<head>
    <title>Install Fetch404</title>
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
        body, input, textarea, select, button {
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


        .list {
            padding: 0;
            list-style-type: none;
        }
        .list li {
            border-top: 2px solid #eee;
            padding-left: 200px;
            margin-top: 10px;
            padding-top: 10px;
            overflow: hidden;
        }
        .list label {
            float: left;
            margin-left: -200px;
            color: #aaa;
        }

        .form {
            padding: 0;
            margin: 0;
            overflow: hidden;
            text-align: left;
        }
        .form > li {
            display: block;
            list-style: none;
            margin-bottom: 7px;
            overflow: hidden;
            margin-right: 2%;
        }
        .form > li.half {
            width: 48%;
            margin-right: 2%;
            float: left;
        }
        .form input[type=text],
        .form input[type=password],
        .form select,
        .form textarea,
        .form .input {
            margin: 0 3px 3px 0;
            font-size: 120%;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
        }

        .button {
            cursor: pointer;
            border-radius: 3px;
            background: #f6f6f6;
            font-weight: 300;
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

        input[type=text],
        input[type=password],
        textarea,
        .input {
            font-weight: 300;
            background: #fff;
            border: 1px solid #e5e5e5;
            padding: 5px;
            border-radius: 3px;
            vertical-align: -1px;
            margin: 0;
            -webkit-appearance: none;
        }
        input[type=text]:focus, input[type=password]:focus, textarea:focus, .input:focus {
            outline: 0;
            border-color: #e5e5e5;
        }
        input.error {
            border-color: #c00;
        }
        div.error {
            color: #c00;
            font-size: 90%;
        }
        .warning strong {
            display:block;
            font-weight: 600;
            color: #666;
        }
        .warning {
            color: #aaa;
            font-size: 90%;
        }
    </style>
    <script>
        $(function() {
            $("#advancedLink").click(function(e) {
                e.preventDefault();
                $(".advanced").fadeToggle("fast");
            });
            $('.advanced').hide();
            $('#domain').text(document.domain);
        });
    </script>
</head>
<body>

<div id='container'>
    <span class="fa-stack fa-3x">
        <i class="fa fa-square fa-stack-2x"></i>
        <i class="fa fa-comment-o fa-stack-1x fa-inverse"></i>
    </span>
    <br>
    <h1>Welcome to Fetch404</h1>
    <h2>
        <small style="color: #ccc">
            You will be installing Fetch404 on the following domain:
            <strong id="domain" style="color: black;"></strong>
        </small>
        <br />
        Set up your forum by filling out the form below.
        <br />
        <small style="color: lightgray;">* Need help? Visit the <a href="http://fetch404.com">Fetch404 support forum</a>.</small>
    </h2>
    {!! Form::open(['route' => array('install.post')]) !!}
        <div class='details'>
            <ul class='form'>
                <li class='half'>
                    {!! Form::text('forumTitle', '', ['placeholder' => 'Forum title', 'value' => Input::old('forumTitle')]) !!}
                    <br>
                </li>
                <li class='half advanced'>
                    {!! Form::text('forumDesc', '', ['placeholder' => 'Forum description', 'value' => Input::old('forumDesc')]) !!}
                    <br>
                </li>
                <li class='clear'></li>
                <li class='half advanced'>
                    {!! Form::label('enable_infractions_label', 'Enable infractions?') !!}
                    {!! Form::checkbox('enable_infractions', '', Input::old('enable_infractions')) !!}
                    <br>
                </li>
                <li class='half advanced'>
                    {!! Form::label('inverse_navbar_label', 'Enable inverse navigation colors?') !!}
                    {!! Form::checkbox('inverse_navbar', '', Input::old('inverse_navbar')) !!}
                    <br>
                </li>
                <li class='clear'></li>
                <li class='half advanced'>
                    {!! Form::label('bootswatch_theme_label', 'Select Bootswatch theme') !!}
                    <select name="bootswatch_theme">
                        <option value="1" selected>Default Bootstrap</option>
                        <option value="2">Cerulean</option>
                        <option value="3">Cosmo</option>
                        <option value="4">Cyborg</option>
                        <option value="5">Darkly</option>
                        <option value="6">Flatly</option>
                        <option value="7">Journal</option>
                        <option value="8">Lumen</option>
                        <option value="9">Paper</option>
                        <option value="10">Readable</option>
                        <option value="11">Sandstone</option>
                        <option value="12">Simplex</option>
                        <option value="13">Slate</option>
                        <option value="14">Spacelab</option>
                        <option value="15">Superhero</option>
                        <option value="16">United</option>
                        <option value="17">Yeti</option>
                    </select>
                    <br>
                </li>
                <li class='clear'></li>
                <li class='half'>
                    <input type='text' placeholder='Outgoing email address' name='outgoing_email' value='{{{ Input::old("outgoing_email") }}}'/>
                    <br>
                    @if ($errors->has('outgoing_email'))
                        <span style="color: #a94442;">
                            {{{ $errors->first('outgoing_email') }}}
                        </span>
                    @endif
                </li>
            </ul>

            <br>

            <ul class='form'>
                <li class='half'>
                    <input type='text' placeholder='MySQL Host' name='mysqlHost' value='{{{ Input::old("mysqlHost") }}}'/>
                    <br>
                    @if ($errors->has('mysqlHost'))
                        <span style="color: #a94442;">
                            {{{ $errors->first('mysqlHost') }}}
                        </span>
                    @endif
                </li>
                <li class='half'>
                    <input type='text' placeholder='MySQL Username' name='mysqlUser' value='{{{ Input::old("mysqlUser") }}}'/>
                    <br>
                    @if ($errors->has('mysqlUser'))
                        <span style="color: #a94442;">
                            {{{ $errors->first('mysqlUser') }}}
                        </span>
                    @endif
                </li>
                <li class='half clear'>
                    <input type='password' placeholder='MySQL Password' name='mysqlPass' value='{{{ Input::old("mysqlPass") }}}'/>
                    <br>
                    @if ($errors->has('mysqlPass'))
                        <span style="color: #a94442;">
                            {{{ $errors->first('mysqlPass') }}}
                        </span>
                    @endif
                </li>
                <li class='half'>
                    <input type='text' placeholder='MySQL Database' name='mysqlDB' value='{{{ Input::old("mysqlDB") }}}'/>
                    <br>
                    @if ($errors->has('mysqlDB'))
                        <span style="color: #a94442;">
                            {{{ $errors->first('mysqlDB') }}}
                        </span>
                    @endif
                </li>
                <li class='clear'></li>
            </ul>

            <br>

            <ul class='form'>
                <li class='half'>
                    <input type='text' placeholder='Admin Username' name='username' value='{{{ Input::old("username") }}}'/>
                    <br>
                    @if ($errors->has('username'))
                        <span style="color: #a94442;">
                            {{{ $errors->first('username') }}}
                        </span>
                    @endif
                </li>
                <li class='half'>
                    <input type='text' placeholder='Admin Email' name='email' value='{{{ Input::old("email") }}}'/>
                    <br>
                    @if ($errors->has('email'))
                        <span style="color: #a94442;">
                            {{{ $errors->first('email') }}}
                        </span>
                    @endif
                </li>
                <li class='half clear'>
                    <input type='password' placeholder='Admin Password' name='password' value='{{{ Input::old("password") }}}'/>
                    @if ($errors->has('password'))
                        <span style="color: #a94442;">
                            {{{ $errors->first('password') }}}
                        </span>
                    @endif
                </li>
                <li class='half'>
                    <input type='password' placeholder='Confirm Password' name='password_confirmation' value=''/>
                </li>
            </ul>

            <br>

            <ul class='form' style='text-align:center'>
                <li><button type='submit' class='submit button'>Install Fetch404 &#155;</button></li>
                <li><a href='#advanced' id='advancedLink'>Advanced Options</a></li>
            </ul>
        </div>
    {!! Form::close() !!}
</div>
</body>
</html>