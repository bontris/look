<!DOCTYPE html>
<html>
<head>
    <title>Bureau Medellin</title>
	<style type="text/css">
		a {
			overflow: hidden;
			display: block;
		    margin: 10px 0px;
		    padding: 10px 30px;
		    text-decoration: none;
			max-width: 250px;
		    border-radius: 8px;
		    text-align: center;
		    line-height: 24px;
		    font-weight: 700;
		    font-size: 16px;
		    border: none;
		    color: #FFFFFF;
		    background: #D36100;
		}
	</style>
</head>
<body>
    <h2>¡Gracias por tu donación!</h2>
	<p>Estimado/a <b>{{$name}}</b>,</p>
	<p>En nombre de todo el equipo de <b>{{$firm->name}}</b>, el Bureau y de la ciudad, queremos agradecerte por tu generosa donación al turismo responsable.</p>
	<p>Tu contribución es invaluable y tendrá un impacto significativo en el desarrollo sostenible del turismo en Medellín.</p>
	<p>Ahora haces parte del Parche Paisa, una comunidad que disfruta de los siguientes beneficios:</p>
	<p>
		<a href="https://bond.bontris.com/sign/{{$hash}}">Conoce los Beneficios</a>
	</p>
	<p>Con este QR podrás acceder a grandes descuentos:</p>
	<p>
		<img src="{!!$message->embedData(QrCode::size(300)->format('png')->generate('Scan code'), 'code.png', 'image/png')!!}">
	</p>
	<br>
	<p>Gracias parcero/a,</p>
</body>
</html>