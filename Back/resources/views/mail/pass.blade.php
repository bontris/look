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
		    background: #2ba5ae;
		}
	</style>
    <h2>Cambio de contrasña</h2>
	<p>Hola {{$name}}, tu contraseña de <b>Cadivas</b> ha sido cambiada.</p>
	<p>Si no has realizado este cambio, haz clic a continuación para cambiar tu contraseña.</p>
	<p>El enlace a continuación permanecerá activo por 24 horas.</p>
	<a href="{{route('pass', ['hash' => $hash, 'pass' => $pass])}}">Cambiar contraseña</a>
</body>
</html>