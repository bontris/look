<!DOCTYPE html>
<html>
<head>
    <title>Cuenta [Ganado]</title>
</head>
<body>
	<style type="text/css">
		a {
			overflow: hidden;
			display: block;
			float: left;
		    margin: 10px 0px;
		    padding: 10px 30px;
		    text-decoration: none;
		    border-radius: 8px;
		    text-align: center;
		    line-height: 24px;
		    font-weight: 700;
		    font-size: 16px;
		    border: none;
		    color: #FFFFFF;
		    background: #231F21;
		}
	</style>
    <h2>Cambiar tu contraseña</h2>
	<p>Hola {{$name}}, hemos recibido una peticióon de cambio dee contraseña para tu cuenta en <b>[Ganado]</b>.</p>
	<p>Si tu no has solicitado el cambio de contraseña, entonces puedes ignorar este correo electrónicco y tu contraseña no será cambiada.</p>
	<p>El enlace a continuación permanecerá activo por 24 horas.</p>
	<a href="{{route('pass', ['hash' => $hash, 'pass' => $pass])}}">Cambiar contraseña</a>
</body>
</html>