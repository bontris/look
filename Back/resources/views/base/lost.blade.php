@extends('base')

@section('page')
	<v-layout justify-center>
		<v-card width="460"
			    class="px-2"
				outlined>
			<v-progress-linear
				color="primary"
				slot="progress"
				v-show="wait"
				indeterminate
				absolute>
	        </v-progress-linear>
	        <v-card-text>
	        	<v-row>
        			<v-col>
        				<v-img class="my-4" src="/images/dark.png" height="96" contain/>
        			</v-col>
        		</v-row>
        		<v-row>
        			<v-col>
        				<v-list-item class="px-0">
					        <v-list-item-content>
					          	<v-list-item-title class="headline">
					            	Recuperar contraseña
					          	</v-list-item-title>
					          	<v-list-item-subtitle>Por favor ingresa tu correo electrónico y recibiras instrucciones para recuperar tu contraseña.</v-list-item-subtitle>
					        </v-list-item-content>
					    </v-list-item>
        			</v-col>
        		</v-row>
	        	<v-row v-if="note.text" dense>
        			<v-col>
        				<v-alert :type="(note.type == 'done') ? 'success' : 'error'" tile>@{{note.text}}</v-alert>
        			</v-col>
        		</v-row>
	        	<v-row>
	        		<v-col>
	        			<v-text-field
							label="Correo electrónico"
							type="text"
							v-model="form.mail"
							:error-messages="(note.list.mail ? [note.list.mail] : [])"
							:disabled="wait"
							prepend-inner-icon="mdi-email"
							autocomplete="off"
							hide-details="auto"
							filled>
			            </v-text-field>
	        		</v-col>
	        	</v-row>
	        	<v-row dense>
	        		<v-col>
	        			<v-btn
							@click="send(form)"
							color="primary"
							:disabled="wait"
							large
							block
							tile>
				        	Continuar
				      	</v-btn>
	        		</v-col>
	        	</v-row>
	        	<v-row>
	        		<v-col>
	        			<v-btn
							href="{{route('sign')}}"
							color="primary"
							:disabled="wait"
							outlined
							large
							block
							tile>
				        	¿Ya tienes un usuario y contraseña?
				      	</v-btn>
	        		</v-col>
	        	</v-row>
	        </v-card-text>
		</v-card>
	</v-layout>
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
			  		note: {
			  			type: null,
			  			text: null,
			  		    list: {}
			  		},
			  		form: {
			  			mail: null
			  		}
			    },
			    methods: {
			    	send: function (form) {
			    		var data = new FormData();

		    		    data.append('mail', (form.mail || ''));

			    		axios.post("{{route('lost')}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
                             .then(function (done) {
                            self.wait = false;

                            self.form = {mail: null};
                            
                            self.note = {list: {},
                                         type: 'done',
                            	         text: done.data.text};
                        })
                        .catch(function (fail) {
                            self.wait = false;

                            if (fail.response.data.text) {
                            	self.note = {type: 'fail',
                            	             text: fail.response.data.text,
                            		         list: fail.response.data.list || {}};
                            } else {
                            	self.note = {list: {},
                            	             type: 'fail',
                            	             text: 'Se presentó un error inesperado.'};
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