@extends('team')

@section('page')
	<v-layout column>
		<v-row dense>
			<v-col>
				<v-layout
                   class="white px-2">
					<v-list>
						<v-list-item
							class="px-1">
							<v-list-item-avatar
                                color="#546E7B"
                                size="36"
                                tile>
								<v-icon
                                    color="white">
									mdi-hand-heart
								</v-icon>
							</v-list-item-avatar>
							<v-list-item-content
								class="pa-0">
								<v-list-item-title>
									Donaciones
								</v-list-item-title>
								<v-list-item-subtitle>
									<v-breadcrumbs
										class="pa-0"
										:items="[{text: 'Inicio', href: '{{route('dash')}}'}, {text: 'Donaciones'}]">
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
		<v-row>
			<v-col class="mx-3 pt-1">
				<v-card outlined>
					<v-toolbar flat>
						<v-toolbar-title>
							<v-sheet v-if="view == 1">
								<v-list>
									<v-list-item>
										<v-list-item-content>
											<v-list-item-title>
												LISTADO DE DONACIONES
											</v-list-item-title>
										</v-list-item-content>
									</v-list-item>
								</v-list>
							</v-sheet>
							<v-sheet v-else>
								<v-list>
									<v-list-item>
										<v-list-item-content>
											<v-list-item-title>
												@{{pick ? 'EDITAR DONACÓN' : 'NUEVA DONACÓN'}}
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
								v-if="same(view, 1)"
                                color="primary"
                                :disabled="wait"
                                icon>
                                <v-icon>mdi-download</v-icon>
                            </v-btn>
                            <v-btn
								v-if="same(view, 1)"
                                color="primary"
                                @click.stop="open('make')"
                                :disabled="wait"
                                icon>
                                <v-icon>mdi-plus</v-icon>
                            </v-btn>
                        </v-layout>
					</v-toolbar>
					<v-divider>
					</v-divider>
					<v-card-text class="pa-0">
						<v-sheet v-if="view == 1">
							<v-menu
								:close-on-content-click="false"
								max-height="480"
								max-width="380"
								nudge-bottom="12"
								v-model="menu.show"
								offset-y>
								<template v-slot:activator="{on, attrs}">
									<v-layout
										class="mx-0 px-2 grey lighten-4"
										row>
										<v-chip
										    v-for="chip in menu.form"
											@click:close="sort(chip, true)"
											@click.stop="show(chip)"
											class="my-2 ml-2"
											close>
											<b>@{{chip.name}}</b>: @{{chip.text}}
										</v-chip>
										<v-text-field
											prepend-inner-icon="mdi-magnify"
											background-color="grey lighten-4"
											autocomplete="off"
											append-icon="mdi-menu-down"
											label="Buscar"
											v-model="sort.text"
											:readonly="wait"
											hide-details
											single-line
											clearable
											attach
											solo
											flat>
										</v-text-field>
									</v-layout>
									<v-divider>
                                    </v-divider>
                                </template>
								<v-card>
									<v-sheet v-if="menu.pick">
										<v-sheet>
											<v-list>
												<v-list-item>
													<v-list-item-action>
														<v-btn
															@click="menu.pick = null"
															icon>
															<v-icon>mdi-arrow-left</v-icon>
														</v-btn>
													</v-list-item-action>
													<v-list-item-content>
														<v-list-item-title>
															@{{menu.pick.name}}
														</v-list-item-title>
													</v-list-item-content>
													<v-list-item-action>
														<v-btn
															@click="sort(menu.pick)"
															icon>
															<v-icon>mdi-check</v-icon>
														</v-btn>
													</v-list-item-action>
												</v-list-item>
											</v-list>
											<v-divider>
                                        	</v-divider>
                                        </v-sheet>
										<v-sheet v-if="menu.pick.type == 'text'">
											<v-text-field
												background-color="grey lighten-4"
												v-model="menu.pick.data"
												:placeholder="menu.pick.hint"
												autofocus="true"
												hide-details
												single-line
												solo
												flat>
											</v-text-field>
										</v-sheet>
										<v-sheet v-if="menu.pick.type == 'date'">
											<v-date-picker
												class="mb-2"
												width="380"
												locale="es"
												v-model="menu.pick.data"
												:picker-date.sync="menu.date"
												scrollable
												no-title>
											</v-date-picker>
										</v-sheet>
										<v-sheet v-if="menu.pick.type == 'menu'">
											<v-list>
												<v-list-item
													v-for="item in menu.pick.list"
													@click="menu.pick.data = item"
													link>
													<v-list-item-content>
														<v-list-item-title>
															@{{item.name}}
														</v-list-item-title>
													</v-list-item-content>
													<v-list-item-action v-if="menu.pick.data == item">
														<v-icon color="blue">mdi-check</v-icon>
													</v-list-item-action>
												</v-list-item>
											<v-list>
                                    	</v-sheet>
									</v-sheet>
									<v-sheet v-else>
										<v-list>
											<v-list-item class="px-6 pb-1">
												<v-list-item-icon>
													<v-icon>mdi-filter-variant</v-icon>
												</v-list-item-icon>
												<v-list-item-title>
													Filtros
												</v-list-item-title>
											</v-list-item>
											<v-divider>
                                        	</v-divider>
										</v-list>
										<v-list>
											<v-list-item
												v-for="item in menu.data"
												@click="menu.pick = {type: item.type, item: item.item, name: item.name, hint: item.hint, list: item.list, text: null, data: null}"
												link>
												<v-list-item-content>
													<v-list-item-title>@{{item.name}}</v-list-item-title>
												</v-list-item-content>
											</v-list-item>
										</v-list>
                                    </v-sheet>
								</v-card>
							</v-menu>
						</v-sheet>
						<v-sheet v-if="view == 1">
							<v-data-table
								:headers="[{sortable: false},
										   {text: 'Código', align: 'start', sortable: false},
										   {text: 'Monto', align: 'right', sortable: false},
										   {text: 'Nombre', align: 'start', sortable: false},
										   {text: 'Correo', align: 'start', sortable: false},
										   {text: 'Fecha inicio', align: 'right', sortable: false},
										   {text: 'Fecha final', align: 'right', sortable: false},
										   {sortable: false, width:  188}]"
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
												:indeterminate="(Boolean(bulk.list.length) && same((bulk.list.length == list.filter(function (item) {return item.lock ? false : true}).length), false))"
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
										<th width="160">
											<v-layout>
												<v-spacer>
												</v-spacer>
												<v-btn
													class="ml-8 mr-2"
													@click="open('edit')"
													:disabled="bulk.list.length ? false : true"
													icon>
													<v-icon>mdi-pencil</v-icon>
												</v-btn>
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
												:disabled="Boolean(item.lock)"
												@change="$event ? bulk.list.push(item) : bulk.list.splice(bulk.list.indexOf(item), 1)"
												:input-value="~bulk.list.indexOf(item)">
											</v-checkbox>
										</td>
										<td width="140">
											<div class="font-weight-medium">
												@{{item.code}}
											</div>
										</td>
										<td width="160" align="right">
											<div class="font-weight-medium">
												@{{item.load}}
											</div>
										</td>
										<td>
											<div class="font-weight-medium">
												@{{item.name}}
											</div>
										</td>
										<td>
											<div class="font-weight-medium">
												@{{item.mail}}
											</div>
										</td>
										<td width="160" align="right">
											<div class="font-weight-medium">
												@{{date(item.from, 'DD/MM/YYYY')}}
											</div>
										</td>
										<td width="160" align="right">
											<div class="font-weight-medium">
												@{{date(item.stop, 'DD/MM/YYYY')}}
											</div>
										</td>
										<td>
											<v-layout>
												<v-spacer>
												</v-spacer>
												<v-btn
													:disabled="bulk.list.length ? true : false"
													:color="item.lock ? 'red' : 'green'"
													@click="open('lock', item)"
													class="mr-2"
													icon>
													<v-icon>
														mdi-lock-reset
													</v-icon>
												</v-btn>
												<v-btn @click="open('edit', item)" class="mr-2" icon>
													<v-icon>mdi-pencil</v-icon>
												</v-btn>
												<v-btn @click="open('drop', item)" :disabled="(item.item == {{Auth::user()->id}})" icon>
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
											<p>@{{(sort.text || '').trim() ? 'No se encontraron elementos.' : 'No hay elementos disponibles.'}}</p>
										</div>
									</div>
								</template>
							</v-data-table>
						</v-sheet>
						<v-sheet v-else>
							<v-layout
								class="mx-4 my-1 mt-4"
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
								<v-sheet>
									<v-row dense>
										<v-col>
											<v-text-field
												type="text"
												label="Código"
												autocomplete="off"
												hide-details="auto"
												v-model="form.code"
												:error-messages="(fail.list.code ? [fail.list.codee] : [])"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
										<v-col>
											<v-text-field
												type="text"
												label="Monto"
												hide-details="auto"
												v-model="form.load"
												:error-messages="(fail.list.load ? [fail.list.load] : [])"
												:disabled="wait"
												autocomplete="off"
												filled>
											</v-text-field>
										</v-col>
									</v-row>
									<v-row dense>
										<v-col>
											<v-menu
												v-model="menu.from"
												:close-on-content-click="false"
												:nudge-right="40"
												transition="scale-transition"
												offset-y
												min-width="290px">
												<template v-slot:activator="{on}">
													<v-text-field
														v-on="on"
														:error-messages="(fail.list.from ? [fail.list.from] : [])"
														:value="(form.from ? date(form.from, 'DD/MM/YYYY') : '')"
														label="@lang('Fecha inicial')"
														:disabled="wait"
														readonly
														required
														filled>
													</v-text-field>
												</template>
												<v-date-picker
													v-model="form.from"
													@input="(menu.from = false)"
													locale="{{App::getLocale()}}"
													scrollable
													no-title>
												</v-date-picker>
											</v-menu>
										</v-col>
										<v-col>
											<v-menu
												v-model="menu.stop"
												:close-on-content-click="false"
												:nudge-right="40"
												transition="scale-transition"
												offset-y
												min-width="290px">
												<template v-slot:activator="{on}">
													<v-text-field
														v-on="on"
														:error-messages="(fail.list.stop ? [fail.list.stop] : [])"
														:value="(form.stop ? date(form.stop, 'DD/MM/YYYY') : '')"
														label="@lang('Fecha final')"
														:disabled="wait"
														readonly
														required
														filled>
													</v-text-field>
												</template>
												<v-date-picker
													v-model="form.stop"
													@input="(menu.stop = false)"
													locale="{{App::getLocale()}}"
													scrollable
													no-title>
												</v-date-picker>
											</v-menu>
										</v-col>
									</v-row>
									<v-row dense>
										<v-col>
											<v-text-field
												type="text"
												label="Nombre"
												autocomplete="off"
												hide-details="auto"
												v-model="form.name"
												:error-messages="(fail.list.name ? [fail.list.name] : [])"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
										<v-col>
											<v-select
												item-value="item"
												item-text="name"
												:items="[{item: 'CO', name: 'Colombia'},
														 {item: 'US', name: 'Estados Unidos'},
														 {item: 'CA', name: 'Canada'},
														 {item: 'MX', name: 'México'},
														 {item: 'CL', name: 'Chile'},
														 {item: 'AR', name: 'Argentina'},
														 {item: 'EC', name: 'Ecuador'},
														 {item: 'CA', name: 'Canada'},
														 {item: 'GB', name: 'Reino Unido'},
														 {item: 'FR', name: 'Francia'},
														 {item: 'DE', name: 'Alemania'},
														 {item: 'ES', name: 'España'},
														 {item: 'IT', name: 'Italia'}]"
												v-model="form.land"
												:error-messages="(fail.list.land ? [fail.list.land] : [])"
												label="País"
												:disabled="wait"
												hide-details="auto"
												filled> 
											</v-select>
										</v-col>
									</v-row>
									<v-row dense>
										<v-col>
											<v-text-field
												type="text"
												label="Celular"
												hide-details="auto"
												autocomplete="off"
												v-model="form.cell"
												:error-messages="(fail.list.cell ? [fail.list.cell] : [])"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
										<v-col>
											<v-text-field
												type="text"
												label="Correo"
												hide-details="auto"
												autocomplete="off"
												v-model="form.mail"
												:error-messages="(fail.list.mail ? [fail.list.mail] : [])"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
									</v-row>
									<v-row dense>
										<v-col>
											<v-textarea
												label="Nota"
												maxlength="256"
												hide-details="auto"
												autocomplete="off"
												v-model="form.note"
												:error-messages="(fail.list.note ? [fail.list.note] : [])"
												:value="form.note"
												:disabled="wait"
												auto-grow
												counter
												filled>
											</v-textarea>
										</v-col>
									</v-row>
								</v-sheet>
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

	<v-dialog v-model="show.wait" width="40%" persistent outlined tile>
        <v-card>
          	<v-card-title class="headline">Quitar bloqueo</v-card-title>
          	<v-divider></v-divider>
          	<v-card-text class="mt-4">
          		¿Desea quitar el bloqueo para registro <b>@{{pick ? pick.name : null}} @{{pick ? pick.last : null}} / @{{pick ? pick.code : null}}</b>?
          	</v-card-text>
          	<v-card-actions>
            	<v-spacer></v-spacer>
            	<v-btn color="secondary" text @click="(show.wait = false)" :disabled="wait">Cancelar</v-btn>
            	<v-btn color="red" text @click="lock(pick, true)" :disabled="wait">Quitar</v-btn>
          	</v-card-actions>
        </v-card>
	</v-dialog>

    <v-dialog v-model="show.drop" width="40%" persistent outlined tile>
        <v-card>
          	<v-card-title class="headline">Eliminar requistro</v-card-title>
          	<v-divider></v-divider>
          	<v-card-text class="mt-4">
          		¿Desea eliminar el registro <b>@{{pick ? pick.name : null}} @{{pick ? pick.last : null}} / @{{pick ? pick.code : null}}</b>?
          	</v-card-text>
          	<v-card-actions>
            	<v-spacer></v-spacer>
            	<v-btn color="secondary" text @click="(show.drop = false)" :disabled="wait">Cancelar</v-btn>
            	<v-btn color="red" text @click="drop(pick)" :disabled="wait">Eliminar</v-btn>
          	</v-card-actions>
        </v-card>
	</v-dialog>

	<v-dialog v-model="show.lock" width="40%" persistent outlined tile>
        <v-card>
            <v-card-title class="headline" dark>@{{((pick && pick.lock) ? 'Habilitar registro' : 'Bloquear registro')}}</v-card-title>
            <v-divider></v-divider>
            <v-card-text class="mt-4">
            	¿Desea @{{((pick && pick.lock) ? 'habilitar' : 'bloquear')}} el registro <b>@{{pick ? pick.name : null}} @{{pick ? pick.last : null}} / @{{pick ? pick.code : null}}</b>?
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="secondary" text @click="(show.lock = false)" :disabled="wait">Cancelar</v-btn>
                <v-btn :color="((pick && pick.lock) ? 'success' : 'error')" text @click="lock(pick, false)" :disabled="wait">@{{((pick && pick.lock) ? 'Habilitar' : 'Bloquear')}}</v-btn>
            </v-card-actions>
        </v-card>
	</v-dialog>
