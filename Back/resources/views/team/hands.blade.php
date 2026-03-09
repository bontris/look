@extends('team')

@section('page')
	<v-layout column>
		<v-row dense>
			<v-col>
				<v-list>
					<v-list-item
						class="px-3 py-1">
						<v-list-item-content>
							<v-list-item-title>
							<v-breadcrumbs
								class="px-0 py-0"
								:items="[{text: 'Inicio', href: '{{route('dash')}}', disabled: false},
										 {text: 'Autores', href: '{{route('team.hands')}}', disabled: true}]">
							</v-breadcrumbs>
							</v-list-item-title>
						</v-list-item-content>
					</v-list-item>
				</v-list>
				<v-divider>
				</v-divider>
			</v-col>
		</v-row>
		<v-row>
			<v-col class="mx-4">
				<v-card outlined>
				<v-toolbar flat>
						<v-toolbar-title>
							<v-sheet v-if="view == 1">
								<v-list>
									<v-list-item>
										<v-list-item-content>
											<v-list-item-title class="title">
												Listado de autores
											</v-list-item-title>
										</v-list-item-content>
									</v-list-item>
								</v-list>
							</v-sheet>
							<v-sheet v-else>
								<v-list>
									<v-list-item>
										<v-list-item-content>
											<v-list-item-title class="title">
												@{{pick ? 'Editar autor' : 'Nuevo autor'}}
											</v-list-item-title>
										</v-list-item-content>
									</v-list-item>
								</v-list>
							</v-sheet>
						</v-toolbar-title>
						<v-spacer>	
						</v-spacer>
						<v-sheet v-if="view == 1">
							<v-btn
								class="mr-4"
								color="primary"
								@click.stop="open('make')"
								:disabled="wait">
								<v-icon left>mdi-plus</v-icon>&nbsp;Agregar autor
							</v-btn>
						</v-sheet>
					</v-toolbar>
					<v-divider>
					</v-divider>
					<v-card-text class="pa-0">
						<v-sheet v-if="view == 1">
							<v-text-field
								prepend-inner-icon="mdi-magnify"
								background-color="grey lighten-4"
								autocomplete="off"
								label="Buscar"
								v-model="find"
								:readonly="wait"
								hide-details
								single-line
								clearable
								attach
								solo
								flat>
							</v-text-field>
							<v-divider>
                            </v-divider>
						</v-sheet>
						<v-sheet v-if="view == 1">
							<v-row>
								<v-col>
									<v-data-table
				    				    :headers="[{text: 'Estado', align: 'start', sortable: false},
			    				                   {text: 'Avatar', align: 'start', sortable: false},
			    				                   {text: 'Nombre', align: 'start', sortable: false},
			    				                   {text: 'Apellidos', align: 'start', sortable: false},
												   {text: 'Teléfono', align: 'start', sortable: false},
												   {text: 'Correo', align: 'start', sortable: false},
			    				                   {sortable: false}]"
								        :items="list"
								        :server-items-length="high"
								        :footer-props="{showFirstLastPage: true,
								                        itemsPerPageOptions: [16, 32, 64],
								                        pageText: '{0} - {1} de {2}',
									                    firstIcon: 'mdi-page-first',
									                    lastIcon: 'mdi-page-last',
									                    prevIcon: 'mdi-chevron-left',
									                    nextIcon: 'mdi-chevron-right',
									                    itemsPerPageText: 'Filas por página'}"
									    :options.sync="data"
									    :page.sync="page"
									    :loading="true"
								        bordered>
								        <v-progress-linear
								            color="primary"
								            slot="progress"
								          	v-show="(wait && (show == false))"
								            indeterminate>
								        </v-progress-linear>
								        <template v-slot:item="{item}">
								        	<tr>
								        		<td width="140">
								        			<v-chip :color="(item.lock ? 'error' : 'green')" text-color="white" small>@{{item.lock ? 'Bloqueado' : 'Disponible'}}</v-chip>
								        		</td>
								        		<td width="80">
								        			<v-menu
														transition="slide-y-transition"
														right="true"
											    		offset-y>
														<template v-slot:activator="{on, attrs}">
															<v-btn
																v-bind="attrs"
						                                        v-on="on"
																:disabled="wait"
																icon>
																<v-avatar size="36" :color="(item.face ? 'white' : `#${item.tone}`)">
																	<img :src="('/snaps/' + item.face + '/thumb')" v-if="item.face">
																	<span class="font-weight-bold white--text caption" v-else>@{{item.name.charAt(0).toUpperCase()}}@{{item.last.charAt(0).toUpperCase()}}</span>
																</v-avatar>
															</v-btn>
														</template>
														<v-list>
															<label>
																<v-list-item link>
													        		<v-list-item-icon>
													            		<v-icon>mdi-camera</v-icon>
													        		</v-list-item-icon>
													        		<v-list-item-title>Cambiar imágen</v-list-item-title>
													    		</v-list-item>
													        	<input class="d-none" type="file" accept="image/*" @change="open('crop', item, $event.target)">
													   		</label>
													        <v-list-item v-if="item.face" @click="open('wipe', item)">
													        	<v-list-item-icon>
													              	<v-icon>mdi-delete</v-icon>
													            </v-list-item-icon>
													            <v-list-item-title>Eliminar imágen</v-list-item-title>
													        </v-list-item>
												        </v-list>
													</v-menu>
								        		</td>
								        		<td>
								        			<div class="font-weight-medium">
								        				@{{item.name}}
								        			</div>
								        		</td>
												<td>
								        			<div class="font-weight-medium">
								        				@{{item.last}}
								        			</div>
								        		</td>
												<td>
								        			<div class="font-weight-medium" :class="{'grey--text': item.work ? false : true}">
								        				@{{item.work || 'Nínguno'}}
								        			</div>
								        		</td>
												<td>
								        			<div class="font-weight-medium" :class="{'grey--text': item.work ? false : true}">
								        				@{{item.mail || 'Nínguno'}}
								        			</div>
								        		</td>
								        		<td class="pl-0 pr-0" width="160">
								        			<v-btn class="mr-2" :color="(item.lock ? 'success' : 'error')" @click="open('lock', item)" icon>
									                	<v-icon v-if="(item.lock ? true : false)">mdi-check-circle-outline</v-icon>
									                	<v-icon v-else>mdi-close-circle-outline</v-icon>
									              	</v-btn>
								        			<v-btn class="mr-2" @click="open('edit', item)" icon>
									                	<v-icon>mdi-pencil</v-icon>
									              	</v-btn>
									              	<v-btn @click="open('drop', item)" icon>
									                	<v-icon>mdi-delete</v-icon>
									              	</v-btn>
								        		</td>
								        	</tr>
								        </template>
								        <template slot="loading">
									    	<div class="none">
								    			<svg width="55" height="64" viewBox="0 0 55 64">
													<path d="M36.536 34.286h11.286c-0.071-0.179-0.107-0.393-0.179-0.571l-7.571-17.714h-25.286l-7.571 17.714c-0.071 0.179-0.107 0.393-0.179 0.571h11.286l3.393 6.857h11.429zM54.857 35.357v17.214c0 1.25-1.036 2.286-2.286 2.286h-50.286c-1.25 0-2.286-1.036-2.286-2.286v-17.214c0-1.286 0.393-3.179 0.893-4.393l8.5-19.714c0.5-1.179 1.929-2.107 3.179-2.107h29.714c1.25 0 2.679 0.929 3.179 2.107l8.5 19.714c0.5 1.214 0.893 3.107 0.893 4.393z"></path>
												</svg>
								    			<div>
								    				<b>Sin registros</b>
								    				<p>@{{(sort.text || '').trim() ? 'No se encontraron elementos.' : 'No hay elementos disponibles.'}}</p>
								    			</div>
								    		</div>
								    	</template>
								    </v-data-table>
								</v-col>
							</v-row>
						</v-sheet>
						<v-sheet v-else>
							<v-layout
							    class="mx-4 my-1"
								column>
								<v-row
									v-if="fail.text"
									dense>
									<v-col>
										<v-alert
											type="error"
											tile>
											@{{fail.text}}
										</v-alert>
									</v-col>
								</v-row>
								<v-row>
									<v-col>
										<v-text-field
											type="text"
											label="Nombre: *"
											v-model="form.name"
											:error-messages="(fail.list.name ? [fail.list.name] : [])"
											:disabled="wait"
											autocomplete="nope"
											hide-details="auto"
											filled>
										</v-text-field>
									</v-col>
									<v-col>
										<v-text-field
											type="text"
											label="Apellidos: *"
											v-model="form.last"
											:error-messages="(fail.list.last ? [fail.list.last] : [])"
											:disabled="wait"
											autocomplete="nope"
											hide-details="auto"
											filled>
										</v-text-field>
									</v-col>
								</v-row>
								<v-row>
									<v-col>
										<v-text-field
											type="text"
											label="Teléfono"
											v-model="form.work"
											:error-messages="(fail.list.work ? [fail.list.work] : [])"
											:disabled="wait"
											autocomplete="nope"
											hide-details="auto"
											filled>
										</v-text-field>
									</v-col>
									<v-col>
										<v-text-field
											type="text"
											label="Correo electrónico"
											v-model="form.mail"
											:error-messages="(fail.list.mail ? [fail.list.mail] : [])"
											:disabled="wait"
											autocomplete="nope"
											hide-details="auto"
											filled>
										</v-text-field>
									</v-col>
								</v-row>
								<v-row>
									<v-col>
										<v-text-field
											type="text"
											label="Acerca de: *"
											v-model="form.note"
											:error-messages="(fail.list.note ? [fail.list.note] : [])"
											:disabled="wait"
											autocomplete="nope"
											hide-details="auto"
											filled>
										</v-text-field>
									</v-col>
								</v-row>
							</v-layout>
							<v-layout class="pa-4">
								<v-spacer>
								</v-spacer>
								<v-btn
									color="secondary"
									@click="view = 1"
									:disabled="wait"
									text
									tile>
									Cancelar
								</v-btn>
								<v-btn
									class="white--text ml-6"
									color="success"
									@click="save(form, pick)"
									:disabled="wait"
									text
									tile>
									Guardar
								</v-btn>
							</v-layout>
						</v-sheet>
					</v-card-text>
				</v-card>
			</v-col>
		</v-row>
	</v-layout>

	<v-dialog
		v-model="crop"
		width="40%"
		persistent
		outlined
		tile>
		<v-card>
			<v-toolbar
				class="mx-4"
				flat>
				<v-toolbar-title>
					Cambiar imagén
				</v-toolbar-title>
				<v-progress-linear
		            :indeterminate="wait"
		            :active="wait"
		            absolute
		            bottom>
		        </v-progress-linear>
				<v-spacer></v-spacer>
				<v-btn
					@click="(crop = false)"
					:disabled="wait"
					icon>
					<v-icon>mdi-close</v-icon>
				</v-btn>
			</v-toolbar>
			<v-divider>
			</v-divider>
			<v-container>
				<v-row v-if="fail.text">
        			<v-col>
        				<v-alert
							type="error"
							tile>@{{fail.text}}</v-alert>
        			</v-col>
        		</v-row>
        		<v-row>
        			<v-col>
        				<v-crop
							ref="snap"
							width="100%"
							ratio="1.1"
							:boot="boot">
						</v-crop>
        			</v-col>
        		</v-row>
			</v-container>
			<v-divider>
			</v-divider>
			<v-card-actions class="grey lighten-4">
				<v-btn
					class="mr-2"
					color="blue"
					@click="snap.rotate(90)"
					icon>
					<v-icon>mdi-rotate-right</v-icon>
				</v-btn>
				<v-btn
					color="blue"
					@click="snap.rotate(-90)"
					icon>
					<v-icon>mdi-rotate-left</v-icon>
				</v-btn>
				<v-spacer>
				</v-spacer>
				<v-btn
					class="mr-2"
					color="secondary"
					@click="(crop = false)"
					:disabled="wait"
					tile
					text>
					Cancelar
				</v-btn>
				<v-btn
					class="mr-2"
					color="success"
					@click="face(pick, snap)"
					:disabled="wait"
					tile
					text>
					Guardar
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>

	<v-dialog v-model="none" width="40%" persistent outlined tile>
        <v-card>
          	<v-card-title class="headline">Eliminar imágen</v-card-title>
          	<v-divider></v-divider>
          	<v-card-text class="mt-4">
          		¿Desea eliminar la imágen del autor <b>@{{pick ? pick.name : null}} @{{pick ? pick.last : null}} / @{{pick ? pick.code : null}}</b>?
          	</v-card-text>
          	<v-card-actions>
            	<v-spacer></v-spacer>
            	<v-btn color="secondary" @click="(none = false)" :disabled="wait" text>Cancelar</v-btn>
            	<v-btn color="red" @click="face(pick)" :disabled="wait" text>Eliminar</v-btn>
          	</v-card-actions>
        </v-card>
	</v-dialog>

	<v-dialog v-model="wipe" width="40%" persistent outlined tile>
		<v-card>
			<v-card-title class="headline">Eliminar autor</v-card-title>
			<v-divider></v-divider>
			<v-card-text class="mt-4">¿Desea eliminar el autor <b>@{{pick ? pick.name : null}} @{{pick ? pick.last : null}} / @{{pick ? pick.code : null}}</b>?</v-card-text>
			<v-card-actions>
			<v-spacer></v-spacer>
			<v-btn color="secondary" text @click="(wipe = false)" :disabled="wait">Cancelar</v-btn>
			<v-btn color="red" text @click="drop(pick)" :disabled="wait">Eliminar</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>

	<v-dialog v-model="swap" width="40%" persistent outlined tile>
		<v-card>
			<v-card-title class="headline" dark>@{{((pick && pick.lock) ? 'Habilitar autor' : 'Bloquear autor')}}</v-card-title>
			<v-divider></v-divider>
			<v-card-text class="mt-4">¿Desea @{{((pick && pick.lock) ? 'habilitar' : 'bloquear')}} el autor <b>@{{pick ? pick.name : null}} @{{pick ? pick.last : null}} / @{{pick ? pick.code : null}}</b>?</v-card-text>
			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn color="secondary" text @click="(swap = false)" :disabled="wait">Cancelar</v-btn>
				<v-btn :color="((pick && pick.lock) ? 'success' : 'error')" text @click="lock(pick)" :disabled="wait">@{{((pick && pick.lock) ? 'Habilitar' : 'Bloquear')}}</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
