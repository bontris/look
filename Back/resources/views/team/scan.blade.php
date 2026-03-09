@extends('team')

@section('page')
	<v-layout column>
		<v-row class="ma-0">
			<v-col class="pa-0">
				<v-layout class="white px-2">
					<v-list>
						<v-list-item class="px-1">
							<v-list-item-avatar
                                color="teal"
                                size="36"
                                tile>
								<v-icon color="white">
									mdi-qrcode-scan
								</v-icon>
							</v-list-item-avatar>
							<v-list-item-content class="pa-0">
								<v-list-item-title>
									Escaneo
								</v-list-item-title>
								<v-list-item-subtitle>
									<v-breadcrumbs
										class="pa-0"
										:items="[{text: 'Inicio', href: '{{route('dash')}}'}, {text: 'Escaneo'}]">
									</v-breadcrumbs>
								</v-list-item-subtitle>
							</v-list-item-content>
						</v-list-item>
					</v-list>
				</v-layout>
				<v-divider>
				</v-divider>
			</v-col>
		</v-row>
		<v-row class="ma-0">
            <v-col class="ma-0">
				<v-card outlined>
                    <v-toolbar flat>
                        <v-toolbar-title>
                            <v-sheet>
                                <v-list>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title>
                                                @{{{1: 'CÁMARA', 2: 'DETALLES'}[view]}}
                                            </v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list>
                            </v-sheet>
                        </v-toolbar-title>
                        <v-layout
                            class="mr-2"
                            justify-center
                            align-center>
                            <v-spacer>	
                            </v-spacer>
                            <v-btn
                                color="primary"
                                @click.stop="(view = 1)"
                                :disabled="wait"
                                icon>
                                <v-icon>mdi-camera</v-icon>
                            </v-btn>
                        </v-layout>
                    </v-toolbar>
                    <v-divider>
                    </v-divider>
                    <v-card-text>
                        <v-row
                            v-if="same(view, 1)"
                            dense>
                            <v-col>
                                <qrcode-stream
                                    @decode="scan"
                                    :track="wrap" />
                            </v-col>
                        </v-row>
                        <v-row
                            v-else
                            dense>
                            <v-col
                                class="d-flex flex-column text-center align-center justify-center"
                                cols="12">
                                <v-avatar
                                    :color="`${(data ? 'green' : 'red')} lighten-1`"
                                    class="mt-6" 
                                    size="64">
                                    <v-icon
                                        size="42"
                                        dark>
                                        @{{data ? 'mdi-check' : 'mdi-close'}}
                                    </v-icon>
                                </v-avatar>
                                <v-list>
                                    <v-list-item v-if="data">
                                        <v-list-item-content>
                                            <v-list-item-title>
                                                Escaneo exitoso
                                            </v-list-item-title>
                                            <v-list-item-subtitle>
                                                Código <b>@{{data?.code}}</b> de un <b>@{{data?.rate}}</b>% de descuento a nombre de <b>@{{data?.name}} @{{data?.last}}</b>.
                                            </v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item v-else>
                                        <v-list-item-content>
                                            <v-list-item-title>
                                                Escaneo fallido
                                            </v-list-item-title>
                                            <v-list-item-subtitle>
                                                @{{fail}}
                                            </v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list>
                            </v-col>
                            <v-col cols="12">
                                <v-btn
                                    @click="lock(data.hash)"
                                    color="primary"
                                    :disabled="(wait || data?.lock)"
                                    :loading="wait"
                                    v-if="same(none(data), false)"
                                    block
                                    large
                                    flat>
                                    Redimir código
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
		</v-row>
	</v-layout>
@endsection

@section('code')
    <script type="text/javascript">
  		Vue.ready(function () {
  			var self = new Vue({
			  	el: '#page',
			  	vuetify: new Vuetify(),
			  	data: {
			  		wait: false,
			  		data: null,
                    fail: null,
                    view: 1,
			  		load: 0,
			  		cost: 0
			    },
			    watch: {
					'data.stay.mode': function (mode) {
						this.show('stay', this.data.stay);
					},
					'data.stay.view': function (view) {
						this.show('stay', this.data.stay);
					},
					'data.book.mode': function (mode) {
						this.show('book', this.data.book);
					},
					'data.book.view': function (view) {
						this.show('book', this.data.book);
					},
					'data.pack.mode': function (mode) {
						this.show('pack', this.data.pack);
					},
					'data.pack.view': function (view) {
						this.show('pack', this.data.pack);
					}
			    },
			    methods: {
			    	pull: function (from, stop, busy, done) {
			    		var data = new FormData();

		    		    data.append('from', (from || ''));

						data.append('stop', (stop || ''));

			    		axios.post("{{route('dash', ['task' => 'load'])}}", data, {'X-CSRF-TOKEN': '{{csrf_token()}}'})
				             .then(function (data) {
							self.data.size = data.data.size ?? 0;

							self.data.bond = data.data.bond ?? {};

				            setTimeout(function () {
				            	self.busy = false;
				            }, 300);

							if (done) {
				            	done(false);
				            }
				        }).catch(function (fail) {console.log(fail)
				            setTimeout(function () {
				            	self.busy = false;

				            	self.note = {show: true,
                            	             type: 'fail',
                            	             text: 'Se presentó un error inesperado.'};
				            }, 300);

							if (done) {
				            	done(true);
				            }
				        });

				        this.busy = busy;
			    	},
					wrap: function (detectedCodes, ctx) {
                        for (const detectedCode of detectedCodes) {
                            const [firstPoint, ...otherPoints] = detectedCode.cornerPoints

                            ctx.strokeStyle = "red";

                            ctx.beginPath();

                            ctx.moveTo(firstPoint.x, firstPoint.y);

                            for (const { x, y } of otherPoints) {
                                ctx.lineTo(x, y);
                            }

                            ctx.lineTo(firstPoint.x, firstPoint.y);

                            ctx.closePath();

                            ctx.stroke();
                        }
                    },
                    scan: function (code) {
                        var data = new FormData();

                        data.append('code', code);

                        axios.post("{{route('team.gifts', ['task' => 'scan'])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
                            .then(function (data) {
                            self.data = data.data;

                            self.busy = false;

                            self.view = 2;
                        })
                        .catch(function (fail) {
                            self.fail = self.same(fail?.response?.status, 404, 'El código escaneado no es válido.', 'No se pudo escanear el código.');

                            self.busy = false;

                            self.data = null;

                            self.view = 2;
                        });

                        self.busy = true;
                    },
                    lock: function (code) {
                        var data = new FormData();

                        data.append('code', code);

                        axios.post("{{route('team.gifts', ['task' => 'lock'])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
                             .then(function (data) {
                            self.data.lock = true;

                            self.wait = false;

                            self.note = {
                                show: true,
                                type: 'done',
                                text: data?.data?.text ?? 'El código fue redimido correctamente.'
                            };
                        })
                        .catch(function (fail) {
                            if (fail?.response?.data?.text) {
                                self.note = {
                                    show: true,
                                    type: 'fail',
                                    text: fail.response.data.text
                                };
                            } else {
                                self.note = {
                                    show: true,
                                    type: 'fail',
                                    text: 'Se presentó un error inesperado.'
                                };
                            }

                            self.wait = false;
                        });

                        self.wait = true;
                    }
			    },
			    mounted: function () {
					setTimeout(() => {
							self.done = true;
					
					}, 300);
				}
			})
		  });

  	</script>
@stop