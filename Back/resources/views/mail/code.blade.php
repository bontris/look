<!DOCTYPE html>
<html>
<head>
    <title>Te damos la bienvenida a {{env('APP_NAME')}}</title>
</head>
<body>
	<p>Hola <b>{{$name}}</b>,</p>
    <p>¡Es un gusto darte la bienvenida a {{env('APP_NAME')}}!</p>
	<p>Desde ahora cuentas con acceso a nuestra plataforma digital, pensada para que tengas toda la información y servicios legales de tu empresa en un solo lugar, de manera ágil y sencilla.</p>
    @if (isset($lock))
    <p>Ingresa aquí: <a href="{{route('lock', ['hash' => $seek])}}">{{route('lock', ['hash' => $seek])}}</a></p>
	@else
	<p>Ingresa aquí: <a href="{{route('sign')}}">{{route('sign')}}</a></p>
    @endif
    <p>Usuario: <b>{{$mail}}</b></p>
    @if (isset($lock))
    <p>Código: <b>{{$lock}}</b></p>
    @else
    <p>Contraseña: <b>{{$pass}}</b></p>
    @endif
    <br>
    <p>Nos alegra mucho que ya hagas parte de nuestra comunidad.</p>
	<p>Recuerda que estamos aquí para apoyarte en todo momento; si tienes alguna duda o necesitas ayuda, no dudes en escribirnos.</p>
	<p>Un abrazo,</p>
	<img src="{{url('/images/0008.png')}}" style="display: block; height: auto; border: 0; width: 800px; margin: 16px 0px" alt="Taller A">
</body>
</html>