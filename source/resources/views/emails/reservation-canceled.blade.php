<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <style>
            body {
                font-family: 'Nunito';
            }
            table, th, td {
                border: 1px solid black;
            }
        </style>
    </head>
    <body>
        Dobrý den, vážený zákazníku, 
        vzhledem k tomu, že jste si dnes nevyzvedl Vaše rezervace, tak budou automaticky zrušeny.<br><br>
        Jedná se o následující tituly: <br><br>
        
        <table>
            <tr>
                <th>Název titulu</th>
                <th>Množství</th>
                <th>Datum rezervace</th>
                <th>Místo rezevace</th>
            </tr>
        @foreach ($reservationsToBeCanceled as $reservation)
            <tr>
                <td>{{ $reservation['title_name'] }} ({{$reservation['language']}} dabing )</td>
                <td>{{ $reservation['quantity'] }}</td>
                <td>{{ $reservation['reservation'] }} - {{ $reservation['reservation_till'] }}</td>
                <td>{{ $reservation['store_address'] }}</td>
            </tr>
        @endforeach
        </table>
    </body>
</html>
