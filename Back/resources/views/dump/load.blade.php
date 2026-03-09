
<div style="margin: 0px; padding: 0px; border: 2px solid #222523; background: #FFFFFF; font-family: &quot;Public Sans&quot;, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Oxygen, Ubuntu, Cantarell, &quot;Fira Sans&quot;, &quot;Droid Sans&quot;, &quot;Helvetica Neue&quot;, sans-serif;">
    <div style="padding: 16px;">
        <div style="padding: 16px 32px; background: #FF6B00; border-radius: 8px;">
            <img src="{{asset('images/dark.png')}}" style="width: auto; height: 32px; margin: 0px; border: none; padding:  0px;">
        </div>
    </div>
    <div style="padding: 16px 64px; background: #222523;">
        <div style="color: #FFFFFF; font-size: 48px; font-weight: 500;">Informe de horas y servicios</div>
    </div>
    <div style="margin: 70px 64px;">
        <div style="margin: 62px 0px; display: grid; grid-template-columns: 1fr 1fr; row-gap: 24px">
            <div style="color: #222523; font-size: 32px; font-weight: 300;">Medellín, {{date('d', $time)}} de {{$list[date('n', $time)]}} de {{date('Y', $time)}}</div>
            <div style="color: #222523; background: #B4BCDE; display: flex; font-size: 22px; font-weight: 300; align-items: center; justify-content: center;">{{date('d', $from)}} de {{$list[date('n', $from)]}} de {{date('Y', $from)}} - {{date('d', $stop)}} de {{$list[date('n', $stop)]}} de {{date('Y', $stop)}}</div>
        </div>
        <div style="display: grid; grid-template-columns: {!!((($firm->plan == 1) || ($firm->plan == 2)) ? '1fr 1fr 1fr 1fr 1fr' : '1fr 1fr 1fr 1fr')!!}; column-gap: 32px;">
            <div style="padding: 32px; background: #B4BCDE; display: flex; flex-flow: column wrap; border-radius: 32px; align-items: center;">
                <div style="color: #222523; font-size: 24px; font-weight: 600; text-align: center;">Horas consumidas</div>
                <img src="{{asset('images/0001.png')}}" style="width: auto; height: 96px; margin: 24px 0px;">
                <div style="color: #222523; font-size: 24px; font-weight: 300">{{gmdate('H:i', array_reduce($load, function ($time, $item){return $time + $item['time'];}, 0))}}</div>
            </div>
            @if ((($firm->plan == 1) || ($firm->plan == 2)))
            <div style="padding: 32px; background: #B4BCDE; display: flex; flex-flow: column wrap; border-radius: 32px; align-items: center;">
                <div style="color: #222523; font-size: 24px; font-weight: 600; text-align: center;">Horas restantes</div>
                <img src="{{asset('images/0001.png')}}" style="width: auto; height: 96px; margin: 24px 0px;">
                <div style="color: #222523; font-size: 24px; font-weight: 300">{{gmdate('H:i', max((((intval(($left ?? $firm->time)) * 3600) + ((round((floatval($left ?? $firm->time) * 100)) % 100) * 60))  - array_reduce($load, function ($time, $item){return $time + $item['time'];}, 0)), 0))}}</div>
            </div>
            @endif
            <div style="padding: 32px; background: #495E5F; display: flex; flex-flow: column wrap; border-radius: 32px; align-items: center;">
                <div style="color: #222523; font-size: 24px; font-weight: 600; text-align: center;">Servicios usados</div>
                <img src="{{asset('images/0002.png')}}" style="width: auto; height: 96px; margin: 24px 0px;">
                <div style="color: #222523; font-size: 24px; font-weight: 300">{{count($load)}}</div>
            </div>
            <div style="padding: 32px; background: #DD7035; display: flex; flex-flow: column wrap; border-radius: 32px; align-items: center;">
                <div style="color: #222523; font-size: 24px; font-weight: 600; text-align: center;">Tu plan</div>
                <img src="{{asset('images/0003.png')}}" style="width: auto; height: 96px; margin: 24px 0px;">
                <div style="color: #222523; font-size: 24px; font-weight: 300">{{([1 => 'Fee Mensual', 2 => 'Bolsa de Horas', 3 => 'Prestación de Servicios', 4 => 'Ilimitado'][$firm->plan] ?? 'Ninguno')}}</div>
            </div>
            <div style="padding: 32px; background: #EEE9DD; display: flex; flex-flow: column wrap; border-radius: 32px; align-items: center;">
                <div style="color: #222523; font-size: 24px; font-weight: 600; text-align: center;">Total a pagar</div>
                <img src="{{asset('images/0004.png')}}" style="width: auto; height: 96px; margin: 24px 0px;">
                <div style="color: #222523; font-size: 24px; font-weight: 300">COP {{number_format(array_reduce($load, function ($cost, $item){return $cost + $item['cost'];}, 0), 0, ',', '.')}}</div>
            </div>
        </div>
    </div>
    <div style="padding: 16px 64px; background: #222523;">
        <div style="color: #FFFFFF; font-size: 48px; font-weight: 500;">Informe detallado</div>
    </div>
    <div style="padding: 16px;">
        <div style="padding: 16px 32px; background: #495E5F; border-radius: 8px;">
            <div style="color: #FFFFFF; font-size: 32px; font-weight: 300;">de horas y servicios gestionados en el transcurso de la ejecución del contrato.</div>
        </div>
        <div style="margin: 48px 80px; color: #222523; font-size: 32px; font-weight: 300;">A continuación, se señala el tiempo utilizado por <b>TALLER A</b> para llevara cabo los servicios gestionados especialmente para las necesidades de <b>{{strtoupper($firm->name)}}</b> en el transcurso del mes de <b>{{strtoupper($list[date('n', $from)])}}</b> del año <b>{{date('Y', $from)}}</b>:</div>
        <div style="margin: 48px 80px;">
            <table style="width: 100%; border: 1px solid #222523;" cellspacing="0">
                <thead>
                    <tr>
                        <th style="padding: 16px 32px; border: 1px solid #222523;">Solicitud</th>
                        <th style="padding: 16px 32px; border: 1px solid #222523; width: 20%">Tiempo</th>
                        </tr>
                </thead>
                <tbody>
                    @foreach ($load as $item)
                    <tr>
                        <td style="padding: 16px 32px; border: 1px solid #222523;">{{$item['name']}}</td>
                        <td style="padding: 16px 32px; border: 1px solid #222523; text-align: center;">{{gmdate('H:i', $item['time'])}}</td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
        </div>
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