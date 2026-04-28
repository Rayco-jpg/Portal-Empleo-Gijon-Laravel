<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Portal de Empleo Gijón</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="{{ (isset($_COOKIE['tema']) && $_COOKIE['tema'] == 'oscuro') ? 'modo-oscuro' : '' }}">
    <main>
        @yield('content')
    </main>
</body>
</html>