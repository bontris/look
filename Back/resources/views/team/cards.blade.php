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
										 {text: 'Anuncios', href: '{{route('team.cards')}}', disabled: true}]">
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
												Listado de anuncios
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
												@{{pick ? 'Editar anuncio' : 'Nuevo anuncio'}}
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
								<v-icon left>mdi-plus</v-icon>&nbsp;Agregar anuncio
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
				    				    :headers="[{text: 'ESTADO', align: 'start', sortable: false},
                                                   {text: 'CÓDIGO', width: 120, align: 'start', sortable: false},
			    				                   {text: 'PORTADA', width: 120, align: 'center', sortable: false},
			    				                   {text: 'TÍTULO', align: 'start', sortable: false},
                                                   {text: 'CREACIÓN', width: 140, align: 'end', sortable: false},
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
                                                <td>
								        			<div class="font-weight-medium">
								        				@{{item.code}}
								        			</div>
								        		</td>
								        		<td>
                                                    <div class="text-center">
                                                        <v-avatar size="36" color="white">
                                                            <img :src="`/snaps/${item.snap}/thumb`">
                                                        </v-avatar>
                                                    </div>
								        		</td>
												<td>
								        			<div class="font-weight-medium">
								        				@{{item.name}}
								        			</div>
								        		</td>
												<td>
								        			<div class="font-weight-medium text-end">
                                                        @{{date(item.creation, 'DD/MM/YYYY')}}
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
							    class="ma-4"
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
											label="Título: *"
											v-model="form.name"
											:error-messages="(fail.list.name ? [fail.list.name] : [])"
											:disabled="wait"
											autocomplete="nope"
                                            hide-details="auto"
											filled>
										</v-text-field>
									</v-col>
                                    <v-col>
                                        <v-file-input
                                            accept="image/jpeg,image/png"
                                            :label="(pick ? 'Portada' : 'Portada: *')"
                                            v-model="form.snap"
                                            :error-messages="(fail.list.snap ? [fail.list.snap] : [])"
                                            :disabled="wait"
                                            hide-details="auto"
                                            clearable
                                            filled>
                                        </v-file-input>
                                    </v-col>
								</v-row>
                                <v-row>
									<v-col>
										<v-text-field
											type="text"
											label="Descripción"
											v-model="form.text"
											:error-messages="(fail.list.text ? [fail.list.text] : [])"
											:disabled="wait"
											autocomplete="nope"
                                            hide-details="auto"
											filled>
										</v-text-field>
									</v-col>
                                    <v-col>
                                        <v-text-field
											type="text"
											label="Enlace"
											v-model="form.link"
											:error-messages="(fail.list.link ? [fail.list.link] : [])"
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

	<v-dialog v-model="wipe" width="40%" persistent outlined tile>
		<v-card>
			<v-card-title class="headline">Eliminar anuncio</v-card-title>
			<v-divider></v-divider>
			<v-card-text class="mt-4">¿Desea eliminar el anuncio <b>@{{pick ? pick.name : null}} / @{{pick ? pick.code : null}}</b>?</v-card-text>
			<v-card-actions>
			<v-spacer></v-spacer>
			<v-btn color="secondary" text @click="(wipe = false)" :disabled="wait">Cancelar</v-btn>
			<v-btn color="red" text @click="drop(pick)" :disabled="wait">Eliminar</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>

	<v-dialog v-model="swap" width="40%" persistent outlined tile>
		<v-card>
			<v-card-title class="headline" dark>@{{((pick && pick.lock) ? 'Habilitar anuncio' : 'Bloquear anuncio')}}</v-card-title>
			<v-divider></v-divider>
			<v-card-text class="mt-4">¿Desea @{{((pick && pick.lock) ? 'habilitar' : 'bloquear')}} el anuncio <b>@{{pick ? pick.name : null}} / @{{pick ? pick.code : null}}</b>?</v-card-text>
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
                    kinds: [],
                    hands: [],
			  		wait: false,
			  		show: false,
			  		wipe: false,
			  		swap: false,
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
                        code: null,
                        link: null,
                        name: null,
                        text: null,
                        snap: null
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
			    		axios.get("{{route('team.cards', ['task' => 'load'])}}?take=" + (take || '') + '&page=' + (page || '') +
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
			    				    axios.get("{{route('team.cards', ['task' => 'load'])}}/" + item.hash, {})
							             .then(function (data) {
							            self.form = {snap: null,
                                                     lock: data.data.lock,
					             			         code: data.data.code,
			             			    	         link: data.data.link,
			             			    	         name: data.data.name,
			             			    	         text: data.data.text};

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
                                                 link: null,
									  			 name: null,
									  			 text: null,
                                                 snap: null};

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

		    		    data.append('code', (form.code || ''));

                        data.append('link', (form.link || ''));

                        data.append('name', (form.name || ''));

                        data.append('text', (form.text || ''));

                        data.append('snap', (form.snap || ''));

		    	        if (item) {
				    		axios.post("{{route('team.cards', ['task' => 'save'])}}/" + item.hash, data, {'X-CSRF-TOKEN': '{{csrf_token()}}'})
	                             .then(function (data) {
	                            self.wait = false;

	                            self.note = {show: true,
                                	         type: 'done',
                                	         text: data.data.text};

				    	        item.snap = data.data.snap;

                                item.link = form.link;

                                item.name = form.name;

                                item.code = form.code;

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
				    		axios.post("{{route('team.cards', ['task' => 'make'])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
	                             .then(function (data) {
	                            self.list.unshift((item = {item: data.data.item,
                                	                       hash: data.data.hash,
                                	                       code: data.data.code,
                                                           slug: data.data.slug,
                                                           snap: data.data.snap,
                            		                       lock: form.lock,
                                                           link: form.link,
                                                           text: form.text}));

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
			    	lock: function (item) {
			    		axios.get("{{route('team.cards', ['task' => 'lock'])}}/" + item.hash, {})
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
			    		axios.get("{{route('team.cards', ['task' => 'drop'])}}/" + item.hash, {})
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