@stop

@section('code')
    <script type="text/javascript">
  		Vue.ready(function () {
  			var self = new Vue({
			  	vuetify: new Vuetify(),
				el: '#page',
			  	data: {
			  		wait: false,
			  		menu: false,
			  		snap: null,
			  		pick: null,
			  		sync: null,
					view: 1,
			  		take: 32,
			  		high: 0,
			  		page: 0,
			  		list: [],
					menu: {
						from: false,
						stop: false
					},
					pile: {
						role: {
							wait: false,
							text: null,
							list: []
						}
					},
			  		data: {
			  			page: 0,
			  			itemsPerPage: 32
			  		},
			  		show: {
			  			form: false,
			  			view: false,
			  			mail: false,
			  			crop: false,
			  			pass: false,
			  			wipe: false,
			  			lock: false,
			  			drop: false
			  		},
			  		fail: {
			  			text: null,
			  		    list: {}
			  		},
			  		sort: {
			  			text: null,
			  			type: null
			  		},
					bulk: {
						show: false,
						list: [],
						data: {
							note: null
						}
					},
			  		form: {
			  			code: null,
						from: null,
						stop: null,
			  			name: null,
			  			cell: null,
			  			mail: null,
			  			load: null,
			  			note: null
			  		}
			    },
			    watch: {
					'pile.role.text': (text) => {
						if (self.pile.role.time) {
							clearTimeout(self.pile.role.time);
						}

						
					},
			    	sort: {
			    		handler: function (sort) {
			    		   	if (self.time) {
				    			clearTimeout(self.time);
				    		}

				    		self.time = setTimeout(function () {
				    			self.load(self.take, null, sort.text, sort.type);
				    		}, 500);
					    },
					    deep: true
			    	},
			    	data: {
			    		handler: function (data) {
			    		   self.load((self.take = data.itemsPerPage), (self.page = data.page), self.sort.text, self.sort.type);
					    },
					    deep: true
			    	}
			    },
			    methods: {
			    	load: function (take, page, text, type, done) {
			    		axios.get("{{route('team.bonds', ['task' => 'load'])}}?take=" + (take || '') + '&page=' + (page || '') +
			    			                                                                           '&find=' + (text || ''), {})
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
			    				    axios.get(`{{route('team.bonds', ['task' => 'load'])}}/${item.hash}`, {})
							             .then(function (data) {
							            self.form = {lock: data.data.lock,
					             			         code: data.data.code,
			             			    	         name: data.data.name,
			             			    	         cell: data.data.cell,
			             			    	         mail: data.data.mail,
													 land: data.data.land,
			             			    	         load: data.data.load,
													 from: data.data.from,
													 stop: data.data.stop,
													 note: data.data.note};

										if (self.wait) {
											setTimeout(function () {
												self.wait = false;
											}, 200);
										} else {
											clearTimeout(self.time);
										}

										self.view = 2;
							        }).catch(function (fail) {
							            self.wait = false;

							            if (fail.response?.data?.text) {
			                            	self.note = {show: true,
			                            	             type: 'fail',
			                            	             text: fail.response.data.text};
			                            } else {
			                            	self.note = {show: true,
			                            	             type: 'fail',
			                            	             text: 'Se presentó un error inesperado.'};
			                            }
							        });

									self.time = setTimeout(function () {
										self.wait = true;
									}, 100);

							        self.pick = item;
			    					break;
			    				case 'crop':
			    				    var file = data.files.item(0);

			    					if (file.type.match('image.*')) {
										var reader = new FileReader();

										reader.onload = function(event) {
											self.file = event.target.result;

											self.show.crop = true;

											self.pick = item;

											if (self.snap) {
												self.snap.replace(self.file, false);
											}
										};

										reader.readAsDataURL(file);
									}
			    					break
			    				case 'wipe':
			    					self.show.wipe = true;

			    			    	self.pick = item;
			    			    	break;
			    			    case 'wait':
			    					self.show.wait = true;

			    			    	self.pick = item;
			    			    	break;
			    			    case 'lock':
			    			        self.show.lock = true;

			    			    	self.pick = item;
			    			    	break;
			    				case 'drop':
			    				    self.show.drop = true;

			    				    self.pick = item;
			    					break;
			    			}
			    		} else {
			    			switch (task) {
			    				case 'make':
			    					self.form = {lock: 0,
			    						         test: 0,
			    						         role: 0,
			    						         code: null,
									  			 nick: null,
									  			 pass: null,
									  			 last: null,
									  			 name: null,
									  			 cell: null,
									  			 mail: null,
									  			 note: null,
									  			 show: false};

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

		    		    data.append('lock', (form.lock ? 1 : 0));

		    		    data.append('code', (form.code || ''));

		    		    data.append('load', (form.load || ''));

		    		    data.append('name', (form.name || ''));

		    	        data.append('cell', (form.cell || ''));

		    	        data.append('mail', (form.mail || ''));

						data.append('land', (form.land || ''));

						data.append('from', (form.from || ''));

		    	        data.append('stop', (form.stop || ''));

		    	        data.append('note', (form.note || ''));

		    	        if (item) {
				    		axios.post("{{route('team.bonds', ['task' => 'save'])}}/" + item.hash, data, {'X-CSRF-TOKEN': '{{csrf_token()}}'})
	                             .then(function (data) {
								self.view = 1;

	                            self.wait = false;
								
	                            self.note = {show: true,
                                	         type: 'done',
                                	         text: data.data.text};

                                item.type = form.type;

                                item.code = form.code;

                                item.nick = form.nick;

                                item.last = form.last;

                                item.name = form.name;

				    	        item.cell = form.cell;

				    	        item.mail = form.mail;

				    	        item.note = form.note;
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
				    		axios.post("{{route('team.bonds', ['task' => 'make'])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
	                             .then(function (data) {
	                            self.list.unshift((item = {item: data.data.item,
                                	                       hash: data.data.hash,
                                	                       code: data.data.code,
                                	                       tone: data.data.tone,
                                	                       face: data.data.face,
                            		                       lock: form.lock,
                            		                       type: form.type,
                            		                       nick: form.nick,
                            		                       last: form.last,
                            		                       name: form.name,
                            		                       mail: form.mail,
                            		                       seen: null}));

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
			    	lock: function (item, wait) {
			    		axios.get("{{route('team.bonds')}}/" + (wait ? 'wait' : 'lock') + '/' + item.hash, {})
				             .then(function (data) {
				            self.wait = false;

				            self.show.lock = false;

				            self.show.wait = false;

				            item.wait = wait ? null :
				                               item.wait;

			             	item.lock = wait ? item.lock :
			             	                   data.data.lock;

		             		self.note = {show: true,
                            	         type: 'done',
                            	         text: data.data.text};
				        }).catch(function (fail) {
				            self.wait = false;

				            self.show.lock = false;

				            self.show.wait = false;

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
			    		axios.get("{{route('team.bonds', ['task' => 'drop'])}}/" + item.hash, {})
				             .then(function (data) {
				            self.wait = false;

				            self.show.drop = false;

			             	self.list.splice(self.list.indexOf(item), 1);

		             		self.note = {show: true,
                            	         type: 'done',
                            	         text: data.data.text};
				        }).catch(function (fail) {
				            self.wait = false;

				            self.show.drop = false;

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
			    	this.load(this.take, this.page, this.sort.text, this.sort.type, function (fail) {
	             		setTimeout(function () {
	             			self.done = true;
	             		}, 500);
	             	});
			    }
			})
  		});
  	</script>
@stop