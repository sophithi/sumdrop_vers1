<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('welcome.title') }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #4f46e5, #2563eb);
        }

        .container {
            width: 380px;
            background: #fff;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0, 0, 0, .2);
        }

        h1 {
            color: #222;
            margin-bottom: 10px;
        }

        p {
            color: #666;
            margin-bottom: 30px;
        }

        .btn {
            display: block;
            width: 100%;
            text-decoration: none;
            padding: 14px;
            margin: 12px 0;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            transition: .3s;
        }

        .employee {
            background: #2563eb;
            color: white;
        }

        .employee:hover {
            background: #1d4ed8;
        }

        .admin {
            background: #111827;
            color: white;
        }

        .admin:hover {
            background: #000;
        }

        .footer {
            margin-top: 25px;
            color: #999;
            font-size: 14px;
        }
    </style>

</head>

<body>

    <div class="container">

        <h1>{{ __('welcome.heading') }}</h1>

        <a href="{{ route('login') }}" class="btn admin">
            {{ __('welcome.get_started') }}
        </a>

        <div class="footer">
            {{ __('welcome.footer') }}
        </div>

    </div>

</body>

</html>