<!DOCTYPE html>
<html>
<head>
    <title>Confirmar correo electrónico</title>
</head>
<body>
	<p>Hola {{$name}},</p>
    <p>¡Te damos la bienvenida a {{env('APP_NAME')}}!</p>
    <p>Por favor confirma tu correo electrónico para completar el proceso de configuración mediante en el siguiente enlace:</p>
    <p><a href="{{route('lock', ['hash' => $seek])}}">{{route('lock', ['hash' => $seek])}}</a></p>
    <p>Correo electrónico: {{$mail}}</p>
    <p>Código de verificación: {{$pass}}</p>
    <br>
    <p>Gracias,<br>{{env('APP_FROM')}}</p>
</body>
</html>