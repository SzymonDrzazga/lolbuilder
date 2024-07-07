<!DOCTYPE html>
<html>

<head>
    <title>{{ $champ['name'] }}</title>
</head>

<body>
    <h1>{{ $champ['name'] }}</h1>
    <p>{{ $champ['title'] }}</p>
    <img src="{{ $champ['image']['full'] }}" alt="{{ $champ['name'] }}">
</body>

</html>
