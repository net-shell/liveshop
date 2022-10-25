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
            }

            .h-full {
                height: 100%;
            }

            code {
                border: 1px solid #ccc;
                border-radius: .2rem;
                padding: .2rem .4rem;
            }
        </style>
    </head>
    <body>
        <div class="flex-center h-full">
            <div>
                <code>LiveShop</code>
            </div>
        </div>
    </body>
</html>
