@extends('base')

@section('page')
	<v-layout justify-center>
		<v-card width="460"
			    class="px-2"
				outlined>
			<v-progress-linear color="primary"
	                           slot="progress"
	          	               v-show="wait"
	                           indeterminate
	                           absolute>
	        </v-progress-linear>
	        <v-card-text>
	        	<v-row>
        			<v-col>
        				<v-img class="my-4" src="/images/dark.png" height="64" contain/>
        			</v-col>
        		</v-row>
        		<v-row>
        			<v-col>
        				<v-list-item class="px-0">
					        <v-list-item-content>
					          	<v-list-item-title class="headline">
					            	Registro de usuarios
					          	</v-list-item-title>
					          	<v-list-item-subtitle>Por favor ingresa todos los datoos para crear tu cuenta.</v-list-item-subtitle>
					        </v-list-item-content>
					    </v-list-item>
        			</v-col>
        		</v-row>
	        	<v-row v-if="fail.text" dense>
        			<v-col>
        				<v-alert type="error" tile>@{{fail.text}}</v-alert>
        			</v-col>
        		</v-row>
	        	<v-row dense>
	        		<v-col>
	        			<v-text-field
							label="Nombre"
							type="text"
							v-model="form.name"
							:error-messages="(fail.list.name ? [fail.list.name] : [])"
							:disabled="wait"
							prepend-inner-icon="mdi-account"
							autocomplete="off"
							hide-details="auto"
							filled>
			            </v-text-field>
	        		</v-col>
	        	</v-row>
				<v-row dense>
	        		<v-col>
						<v-text-field
							label="Apellidos"
							type="text"
							v-model="form.last"
							:error-messages="(fail.list.laas ? [fail.list.last] : [])"
							:disabled="wait"
							prepend-inner-icon="mdi-account"
							autocomplete="off"
							hide-details="auto"
							filled>
			            </v-text-field>
					</v-col>
				</v-row>
				<v-row dense>
	        		<v-col>
						<v-text-field
							label="Teléfono"
							type="text"
							v-model="form.cell"
							:error-messages="(fail.list.cell ? [fail.list.cell] : [])"
							:disabled="wait"
							prepend-inner-icon="mdi-phone"
							autocomplete="off"
							hide-details="auto"
							filled>
			            </v-text-field>
					</v-col>
				</v-row>
				<v-row dense>
	        		<v-col>
						<v-text-field
							label="Correo electrónico"
							type="text"
							v-model="form.mail"
							:error-messages="(fail.list.mail ? [fail.list.mail] : [])"
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
						<v-text-field
							label="Contraseña"
							v-model="form.pass"
							:error-messages="(fail.list.pass ? [fail.list.pass] : [])"
							prepend-inner-icon="mdi-key"
							:append-icon="(form.show ? 'mdi-eye' : 'mdi-eye-off')"
							:type="(form.show ? 'text' : 'password')"
							:disabled="wait"
							@click:append="(form.show = form.show ? false : true)"
							autocomplete="off"
							hide-details="auto"
							filled>
			            </v-text-field>
					</v-col>
				</v-row>
	        	<v-row>
	        		<v-col>
	        			<v-btn
							@click="send(form)"
							color="primary"
							:disabled="wait"
							:loading="wait"
							large
							block
							tile>
				        	Continuar
				      	</v-btn>
	        		</v-col>
	        	</v-row>
	        	<v-row dense>
	        		<v-col>
	        			<v-btn
							href="{{route('sign')}}"
							color="primary"
							:disabled="wait"
							outlined
							large
							block
							tile>
				        	¿Ya tienes una cuenta?
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
			  		fail: {
			  			text: null,
			  		    list: {}
			  		},
			  		form: {
			  			user: null,
			  			pass: null,
			  			cell: null,
			  			mail: null,
			  			name: null,
			  			last: null,
			  			show: null
			  		}
			    },
			    methods: {
			    	send: function (form) {
			    		var data = new FormData();

		    		    data.append('user', (form.user || ''));

		    		    data.append('pass', (form.pass || ''));

		    		    data.append('cell', (form.cell || ''));

		    		    data.append('mail', (form.mail || ''));

		    		    data.append('last', (form.last || ''));

		    		    data.append('name', (form.name || ''));

			    		axios.post("{{route('join')}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
                             .then(function (data) {
                            window.location.href = data.data.next;
                        })
                        .catch(function (fail) {
                            self.wait = false;

                            if (fail.response.data.text) {
                            	self.fail = {text: fail.response.data.text,
                            		         list: fail.response.data.list || {}};
                            } else {
								self.fail = {text: 'Se presentó un error inesperado.',
                            		         list: {}};
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