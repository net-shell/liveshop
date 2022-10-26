<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>LiveShop</title>

        <style>
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }

            body {
                background-color: #333;
                color: #ccc;
                font-family: monospace;
            }

            .flex-center {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100%;
            }

            .btn {
                border: 1px solid #ccc;
                border-radius: .2rem;
                padding: 1rem;
                color: #fff;
                font-size: 2em;
                text-decoration: none;
            }

            .btn:hover {
                background-color: rgba(255, 255, 255, .25);
            }
        </style>
    </head>
    <body>
        <div class="flex-center">
            <div>
                <a class="btn" href="{{ route('client') }}">Каталог</a>
            </div>
        </div>
    </body>
</html>
