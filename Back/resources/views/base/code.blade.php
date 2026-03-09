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
        				<v-img class="my-4" src="/images/dark.png" height="64" contain/>
        			</v-col>
        		</v-row>
        		<v-row>
        			<v-col>
        				<v-list-item class="px-0">
					        <v-list-item-content>
					          	<v-list-item-title class="headline">
					            	Código de confirmación
					          	</v-list-item-title>
					          	<v-list-item-subtitle>Ingresa el código de confirmación que enviamos a tu correo electrónico.</v-list-item-subtitle>
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
							label="Código de verificación"
							type="password"
							v-model="form.pass"
							:error-messages="(fail.list.pass ? [fail.list.pass] : [])"
							:disabled="wait"
							prepend-inner-icon="mdi-lock"
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
							:loading="wait"
							large
							block>
				        	Continuar
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
			  			pass: null
			  		}
			    },
			    methods: {
			    	send: function (form) {
			    		var data = new FormData();

		    		    data.append('pass', (form.pass || ''));

			    		axios.post("{{route('code', ['type' => $type, 'item' => $item])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
                             .then(function (data) {
                            window.location.href = data.data.next;
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