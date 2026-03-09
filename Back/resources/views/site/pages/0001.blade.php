@extends('site')

@section('page')
	<div id="page">
		<div class="path">
			<div class="wrap">
				<em>
					<i>Nosotros</i>
					<b>Quiénes somos</b>
				</em>
      			<ul>
      				<li>
      					<a href="{{route('home')}}">Inicio</a>
      				</li>
        			<li>Presentación</li>
      			</ul>
    		</div>
		</div>
		<div class="main">
			<div class="wrap">
				<div class="rich">
					<div class="grid">
						<div class="item">
							<h1>NOSOTROS</h1>
            				<p>Soluciones en IoT, Telemetría y Telecontrol para el Sector Industrial, Agroambiental y Hospitalario.</p>
						</div>
						<div class="item">
							<h1>PRESENTACIÓN</h1>
							<p>Nos permitimos presentarnos como Devint S.A.S una empresa formada por un equipo de ingenieros expertos en diseño electrónico, desarrollo de sistemas embebidos y las comunicaciones inalámbricas, contamos con 4 años de experiencia en el mercado. Nos dedicamos a diseñar, innovar, fabricar y proveer dispositivos electrónicos orientados a la telemetría e IOT (Internet de las cosas) para el sector industrial, domótica y salud.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop