@extends('site')

@section('page')
	<div id="page">
		<div class="path">
			<div class="wrap">
				<em>
					<i>Nosotros</i>
					<b>Contáctanos</b>
				</em>
      			<ul>
      				<li>
      					<a href="{{route('home')}}">Inicio</a>
      				</li>
        			<li>Contáctanos</li>
      			</ul>
    		</div>
		</div>
		<div class="main">
			<div class="wrap">
				<div class="rich">
					<div class="grid">
						<div class="item">
							<h1>Escríbenos</h1>
            				<p>Para nosotros es muy importante escucharte, déjanos tu mensaje.</p>
						</div>
                    </div>
				</div>
                <div class="load" :done="done">
                    <svg viewBox="0 0 60 60">
                        <circle cx="30" cy="30" r="25" ring></circle>
                    </svg>
                    <div>
                        <b>Cargando</b>
                        <p>Por favor espere...</p>
                    </div>
                </div>
                <div class="form">
                    <v-app>
                        <v-snackbar
                            v-model="note.show"
                            :color="{done: 'success', warn: 'warning', fail: 'error'}[note.type]"
                            timeout="3000"
                            multi-line
                            tile
                            top>
                            @{{note.text}}
                            <template v-slot:action="{attrs}">
                                <v-btn
                                    v-bind="attrs"
                                    @click="(note.show = false)"
                                    icon>
                                    <v-icon>mdi-close</v-icon>
                                </v-btn>
                            </template>
                        </v-snackbar>
                        <v-main>
                            <div class="grid">
                                <div class="item" v-if="fail.text">
                                    <v-alert type="error" tile>@{{fail.text}}</v-alert>
                                </div>
                                <div class="item">
                                    <div class="grid">
                                        <div class="item half">
                                            <v-select
                                                item-value="item"
                                                item-text="text"
                                                :items="[{item: 1, text: 'Solicitud'},
                                                         {item: 2, text: 'Sugerencia'},
                                                         {item: 3, text: 'Felicitaciones'}]"
                                                v-model="form.type"
                                                :error-messages="(fail.list.type ? [fail.list.type] : [])"
                                                label="Tipo"
                                                :disabled="wait"
                                                hide-details="auto"
                                                filled>	
                                            </v-select>
                                        </div>
                                        <div class="item half">
                                            <v-text-field
                                                label="Nombre: *"
                                                type="text"
                                                v-model="form.name"
                                                :error-messages="(fail.list.name ? [fail.list.name] : [])"
                                                :disabled="wait"
                                                autocomplete="off"
                                                hide-details="auto"
                                                filled>
                                            </v-text-field>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="grid">
                                        <div class="item half">
                                            <v-text-field
                                                label="Correo: *"
                                                type="text"
                                                v-model="form.mail"
                                                :error-messages="(fail.list.mail ? [fail.list.mail] : [])"
                                                :disabled="wait"
                                                autocomplete="off"
                                                hide-details="auto"
                                                filled>
                                            </v-text-field>
                                        </div>
                                        <div class="item half">
                                            <v-text-field
                                                label="Teléfono"
                                                type="text"
                                                v-model="form.cell"
                                                :error-messages="(fail.list.cell ? [fail.list.cell] : [])"
                                                :disabled="wait"
                                                autocomplete="off"
                                                hide-details="auto"
                                                filled>
                                            </v-text-field>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <v-textarea
                                        auto-grow="true"
                                        label="Mensaje: *"
                                        maxlength="2048"
                                        v-model="form.text"
                                        :error-messages="(fail.list.text ? [fail.list.text] : [])"
                                        :disabled="wait"
                                        hide-details="auto"
                                        counter
                                        filled>
                                    </v-textarea>
                                </div>
                                <div class="item trim">
                                    <div class="grid">
                                        <div class="item half">
                                            <v-btn
                                                @click="send(form)"
                                                color="primary"
                                                :disabled="wait"
                                                :loading="wait"
                                                large
                                                block
                                                tile>
                                                Enviar
                                            </v-btn>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </v-main>
                    </v-app>
                </div>
			</div>
		</div>
	</div>
@stop

@section('code')
	<script type="text/javascript">
  		$(document).ready(function () {
  			var self = new Vue({
			  	vuetify: new Vuetify(),
				el: '#body',
			  	data: {
			  		wait: false,
			  		done: false,
			  		fail: {
			  			text: null,
			  		    list: {}
			  		},
                    note: {
                        show: false,
                        type: null,
                        text: null
                    },
			  		form: {
			  			type: null,
			  			name: null,
                        mail: null,
			  			cell: null,
                        text: null
			  		}
			    },
			    methods: {
			    	send: function (form) {
			    		var data = new FormData();

                        self.fail = {text: null,
			    			         list: {}};

                        data.append('type', (form.type || 0));

                        data.append('mail', (form.mail || ''));

                        data.append('cell', (form.cell || ''));

		    		    data.append('name', (form.name || ''));

		    		    data.append('text', (form.text || ''));

			    		axios.post("{{route('mail')}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
                             .then(function (data) {
                            self.wait = false;

                            self.form = {type: null,
                                         name: null,
                                         mail: null,
                                         cell: null,
                                         text: null};

                            self.note = {show: true,
                                	     type: 'done',
                                	     text: data.data.text};
                        })
                        .catch(function (fail) {
                            self.wait = false;

                            if (fail.response.data.text) {
                            	self.fail = {text: fail.response.data.text,
                            		         list: fail.response.data.list || {}};
                            } else {
                            	self.fail.text = 'Se presentó un error inesperado.';
                            }
                        });

                        self.wait = true;
			    	}
			    },
			    mounted: function () {
			    	setTimeout(function () {
             			self.done = true;
             		}, 500);
			    }
			})
  		});
  	</script>
@stop