@stop

@section('code')
    <script type="text/javascript">
  		$(document).ready(function () {
  			var self = new Vue({
			  	vuetify: new Vuetify(),
				el: '#page',
			  	data: {
			  		wait: false,
			  		show: false,
			  		wipe: false,
					none: false,
			  		swap: false,
					crop: false,
			  		find: null,
			  		pick: null,
					snap: null,
					view: 1,
			  		take: 32,
			  		high: 0,
			  		page: 0,
			  		list: [],
			  		data: {
			  			page: 0,
			  			itemsPerPage: 32
			  		},
			  		fail: {text: null,
			  		       list: {}},
			  		sort: {
			  			text: null
			  		},
			  		form: {
			  			lock: null,
			  			file: null,
			  			name: null,
			  			last: null,
			  			work: null,
			  			mail: null,
			  			note: null
			  		}
			    },
			    watch: {
			    	find: {
			    		handler: function (find) {
			    		   if (self.time) {
				    			clearTimeout(self.time);
				    		}

				    		self.time = setTimeout(function () {
				    			self.load(self.take, null, find);
				    		}, 500);
					    },
					    deep: true
			    	},
			    	data: {
			    		handler: function (data) {
			    		   self.load((self.take = data.itemsPerPage), (self.page = data.page), self.find);
					    },
					    deep: true
			    	}
			    },
			    methods: {
			    	load: function (take, page, find, done) {
			    		axios.get("{{route('team.hands', ['task' => 'load'])}}?take=" + (take || '') + '&page=' + (page || '') +
			    			                                                                           '&find=' + (find || ''), {})
				             .then(function (data) {
				            self.list = data.data.data || [];

				            self.high = data.data.high || 0;

				            self.page = data.data.page || 0;

				            self.take = data.data.take || 0;

				            self.wait = false;

				            if (done) {
				            	done(false);
				            }
				        }).catch(function (fail) {
				            self.wait = false;

				            if (done) {
				            	done(true);
				            }
				        });

				        this.wait = true;
			    	},
			    	open: function (task, item, data) {
			    		self.note = {show: false,
	                                 type: null,
	                                 text: null};

			    		self.fail = {text: null,
			    			         list: {}};

			    		if (item) {
			    			switch (task) {
			    				case 'edit':
			    				    axios.get("{{route('team.hands', ['task' => 'load'])}}/" + item.hash, {})
							             .then(function (data) {
							            self.form = {lock: data.data.lock,
					             			         code: data.data.code,
			             			    	         name: data.data.name,
			             			    	         last: data.data.last,
			             			    	         work: data.data.work,
			             			    	         mail: data.data.mail,
			             			    	         note: data.data.note};

										self.wait = false;

										self.view = 2;
							        }).catch(function (fail) {
							            self.wait = false;

							            if (fail.response.data.text) {
			                            	self.note = {show: true,
			                            	             type: 'fail',
			                            	             text: fail.response.data.text};
			                            } else {
			                            	self.note = {show: true,
			                            	             type: 'fail',
			                            	             text: 'Se presentó un error inesperado.'};
			                            }
							        });

							        self.pick = item;

							        this.wait = true;
			    					break;
								case 'crop':
			    				    var file = data.files.item(0);

			    					if (file.type.match('image.*')) {
										var reader = new FileReader();

										reader.onload = function(event) {
											self.file = event.target.result;

											self.crop = true;

											self.pick = item;

											if (self.snap) {
												self.snap.replace(self.file, false);
											}
										};

										reader.readAsDataURL(file);
									}
			    					break
								case 'wipe':
			    					self.none = true;

			    			    	self.pick = item;
			    			    	break;
			    			    case 'lock':
			    			    	self.pick = item;

			    			    	self.swap = true;
			    			    	break;
			    				case 'drop':
			    				    self.pick = item;

			    				    self.wipe = true;
			    					break;
			    			}
			    		} else {
			    			switch (task) {
			    				case 'make':
			    					self.form = {lock: null,
			    						         code: null,
									  			 name: null,
									  			 last: null,
									  			 work: null,
									  			 mail: null,
									  			 note: null};

		             			    self.pick = null;

					             	self.view = 2;
			    					break;
			    			}
			    		}
			    	},
			    	save: function (form, item) {
                        var data = new FormData();

                        self.fail = {text: null,
			    			         list: {}};

		    		    data.append('lock', (form.lock || 0));

		    		    data.append('name', (form.name || ''));

		    		    data.append('last', (form.last || ''));

		    	        data.append('work', (form.work || ''));

		    	        data.append('mail', (form.mail || ''));

		    	        data.append('note', (form.note || ''));

		    	        if (item) {
				    		axios.post("{{route('team.hands', ['task' => 'save'])}}/" + item.hash, data, {'X-CSRF-TOKEN': '{{csrf_token()}}'})
	                             .then(function (data) {
	                            self.wait = false;

	                            self.note = {show: true,
                                	         type: 'done',
                                	         text: data.data.text};

                                item.name = form.name;

                                item.last = form.last;

				    	        item.work = form.work;

                                item.mail = form.mail;

                                self.view = 1;
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
				    	} else {
				    		axios.post("{{route('team.hands', ['task' => 'make'])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
	                             .then(function (data) {
	                            self.list.unshift((item = {item: data.data.item,
                                	                       hash: data.data.hash,
                                	                       code: data.data.code,
                            		                       lock: form.lock,
                            		                       name: form.name,
                            		                       last: form.last,
                            		                       work: form.work,
                                                           mail: form.mail}));

                                self.note = {show: true,
                                	         type: 'done',
                                	         text: data.data.text};

                                self.wait = false;

                                self.view = 1;
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
				    	}

				    	self.wait = true;
			    	},
			    	send: function (form) {
			    		var data = new FormData();

                        self.fail = {text: null,
			    			         list: {}};

		    		    data.append('file', (form.file || ''));

			    		axios.post("{{route('team.hands', ['task' => 'push'])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}', 'Content-Type': 'multipart/form-data'}})
	                             .then(function (data) {
	                        self.load(self.take, self.page, self.sort.text);

                            self.note = {show: true,
                            	         type: 'done',
                            	         text: data.data.text};

                            self.wait = false;

                            self.push = false;
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
			    	},
					face: function (item, snap) {
			    		var data = new FormData();

						if (snap) {
							data.append('file', self.blob(snap.getCroppedCanvas({width: 640, height: 640})));
						}

			    		axios.post("{{route('team.hands', ['task' => 'face'])}}/" + item.hash, data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
                             .then(function (data) {
                            item.face = data.data.file;

                            self.note = {show: true,
                            	         type: 'done',
                            	         text: data.data.text};
                            
                            self.crop = false;

                            self.none = false;

                            self.wait = false;
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
			    	},
			    	lock: function (item) {
			    		axios.get("{{route('team.hands', ['task' => 'lock'])}}/" + item.hash, {})
				             .then(function (data) {
				            self.swap = false;

				            self.wait = false;

			             	item.lock = data.data.flag;

		             		self.note = {show: true,
                            	         type: 'done',
                            	         text: data.data.text};
				        }).catch(function (fail) {
				        	self.swap = false;

				            self.wait = false;

				            if (fail.response.data.text) {
                            	self.note = {show: true,
                            	             type: 'fail',
                            	             text: fail.response.data.text};
                            } else {
                            	self.note = {show: true,
                            	             type: 'fail',
                            	             text: 'Se presentó un error inesperado.'};
                            }
				        });

				        self.wait = true;
			    	},
			    	drop: function (item) {
			    		axios.get("{{route('team.hands', ['task' => 'drop'])}}/" + item.hash, {})
				             .then(function (data) {
				            self.wipe = false;

				            self.wait = false;

			             	self.list.splice(self.list.indexOf(item), 1);

		             		self.note = {show: true,
                            	         type: 'done',
                            	         text: data.data.text};
				        }).catch(function (fail) {
				        	self.wipe = false;

				            self.wait = false;

				            if (fail.response.data.text) {
                            	self.note = {show: true,
                            	             type: 'fail',
                            	             text: fail.response.data.text};
                            } else {
                            	self.note = {show: true,
                            	             type: 'fail',
                            	             text: 'Se presentó un error inesperado.'};
                            }
				        });

				        this.wait = true;
			    	},
					boot: function(data) {
						self.snap = data;

						self.snap.replace(self.file, false);
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