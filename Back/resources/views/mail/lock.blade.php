<!DOCTYPE html>
<html>
<head>
    <title>Confirmar correo electrónico</title>
</head>
<body>
	<p>Hola {{$name}},</p>
    <p>¡Te damos la bienvenida a {{env('APP_NAME')}}!</p>
    @if (isset($lock))
    <p>Por favor confirma tu correo electrónico mediante el siguiente enlace:</p>
    <p><a href="{{route('lock', ['hash' => $seek])}}">{{route('lock', ['hash' => $seek])}}</a></p>
    @endif
    <p>Correo electrónico: {{$mail}}</p>
    @if (isset($lock))
    <p>Código de verificación: {{$lock}}</p>
    @else
    <p>Contraseña de ingreso: {{$pass}}</p>
    @endif
    <br>
    <p>Gracias,<br>{{env('APP_FROM')}}</p>
</body>
</html>