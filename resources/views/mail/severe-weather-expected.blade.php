<x-mail::message>

    <h1 style="text-align: center;">Severe weather expected</h1>

    @foreach($cities as $city)
        <h1 style="margin-top: 6px;">{{$city->name}}</h1>

        <table style="border-collapse: separate; border-spacing: 3px;">
            <thead>
                <tr>
                    <th style="text-align: left;">Date</th>
                    <th style="text-align: left;">Time</th>
                    <th style="text-align: left;">Rain (mm)</th>
                    <th style="text-align: left;">Snow (mm)</th>
                    <th style="text-align: left;">UV Index</th>
                </tr>
            </thead>
            <tbody>
            @foreach($city->weather as $weather)
                <tr>
                    <td style="text-align: center;">{{ \Illuminate\Support\Carbon::createFromTimestamp($weather['dt'])->toDateString() }}</td>
                    <td style="text-align: center;">{{ \Illuminate\Support\Carbon::createFromTimestamp($weather['dt'])->toTimeString() }}</td>
                    <td style="text-align: center;">{{ $weather['rain_1h'] > 0 ? $weather['rain_1h'] : '-' }}</td>
                    <td style="text-align: center;">{{ $weather['snow_1h'] > 0 ? $weather['snow_1h'] : '-' }}</td>
                    <td style="text-align: center;">{{ $weather['uvi'] > 0 ? $weather['uvi'] : '-' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endforeach

</x-mail::message>


