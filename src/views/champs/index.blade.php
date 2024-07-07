<!DOCTYPE html>
<html>

<head>
    <title>Champions</title>
</head>

<body>
    <h1>Champions</h1>
    <ul>
        @foreach ($champs as $champ)
            <li>{{ $champ['name'] }}</li>
        @endforeach
    </ul>
</body>

</html>
