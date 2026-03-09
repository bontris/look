@extends('site')

@section('page')
    <div id="page">
        <div class="wrap">
            <div class="path">
                <em>
                    <b>Artículos</b>
                </em>
                <ul>
                    <li>
                        <a href="{{route('home')}}">Inicio</a>
                    </li>
                    <li>
                        Artículos
                    </li>
                </ul>
            </div>
        	
            @if (count($list))
            <div class="grid">
            @foreach ($list as $post)
                <div class="item">
                    <div class="post">
                        <div class="snap">
                            <a href="/posts/{{$post['slug']}}">
                                <img src="/snaps/{{$post['snap']}}">
                            </a>
                        </div>
                        <div class="side">
                            <div class="head">
                                <i><a style="color: #{{$post['kind']['tone']}}">{{$post['kind']['name']}}</a></i>
                                <b><a href="/posts/{{$post['slug']}}">{{$post['caption']}}</a></b>
                            </div>
                            <div class="body">
                                <i>Por <a>{{$post['hand']['name']}} {{$post['hand']['last']}}</a>&nbsp;/&nbsp{{[1 => 'Enero',
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
                                                                                                        12 => 'Diciembre'][date('n', strtotime($post['creation']))]}} {{date('d', strtotime($post['creation']))}} de {{date('Y', strtotime($post['creation']))}}</i>
                                <p>{{$post['abstract']}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="none">
            </div>
            @endif
            
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