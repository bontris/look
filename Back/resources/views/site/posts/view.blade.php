@extends('site')

@section('page')
    <div id="page">
        <div class="wrap">
            <div class="path">
                <em>
                    <b>{{$post['kind']['name']}}</b>
                </em>
                <ul>
                    <li>
                        <a href="{{route('home')}}">Inicio</a>
                    </li>
                    <li>
                        <a href="{{route('site.posts')}}">Artículos</a>
                    </li>
                    <li>
                        {{$post['caption']}}
                    </li>
                </ul>
            </div>
        	<div class="grid">
                <div class="item wide">
                    <div class="high">
                        <div class="snap">
                            <img src="/snaps/{{$post['snap']}}">
                        </div>
                        <div class="text">
                            <b>{{$post['caption']}}</b>
                            <p>{{$post['abstract']}}</p>
                        </div>
                    </div>
                    <div class="rich">
                        {!!$post['content']!!}
                    </div>
                </div>
                <div class="item tiny">
                    <div class="grid">
                        <div class="item">
                            <div class="card">
                                <img src="/snaps/{{$post['hand']['face']}}">
                                <b>{{$post['hand']['name']}} {{$post['hand']['last']}}</b>
                                <p>{{$post['hand']['note']}}</p>
                            </div>
                        </div>
                        @if (count($last))
                        <div class="item">
                            <div class="line">Otros artículos</div>
                        </div>
                        @foreach ($last as $item)
                        <div class="item">
                            <div class="chip">
                                <img src="/snaps/{{$item['snap']}}">
                                <a href="/posts/{{$item['slug']}}">{{$item['caption']}}</a>
                                <i>{{[1 => 'Enero',
                                    2 => 'Febrero',
                                    3 => 'Marzo',
                                    4 => 'Abril',
                                    5 => 'Mayo',
                                    6 => 'Junio',
                                    7 => 'Julio',
                                    8 => 'Agosto',
                                    9 => 'Septiembre',
                                    10 => 'Octubre',
                                    11 => 'Noviembre',
                                    12 => 'Diciembre'][date('n', strtotime($item['creation']))]}} {{date('d', strtotime($item['creation']))}} de {{date('Y', strtotime($item['creation']))}}</i>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('code')
<script type="text/javascript">
  		$(document).ready(function () { 
  			var page = new Vue({
			  	vuetify: new Vuetify(),
				el: '#body',
			  	data: {
			  		card: 0,
                    time: 0
			    },
			    methods: {
                    move: function (item, stop) {
                        if ((item == -1)) {
                            this.card = this.size - 1;
                        } else {
                            if ((item == this.size)) {
                                this.card = 0;
                            } else {
                                this.card = item;
                            }
                        }

                        if (stop) {
                            clearInterval(this.time);
                        }
                    }
			    },
			    mounted: function () {

			    }
			})
  		});
  	</script>
@stop