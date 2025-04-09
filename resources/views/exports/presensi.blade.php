<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>NIPD</th>
                <th>NAMA</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datas as $index => $data)
                @php
                    $status = 'belum hadir';
                    if ($data->presence_in) {
                        $status = 'hadir';
                    } elseif ($data->absence_date) {
                        $status = $data->absence_type;
                    }
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->nipd }}</td>
                    <td>{{ $data->name }}</td>
                    <td>{{ $status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
