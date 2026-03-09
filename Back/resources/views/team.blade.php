<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
    <head>
        <meta charset="utf-8">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{csrf_token()}}">

        <title>{{config('app.name', 'Test')}}</title>

        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">

        <link href="/styles/material.min.css?v=1.2" rel="stylesheet">

        <link href="/styles/cropper.css?v=1.1" rel="stylesheet">

        <link href="/styles/vuetify.css?v=2.6.3" rel="stylesheet">

        <link href="/styles/main.css?v=1.3" rel="stylesheet">

        <link href="/styles/team.css?v=1.8" rel="stylesheet">

        <link rel="icon" type="image/png" href="/icon.png">
    </head>
    <body>
    	<div id="page">
    		<div class="load" :done="done">
	            <svg viewBox="0 0 60 60">
					<circle cx="30" cy="30" r="25" ring></circle>
				</svg>
				<div>
					<b>Cargando</b>
					<p>Por favor espere...</p>
				</div>
			</div>
			<div class="main">
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
		    		<v-app-bar
		    		    color="white"
		    		    clipped-left
						outlined
		    		    short
		    		    flat
		            	app>
						<v-sheet
							class="flex-1-1-auto d-flex"
							width="299"
							height="55">
							<v-app-bar-nav-icon
								v-if="tiny"
								@click="(side.show = side.show ? false : true)">
							</v-app-bar-nav-icon>
							<v-list>
								<v-list-item >
									@if (isset(Auth::user()->firm->icon))
									<v-list-item-avatar tile>
										<v-img
											position="left center"
											height="40"
											src="/snaps/{{Auth::user()->firm->icon}}/thumb"
											contain>
									</v-list-item-avatar>
									@else
									<v-list-item-avatar
									    color="#000000"
										tile>
										<v-icon color="#FFFFFF">mdi-domain</v-icon>
									</v-list-item-avatar>
								    @endif
									<v-list-item-content
										v-if="none(tiny)"
										class="py-2">
										<v-list-item-title>
											{{isset(Auth::user()->firm->name) ? Auth::user()->firm->name : env('APP_NAME')}}
										</v-list-item-title>
										<v-list-item-subtitle>
											Panel de administración
										</v-list-item-subtitle>
									</v-list-item-content>
								</v-list-item>
							</v-list>
							<v-divider
								v-if="none(tiny)"
								vertical>
							</v-divider>
                        </v-sheet>
				    	<v-app-bar-nav-icon
							v-if="none(tiny)"
				    	    class="ml-4"
				    		@click="(side.tiny = side.tiny ? false : true)">
				    	</v-app-bar-nav-icon>
				    	<v-autocomplete
				    	    prepend-inner-icon="mdi-magnify"
				    	    no-data-text="No hay coincidencias"
							clear-icon="mdi-close-circle"
				    	    class="ml-4 mt-4 mr-4"
				            label="Buscar"
				            :background-color="spot.drop ? '#FFFFFF' : '#F3F3F3'"
				            :items="spot.list.length ? spot.list : spot.last"
				            @focus="spot.drop = true"
				            @blur="spot.drop = false"
				            single-line
				            clearable
				            outlined
				            dense
							v-if="none(tiny)">
				            <template v-slot:item="data">
				                <v-list-item-icon>
				                	<v-icon>@{{data.item.icon}}</v-icon>
			                    </v-list-item-icon>
			                    <v-list-item-content>
			                      	<v-list-item-title>@{{data.item.text}}</v-list-item-title>
			                      	<v-list-item-subtitle v-if="data.item.hint">@{{data.item.hint}}</v-list-item-subtitle>
			                    </v-list-item-content>
			                </template>
			            </v-autocomplete>
				    	<div class="flex-1-1-auto d-flex align-center justify-end" :class="{'mx-2': tiny}">
							<v-btn
								@click="(help.show = help.show ? false : true)"
								:class="{'mr-4': none(tiny)}"
								icon>
					          	<v-icon>mdi-bell</v-icon>
					        </v-btn>
					        <v-divider
								v-if="none(tiny)"
								vertical>
					        </v-divider>
					    	<v-menu
					    	    transition="slide-y-transition"
								nudge-right="1"
					    		offset-y
								min-width="360"
								max-width="360"
								:left="none(tiny)"
					    		flat>
						        <template v-slot:activator="{on, bind}">
									<v-btn
										v-if="tiny"
										v-bind="bind"
			            				v-on="on"
										icon>
										<v-avatar
											color="{{Auth::user()->face ? 'white' : sprintf('#%s', Auth::user()->tone)}}"
											size="40">
											<v-badge
												offset-x="-1"
												offset-y="-1"
												color="{{['grey', 'green', 'orange', 'red'][intval(Auth::user()->live)]}}"
												bordered
												bottom
												dot>
												@if (Auth::user()->face)
													<v-img src="/snaps/{{Auth::user()->face}}/thumb">
												@else
													<span class="font-weight-bold white--text caption">{{strtoupper(Auth::user()->name[0])}}{{strtoupper(Auth::user()->last[0])}}</span>
												@endif
											</v-badge>
										</v-avatar>
									</v-btn>
									<v-list
										v-else
										width="250"
										height="55">
			                            <v-list-item
			                            	v-bind="bind"
			            					v-on="on">
											<v-list-item-avatar color="{{Auth::user()->face ? 'white' : sprintf('#%s', Auth::user()->tone)}}">
												<v-badge
													offset-x="-1"
													offset-y="-1"
													color="{{['grey', 'green', 'orange', 'red'][intval(Auth::user()->live)]}}"
													bordered
													bottom
													dot>
													@if (Auth::user()->face)
										        		<v-img src="/snaps/{{Auth::user()->face}}/thumb">
											    	@else
											    		<span class="font-weight-bold white--text caption">{{strtoupper(Auth::user()->name[0])}}{{strtoupper(Auth::user()->last[0])}}</span>
											    	@endif
												</v-badge>
											</v-list-item-avatar>
						                    <v-list-item-content class="py-0">
						                      	<v-list-item-title>
													{{Auth::user()->name}}
												</v-list-item-title>
						                      	<v-list-item-subtitle>
													{{[1 => 'Administrador', 2 => 'Colaborador', '3' => 'Comerciante', 4 => 'Visitante'][Auth::user()->type]}}
												</v-list-item-subtitle>
						                    </v-list-item-content>
						                    <v-list-item-icon class="pa-0">
						                    	<v-icon>mdi-menu-down</v-icon>
						                    </v-list-item-icon>
										</v-list-item>
									</v-list>
						        </template>
						        <v-list>
						          	<v-list-item
						          		href="{{route('dash')}}"
						          		link>
						            	<v-list-item-avatar
											color="{{(Auth::user()->face ? 'grey lighten-2' : sprintf('#%s', Auth::user()->tone))}}"
											size="64">
						            	@if (Auth::user()->face)
								        	<img src="/snaps/{{Auth::user()->face}}/thumb">
									    @else
									    	<div class="font-weight-bold white--text title">{{strtoupper(Auth::user()->name[0])}}{{strtoupper(Auth::user()->last[0])}}</div>
									    @endif
								    	</v-list-item-avatar>
								    	<v-list-item-content>
							        		<v-list-item-title>
								        		{{Auth::user()->name}} {{Auth::user()->last}}
								        	</v-list-item-title>
								        	<v-list-item-subtitle>
								            	{{Auth::user()->mail}}
								        	</v-list-item-subtitle>
								    	</v-list-item-content>
						          	</v-list-item>
									<v-divider>
									</v-divider>
									<v-list-item
									    @click="(help.show = help.show ? false : true)"
										link>
						            	<v-list-item-icon>
							              	<v-icon>mdi-help-circle</v-icon>
							            </v-list-item-icon>
							            <v-list-item-content>
							              	<v-list-item-title>¿Necesitas ayuda?</v-list-item-title>
											<v-list-item-subtitle>Recursos de ayuda disponibles.</v-list-item-subtitle>
							            </v-list-item-content>
						            </v-list-item>
						            <v-list-item
										link>
						            	<v-list-item-icon>
							              	<v-icon>mdi-message-alert</v-icon>
							            </v-list-item-icon>
							            <v-list-item-content>
							              	<v-list-item-title>¿Algo salió mal?</v-list-item-title>
							              	<v-list-item-subtitle>Haznos saber sobre una función rota.</v-list-item-subtitle>
							            </v-list-item-content>
						            </v-list-item>
						            <v-divider>
									</v-divider>
						            <v-list-item
										href="{{route('exit')}}"
										link>
						            	<v-list-item-icon>
							              	<v-icon color="red">mdi-power</v-icon>
							            </v-list-item-icon>
							            <v-list-item-content>
							              	<v-list-item-title>Cerrar sesión</v-list-item-title>
							            </v-list-item-content>
						            </v-list-item>
						        </v-list>
						    </v-menu>
				    	</div>
			        </v-app-bar>

		        	<v-main>
						<v-dialog
							transition="fade-transition"
							v-model="spin" 
							persistent
							fullscreen
							tile>
						  	<v-container
							  	style="background: rgba(0, 0, 0, 0.1)"
								fill-height
								fluid>
						    	<v-layout
									justify-center
									align-center>
						      		<v-progress-circular
						        		color="custom"
						        		size="64"
										indeterminate>
						      		</v-progress-circular>
						    	</v-layout>
						  	</v-container>
						</v-dialog>

				        <v-layout>
				        	<v-navigation-drawer
							    :mini-variant.sync="side.tiny"
						        v-model="side.show"
						        width="300"
						        clipped
						        app>
								<v-text-field
									prepend-inner-icon="mdi-magnify"
									background-color="grey lighten-4"
									clear-icon="mdi-close-circle"
									autocomplete="off"
									height="52"
									label="Buscar"
									hide-details
									single-line
									clearable
									attach
									solo
									flat>
								</v-text-field>
								<v-divider>
                                </v-divider>
						        <v-list
									class="py-0"
									subheader>
									<v-list-item
										href="{{route('dash')}}"
										link>
							          	<v-list-item-icon>
							            	<v-icon>mdi-view-dashboard</v-icon>
							          	</v-list-item-icon>
							          	<v-list-item-title>Reporte</v-list-item-title>
							        </v-list-item>

									@if ((Auth::user()->type == User::MATE))
									<v-list-item
										href="{{route('scan')}}"
										link>
							          	<v-list-item-icon>
							            	<v-icon>mdi-qrcode-scan</v-icon>
							          	</v-list-item-icon>
							          	<v-list-item-title>Escaneo</v-list-item-title>
							        </v-list-item>
							        @endif

									@if ((Auth::user()->type == User::ROOT))
									<v-list-item
										class="pl-4"
										href="{{route('core.gains')}}"
										link>
										<v-list-item-icon>
											<v-icon>mdi-timer</v-icon>
										</v-list-item-icon>
										<v-list-item-title>Avances</v-list-item-title>
									</v-list-item>
									<v-subheader v-if="same(side.tiny, false)">
										@lang('PLANEACIÓN')
									</v-subheader>
									<v-list-item
										class="pl-4"
										href="{{route('core.works')}}"
										link>
										<v-list-item-icon>
											<v-icon>mdi-lightbulb-on</v-icon>
										</v-list-item-icon>
										<v-list-item-title>Proyectos</v-list-item-title>
									</v-list-item>
									<v-list-item
										class="pl-4"
										href="{{route('core.units')}}"
										link>
										<v-list-item-icon>
											<v-icon>mdi-boom-gate</v-icon>
										</v-list-item-icon>
										<v-list-item-title>Unidades</v-list-item-title>
									</v-list-item>
									<v-list-item
										class="pl-4"
										href="{{route('core.times')}}"
										link>
										<v-list-item-icon>
											<v-icon>mdi-shovel</v-icon>
										</v-list-item-icon>
										<v-list-item-title>Obras</v-list-item-title>
									</v-list-item>
									<v-list-item
										class="pl-4"
										href="{{route('core.steps')}}"
										link>
										<v-list-item-icon>
											<v-icon>mdi-flag</v-icon>
										</v-list-item-icon>
										<v-list-item-title>Fases</v-list-item-title>
									</v-list-item>
									<v-subheader v-if="same(side.tiny, false)">
										@lang('PERSONAS')
									</v-subheader>
									<v-list-item
										class="pl-4"
										href="{{route('core.leads')}}"
										link>
										<v-list-item-icon>
											<v-icon>mdi-account-multiple</v-icon>
										</v-list-item-icon>
										<v-list-item-title>Contactos</v-list-item-title>
									</v-list-item>
									<v-list-item
										class="pl-4"
										href="{{route('core.hands')}}"
										link>
										<v-list-item-icon>
											<v-icon>mdi-account-supervisor</v-icon>
										</v-list-item-icon>
										<v-list-item-title>Empleados</v-list-item-title>
									</v-list-item>
									<v-divider>
									</v-divider>
							        <v-list-group
							          	prepend-icon="mdi-domain"
							          	color="custom">
							          	<v-list-item-content slot="activator">
							                <v-list-item-title>@lang('Organización')</v-list-item-title>
							            </v-list-item-content>
								        <v-list-item
											:class="same(side.tiny, true, 'pl-4', 'pl-8')"
											href="{{route('core.shops')}}"
											link>
											<v-list-item-icon>
												<v-icon>mdi-domain</v-icon>
											</v-list-item-icon>
											<v-list-item-title>Empresas</v-list-item-title>
										</v-list-item>
										<v-list-item
											:class="same(side.tiny, true, 'pl-4', 'pl-8')"
											href="{{route('core.shops')}}"
											link>
											<v-list-item-icon>
												<v-icon>mdi-cog</v-icon>
											</v-list-item-icon>
											<v-list-item-title>@lang('Configuración')</v-list-item-title>
										</v-list-item>
							        </v-list-group>
									<v-divider>
									</v-divider>
							        <v-list-group
							          	prepend-icon="mdi-shield-account"
							          	color="custom">
							          	<v-list-item-content slot="activator">
							                <v-list-item-title>@lang('Autenticación')</v-list-item-title>
							            </v-list-item-content>
							            <v-list-item
							                :class="same(side.tiny, true, 'pl-4', 'pl-8')"
											href="{{route('core.roles')}}"
											link>
								          	<v-list-item-icon>
								            	<v-icon>mdi-shield-key</v-icon>
								          	</v-list-item-icon>
								          	<v-list-item-title>@lang('Perfiles')</v-list-item-title>
								        </v-list-item>
							            <v-list-item
							                :class="same(side.tiny, true, 'pl-4', 'pl-8')"
											href="{{route('core.users')}}"
											link>
								          	<v-list-item-icon>
								            	<v-icon>mdi-account</v-icon>
								          	</v-list-item-icon>
								          	<v-list-item-title>@lang('Usuarios')</v-list-item-title>
								        </v-list-item>
							        </v-list-group>
							        @endif
						        </v-list>
								<template v-if="same(side.tiny, false, true)" v-slot:append>
									<v-divider>
									</v-divider>
									<v-row>
										<v-col class="body-2 mx-4">
											@&nbsp;{{date('Y')}}&nbsp;<a class="text-decoration-none" href="https://bontris.com" target="_blank">Bontris</a>
										</v-col>
									</v-row>
								</template>
						    </v-navigation-drawer>
						    <v-navigation-drawer
						      	v-model="help.show"
						      	width="350"
						      	temporary
						      	right
						      	app>
						    </v-navigation-drawer>
					        @yield('page')
				        </v-layout>
		        	</v-main>
		    	</v-app>
			</div>
    	</div>
        <script src="/scripts/vue.js"></script>
        <script src="/scripts/axios.js"></script>
        <script src="/scripts/chart.js?v=1.7"></script>
        <script src="/scripts/moment.js"></script>
		<script src="/scripts/qrcode.js"></script>
        <script src="/scripts/cropper.js"></script>
        <script src="/scripts/vuetify.js?v=2.3.7"></script>
		<script src="/scripts/ckeditor.js?v=1.6"></script>
		<script src="/scripts/plugins/geo.js?v=1.1"></script>
		<script src="/scripts/plugins/labels.js?v=1.1"></script>
		<script src="/scripts/QrcodeReader.min.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAS46B7UGTsO-kUnSZ1sMuYUz3XODta7pI&libraries=drawing"></script>
        <script type="text/javascript">
            (function (time) {
				Vue.component('v-crop', {
              	  	data: () => ({
              	    	object: null
              	  	}),
				  	props: {
				  		boot: Function,
				  		ratio: {
				  			type: Number,
				  			default: 1.6
				  		},
				  		class: {
				  			type: String,
				  			default: 'crop'
				  		}
				  	},
				  	watch: {
				  		file: function (file) {
				  			this.object.replace(file, false);
				  		},
						ratio: function (data) {
				  			this.object.setAspectRatio(data);
				  		}
				 	},
				  	render: function (h) {
				  		return h('div', {class: this.class}, [h('img', {ref: 'snap'})]);
				 	},
				  	mounted: function () {
				  		if (this.boot) {
				  			this.boot((this.object = new Cropper(this.$refs.snap, {
				    			aspectRatio: this.ratio,
								autoCropArea: 1,
								zoomable: true,
								restore: false
				    		})));
				  		}
				 	},
				  	beforeDestroy: function () {
				  		this.object.destroy();
				  	}
				});

				Vue.component('v-code', {
					data: () => ({
						node: null
					}),
					props: {
						class: {
							type: String,
							default: 'code'
						},
						width: {
							type: Number,
							default: 128
						},
						height: {
							type: Number,
							default: 128
						}
					},
					render: function (h) {
						return h('div', {ref: 'slot', class: this.class, attrs: {width: this.width, height: this.height}});
					},
					mounted: function () {
						let self = this;

						self.node =  new QRCode(this.$refs.slot, {
							text: "http://jindo.dev.naver.com/collie",
							width: self.width,
							height: self.height,
							colorDark : "#000000",
							colorLight : "#ffffff",
							correctLevel : QRCode.CorrectLevel.H
						});
					},
					methods: {
						onScanSuccess (decodedText, decodedResult) {
							this.$emit('result', decodedText, decodedResult);
						}
					}
				});

				Vue.component('v-rich', {
              	  	data: () => ({
              	    	editor: null
              	  	}),
				  	props: {
				  		boot: Function,
				  		ratio: {
				  			type: Number,
				  			default: 1.6
				  		},
				  		class: {
				  			type: String,
				  			default: 'rich'
				  		},
				  		width: {
				  			type: Number,
				  			default: 400
				  		},
				  		height: {
				  			type: Number,
				  			default: 400
				  		},
						content: {
				  			type: String,
				  			default: ''
				  		},
				  	},
				  	watch: {
				  		content: function (file) {
				  			//this.object.replace(file, false);
				  		}
				  	},
				  	render: function (h) {
				  		return h('textarea', {class: this.class, ref: 'rich'}, [this.content]);
				  	},
				  	mounted: function () {
					  	var self = this;
						ClassicEditor.create(this.$refs.rich, {
							toolbar: {
								items: [
									'heading',
									'|',
									'bold',
									'italic',
									'underline',
									'link',
									'bulletedList',
									'numberedList',
									'|',
									'fontBackgroundColor',
									'fontColor',
									'fontFamily',
									'fontSize',
									'highlight',
									'|',
									'horizontalLine',
									'pageBreak',
									'|',
									'alignment',
									'|',
									'outdent',
									'indent',
									'|',
									'imageUpload',
									'blockQuote',
									'insertTable',
									'mediaEmbed',
									'|',
									'specialCharacters',
									'codeBlock',
									'htmlEmbed',
									'sourceEditing',
									'|',
									'undo',
									'redo'
								]
							},
							image: {
								toolbar: [
									'imageTextAlternative',
									'imageStyle:inline',
									'imageStyle:block',
									'imageStyle:side',
									'linkImage'
								]
							},
							table: {
								contentToolbar: [
									'tableColumn',
									'tableRow',
									'mergeTableCells',
									'tableCellProperties',
									'tableProperties'
								]
							},
							mediaEmbed: {
								previewsInData: true
							},
            				extraPlugins: [function (editor) {
              					editor.plugins.get('FileRepository').createUploadAdapter = function (loader) {
                  					return new class Image {
                    					constructor(loader) {
        									this.loader = loader;
    									}

										upload() {
											return this.loader.file.then(file => new Promise((resolve, reject) => {
													this._initRequest();
													this._initListeners(resolve, reject, file);
													this._sendRequest(file);
											}));
										}

										abort() {
											if (this.xhr) {
												this.xhr.abort();
											}
										}

										_initRequest() {
											const xhr = this.xhr = new XMLHttpRequest();

											xhr.open( 'POST', '/save', true );

											xhr.setRequestHeader('X-CSRF-TOKEN', '{{csrf_token()}}');

											xhr.responseType = 'json';
										}

										_initListeners(resolve, reject, file) {
											const xhr = this.xhr;
											const loader = this.loader;
											const genericErrorText = `Couldn't upload file: ${ file.name }.`;

											xhr.addEventListener( 'error', () => reject( genericErrorText ) );
											xhr.addEventListener( 'abort', () => reject() );
											xhr.addEventListener( 'load', () => {
												const response = xhr.response;

												if ( !response || response.error ) {
													return reject( response && response.error ? response.error.message : genericErrorText );
												}

												resolve( {
													default: `/snaps/${response.file}`
												} );
											} );

											if ( xhr.upload ) {
												xhr.upload.addEventListener( 'progress', evt => {
													if ( evt.lengthComputable ) {
														loader.uploadTotal = evt.total;
														loader.uploaded = evt.loaded;
													}
												} );
											}
										}

										_sendRequest( file ) {
											const data = new FormData();

											data.append( 'file', file );

											this.xhr.send( data );
										}
                  					}(loader);
              					};
            				}],
						    language: 'es'}).then(function (editor) {
							self.editor = editor;editor.model.document.on('change:data', function () {
								self.$emit('update:content', editor.getData());
							});
                       	});
				  	},
				  	beforeDestroy: function () {
				  		//this.object.destroy();
				  	}
				});

				Vue.component('v-chart', {
              	  data: () => ({
					chart: null
              	  }),
				  props: {
					type: {
						type: String
					},
					data: {
						type: Object
					},
				  	height: {
				  		type: String,
				  		default: '100vh'
				  	},
					plugins: {
						type: Array,
						default: []
					},
					options: {
						type: Object,
						default: {}
					},
				  },
				  watch: {
					type: function (type) {
						this.chart.destroy();

				  		this.chart = new Chart(this.$refs.chart.getContext('2d'), {
							type: type,
							data: this.data,
							plugins: this.plugins,
							options: this.options
						});
				  	},
				  	data: function (data) {
				  		this.chart.data = data;

						this.chart.update();
				  	},
					pluging: function (plugins) {
						this.chart.plugins = plugins;
					},
					options: function (options) {
						this.chart.options = options;
					}
				  },
				  render: function (h) {
					return h('div', {class: this.class, style: `height: ${this.height}`}, [h('canvas', {ref: 'chart'})]);
				  },
				  mounted: function () {
					this.chart = new Chart(this.$refs.chart.getContext('2d'), {
						type: this.type,
						data: this.data,
						plugins: this.plugins,
						options: this.options
					});
				  },
				  beforeDestroy: function () {
				  	this.chart.destroy();
				  }
				});

				Vue.mixin({
					data: function () {
						return {
							//size: (this.$vuetify?.breakpoint?.width < this.$vuetify?.breakpoint?.thresholds?.md) ? 1 : ((this.$vuetify?.breakpoint?.width < this.$vuetify?.breakpoint?.thresholds?.lg) ? 2 : 3),
							tiny: Boolean(this.$vuetify?.breakpoint?.mobile),
							side: {
								show: this.$vuetify?.breakpoint?.mobile ? false : true,
								tiny: false
							},
							user: {
								live: 0,
								type: 1,
								face: null,
								mail: null,
								last: null,
								name: null
							},
							help: {
								show: false,
								name: 'Test',
								data: 'This is a test'
							},
							note: {
					  			show: false,
					  			type: null,
					  			text: null
					  		},
					  		spot: {
					  			drop: false,
					  			text: null,
					  			last: [{icon: 'mdi-history', text: 'juan gomez'}, {icon: 'mdi-history', text: 'esperanza'}],
					  			list: []
					  		},
					  		spin: false,
					  		done: false,
							time: null
						};
					},
				  	methods: {
						same: function (one, two, yes, not, me) {
							return ((one === two) ? ((yes instanceof Function) ? (yes(me, one) ?? true) : (yes ?? true)) : ((not instanceof Function) ? (not(me, one) ?? false) : (not ?? false)));
						},
						none: function (data, yes, not, me) {
							return (((data === null) || (data === undefined) || ((typeof data === 'string') || (typeof data === 'object') ? (data.length === 0) : (data ? false : true))) ? ((yes instanceof Function) ? (yes(me, data) ?? true) : (yes ?? true)) : ((not instanceof Function) ? (not(me, data) ?? false) : (not ?? false)));
						},
						copy: function (base, data) {
							return Object.assign(base, data);
						},
						test: function (test, data) {
							if ((typeof data === 'string')) {
								return (new RegExp(test)).test(data);
							}

							return false;
						},
						echo: function (data) {
							console.log(data);
						},
						trim: function (data, turn) {
							if ((typeof data === 'string')) {
								if (turn) {
									return data.trim().replace(/[àáâäæãåāèéêëēėęîïíīįìôöòóœøōõûüùúūçñ]/ig, (item) => {
										switch (item.toLowerCase()) {
										case 'à':
										case 'á':
										case 'â':
										case 'ä':
										case 'æ':
										case 'ã':
										case 'å':
										case 'ā':
											return 'a';
										case 'è':
										case 'é':
										case 'ê':
										case 'ë':
										case 'ē':
										case 'ė':
										case 'ę':
											return 'e';
										case 'î':
										case 'ï':
										case 'í':
										case 'ī':
										case 'į':
										case 'ì':
											return 'i';
										case 'ô':
										case 'ö':
										case 'ò':
										case 'ó':
										case 'œ':
										case 'ø':
										case 'ō':
										case 'õ':
											return 'o';
										case 'û':
										case 'ü':
										case 'ù':
										case 'ú':
										case 'ū':
											return 'u';
										case 'ç':
											return 'c';
										case 'ñ':
											return 'n';
										}
									});
								} else {
									return data.trim();
								}
							} else {
								if ((data instanceof Array)) {
									return data.filter((item) => {
										return ((item === null) || (typeof item === 'undefined')) ? false : true;
									});
								}
							}

							return data;
						},
						clip: function (data, from, stop) {
							if ((data < from)) {
								return from;
							} else {
								if ((data > stop)) {
									return stop;
								}
							}

							return data;
						},
						most: function (list, high) {
							return high ? Math.max(...list) : Math.min(...list);
						},
						walk: function (list, task, hold) {
							if ((list instanceof Array)) {
								list.forEach((data, item) => {
									task(data, item, hold);
								});
							} else {
								if ((list instanceof Object)) {
									Object.keys(list).forEach((name) => {
										task(list[name], name, hold);
									});
								}
							}

							return hold;
						},
						loop: function (from, last, task, back) {
							for (var item = from; item <= last; item++) {
								task(item, back);
							}

							return back;
						},
				  		find: function (list, name, data, pull, none) {
							var item = list.findIndex((item) => {
								if ((item[name] == data)) {
									return true;
								}
							});

							if ((~item)) {
								if (pull) {
									if ((pull === true)) {
										return item;
									}
									
									return list[item][pull];
								}

								return list[item];
							}

							return none;
						},
						fold: function (list, task, data) {
							if ((list instanceof Array)) {
								list.forEach((item) => {
									data = task(data, item) ?? data;
								})
							} else {
								if ((list instanceof Object)) {
									Object.keys(list).forEach((name) => {
										data = task(data, list[name], name) ?? data;
									})
								}
							}

							return data;
						},
						blob: function (canvas, type) {
							var text = atob(canvas.toDataURL(type).split(',')[1]);

							var data = new Uint8Array(text.length);

							for (var index = 0; index < text.length; index++) {
								data[index] = text.charCodeAt(index);
							}

							return new Blob([data], {
								type: type ?? 'image/png'
							});
						},
						text: function (value, length, decimals, thousands) {
		                  	length = isFinite(+length) ? Math.abs(length) : 0;

		                  	value = (value + '').replace(/[^0-9+\-Ee.]/g, '');

		                  	value = isFinite(+value) ? +value : 0;

		                  	var base = Math.pow(10, length);

		                  	var string = (length ? '' + (Math.round(value * base) / base) : '' + Math.round(value)).split('.');

		                  	if ((string[0].length > 3)) {
		                      	string[0] = string[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, (thousands === undefined) ? ',' : thousands);
		                  	}

		                  	if (((string[1] || '').length < length)) {
		                      	string[1] = string[1] || '';

		                      	string[1] = string[1] + new Array(length - string[1].length + 1).join('0');
		                  	}

		                  	return string.join((typeof decimals === 'undefined') ? '.' : decimals);
		                },
						fire: function (done, wait, data) {
							if (this.time) {
                                clearTimeout(this.time);
                            }

                            this.time = setTimeout(() => {
                                done(data);
                            }, wait);
						},
		                date: function (date, text, unit) {
							if (this.none(unit)) {
								return moment(date).format(text);
							} else {
								return moment(text).diff(moment(date), unit);
							}
		                },
						fade: function (tint, tone) {
							tone = Math.max(Math.min(tone, 1), 0.01);

							tint = parseInt(tint, 16);

							var red = ((255 - ((tint & 0xFF0000) >> 16)) * tone) + ((tint & 0xFF0000) >> 16);

							var green = ((255 - ((tint & 0x00FF00) >> 8)) * tone) + ((tint & 0x00FF00) >> 8);

							var blue = ((255 - ((tint & 0x0000FF) >> 0)) * tone) + ((tint & 0x0000FF) >> 0);

							return ((((red << 8) | green) << 8) | blue).toString(16);
						}
				  	},
					mounted: function () {
						window.addEventListener('resize', (() => {
							//this.size = (this.$vuetify?.breakpoint?.width < this.$vuetify?.breakpoint?.thresholds?.md) ? 1 : ((this.$vuetify?.breakpoint?.width < this.$vuetify?.breakpoint?.thresholds?.lg) ? 2 : 3);
						}).bind(this), {
							passive: true
						});
					}
				});
            })(false)
        </script>
        @yield('code')
    </body>
</html>