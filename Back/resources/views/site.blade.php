<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
	<head>
    	<meta name="viewport" content="minimum-scale=1.0, maximum-scale=1.0, width=device-width">
        <meta charset="UTF-8">
        <link href="/styles/material.min.css?v=1.2" rel="stylesheet">
        <link href="/styles/vuetify.css?v=1.4" rel="stylesheet">
        <link href="/styles/site.css?v=1.5" rel="stylesheet">
    	<link rel="icon" type="image/png" href="/icon.png">
		<title>{{config('app.name', 'Test')}}</title>
  	</head>
	<body>
		<div id="head">
      		<div class="wrap">
        		<div class="logo">
          			<img src="/images/dark.png">
        		</div>
        		<div class="main">
          			<div class="menu">
          				<label for="head:menu:main">
            				<svg viewBox="0 0 24 24">
							    <path d="M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z"/>
							</svg>
            			</label>
            			<input type="checkbox" id="head:menu:main">
            			<nav>
              				<dl>
                				<dt>
                  					<a href="{{route('home')}}">Inicio</a>
                				</dt>
                				<dt>
                  					<a href="{{route('about', ['path' => 'nosotros'])}}">Nosotros</a>
                				</dt>
								<dt>
                  					<a href="{{route('site.posts')}}">Artículos</a>
                				</dt>
                				<dt>
                  					<a href="/contactanos">Contáctanos</a>
	                			</dt>
              				</dl>
            			</nav>
          			</div>
                    @if (Auth::check())
                    <div class="knob">
            			<a href="{{route('dash')}}">Panel de Administración</a>
          			</div>
                    @else
          			<div class="knob">
            			<a href="{{route('sign')}}">Iniciar sesión</a>
          			</div>
                    @endif
        		</div>
      		</div>
    	</div>
    	<div id="body">
            @yield('page')
    	</div>
		<div id="foot">
      		<div class="main">
      			<div class="wrap">
      	  			<div class="grid">
      	    			<div class="item">
              				<div class="logo">
              					<img src="/images/icon.png">
              				</div>
      	    			</div>
      	    			<div class="item">
      	    				<div class="menu">
      	    					<em>Contácto</em>
      	    					<dl>
      	    						<dt>
      	    							<p>CLL 37B #96-04</p>
      	    						</dt>
      	    						<dt>
      	    							<p>(+57) 312 287 6580</p>
      	    						</dt>
      	    						<dt>
      	    							<p>contacto@devint.co</p>
      	    						</dt>
      	    						<dt>
      	    							<p>Medellín – Colombia</p>
      	    						</dt>
      	    					</dl>
      	    				</div>
      	    			</div>
      	    			<div class="item">
      	    				<div class="menu">
      	    					<em>Enlaces</em>
      	    					<dl>
      	    						<dt>
      	    							<a href="{{route('site.posts')}}">Noticias</a>
      	    						</dt>
      	    						<dt>
      	    							<a href="{{route('about', ['path' => 'nosotros'])}}">Nosotros</a>
      	    						</dt>
      	    						<dt>
      	    							<a href="{{route('mail')}}">Contáctanos</a>
      	    						</dt>
      	    					</dl>
      	    				</div>
      	    			</div>
						  <div class="item">
      	    				<div class="menu">
      	    					<em>Redes</em>
      	    					<dl>
      	    						<dt>
      	    							<a href="https://facebook.com/devint.co" target="_blank">Fecebook</a>
      	    						</dt>
      	    						<dt>
      	    							<a href="https://instagram.com/devint.co">Instagram</a>
      	    						</dt>
      	    						<dt>
      	    							<a href="#">Youtube</a>
      	    						</dt>
      	    					</dl>
      	    				</div>
      	    			</div>
      	  			</div>
        		</div>
      		</div>
      		<div class="pane">
      			<div class="wrap">
      	  			<div class="menu">
            			<ul role="menu">
              				<li>
              					<a href="#home">Inicio</a>
              				</li>
              				<li>
              					<a href="#terms">Terminos</a>
              				</li>
              				<li>
              					<a href="#privacity">Privacidad</a>
              				</li>
            			</ul>
          			</div>
          			<div class="side">
            			<strong>Copyright&nbsp;&copy;&nbsp;2021&nbsp;Devint</strong>
	          			<small>Powered by&nbsp;<a href="https://bontris.com" target="_blank">Bontris</a></small>
	          			<span><a href="#body" title="Volver arriba"><i class="icon mdi mdi-chevron-up"></i></a></span>
          			</div>
        		</div>
      		</div>
    	</div>	
        <script src="/scripts/vue.min.js"></script>
        <script src="/scripts/i18n.min.js"></script>
        <script src="/scripts/axios.min.js"></script>
        <script src="/scripts/jquery.min.js"></script>
        <script src="/scripts/vuetify.js"></script>
        @yield('code')
	</body>
</html>