<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Blockbuster 2.0</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Icon -->
        <link rel="icon" href="{{ URL::asset('favicon.ico') }}" type="image/x-icon"/>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <style>
            body {font-family: 'Nunito', serif;}
        </style>
        <script type="text/javascript">
            window.Laravel = {
                csrfToken: "{{ csrf_token() }}",
                jsPermissions: {!! auth()->check()?auth()->user()->jsPermissions():null !!}
            }
        </script>
    </head>
    <body>
        <div id="app">
            <app></app>
        </div>
        <script src="https://www.paypal.com/sdk/js?client-id=AfXYdQoUcswgI0alGokJ-SaxrwIQzD3geOqYoG3ub_rG8KpCcMj0u40-NiGiWECJA3MjXjVYcRsnPfwa"></script>
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
