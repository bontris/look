
<div style="margin: 0px; padding: 0px; border: 2px solid #222523; background: #FFFFFF; font-family: &quot;Public Sans&quot;, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Oxygen, Ubuntu, Cantarell, &quot;Fira Sans&quot;, &quot;Droid Sans&quot;, &quot;Helvetica Neue&quot;, sans-serif;">
    <div style="padding: 16px;">
        <div style="padding: 16px 32px; background: #FF6B00; border-radius: 8px;">
            <img src="{{asset('images/dark.png')}}" style="width: auto; height: 32px; margin: 0px; border: none; padding:  0px;">
        </div>
    </div>
    <div style="padding: 16px 64px; background: #222523;">
        <div style="color: #FFFFFF; font-size: 48px; font-weight: 500;">Reporte Consulta</div>
    </div>
    <div style="margin: 70px 64px;">
        <div style="margin: 48px 80px;">
            <table style="width: 100%; border: 1px solid #222523;" cellspacing="0">
                <thead>
                    <tr>
                        <th style="padding: 16px 32px; border: 1px solid #222523;">Empresa</th>
                        <th style="padding: 16px 32px; border: 1px solid #222523;">Usuario</th>
                        <th style="padding: 16px 32px; border: 1px solid #222523;">Número</th>
                        <th style="padding: 16px 32px; border: 1px solid #222523;">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <tr> 
                        <td style="padding: 16px 32px; border: 1px solid #222523;">{{$firm->name}}</td>
                        <td style="padding: 16px 32px; border: 1px solid #222523;">{{$skip->name}} {{$skip->last}}</td>
                        <td style="padding: 16px 32px; border: 1px solid #222523; text-align: center;">{{$card}}</td>
                        <td style="padding: 16px 32px; border: 1px solid #222523; text-align: center;">{{date('d', $time)}} de {{$list[date('n', $time)]}} de {{date('Y', $time)}}</td>
                    </tr>
                </tbody>
                </table>
        </div>
    </div>
    <div style="padding: 16px;">
        <div style="padding: 16px 32px; background: #495E5F; border-radius: 8px;">
            <div style="color: #FFFFFF; font-size: 32px; font-weight: 300; text-align: center;">Principales Listas Restrictivas y/o Vinculantes</div>
        </div>
        <div style="margin: 32px 80px; color: #222523; font-size: 32px; font-weight: 300; text-align: center;">No hay coincidencias</div>
    </div>
    <div style="padding: 16px;">
        <div style="padding: 16px 32px; background: #B4BCDE; border-radius: 8px;">
            <div style="color: #222523; font-size: 32px; font-weight: 300; text-align: center;">Listas asociadas a LA/FT/FPADM, Corrupción u otros delitos (Penal) y Extinción de Dominio</div>
        </div>
        <div style="margin: 32px 80px; color: #222523; font-size: 32px; font-weight: 300; text-align: center;">No hay coincidencias</div>
    </div>
    <div style="padding: 16px;">
        <div style="padding: 16px 32px; background: #EEE9DD; border-radius: 8px;">
            <div style="color: #222523; font-size: 32px; font-weight: 300; text-align: center;">Listas asociadas a LA/FT/FPADM, Corrupción u otros similares (Administrativo)</div>
        </div>
        <div style="margin: 32px 80px; color: #222523; font-size: 32px; font-weight: 300; text-align: center;">No hay coincidencias</div>
    </div>
    <div style="padding: 16px;">
        <div style="padding: 16px 32px; background: #DD7035; border-radius: 8px;">
            <div style="color: #FFFFFF; font-size: 32px; font-weight: 300; text-align: center;">Sanciones Administrativas y Listas de Afectación Financiera</div>
        </div>
        <div style="margin: 32px 80px; color: #222523; font-size: 32px; font-weight: 300; text-align: center;">No hay coincidencias</div>
    </div>
    <div style="padding: 32px 96px 192px 96px; background: #FF6B00;">
        <div style="color: #FFFFFF; font-size: 32px; font-weight: 300;">En caso de tener alguna inquietud al respecto no dude en comunicarse con nosotros.</div>
    </div>
    <div style="margin: -160px 16px 48px 16px; padding: 32px 64px; background: #222523; border-radius: 32px; height: 250px; display: grid; grid-template-columns: 1fr 1fr 2fr; row-gap: 24px;">
        <div style="display: grid; grid-template-columns: 1fr; row-gap: 24px;">
            <div style="display: grid; grid-template-columns: 64px 1fr; align-items: center;">
                <img src="{{asset('images/0005.png')}}" style="width: 48px; height: 48px;">
                <div style="color: #FFFFFF; font-size: 32px; font-weight: 300;">Medellín</div>
            </div>
            <div style="display: grid; grid-template-columns: 64px 1fr; align-items: center;">
                <img src="{{asset('images/0006.png')}}" style="width: 48px; height: 48px;">
                <div style="color: #FFFFFF; font-size: 32px; font-weight: 300;">(301) 411 7884</div>
            </div>
            <div style="display: grid; grid-template-columns: 64px 1fr; align-items: center;">
                <img src="{{asset('images/0007.png')}}" style="width: 48px; height: 48px;">
                <div style="color: #FFFFFF; font-size: 32px; font-weight: 300;">info@tallera.co</div>
            </div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr; row-gap: 24px;">
            <div style="display: grid; grid-template-columns: 64px 1fr; align-items: center;">
                <img src="{{asset('images/0005.png')}}" style="width: 48px; height: 48px;">
                <div style="color: #FFFFFF; font-size: 32px; font-weight: 300;">Bogotá</div>
            </div>
            <div style="display: grid; grid-template-columns: 64px 1fr; align-items: center;">
                <img src="{{asset('images/0006.png')}}" style="width: 48px; height: 48px;">
                <div style="color: #FFFFFF; font-size: 32px; font-weight: 300;">(301) 411 7884</div>
            </div>
            <div style="display: grid; grid-template-columns: 64px 1fr; align-items: center;">
                <img src="{{asset('images/0007.png')}}" style="width: 48px; height: 48px;">
                <div style="color: #FFFFFF; font-size: 32px; font-weight: 300;">info@tallera.co</div>
            </div>
        </div>
    </div>
</div>