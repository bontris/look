@extends('site')

@section('page')
    @if (count($cards))
	<div id="view">
      	<div class="wrap">
        	<div class="grid">
          		<sup @click="move(card - 1, true)">
            		<svg>
              			<path d="M15.683 4.031l-7.112 7.112 7.112 7.112c0.335 0.335 0.335 0.871 0 1.205l-2.223 2.223c-0.335 0.335-0.871 0.335-1.205 0l-9.938-9.938c-0.335-0.335-0.335-0.871 0-1.205l9.938-9.938c0.335-0.335 0.871-0.335 1.205 0l2.223 2.223c0.335 0.335 0.335 0.871 0 1.205z"/>
            		</svg>
          		</sup>
                @foreach ($cards as $card)
        		<div class="item">
            		<input type="radio" name="home:cards" :checked="card == {{$loop->index}}" bind:checked="card = {{$loop->index}}" {{$loop->first ? 'checked' : ''}}>
            		<label hook:click="call move with {item}, true">{{$card['code']}}</label>
            		<span>
			            <img src="/snaps/{{$card['snap']}}">
                        <div>
			                <b>{{$card['name']}}</b>
			                <p>{{$card['text']}}</p>
                        </div>
                    </span>
          		</div>
                @endforeach
          		<sub @click="move(card + 1, true)">
            		<svg>
              			<path d="M14.826 11.746l-9.938 9.938c-0.335 0.335-0.871 0.335-1.205 0l-2.223-2.223c-0.335-0.335-0.335-0.871 0-1.205l7.112-7.112-7.112-7.112c-0.335-0.335-0.335-0.871 0-1.205l2.223-2.223c0.335-0.335 0.871-0.335 1.205 0l9.938 9.938c0.335 0.335 0.335 0.871 0 1.205z"/>
            		</svg>
          		</sub>
            </div>
        </div>
    </div>
    @endif

    @if (count($posts))
    <div id="news">
        <div class="wrap">
        	<div class="grid">
                @foreach ($posts as $post)
                <div class="item">
                    <div class="snap">
                        <a href="/posts/{{$post['slug']}}">
                            <img src="/snaps/{{$post['snap']}}">
                        </a>
                    </div>
                    <div class="side">
                        <div class="head">
                            <i><a style="color: #{{$post['kind']['tone']}}" href="">{{$post['kind']['name']}}</a></i>
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
                @endforeach
            </div>
        </div>
    </div>
    @endif
@stop

@section('code')
<script type="text/javascript">
  		$(document).ready(function () {
  			var page = new Vue({
			  	vuetify: new Vuetify(),
				el: '#body',
			  	data: {
                    size: {!!count($cards)!!},
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
                    this.time = setInterval(function () {
                        page.move(page.card + 1);
                    }, 6000);
			    }
			})
  		});
  	</script>
@stop