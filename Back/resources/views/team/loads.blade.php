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
										 {text: 'Registros', href: '{{route('team.loads')}}', disabled: true}]">
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
							<v-sheet>
								<v-list>
									<v-list-item>
										<v-list-item-content>
											<v-list-item-title class="title">
												Listado de registros
											</v-list-item-title>
										</v-list-item-content>
									</v-list-item>
								</v-list>
							</v-sheet>
						</v-toolbar-title>
						<v-spacer>	
						</v-spacer>
					</v-toolbar>
					<v-divider>
					</v-divider>
					<v-card-text class="pa-0">
						<v-sheet>
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
						<v-sheet>
							<v-row>
								<v-col>
									<v-data-table
				    				    :headers="[{sortable: false},
										           {text: 'Código', width: 160, align: 'start', sortable: false},
                                                   {text: 'Tipo', width: 140, align: 'start', sortable: false},
			    				                   {text: 'Variable', align: 'start', sortable: false},
                                                   {text: 'Dispositivo', align: 'start', sortable: false},
                                                   {text: 'Información', align: 'start', sortable: false},
                                                   {text: 'Llegada', width: 180, align: 'end', sortable: false},
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
										hide-default-header
								        bordered>
								        <v-progress-linear
								            color="primary"
								            slot="progress"
								          	v-show="(wait && (show == false))"
								            indeterminate>
								        </v-progress-linear>
										<template v-slot:header="{props}">
											<thead class="v-data-table-header">
												<th width="40">
													<v-checkbox
														:indeterminate="bulk.list.length && ((bulk.list.length == list.filter(function (item) {return item.lock ? false : true}).length) == false)"
														:input-value="(bulk.list.length ? (bulk.list.length == list.filter(function (item) {return item.lock ? false : true}).length) : false)"
														@change="$event ? bulk.list = list.filter(function (item) {return item.lock ? false : true}).map(function (item) {return item}) : bulk.list = []">
													</v-checkbox>
												</th>
												<th 
													scope="col"
													role="columnheader"
													:class="`text-${head.align}`"
													:width="head.width"
													v-for="head in props.headers"
													v-if="head.text">
													<span>@{{head.text.toUpperCase()}}</span>
												</th>
												<th width="80">
													<v-layout>
														<v-spacer>
														</v-spacer>
														<v-btn
															@click="open('drop')"
															:disabled="bulk.list.length ? false : true"
															icon>
															<v-icon>mdi-delete</v-icon>
														</v-btn>
													</v-layout>
												</th>
											</thead>
										</template>
								        <template v-slot:item="{item}">
								        	<tr>
												<td>
													<v-checkbox
														:disabled="item.lock"
														@change="$event ? bulk.list.push(item) : bulk.list.splice(bulk.list.indexOf(item), 1)"
														:input-value="~bulk.list.indexOf(item)">
													</v-checkbox>
												</td>
                                                <td>
								        			<div class="font-weight-medium">
								        				@{{item.code}}
								        			</div>
								        		</td>
                                                <td>
								        			<div class="font-weight-medium">
								        				<v-chip :color="{1: 'blue', 2: 'green', 3: 'amber', 4: 'orange'}[item.type]" text-color="white" small>@{{{1: 'Entero', 2: 'Flotante', 3: 'Cadena', 4: 'Objeto'}[item.type]}}</v-chip>
								        			</div>
								        		</td>
												<td>
								        			<div class="font-weight-medium">
								        				@{{item.name}}
								        			</div>
								        		</td>
                                                <td>
								        			<div class="font-weight-medium">
								        				@{{item.node}}
								        			</div>
                                                    <div class="grey--text">
                                                        @{{item.card}}
                                                    </div>
								        		</td>
                                                <td>
								        			<div class="font-weight-medium">
								        				@{{item.data}}
								        			</div>
								        		</td>
												<td>
								        			<div class="font-weight-medium text-end">
                                                        @{{date(item.creation, 'DD/MM/YYYY HH:mm:ss')}}
								        			</div>
								        		</td>
								        		<td class="pl-0 pr-4">
													<v-layout>
														<v-spacer>
														</v-spacer>
														<v-btn @click="open('drop', item)" icon>
															<v-icon>mdi-delete</v-icon>
														</v-btn>
													</v-layout>
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
								    				<p>@{{(find || '').trim() ? 'No se encontraron elementos.' : 'No hay elementos disponibles.'}}</p>
								    			</div>
								    		</div>
								    	</template>
								    </v-data-table>
								</v-col>
							</v-row>
						</v-sheet>
					</v-card-text>
				</v-card>
			</v-col>
		</v-row>
	</v-layout>

	<v-dialog v-model="wipe" width="40%" persistent outlined tile>
		<v-card>
			<v-card-title class="headline">Eliminar variable</v-card-title>
			<v-divider></v-divider>
			<v-card-text class="mt-4">¿Desea eliminar el registro <b>@{{pick ? pick.code : null}}</b>?</v-card-text>
			<v-card-actions>
			<v-spacer></v-spacer>
			<v-btn color="secondary" text @click="(wipe = false)" :disabled="wait">Cancelar</v-btn>
			<v-btn color="red" text @click="drop(pick)" :disabled="wait">Eliminar</v-btn>
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
                    nodes: [],
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
			  		fail: {
                        text: null,
			  		    list: {}
                    },
					bulk: {
						show: false,
						list: [],
						data: {
							type: null,
                            node: null
						}
					},
			  		form: {
                        lock: null,
                        type: null,
                        node: null,
                        nick: null,
                        name: null
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
			    		axios.get("{{route('team.loads', ['task' => 'load'])}}?take=" + (take || '') + '&page=' + (page || '') +
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
			    				case 'drop':
			    				    self.pick = item;

			    				    self.wipe = true;
			    					break;
			    			}
			    		} else {
			    			switch (task) {
			    				case 'make':
			    					self.form = {lock: 0,
                                                 type: null,
                                                 node: null,
                                                 nick: null,
									  			 name: null};

		             			    self.pick = null;

					             	self.view = 2;
			    					break;
			    			}
			    		}
			    	},
			    	drop: function (item) {
			    		axios.get("{{route('team.items', ['task' => 'drop'])}}/" + item.hash, {})
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
                    axios.get("{{route('team.nodes', ['task' => 'pull'])}}", {})
				             .then(function (nodes) {
                        self.nodes = nodes.data.data;

                        setTimeout(function () {
                            self.done = true;
                        }, 500);
                    });
				}
			})
  		});
  	</script>
@stop