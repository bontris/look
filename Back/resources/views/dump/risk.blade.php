<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concepto de Viabilidad - Marca {{$make->name}}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: #FF6A03;
            color:white;
            backdrop-filter: blur(10px);
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .headername {
            background: #252a27;
            color:white;
            backdrop-filter: blur(10px);
            padding: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
            overflow: hidden;
        }



        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        .header h1 {
            font-size: 2.5em;
            color: #252a27;
            margin-bottom: 10px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .brand-name {
            display: inline-block;
            background-clip: text;
            font-size: 2.4em;
            font-weight: 800;
            margin: 40px 0;
            position: relative;
            z-index: 1;
            color:white;
        }

        .header-info {
            color: #252a27;
            font-size: 1.1em;
            position: relative;
            z-index: 1;
            background: linear-gradient(180deg, #bcc3e4 0%, #ffffff 100%);
            text-align: center;
            padding: 40px;
        }



        .section-title {
            align-items: center;
            font-size: 1.8em;
            color: #ffffff;
            font-weight: 600;
        }

        .section-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #ff6b35, #1a1a1a);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            font-weight: bold;
        }

        .classes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .class-card {
            padding: 25px;
        }

        .class-number {
            padding: 10px;
            font-size: 2em;
            font-weight: 800;
            color: #262929;
        }

        .class-description {
            color: #ccc;
            margin-bottom: 15px;
            font-weight: 500;
        }

        .class-includes {
            padding: 15px;
            font-size: 0.9em;
            color: #fff;
        }

        .viability-score {
            text-align: center;
            background: #ff6b35;
            color: white;
            padding: 40px;
        }

        .score-number {
            font-size: 4em;
            font-weight: 800;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .score-text {
            font-size: 1.3em;
            opacity: 0.9;
        }

        .similar-brands {
            display: grid;
            gap: 20px;
        }

        .make-name-icon {
            display: block;
            width: 128px;
            height: auto;
            margin: 40px auto 0px auto;
        }

        .brand-card {
            background: #FAD5C1;
            color: white;
            padding: 25px;
        }

        .brand-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .brand-name-icon {
            width: 96px;
            height: auto;
        }

        .brand-name-card {
            font-size: 1.5em;
            font-weight: 700;
            color: #1a1a1a;
        }

        .brand-classes {
            background: #ff6b35;
            color: #1a1a1a;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 600;
        }

        .brand-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 15px 0;
            background: rgb(255, 255, 255);
            padding: 15px;
           
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-weight: 600;
            color: #262929;
            font-size: 0.9em;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #262929;
            font-weight: 500;
        }

        .risk-analysis {
            background: #FFFFFF;
            padding: 20px;
            margin-top: 15px;
            color: #262929;
        }

        .search-results {
            overflow-x: auto;
        }

        .results-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            color: #252a27;
            overflow: hidden;
        }

        .results-table th {
            background: #BCC3E4;
            color: #252a27;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .results-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #333;
        }


        .footer {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            margin-top: 30px;
        }

        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .contact-item {
            background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border: 1px solid rgba(255, 107, 53, 0.3);
        }

        .highlight {
            background: linear-gradient(135deg, #ff6b35 0%, #1a1a1a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }

        .why-read {
            background: #ff6b35;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .niza {
            background: #252a27;
            color: white;
            padding: 30px;
            text-align: center;
        }

        .clases {
            background: #EBEDF7;
            color: 252a27;
            padding: 30px;
            text-align: center;
        }

        .why-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            background-color: #f8f5f0;
            padding: 40px;
        }

        .why-item {
            background: #252a27;
            border: 1px solid #252a27;
            padding: 15px;
            text-align: center;
            font-weight: 600;
            color: #fff;
            transition: all 0.3s ease;
        }

        .social-container {
        display: flex;
        gap: 20px;
        }

        .social-icon {
        background-color: white;
        border-radius: 50%;
        padding: 12px;
        width: 48px;
        height: 48px;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: background-color 0.3s ease;
        }

        .social-icon img {
        width: 20px;
        height: 20px;
        filter: invert(0%) brightness(0%); /* negro */
        }
        .contenedor-principal {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #262929;
        padding: 40px;
        margin-top: -10px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .header {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 1.8em;
            }
            
            .brand-name {
                font-size: 1em;
            }
            
            .classes-grid {
                grid-template-columns: 1fr;
            }
            
            .score-number {
                font-size: 3em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 style="color:white;">CONCEPTO DE VIABILIDAD</h1>
        </div>
        <div class="headername">
            <div class="brand-name">{{$make->name}}</div>
        </div>
        <div class="header-info">
            <p>Medellín, {{date('d', $time)}} de {{$list[date('n', $time)]}} de {{date('Y', $time)}}</p>
            <p>¡Hola {{$make->title}}!</p>
        </div>

        <!-- Why Read Section -->
        <div class="section why-read">
            <div class="section-title">
                ¿POR QUÉ DEBERÍAS LEERLO?
            </div>
        </div>

        <div class="why-list">
            <div class="why-item">Conocer</div>
            <div class="why-item">Entender</div>
            <div class="why-item">Descubrir</div>
            <div class="why-item">Profundizar</div>
        </div>

        <div class="section" style="background-color: #F0ECE1; padding: 40px;">
            <p>Por medio de la presente, se procede a expedir concepto relacionado con la viabilidad en el registro de la marca <b>{{$make->name}}</b> en los siguientes términos.</p>
            @if (isset($make->icon))
            <img class="make-name-icon" src="{{url("/snaps/$make->icon")}}" alt="Logo">
            @endif
        </div>

        <!-- Clases de Niza -->
        <div class="section niza">
            <div class="section-title">
                CLASE DE NIZA SUGERIDA
            </div>
        </div>

        <div class="section clases">
            <div class="classes-grid">
                @foreach ((json_decode($make->sort, true) ?? []) as $item)
                <div class="class-card">
                    <div class="class-number" style="background-color: #BCC3E4; #262929;  ">Clase #{{$item['code']}}</div>
                    <div class="class-description" style="background-color: #FFFFFF; color: #262929; text-align: left; padding: 10px; padding-bottom: 40px;">
                        <strong>Descripción General:</strong> {{$item['note']}}
                    </div>
                    @if (trim($item['more']))
                    <div class="class-includes" style="background-color: #FFFFFF; color: #262929; text-align: left; padding: 10px;">
                        <strong>Esta clase incluye en particular:</strong> {{$item['more']}}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Importancia de la Marca -->

        <div class="section niza">
            <div class="section-title">
                LA IMPORTANCIA DE LA MARCA Y SU REGISTRO
            </div>
        </div>
        <div class="section" style="background-color: #F0ECE1; padding: 40px;">
            <p>Con el registro de la marca, lo que se busca es la <b>individualización de productos o servicios</b> respecto a otros de su misma categoría o de la competencia. Así mismo, la protección de una marca tiene como finalidad evitar problemas como la confusión, signos idénticos y la dilución de este.</p>
            <br>
            <p>En Colombia el derecho sobre la marca se adquiere con el registro y genera un <b>monopolio exclusivo y excluyente</b> de manera territorial, es decir, sólo está protegida frente a terceros, siempre y cuando haya sido registrada ante la entidad competente, en este caso la Superintendencia de Industria y Comercio (SIC) en Colombia.</p>
            <br>
            <p>El objetivo del registro de marca es entonces constituir <b>propiedad y derechos exclusivos</b> de explotación comercial sobre el texto y/o las figuras distintivas que la conforman. El registro permite al titular obtener la propiedad sobre la marca y convertirse así en un activo que se podrá vender, licenciar y/o usar como garantía.</p>
        </div>

        <!-- Marcas Similares -->

        <div class="section niza">
            <div class="section-title">
                MARCAS SIMILARES
            </div>
        </div>
        @if (trim($make->introduction))
        <div class="section" style="background-color: #F0ECE1; padding: 40px;">
            <p>{{$make->introduction}}</p>
        </div>
        @endif
        <div class="section">
            <div class="similar-brands">
                <!-- WMI -->
                @foreach ((json_decode($make->risk) ?? []) as $item)
                <div class="brand-card">
                    <div class="brand-header">
                        @if (isset($item->icon))
                        <img class="brand-name-icon" src="{{url("/snaps/$item->icon")}}" alt="Logo">
                        @endif
                        <div class="brand-name-card">{{$item->name}}</div>
                        <div class="brand-classes" style="background-color: #BCC3E4; font-size: 2em;">Clases {{implode(', ', $item->sort ?? [])}}</div>
                    </div>
                    <div class="brand-details" >
                        <div class="detail-item">
                            <span class="detail-label">Titular</span>
                            <span class="detail-value">{{$item->skip}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Expediente</span>
                            <span class="detail-value">{{$item->code}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Vigencia</span>
                            <span class="detail-value">{{date('d', strtotime($item->date))}} de {{$list[date('n', strtotime($item->date))]}} de {{date('Y', strtotime($item->date))}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Tipo</span>
                            <span class="detail-value">{{[1 => 'Mixta', 2 => 'Nominativa', 3 => 'No Figurativa', 4 => '3D', 5 => 'Sonido', 6 => 'Tridimensional mixta'][$item->type]}}</span>
                        </div>
                    </div>
                    @if ((isset($item->zone) && empty(empty(trim($item->zone)))))
                    <div class="risk-analysis">
                        <strong>Cobertura:</strong> {{$item->zone}}
                    </div>
                    @endif
                    <div class="risk-analysis">
                        <strong>Análisis de Riesgo:</strong> {{$item->note}}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="section niza">
            <div class="section-title">
                VIABILIDAD DE REGISTRO ESTIMADA
            </div>
        </div>

        <div class="section clases">
            <div class="classes-grid">
                @foreach ((json_decode($make->rate, true) ?? []) as $item)
                <div class="class-card">
                    <div class="viability-score">
                        <div class="score-number">{{$item['load']}}%</div>
                    </div>
                    <div class="class-description" style="background-color: #262929; color: #FFFFFF; text-align: left; font-size: 2em; padding: 10px;">
                        Clases {{implode(', ', $item['sort'] ?? [])}}
                    </div>
                    @if (trim($item['note']))
                    <div class="class-includes" style="background-color: #FFFFFF; color: #262929; text-align: left; padding: 10px;">
                        {{$item['note']}}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Resultados de Búsqueda -->
         <div class="section niza">
            <div class="section-title">
                RESULTADOS DE LA BÚSQUEDA EN SIC
            </div>
        </div>
        <div class="section">
            <div class="search-results">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Expediente</th>
                            <th>Denominación</th>
                            <th>Vigencia</th>
                            <th>Estado</th>
                            <th>Titular</th>
                            <th>Clases</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ((json_decode($make->seek) ?? []) as $item)
                        <tr>
                            <td>{{$item->code}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{date('d', strtotime($item->date))}} de {{$list[date('n', strtotime($item->date))]}} de {{date('Y', strtotime($item->date))}}</td>
                            <td>{{[1 => 'Negada', 2 => 'Publicada', 3 => 'Registrada', 4 => 'Bajo examen de fondo', 5 => 'Cancelada', 6 => 'Renuncia total'][$item->rank]}}</td>
                            <td>{{$item->skip}}</td>
                            <td>{{implode(', ', $item->sort ?? [])}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if (trim($make->notice))
            <div class="risk-analysis">{{$make->notice}}</div>
        @endif
<img width="1160px" src="https://tallera.co/wp-content/uploads/2025/07/Captura-de-pantalla-2025-07-04-a-las-12.44.02 a.m.png">
<img width="1160px" src="https://tallera.co/wp-content/uploads/2025/07/Captura-de-pantalla-2025-07-04-a-las-12.44.14 a.m.png">
<div class="contenedor-principal">
<div class="social-container" style="background-color: #252a27; text-align: center;" align="center" >
  <a href="https://www.instagram.com/taller_a?igsh=YzFjcGxzajV6dTIw" class="social-icon">
    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg" alt="Instagram">
  </a>
  <a href="https://youtube.com/@tallera3230?si=Rip0v_bsJ02MIscg" class="social-icon">
    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/youtube.svg" alt="YouTube">
  </a>
  <a href="https://www.linkedin.com/company/taller-a/?originalSubdomain=co" class="social-icon">
    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/linkedin.svg" alt="LinkedIn">
  </a>
  <a href="https://www.tiktok.com/@taller.a?_t=8qeg6RZT8AI&_r=1" class="social-icon">
    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/tiktok.svg" alt="TikTok">
  </a>
</div>
</div>


</body>
</html>