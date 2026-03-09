@extends('base')

@section('page')
	<v-layout column>
		<v-row class="ma-0">
			<v-col class="ma-0 d-flex align-center justify-center">
				<v-card
					min-width="210"
					max-width="460"
					class="pa-2"
					outlined>
					<v-card-text>
						<v-row>
							<v-col>
								<v-img class="my-4" src="/images/icon.png" height="96" contain/>
							</v-col>
						</v-row>
						<v-row>
							<v-col>
								<v-list-item class="px-0">
									<v-list-item-content>
										<v-list-item-title class="headline">
											Inicio de sesión
										</v-list-item-title>
										<v-list-item-subtitle>Por favor ingresa tu usuario y contraseña para iniciar sesión.</v-list-item-subtitle>
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
									label="Usuario"
									type="text"
									v-model="form.name"
									:error-messages="(fail.list.name ? [fail.list.name] : [])"
									:disabled="wait"
									prepend-inner-icon="mdi-account"
									autocomplete="off"
									filled>
								</v-text-field>
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
									block
									large
									tile>
									Continuar
								</v-btn>
							</v-col>
						</v-row>

						@if (env('APP_LOST'))
						<v-row>
							<v-col>
								<v-btn href="{{route('lost')}}"
									color="primary"
									:disabled="wait"
									outlined
									large
									block
									tile>
									¿Olvidaste tu contraseña?
								</v-btn>
							</v-col>
						</v-row>
						@endif

						@if (env('APP_JOIN'))
						<v-row dense>
							<v-col>
								<v-btn href="{{route('join')}}"
									color="primary"
									:disabled="wait"
									outlined
									large
									block>
									¿No tienen una cuenta?
								</v-btn>
							</v-col>
						</v-row>
						@endif
					</v-card-text>
				</v-card>
			</v-col>
		</v-row>
	</v-layout>
@stop

@section('code')
	<script type="text/javascript">
  		Vue.ready(function () {
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
			  			show: null
			  		}
			    },
			    methods: {
			    	send: function (form) {
			    		var data = new FormData();

		    		    data.append('name', (form.name || ''));

		    		    data.append('pass', (form.pass || ''));

			    		axios.post("{{route('sign')}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
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
@endsection