@extends('team')

@section('page')
	<v-layout column>
		<v-row class="ma-0">
			<v-col class="pa-0">
				<v-layout
					class="white px-2"
					align-center>
					<v-list>
						<v-list-item class="px-1">
							<v-list-item-avatar
                                color="#FFB300"
                                size="36"
                                tile>
								<v-icon color="white">
                                    mdi-shovel
								</v-icon>
							</v-list-item-avatar>
							<v-list-item-content class="pa-0">
								<v-list-item-title>
									Obras
								</v-list-item-title>
								<v-list-item-subtitle>
									<v-breadcrumbs
										class="pa-0"
										:items="[{text: 'Inicio', href: '{{route('dash')}}'}, {text: 'Obras'}]">
									</v-breadcrumbs>
								</v-list-item-subtitle>
							</v-list-item-content>
						</v-list-item>
					</v-list>
					<v-spacer>
					</v-spacer>
					<v-btn
						:disabled="wait"
						title="Configuración"
						icon>
						<v-icon>mdi-cog</v-icon>
					</v-btn>
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
							<v-sheet v-if="same(view, 1)">
								<v-list>
									<v-list-item>
										<v-list-item-content>
											<v-list-item-title class="text-uppercase">
												Listado de obras
											</v-list-item-title>
										</v-list-item-content>
									</v-list-item>
								</v-list>
							</v-sheet>
							<v-sheet v-else>
								<v-list>
									<v-list-item>
										<v-list-item-content>
											<v-list-item-title class="text-uppercase">
												@{{pick ? 'Editar obra' : 'Nueva obra'}}
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
                                @click.stop="open('make')"
                                :disabled="wait"
								title="Nuevo"
                                icon>
                                <v-icon>mdi-plus</v-icon>
                            </v-btn>
                            <v-btn
								v-if="same(view, 1)"
                                :disabled="wait"
								title="Descargar"
                                icon>
                                <v-icon>mdi-download</v-icon>
                            </v-btn>
							<v-menu
							    :close-on-content-click="false"
								transition="slide-y-transition"
								min-width="360"
								max-width="360"
								nudge-right="1"
								nudge-top="-7"
								v-if="same(view, 1)"
								offset-y
								flat
								left>
								<template v-slot:activator="{on, attrs}">
									<v-btn
										v-bind="attrs"
										v-on="on"
										:disabled="wait"
										title="Filtro"
										icon>
										<v-icon style="width: 16px">
											mdi-filter
										</v-icon>
										<v-icon class="ma-0" style="width: 8px" right>
											mdi-menu-down
										</v-icon>
									</v-btn>
								</template>
								<v-card>
									<v-sheet v-if="pipe.pick">
										<v-sheet>
											<v-list>
												<v-list-item>
													<v-list-item-action>
														<v-btn
															@click="pipe.show = same(pipe.edit && none(pipe.pick.back), false)
															        pipe.pick = pipe.pick.back"
															icon>
															<v-icon>@{{((pipe.edit && none(pipe.pick.back)) ? 'mdi-close' : 'mdi-arrow-left')}}</v-icon>
														</v-btn>
													</v-list-item-action>
													<v-list-item-content>
														<v-list-item-title>
															@{{pipe.pick.name}}
														</v-list-item-title>
													</v-list-item-content>
													<v-list-item-action>
														<v-layout
															justify-center
															align-center>
															<v-btn
																:disabled="none(find(pipe.form, 'nick', pipe.pick.nick))"
																@click="pipe.sort(pipe.pick, true)"
																icon>
																<v-icon>mdi-delete</v-icon>
															</v-btn>
															<v-btn
																:disabled="(none(pipe.pick.data) || (same(none(pipe.pick.test), false) && none(test(pipe.pick.test, trim(pipe.pick.data)))))"
																@click="pipe.sort(pipe.pick)"
																icon>
																<v-icon>mdi-check</v-icon>
															</v-btn>
                                                        </v-layout>
													</v-list-item-action>
												</v-list-item>
											</v-list>
											<v-divider>
                                        	</v-divider>
                                        </v-sheet>
										<v-sheet v-if="same(pipe.pick.type, 'text')">
											<v-text-field
												background-color="grey lighten-4"
                                                clear-icon="mdi-close-circle"
												v-model="pipe.pick.data"
												:placeholder="pipe.pick.hint"
												hide-details
												single-line
                                                clearable
                                                autofocus
												solo
												flat>
											</v-text-field>
										</v-sheet>
										<v-sheet v-if="same(pipe.pick.type, 'date')">
											<v-date-picker
												class="mb-2"
												width="380"
												locale="es"
												v-model="pipe.pick.data"
												scrollable
												no-title
                                                range>
											</v-date-picker>
										</v-sheet>
                                        <v-sheet v-if="same(pipe.pick.type, 'menu')">
											<v-list>
												<template v-for="(data, item) in pipe.pick.list">
													<v-divider v-if="data.line">
                                                    </v-divider>
													<v-list-item
														@click="((~(item = pipe.pick.data.indexOf(data))) ? (same(pipe.pick.data.length, 1) || pipe.pick.data.splice(item, 1)) : (pipe.pick.once ? (pipe.pick.data = [data]) : pipe.pick.data.push(data)))"
														link>
														<v-list-item-content>
															<v-list-item-title>
																@{{data.text}}
															</v-list-item-title>
														</v-list-item-content>
														<v-list-item-action v-if="(~pipe.pick.data.indexOf(data))">
															<v-icon color="blue">mdi-check</v-icon>
														</v-list-item-action>
													</v-list-item>
                                                </template>
												<template v-if="pipe.pick.none">
													<v-divider>
													</v-divider>
													<v-list-item
														@click="pipe.pick = {type: pipe.pick.none.type, hint: pipe.pick.none.hint, list: pipe.pick.none.list, nick: pipe.pick.nick, name: pipe.pick.name, back: pipe.pick, text: null, data: []}"
														link>
														<v-list-item-icon>
															<v-icon>@{{pipe.pick.none.icon}}</v-icon>
														</v-list-item-icon>
														<v-list-item-content>
															<v-list-item-title>
																@{{pipe.pick.none.text}}
															</v-list-item-title>
														</v-list-item-content>
														<v-list-item-icon>
															<v-icon>mdi-chevron-right</v-icon>
														</v-list-item-icon>
													</v-list-item>
												</template>
                                            </v-list>
                                    	</v-sheet>
                                        <v-sheet v-if="same(pipe.pick.type, 'list')">
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
											<v-sheet
												v-if="none(pipe.pick.list, (pipe) => (none(pipe.pick.load(pipe.pick.find, pipe.pick.take, pipe.pick.page, (list) => (pipe.pick.list = list),  find(pipe.form, 'nick', pipe.pick.seek)))), false, pipe)"
												align="center">
												<v-avatar
													color="grey lighten-1"
													class="mt-4" 
													size="64">
													<v-icon
														size="42"
														dark>
														mdi-inbox
													</v-icon>
												</v-avatar>
												<v-list>
													<v-list-item>
														<v-list-item-content>
															<v-list-item-title>
																Sin elementos
															</v-list-item-title>
															<v-list-item-subtitle>
																No se encontraron elementos.
															</v-list-item-subtitle>
														</v-list-item-content>
													</v-list-item>
												</v-list>
											</v-sheet>
                                            <v-list
												class="overflow-y-auto"
												max-height="480"
												v-else>
                                                <v-list-item
													v-for="item in pipe.pick.list"
													:key="pipe.pick.item(item)"
													@click="(pipe.pick.data = item)"
													link>
													<v-list-item-content>
														<v-list-item-title>
															@{{pipe.pick.text(item)}}
														</v-list-item-title>
                                                        <v-list-item-subtitle v-if="pipe.pick.note">
															@{{pipe.pick.note(item)}}
														</v-list-item-subtitle>
													</v-list-item-content>
													<v-list-item-action>
														<v-icon
															v-show="same(pipe.pick.data, item)"
															color="blue">
															mdi-check
														</v-icon>
													</v-list-item-action>
												</v-list-item>
                                            </v-list>
                                        </v-sheet>
									</v-sheet>
									<v-sheet v-else>
										<v-list>
											<v-list-item class="px-6 pb-1">
												<v-list-item-icon>
													<v-icon>mdi-filter-variant</v-icon>
												</v-list-item-icon>
												<v-list-item-title>
                                                    <v-layout wrap align-center>
														Lista de filtros
                                                        <v-spacer>
                                                        </v-spacer>
                                                        <v-btn
                                                            :disabled="none(pipe.from)"
                                                            @click="(pipe.from = (pipe.from - pipe.size))"
                                                            class="mr-2"
                                                            icon>
                                                            <v-icon>mdi-chevron-left</v-icon>
                                                        </v-btn>
                                                        @{{clip(pipe.from + pipe.size, 1, pipe.data.length)}}&nbsp;<span class="text--disabled">de</span>&nbsp;@{{pipe.data.length}}
                                                        <v-btn
                                                            :disabled="same(clip((pipe.from + pipe.size), 1, pipe.data.length), pipe.data.length)"
                                                            @click="(pipe.from = pipe.from + pipe.size)"
                                                            class="ml-2"
                                                            icon>
                                                            <v-icon>mdi-chevron-right</v-icon>
                                                        </v-btn>
                                                    </v-layout>
												</v-list-item-title>
											</v-list-item>
											<v-divider>
                                        	</v-divider>
										</v-list>
										<v-list>
											<v-list-item
												v-for="(data, item) in pipe.data.slice(pipe.from, pipe.from + pipe.size)"
												:key="item.nick"
												@click="pipe.pick = {type: data.type,
                                                                     nick: data.nick,
                                                                     name: data.name,
                                                                     hint: data.hint,
                                                                     list: data.list,
																	 wipe: data.wipe,
                                                                     none: data.none,
                                                                     once: data.once,
                                                                     load: data.load,
																	 item: data.item,
                                                                     text: data.text,
                                                                     note: data.note,
                                                                     test: data.test,
                                                                     take: data.take,
																	 seek: data.seek,
                                                                     data: find(pipe.form, 'nick', data.nick, 'data', [])}"
												link>
                                                <v-list-item-avatar>
                                                    <v-icon class="grey lighten-4">
                                                        @{{data.icon}}
                                                    </v-icon>
                                                </v-list-item-avatar>
												<v-list-item-content>
													<v-list-item-title>
                                                        @{{data.name}}
                                                    </v-list-item-title>
													<v-list-item-subtitle class="text-truncate">
														@{{find(pipe.form, 'nick', data.nick, 'text')}}
													</v-list-item-subtitle>
												</v-list-item-content>
                                                <v-list-item-icon>
                                                    <v-icon>mdi-chevron-right</v-icon>
                                                </v-list-item-icon>
											</v-list-item>
										</v-list>
                                    </v-sheet>
								</v-card>
							</v-menu>
                        </v-layout>
					</v-toolbar>
					<v-divider>
					</v-divider>
					<v-card-text class="pa-0">
						<v-sheet v-if="same(view, 1)">
							<v-text-field
								prepend-inner-icon="mdi-magnify"
								background-color="grey lighten-4"
								autocomplete="off"
								clear-icon="mdi-close-circle"
								label="Buscar"
								v-model="seek"
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
						<v-sheet v-if="same(view, 1)">
							<v-data-table
								:headers="none(tiny, [
									{text: 'Obra', align: 'start', sortable: false},
                                    {text: 'Completado', width: 320, align: 'start', sortable: false},
									{text: 'Duración', width: 140, align: 'center', sortable: false},
									{text: 'Inicio', width: 140, align: 'end', sortable: false},
                                    {text: 'Terminación', width: 160, align: 'end', sortable: false},
                                    {text: 'Creación', width: 180, align: 'end', sortable: false}
								], [
									{text: 'Proyecto', align: 'start', sortable: false}
								])"
								:items="data.list"
								:server-items-length="data.size"
								:footer-props="{
									showFirstLastPage: true,
									itemsPerPageOptions: [16, 32, 64],
									pageText: '{0} - {1} de {2}',
									firstIcon: 'mdi-page-first',
									lastIcon: 'mdi-page-last',
									prevIcon: 'mdi-chevron-left',
									nextIcon: 'mdi-chevron-right',
									itemsPerPageText: 'Filas por página'
								}"
								:options.sync="page"
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
										<th width="40">
											<v-menu
												v-if="tiny"
												transition="slide-y-transition"
												min-width="260"
												max-width="260"
												offset-y>
												<template v-slot:activator="{on, attrs}">
													<v-btn
														v-bind="attrs"
														v-on="on"
														:disabled="wait"
														icon>
														<v-icon>mdi-dots-vertical</v-icon>
													</v-btn>
												</template>
												<v-list>
                                                    <v-list-item
														@click="open('edit')"
														:disabled="bulk.list.length ? false : true">
														<v-list-item-icon>
															<v-icon>mdi-pencil</v-icon>
														</v-list-item-icon>
														<v-list-item-title>Editar</v-list-item-title>
													</v-list-item>
													<v-list-item
														@click="open('drop')"
														:disabled="bulk.list.length ? false : true">
														<v-list-item-icon>
															<v-icon>mdi-delete</v-icon>
														</v-list-item-icon>
														<v-list-item-title>Eliminar</v-list-item-title>
													</v-list-item>
												</v-list>
											</v-menu>
											<v-layout v-else>
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
												:disabled="wait"
												@change="$event ? bulk.list.push(item) : bulk.list.splice(bulk.list.indexOf(item), 1)"
												:input-value="~bulk.list.indexOf(item)">
											</v-checkbox>
										</td>
										<td>
                                            <v-list>
												<v-list-item>
                                                    <v-list-item-content>
                                                        <v-list-item-title>
                                                            @{{item.name ?? item.work}}
                                                        </v-list-item-title>
                                                        <v-list-item-subtitle v-if="item.name">
                                                            @{{item.work}}
                                                        </v-list-item-subtitle>
                                                    </v-list-item-content>
												</v-list-item>
											</v-list>
										</td>
										<td v-if="none(tiny)">
											<div class="text--primary">
												@{{item.step}}
											</div>
											<v-progress-linear
												class="mt-1"
												:value="same(item.mode, 1, item.load, (item) => (item.load * 100 / item.size), item)"
												class="mt-1" />
										</td>
										<td
											v-if="none(tiny)"
											class="text-center">
                                            <div
                                                class="text--primary"
                                                :style="`color: ${((date(item.open, new Date(), 'days') > 0) ? ((date(item.open, none(item.date, new Date(), item.date), 'days') > date(item.open, item.stop, 'days')) ? '#E53936' : ((date(item.open, none(item.date, new Date(), item.date), 'days') < date(item.open, item.stop, 'days')) ? '#43B048' : '#01579B')) : '#9E9E9E')}!important`">
                                                @{{((date(item.open, new Date(), 'days') > 0) ? date(item.open, none(item.date, new Date(), item.date), 'days') : 0)}} / días
											</div>
										</td>
                                        <td
											v-if="none(tiny)"
											class="text-end">
											<div class="text--primary">
												@{{date(item.open, 'DD/MM/YYYY')}}
											</div>
										</td>
                                        <td
											v-if="none(tiny)"
											class="text-end">
											<div class="text--primary">
												@{{date(item.stop, 'DD/MM/YYYY')}}
											</div>
										</td>
                                        <td
											v-if="none(tiny)"
											class="text-end">
											<div class="text--primary">
												@{{date(item.made, 'DD/MM/YYYY hh:mm:ss')}}
											</div>
                                            <div
												v-if="item.skip"
											 	class="text--secondary">
												@{{item.skip}}
											</div>
										</td>
										<td>
											<v-menu
												v-if="tiny"
												transition="slide-y-transition"
												min-width="260"
												max-width="260"
												offset-y
												left>
												<template v-slot:activator="{on, attrs}">
													<v-btn
														v-bind="attrs"
														v-on="on"
														:disabled="wait"
														icon>
														<v-icon>mdi-dots-vertical</v-icon>
													</v-btn>
												</template>
												<v-list>
                                                    <v-list-item
														@click="open('data', item)">
														<v-list-item-icon>
															<v-icon>mdi-view-compact</v-icon>
														</v-list-item-icon>
														<v-list-item-title>Detalles</v-list-item-title>
													</v-list-item>
													<v-list-item
														@click="open('edit', item)">
														<v-list-item-icon>
															<v-icon>mdi-pencil</v-icon>
														</v-list-item-icon>
														<v-list-item-title>Editar</v-list-item-title>
													</v-list-item>
													<v-divider>
													</v-divider>
													<v-list-item
														@click="open('drop', item)">
														<v-list-item-icon>
															<v-icon>mdi-delete</v-icon>
														</v-list-item-icon>
														<v-list-item-title>Eliminar</v-list-item-title>
													</v-list-item>
												</v-list>
											</v-menu>
											<v-layout v-else>
												<v-spacer>
												</v-spacer>
												<v-btn
													target="_blank"
													:disabled="wait"
													@click="open('data', item)"
													title="Dtealles"
													icon>
													<v-icon>mdi-view-compact</v-icon>
												</v-btn>
												<v-btn
													:disabled="Boolean(bulk.list.length)"
													@click="open('edit', item)"
													title="Editar"
													icon>
													<v-icon>mdi-pencil</v-icon>
												</v-btn>
												<v-btn
													:disabled="Boolean(bulk.list.length)"
													@click="open('drop', item)"
													title="Eliminar"
													icon>
													<v-icon>mdi-delete</v-icon>
												</v-btn>
											</v-layout>
										</td>
									</tr>
								</template>
								<template v-slot:loading>
									<v-sheet v-if="wait">
										<v-avatar
											color="grey lighten-1"
											class="mt-6" 
											size="64">
											<v-icon
												size="42"
												dark>
												mdi-download-network
											</v-icon>
										</v-avatar>
										<v-list>
											<v-list-item>
												<v-list-item-content>
													<v-list-item-title>
														Cargando
													</v-list-item-title>
													<v-list-item-subtitle>
														Por favor espere.
													</v-list-item-subtitle>
												</v-list-item-content>
											</v-list-item>
										</v-list>
									</v-sheet>
									<v-sheet v-else>
										<v-avatar
											color="grey lighten-1"
											class="mt-6" 
											size="64">
											<v-icon
												size="42"
												dark>
												mdi-inbox
											</v-icon>
										</v-avatar>
										<v-list>
											<v-list-item>
												<v-list-item-content>
													<v-list-item-title>
														Sin registros
													</v-list-item-title>
													<v-list-item-subtitle>
														No se encontraron registros.
													</v-list-item-subtitle>
												</v-list-item-content>
											</v-list-item>
										</v-list>
									</v-sheet>
								</template>
							</v-data-table>
						</v-sheet>
						<v-sheet v-else>
							<v-layout
								class="mx-4 my-1 mt-4"
								column>
								<v-sheet>
									<v-row dense>
                                        <v-col :cols="none(tiny, 6, 12)">
                                            <v-autocomplete
												:items="pile.work.list"
												item-value="item"
												no-data-text="No hay opciones"
												label="Proyecto"
                                                class="bind"
												v-model="form.bind"
												@change="(form.file = [])"
												hide-details="auto"
												:filter="(item, find, text) => (((~item.code.indexOf(find.toLocaleUpperCase())) || (~item.name.toLocaleLowerCase().indexOf(find.toLocaleLowerCase()))))"
												:error-messages="[fail?.list?.bind].filter(Boolean)"
												:disabled="wait"
												filled>
												<template v-slot:selection="data">
													@{{data.item.name}}
												</template>
												<template v-slot:item="data">
													<v-list-item-content>
														<v-list-item-title>@{{data.item.name}}</v-list-item-title>
                                                        <v-list-item-subtitle>@{{data.item.code}}</v-list-item-title>
													</v-list-item-content>
												</template>
											</v-autocomplete>
										</v-col>
                                        <v-col
                                            v-if="same((pile.work.list?.find((item) => (same(item.item, form.bind)))?.once ?? 1), 0)"
                                            :cols="none(tiny, 3, 12)">
                                            <v-autocomplete
												:items="pile.unit.list?.filter((item) => (same(item.bind, form.bind)))"
												item-value="item"
												no-data-text="No hay opciones"
												label="Unidad"
                                                class="bind"
												v-model="form.pick"
												hide-details="auto"
												:filter="(item, find, text) => (((~item.code.indexOf(find.toLocaleUpperCase())) || (~item.name.toLocaleLowerCase().indexOf(find.toLocaleLowerCase()))))"
												:error-messages="[fail?.list?.pick].filter(Boolean)"
												:disabled="wait"
												filled>
												<template v-slot:selection="data">
													@{{data.item.code}}
												</template>
												<template v-slot:item="data">
													<v-list-item-content>
														<v-list-item-title>@{{data.item.code}}</v-list-item-title>
													</v-list-item-content>
												</template>
											</v-autocomplete>
                                        </v-col>
                                        <v-col :cols="none(tiny, same((pile.work.list?.find((item) => (same(item.item, form.bind)))?.once ?? 1), 0, 3, 6), 12)">
                                            <v-autocomplete
												:items="pile.step.list?.filter((item) => (same(item.bind, form.bind)))"
												item-value="item"
												no-data-text="No hay opciones"
												label="Fase"
                                                class="bind"
												@change="(form.file = [])"
												v-model="form.next"
												hide-details="auto"
												:filter="(item, find, text) => (((~item.code.indexOf(find.toLocaleUpperCase())) || (~item.name.toLocaleLowerCase().indexOf(find.toLocaleLowerCase()))))"
												:error-messages="[fail?.list?.next].filter(Boolean)"
												:disabled="wait"
												filled>
												<template v-slot:selection="data">
													@{{data.item.name}}
												</template>
												<template v-slot:item="data">
													<v-list-item-content>
														<v-list-item-title>@{{data.item.name}}</v-list-item-title>
													</v-list-item-content>
												</template>
											</v-autocomplete>
										</v-col>
									</v-row>
                                    <v-row dense>
                                        <v-col :cols="none(tiny, 6, 12)">
											<v-menu
											    :close-on-content-click="false"
												v-model="show.menu.form.open"
												transition="scale-transition"
												min-width="290px"
												nudge-right="20"
												nudge-top="-5"
												offset-y>
												<template v-slot:activator="{on}">
													<v-text-field
														v-on="on"
														:error-messages="[fail?.list?.open].filter(Boolean)"
														hide-details="auto"
														class="bind"
														:value="(form.open ? date(form.open, 'DD/MM/YYYY') : '')"
														label="@lang('Fecha de inicio')"
														:disabled="wait"
														readonly
														required
														filled>
													</v-text-field>
												</template>
												<v-date-picker
													v-model="form.open"
													@input="(show.menu.form.open = false)"
													@change="alert('Hola')"
													locale="{{App::getLocale()}}"
													scrollable
													no-title>
												</v-date-picker>
											</v-menu>
										</v-col>
                                        <v-col :cols="none(tiny, 6, 12)">
											<v-menu
											    :close-on-content-click="false"
												v-model="show.menu.form.stop"
												transition="scale-transition"
												min-width="290px"
												nudge-right="20"
												nudge-top="-5"
												offset-y>
												<template v-slot:activator="{on}">
													<v-text-field
														v-on="on"
														:error-messages="[fail?.list?.stop].filter(Boolean)"
														hide-details="auto"
														:class="{bind: same(none(pick), false)}"
														:value="(form.stop ? date(form.stop, 'DD/MM/YYYY') : '')"
														label="@lang('Fecha de finalización')"
														:disabled="wait"
														readonly
														required
														filled>
													</v-text-field>
												</template>
												<v-date-picker
													v-model="form.stop"
													@input="(show.menu.form.stop = false)"
													locale="{{App::getLocale()}}"
													scrollable
													no-title>
												</v-date-picker>
											</v-menu>
										</v-col>
									</v-row>
                                    <v-row>
										<v-col>
											<v-sheet class="text-button">
												Detalles
											</v-sheet>
											<v-divider>
											</v-divider>
										</v-col>
								    </v-row>
									<v-row dense>
										<v-col>
											<v-textarea
												label="Detalles"
												maxlength="1024"
												hide-details="auto"
												autocomplete="off"
												v-model="form.note"
												:error-messages="[fail?.list?.note].filter(Boolean)"
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

	<v-dialog
		v-model="show.spot"
		:width="none(tiny, 768, '100%')"
		persistent
        scrollable
		outlined
		tile>
		<v-card>
            <v-card-title class="pa-0">
				<v-list class="flex">
					<v-list-item>
						<v-list-item-avatar tile>
							<v-icon class="grey lighten-4">
								mdi-map
							</v-icon>
						</v-list-item-avatar>
						<v-list-item-content>
							<v-list-item-title>
                                Ubicación
							</v-list-item-title>
							<v-list-item-subtitle>
                                Ubicación dónde se registró el avance.
							</v-list-item-subtitle>
						</v-list-item-content>
                        <v-list-item-action>
                            <v-btn
                                @click="(show.spot = false)"
                                :disabled="wait"
                                icon>
					            <v-icon>mdi-close</v-icon>
				            </v-btn>
                        </v-list-item-action>
					</v-list-item>
				</v-list>
            </v-card-title>
          	<v-divider>
            </v-divider>
			<v-card-text class="px-4 py-4">
        		<v-row dense>
        			<v-col>
						<div
							id="plat"
							class="mark"
							:style="{height: none(tiny, '480px', '360px')}">
						</div>
        			</v-col>
        		</v-row>
			</v-card-text>
			<v-divider>
            </v-divider>
			<v-card-actions class="grey lighten-4">
				<v-spacer>
				</v-spacer>
				<v-btn
                    v-if="none(tiny)"
					color="secondary"
					@click="(show.spot = false)"
					:disabled="wait"
					tile
					text>
					Cerrar
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>

	<v-dialog
		v-model="show.data"
		:width="none(tiny, 840, '100%')"
		persistent
        scrollable
		outlined
		tile>
		<v-card>
            <v-card-title class="pa-0">
				<v-list class="flex">
					<v-list-item>
						<v-list-item-avatar tile>
							<v-icon class="grey lighten-4">
								mdi-view-compact
							</v-icon>
						</v-list-item-avatar>
						<v-list-item-content>
							<v-list-item-title>
                                Detalles
							</v-list-item-title>
							<v-list-item-subtitle>
                                Todos los detalles del avance
							</v-list-item-subtitle>
						</v-list-item-content>
                        <v-list-item-action>
                            <v-btn
                                @click="(show.data = false)"
                                :disabled="wait"
                                icon>
					            <v-icon>mdi-close</v-icon>
				            </v-btn>
                        </v-list-item-action>
					</v-list-item>
				</v-list>
				<v-tabs v-model="pane">
					<v-tab>General</v-tab>
					<v-tab>Adjuntos</v-tab>
				</v-tabs>
            </v-card-title>
          	<v-divider>
            </v-divider>
			<v-card-text>
				<v-tabs-items
					class="pt-5"
					v-model="pane">
					<v-tab-item>
						<v-row dense>
							<v-col>
								<v-sheet outlined>
									<v-layout column>
										<v-row class="mx-0">
											<v-col
												class="grey lighten-4"
												:cols="none(tiny, 4, 12)">
												Fase
											</v-col>
											<v-col :cols="none(tiny, 8, 12)">
												<div class="font-weight-medium">
													@{{pick?.step}}
												</div>
											</v-col>
										</v-row>
										<v-divider>
										</v-divider>
										<v-row class="mx-0">
											<v-col
												class="grey lighten-4"
												:cols="none(tiny, 4, 12)">
												Costo
											</v-col>
											<v-col :cols="none(tiny, 8, 12)">
												<div class="font-weight-medium">
													$@{{text(((pick?.cost * ((same(pick?.mode, 1, ((pick?.load / pick?.size) * 100), pick?.load) * pick?.rate) / 100)) / 100))}}
												</div>
											</v-col>
										</v-row>
										<v-divider>
										</v-divider>
										<v-row class="mx-0">
											<v-col
												class="grey lighten-4"
												:cols="none(tiny, 4, 12)">
												Completado
											</v-col>
											<v-col :cols="none(tiny, 8, 12)">
												<div class="font-weight-medium">
													@{{pick?.load}} @{{same(pick?.mode, 2, '%', ` de ${pick?.size} ${pick?.unit}`)}}
												</div>
											</v-col>
										</v-row>
										<v-divider>
										</v-divider>
										<v-row class="mx-0">
											<v-col
												class="grey lighten-4"
												:cols="none(tiny, 4, 12)">
												Unidad
											</v-col>
											<v-col :cols="none(tiny, 8, 12)">
												<div class="font-weight-medium">
													@{{pick?.code ?? pick?.work}}
												</div>
											</v-col>
										</v-row>
										<template v-if="same(pick?.sort, 2)">
											<v-divider>
											</v-divider>
											<v-row class="mx-0">
												<v-col
													class="grey lighten-4"
													:cols="none(tiny, 4, 12)">
													Proyecto
												</v-col>
												<v-col :cols="none(tiny, 8, 12)">
													<div class="font-weight-medium">
														@{{pick?.work}}
													</div>
												</v-col>
											</v-row>
										</template>
										<template v-if="pick?.town">
											<v-divider>
											</v-divider>
											<v-row class="mx-0">
												<v-col
													class="grey lighten-4"
													:cols="none(tiny, 4, 12)">
													Municipio
												</v-col>
												<v-col :cols="none(tiny, 8, 12)">
													<div class="font-weight-medium">
														@{{pick?.town}}
													</div>
												</v-col>
											</v-row>
										</template>
										<v-divider>
										</v-divider>
										<v-row class="mx-0">
											<v-col
												class="grey lighten-4"
												:cols="none(tiny, 4, 12)">
												Aprobado
											</v-col>
											<v-col :cols="none(tiny, 8, 12)">
												<div :class="({0: 'grey--text', 1: 'green--text', 2: 'red--text'}[pick?.pass])">
													@{{({0: 'Pendiente', 1: 'Sí', 2: 'No'}[pick?.pass])}}
												</div>
											</v-col>
										</v-row>
										<v-divider>
										</v-divider>
										<v-row class="mx-0">
											<v-col
												class="grey lighten-4"
												:cols="none(tiny, 4, 12)">
												Detalles
											</v-col>
											<v-col :cols="none(tiny, 8, 12)">
												<div :class="none(pick?.note, 'grey--text', 'font-weight-medium')">
													@{{none(pick?.note, 'Ninguno', pick?.note)}}
												</div>
											</v-col>
										</v-row>
									</v-layout>
								</v-sheet>
							</v-col>
						</v-row>
						<v-row dense>
							<v-col>
								<v-sheet outlined>
									<v-layout column>
										<v-row class="mx-0">
											<v-col
												class="grey lighten-4"
												:cols="none(tiny, 4, 12)">
												Registro
											</v-col>
											<v-col :cols="none(tiny, 8, 12)">
												<div class="font-weight-medium">
													@{{pick?.hash}}
												</div>
											</v-col>
										</v-row>
										<v-divider>
										</v-divider>
										<v-row class="mx-0">
											<v-col
												class="grey lighten-4"
												:cols="none(tiny, 4, 12)">
												Modificación
											</v-col>
											<v-col :cols="none(tiny, 8, 12)">
												<div
													class="font-weight-medium"
													:class="none(pick?.mark, 'grey--text', 'back--text')">
													@{{none(pick?.mark, 'Nunca', (pick) => (date(pick.mark, 'DD/MM/YYYY hh:mm:ss')), pick)}}
												</div>
											</v-col>
										</v-row>
										<v-divider>
										</v-divider>
										<v-row class="mx-0">
											<v-col
												class="grey lighten-4"
												:cols="none(tiny, 4, 12)">
												Creación
											</v-col>
											<v-col :cols="none(tiny, 8, 12)">
												<div class="font-weight-medium">
													@{{date(pick?.made, 'DD/MM/YYYY hh:mm:ss')}}
												</div>
											</v-col>
										</v-row>
										<v-divider>
										</v-divider>
										<v-row class="mx-0">
											<v-col
												class="grey lighten-4"
												:cols="none(tiny, 4, 12)">
												Usuario
											</v-col>
											<v-col :cols="none(tiny, 8, 12)">
												<div class="font-weight-medium">
													@{{pick?.skip}}
												</div>
											</v-col>
										</v-row>
									</v-layout>
								</v-sheet>
							</v-col>
						</v-row>
					</v-tab-item>
					<v-tab-item>
						<v-row dense>
							<v-col>
								<v-sheet outlined>
									<v-data-table
										:headers="[
											{text: 'Nombre', align: 'start', sortable: false},
											{sortable: false}
										]"
										:items="pick?.file"
										hide-default-header
										hide-default-footer
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
												<td>
													<v-list>
														<v-list-item>
															<v-list-item-content>
																<v-list-item-title>
																	@{{item.name}}
																</v-list-item-title>
																<v-list-item-subtitle>
																	@{{item.item}}
																</v-list-item-subtitle>
															</v-list-item-content>
														</v-list-item>
													</v-list>
												</td>
												<td>
													<v-layout>
														<v-spacer>
														</v-spacer>
														<v-btn
															target="_blank"
															:disabled="wait"
															@click="open('open', `/files/${item.hash}`)"
															title="Descargar"
															icon>
															<v-icon>mdi-download</v-icon>
														</v-btn>
													</v-layout>
												</td>
											</tr>
										</template>
										<template v-slot:loading>
											<v-sheet v-if="wait">
												<v-avatar
													color="grey lighten-1"
													class="mt-6" 
													size="64">
													<v-icon
														size="42"
														dark>
														mdi-download-network
													</v-icon>
												</v-avatar>
												<v-list>
													<v-list-item>
														<v-list-item-content>
															<v-list-item-title>
																Cargando
															</v-list-item-title>
															<v-list-item-subtitle>
																Por favor espere.
															</v-list-item-subtitle>
														</v-list-item-content>
													</v-list-item>
												</v-list>
											</v-sheet>
											<v-sheet v-else>
												<v-avatar
													color="grey lighten-1"
													class="mt-6" 
													size="64">
													<v-icon
														size="42"
														dark>
														mdi-inbox
													</v-icon>
												</v-avatar>
												<v-list>
													<v-list-item>
														<v-list-item-content>
															<v-list-item-title>
																Sin registros
															</v-list-item-title>
															<v-list-item-subtitle>
																No se encontraron registros.
															</v-list-item-subtitle>
														</v-list-item-content>
													</v-list-item>
												</v-list>
											</v-sheet>
										</template>
									</v-data-table>
								</v-sheet>
							</v-col>
						</v-row>
					</v-tab-item>
				</v-tabs-items>
			</v-card-text>
			<v-divider>
			</v-divider>
			<v-card-actions class="grey lighten-4">
				<v-spacer>
				</v-spacer>
				<v-btn
                    v-if="none(tiny)"
					color="secondary"
					@click="(show.data = false)"
					:disabled="wait"
					tile
					text>
					Cerrar
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>

	<v-dialog
        v-model="show.pass"
        :width="none(tiny, 840, '100%')"
        persistent
        outlined
        tile>
        <v-card>
            <v-card-title class="pa-0">
				<v-list class="flex">
					<v-list-item>
						<v-list-item-avatar tile>
							<v-icon class="grey lighten-4">
								mdi-clipboard-check
							</v-icon>
						</v-list-item-avatar>
						<v-list-item-content>
							<v-list-item-title>
                                Aprobar
							</v-list-item-title>
							<v-list-item-subtitle>
                                Aprobar registro de avance.
							</v-list-item-subtitle>
						</v-list-item-content>
						<v-list-item-action>
                            <v-btn
                                @click="(show.pass = false)"
                                :disabled="wait"
                                icon>
					            <v-icon>mdi-close</v-icon>
				            </v-btn>
                        </v-list-item-action>
					</v-list-item>
				</v-list>
            </v-card-title>
            <v-divider>
            </v-divider>
            <v-card-text class="mt-4">
				<v-row>
					<v-col>
						¿Desea aprobar el avance de <b>@{{pick?.load}} @{{none(pick?.mode, pick?.unit, '%')}}</b> registrado por <b>@{{pick?.skip}}</b> en la unidad <b>@{{pick?.code ?? pick?.work}}</b>?
					</v-col>
				</v-row>
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
				<v-row dense>
					<v-col>
						<v-textarea
							label="Comentarios"
							maxlength="1024"
							hide-details="auto"
							autocomplete="off"
							v-model="more"
							:error-messages="[fail?.list?.more].filter(Boolean)"
							:value="more"
							:disabled="wait"
							auto-grow
							counter
							filled>
						</v-textarea>
					</v-col>
				</v-row>
            </v-card-text>
			<v-divider>
			</v-divider>
            <v-card-actions class="grey lighten-4">
                <v-spacer>
                </v-spacer>
                <v-btn
                    color="secondary"
                    @click="(show.pass = false)"
                    :disabled="wait"
                    text>
                    Cancelar
                </v-btn>
				<v-btn
					v-if="none(pick?.pass)"
                    color="error" 
                    @click="pass(pick, 2, more)"
                    :disabled="wait"
                    text>
                    Rechazar
                </v-btn>
                <v-btn
                    color="success" 
                    @click="pass(pick, 1, more)"
                    :disabled="wait"
                    text>
                    Aprobar
                </v-btn>
            </v-card-actions>
        </v-card>
	</v-dialog>

	<v-dialog
        v-model="show.drop"
        :width="none(tiny, 640, '100%')"
        persistent
        outlined
        tile>
        <v-card>
            <v-card-title class="pa-0">
				<v-list class="flex">
					<v-list-item>
						<v-list-item-avatar tile>
							<v-icon class="grey lighten-4">
								mdi-delete
							</v-icon>
						</v-list-item-avatar>
						<v-list-item-content>
							<v-list-item-title>
                                Eliminar
							</v-list-item-title>
							<v-list-item-subtitle>
                                Eliminar el registro permanentemente.
							</v-list-item-subtitle>
						</v-list-item-content>
						<v-list-item-action>
                            <v-btn
                                @click="(show.drop = false)"
                                :disabled="wait"
                                icon>
					            <v-icon>mdi-close</v-icon>
				            </v-btn>
                        </v-list-item-action>
					</v-list-item>
				</v-list>
            </v-card-title>
          	<v-divider>
            </v-divider>
          	<v-card-text class="mt-4">
          		¿Desea eliminar la obra <b>@{{pick?.step}}</b> en <b>@{{pick?.code  ?? pick?.work}}</b>?
          	</v-card-text>
          	<v-card-actions>
            	<v-spacer>
                </v-spacer>
            	<v-btn
                    color="secondary" 
                    @click="(show.drop = false)"
                    :disabled="wait"
                    text>
                    Cancelar
                </v-btn>
            	<v-btn
                    color="red"
                    @click="drop(pick)"
                    :disabled="wait"
                    text>
                    Eliminar
                </v-btn>
          	</v-card-actions>
        </v-card>
	</v-dialog>
@stop

@section('code')
    <script type="text/javascript">
  		Vue.ready(() => {
  			var self = new Vue({
			  	vuetify: new Vuetify(),
				el: '#page',
			  	data: () => ({
					seek: {!!json_encode($seek)!!},
			  		wait: false,
			  		menu: false,
			  		snap: null,
			  		pick: null,
					unit: null,
					more: null,
					pane: 0,
					view: 1,
                    page: {
			  			page: 1,
			  			itemsPerPage: 32
			  		},
					fail: {
						text: null,
						list: null
					},
					pile: {
                        work: {
							wait: false,
							text: null,
							list: []
						},
                        unit: {
							wait: false,
							text: null,
							list: []
						},
                        step: {
							wait: false,
							text: null,
							list: []
						},
						lead: {
							wait: false,
							text: null,
							list: []
						}
					},
					pipe: {
						show: false,
                        edit: false,
						pick: null,
                        chip: null,
						text: null,
                        size: 6,
                        from: 0,
						form: [],
						data: [
							{   
                                icon: 'mdi-pencil-ruler',
								type: 'list',
								nick: 'work',
								name: 'Proyecto',
								hint: 'Buscar proyecto',
								wipe: [
									'unit',
									'step'
								],
								list: null,
								take: 64,
								item: (item) => (item.item),
								text: (item) => (item.name),
                                note: (item) => (item.code),
								load: (find, take, page, done) => {
                                    done(self.pile.work.list.filter((item) => (self.none(find) || (~item.code.indexOf(find?.toLocaleUpperCase())) || (~item.name.toLocaleLowerCase().indexOf(find?.toLocaleLowerCase())))))
                                }
							},
							{   
                                icon: 'mdi-home-city',
								type: 'list',
								nick: 'unit',
								name: 'Unidad',
								hint: 'Buscar unidad',
								seek: 'work',
								list: null,
								take: 64,
								item: (item) => (item.item),
								text: (item) => (item.code),
								load: (find, take, page, done, work) => {
									done(self.pile.unit.list.filter((item) => ((item.link == work?.item(work?.data)) && ((self.none(find) || (~item.code.indexOf(find?.toLocaleUpperCase())))))))
								}
							},
							{   
                                icon: 'mdi-flag',
								type: 'list',
								nick: 'step',
								name: 'Etapa',
								hint: 'Buscar etapa',
								seek: 'work',
								list: null,
								take: 64,
								item: (item) => (item.item),
								text: (item) => (item.name),
								load: (find, take, page, done, work) => {
                                    done(self.pile.step.list.filter((item) => ((item.link == work?.item(work?.data)) && (self.none(find) || (~item.code.indexOf(find?.toLocaleUpperCase())) || (~item.name.toLocaleLowerCase().indexOf(find?.toLocaleLowerCase()))))))
                                }
							},
							{   
                                icon: 'mdi-account-hard-hat',
								type: 'list',
								nick: 'hook',
								name: 'Contratista',
								hint: 'Buscar contratista',
								seek: 'work',
								list: null,
								take: 64,
								item: (item) => (item.item),
								text: (item) => (`${item.name} ${item.last}`),
                                note: (item) => (item.card),
								load: (find, take, page, done) => {
                                    done(self.pile.lead.list.filter((item) => (self.none(find) || (~item.code.indexOf(find?.toLocaleUpperCase())) || (~item.firm.toLocaleLowerCase().indexOf(find?.toLocaleLowerCase())))))
                                }
							},
                            {   
                                icon: 'mdi-clipboard-check',
								once: true,
								type: 'menu',
								nick: 'pass',
								name: 'Aprobado',
								hint: 'Seleccione una opción',
								list: [
									{
										item: 1,
										text: 'Sí'
									},
									{
										item: 2,
										text: 'No'
									},
									{
										item: 0,
										line: true,
                                        text: 'Pendiente'
                                    }
								]
							},
							{
                                icon: 'mdi-calendar-range',
                                type: 'menu',
                                nick: 'date',
								name: 'Fecha',
								once: true,
                                list: [
                                    {
										item: 'TD',
                                        text: 'Hoy'
                                    },
									{
										item: 'YD',
                                        text: 'Ayer'
                                    },
                                    {
										item: 'WK',
										line: true,
                                        text: 'Esta semana'
                                    },
									{
										item: 'FN',
                                        text: 'Esta quincena'
                                    },
                                    {
										item: 'MH',
                                        text: 'Este mes'
                                    },
									{
										item: 'HY',
                                        text: 'Este semestre'
                                    },
                                    {
										item: 'YR',
                                        text: 'Este año'
                                    }
                                ],
                                none: {
                                    type: 'date',
									icon: 'mdi-calendar',
								    text: 'Fecha personalizada'
                                }
							}
						],
						sort: (pick, drop) => {
							self.pipe.show = false;

							self.pipe.edit = false;

							self.pipe.pick = null;

							if (drop) {
								if (self.same(self.none((drop = self.find(self.pipe.form, 'nick', pick.nick)), true), false)) {
									self.pipe.form.splice(drop, 1);
								}
							} else {
								if (self.none((drop = self.find(self.pipe.form, 'nick', pick.nick)), true)) {
									self.pipe.form.push(pick);
								} else {
									self.pipe.form.splice(drop, 1, pick);
								}

								switch (pick.type) {
									case 'date':
										pick.text = `${self.date(pick.data[0], 'DD/MM/YYYY')}${pick.data[1] ? ' ~ ' : ''}${pick.data[1] ? self.date(pick.data[1], 'DD/MM/YYYY') : ''}`;
										break;
									case 'time':
										pick.text = self.date(pick.data, 'HH:mm');
										break;
									case 'menu':
										pick.text = pick.data.reduce((list, item) => {
											list.push(item.text);

											return list;
										}, []).join(', ');
										break;
									case 'text':
										if (self.test('^([0-9]+)(\\s+[0-9]+)?$', pick.data)) {
											var data = self.trim(pick.data).split(' ');

											pick.text = `${self.text(data[0])}${data[1] ? ' ~ ' : ''}${data[1] ? self.text(data[1]) : ''}`;
										} else {
											pick.text = pick.data;
										}
										break;
									case 'list':
										pick.text = pick.text(pick.data);
										break;
								}
							}

							self.walk(pick.wipe, (item) => {
								if (self.same((drop = self.find(self.pipe.form, 'nick', item, true, false)), false, false, true)) {console.log('drop1', drop, item);
									self.pipe.form.splice(drop, 1);
								}console.log('drop', drop, item);

								if (self.same(self.none((drop = self.find(self.pipe.data, 'nick', item)), true), false)) {
									//self.pipe.data[drop].list = null;
								}
							});

							setTimeout(() => {
								self.load(self.take, null, self.pipe.form.map((item) => {
									switch (item.type) {
										case 'date':
											return `${item.nick}: ${self.date(item.data[0], 'YYYY-MM-DD')}${item.data[1] ? ' ' : ''}${item.data[1] ? self.date(item.data[1], 'YYYY-MM-DD') : ''}`;
										case 'menu':
											return `${item.nick}: ${item.data.map((item) => (item.item)).join(' ')}`;
										case 'time':
											return `${item.nick}: ${self.date(item.data, 'HH:mm')}`;
										case 'list':
											return `${item.nick}: ${item.item(item.data)}`;
										default:
											return `${item.nick}: ${item.data}`;
										
									}
								}).join(', '), true);
							}, 300);
						}
					},
			  		data: {
                        take: 32,
                        size: 0,
			  			page: 0,
                        list: []
			  		},
			  		show: {
                        post: false,
                        spot: false,
			  			form: false,
						data: false,
			  			view: false,
			  			crop: false,
			  			wipe: false,
						pass: false,
			  			lock: false,
			  			drop: false,
						menu: {
							form: {
								open: false,
                                stop: false
							}
						}
			  		},
					bulk: {
						show: false,
						list: [],
						data: {
							note: null
						}
					},
			  		form: {
			  			lock: null,
                        bind: null,
                        pick: null,
			  			next: null,
						rule: null,
			  			code: null,
                        open: null,
                        stop: null,
			  			note: null
			  		}
			    }),
			    watch: {
			    	seek: {
						handler: (data) => {
							if (self.time) {
								clearTimeout(self.time);
							}

							self.time = setTimeout(() => {
								self.load(self.take, null, data);
							}, 500);
					    }
			    	},
			    	page: {
			    		handler: (data) => {
			    		   self.load((self.data.take = data.itemsPerPage), (self.data.page = data.page), self.seek);
					    },
					    deep: true
			    	}
			    },
			    methods: {
			    	load: (take, page, seek) => {
			    		axios.get(`{{route('core.times', ['task' => 'load'])}}?take=${take ?? ''}&page=${page ?? ''}&seek=${seek ?? ''}`, {})
				             .then((data) => {
				            self.data.list = data.data.list ?? [];

				            self.data.size = data.data.size ?? 0;

				            self.data.page = data.data.page ?? 0;

				            self.data.take = data.data.take ?? 0;

				            self.wait = false;
				        }).catch((fail) => {
				            self.wait = false;

							if (fail.response?.data?.text) {
								self.note = {
									show: true,
									type: 'fail',
									text: fail.response.data.text
								};
							} else {
								self.note = {
									show: true,
									type: 'fail',
									text: 'No se pudo cargar los registros.'
								};
							}
				        });

				        self.wait = true;
			    	},
			    	open: (task, item, data, type, post) => {
			    		if (item) {
			    			switch (task) {
			    				case 'edit':
			    				    axios.get(`{{route('core.times', ['task' => 'load'])}}/${item.hash}`, {})
							             .then((data) => {
							            self.form = {
                                            file: [],
                                            lock: data.data.lock,
                                            open: data.data.open,
                                            stop: data.data.stop,
                                            code: data.data.code,
                                            bind: data.data.bind,
                                            pick: data.data.pick,
                                            next: data.data.next,
                                            note: data.data.note
                                        };

										if (self.wait) {
											setTimeout(() => {
												self.wait = false;
											}, 200);
										} else {
											clearTimeout(self.time);
										}

										self.view = 2;
							        }).catch((fail) => {
							            self.wait = false;

							            if (fail.response?.data?.text) {
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
							        });

									self.time = setTimeout(() => {
										self.wait = true;
									}, 100);

							        self.pick = item;
			    					break;
								case 'data':
									axios.get(`{{route('core.times', ['task' => 'data'])}}/${item.hash}`, {})
							             .then((data) => {
										if (self.wait) {
											setTimeout(() => {
												self.wait = false;
											}, 200);
										} else {
											clearTimeout(self.time);
										}

							            self.pick = {
                                            spot: data.data.spot,
											file: data.data.file,
											cost: data.data.cost,
											lock: data.data.lock,
											sort: data.data.sort,
											rate: data.data.rate,
											pass: data.data.pass,
											mode: data.data.mode,
											size: data.data.size,
											load: data.data.load,
											item: data.data.item,
											hash: data.data.hash,
											code: data.data.code,
											work: data.data.work,
											town: data.data.town,
											unit: data.data.unit,
											step: data.data.step,
											skip: data.data.skip,
											note: data.data.note,
											more: data.data.more,
											date: data.data.date,
											made: data.data.made
                                        };

										self.show.data = true;

										self.pane = 0;
							        }).catch((fail) => {
							            self.wait = false;

							            if (fail.response?.data?.text) {
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
							        });

									self.time = setTimeout(() => {
										self.wait = true;
									}, 100);
			    					break;
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
								default:
									window.open(item, '_blank');
			    			}
			    		} else {
			    			switch (task) {
			    				case 'make':
			    					self.form = {
                                        lock: 0,
										file: [],// detete this
                                        rule: [],
                                        bind: null,
                                        pick: null,
                                        next: null,
                                        code: null,
                                        open: null,
                                        stop: null,
                                        note: null
                                    };

					             	self.pick = null;

									self.view = 2;
			    					break;
			    			}
			    		}
			    	},
			    	save: (form, item) => {
                        var data = new FormData();

		    		    data.append('lock', form.lock ?? 0);

                        data.append('bind', form.bind ?? '');

                        data.append('pick', form.pick ?? '');

                        data.append('next', form.next ?? '');

		    		    data.append('code', form.code ?? '');

                        data.append('open', form.open ?? '');

		    		    data.append('stop', form.stop ?? '');

		    	        data.append('note', form.note ?? '');

		    	        if (item) {
				    		axios.post(`{{route('core.times', ['task' => 'save'])}}/${item.hash}`, data, {'X-CSRF-TOKEN': '{{csrf_token()}}'})
	                             .then((data) => {
                                item.sort = form.sort;

                                item.code = form.code;

                                item.open = form.open;

                                item.stop = form.stop;

	                            self.wait = false;

                                self.view = 1;
								
	                            self.note = {
                                    show: true,
                                	type: 'done',
                                	text: data.data.text
                                };
	                        })
	                        .catch((fail) => {
								self.wait = false;

	                            if (fail.response?.data?.text) {
                                    self.note = {
                                        show: true,
                                        type: 'fail',
                                        text: fail.response.data.text
                                    };

	                            	self.fail.list = fail.response.data.list ?? {};
	                            } else {
                                    self.note = {
                                        show: true,
                                        type: 'fail',
                                        text: 'Se presentó un error inesperado.'
                                    };
	                            }
	                        });
				    	} else {
				    		axios.post("{{route('core.times', ['task' => 'make'])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
	                             .then((data) => {
	                            self.data.list.unshift((item = {
                                    item: data.data.item,
                                    hash: data.data.hash,
                                    code: data.data.code,
                                    stop: data.data.stop,
                                    work: data.data.work,
                                    unit: data.data.unit,
                                    step: data.data.step,
                                    lock: form.lock
                                }));

                                self.note = {
                                    show: true,
                                	type: 'done',
                                	text: data.data.text
                                };

                                self.wait = false;

								self.view = 1;
	                        })
	                        .catch((fail) => {
	                            self.wait = false;

	                            if (fail.response?.data?.text) {
                                    self.note = {
                                        show: true,
                                        type: 'fail',
                                        text: fail.response.data.text
                                    };

									self.fail.list = fail.response.data.list ?? {};
	                            } else {
                                    self.note = {
                                        show: true,
                                        type: 'fail',
                                        text: 'Se presentó un error inesperado.'
                                    };
	                            }
	                        });
				    	}

				    	self.wait = true;

						self.fail = {
							text: null,
							list: null
						};
			    	},
			    	drop: (item) => {
			    		axios.get(`{{route('core.times', ['task' => 'drop'])}}/${item.hash}`, {})
				             .then((data) => {
                            self.wait = false;

                            self.show.drop = false;

                            self.data.list.splice(self.data.list.indexOf(item), 1);

                            self.note = {
                                show: true,
                                type: 'done',
                                text: data.data.text
                            };
				        }).catch((fail) => {
				            self.wait = false;

				            self.show.drop = false;

				            if (fail.response?.data?.text) {
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
				        });

				        this.wait = true;
			    	},
			    	boot: (data) => {
						self.snap = data;

						self.snap.replace(self.file, false);
					}
			    },
			    mounted: () => {
                    setTimeout(() => {
                        axios.get(`{{route('core.works', ['task' => 'pull'])}}`)
							.then((data) => {
							self.pile.work.list = data.data.list;

							self.pile.work.wait = false;
						}).catch((fail) => {
							self.pile.hand.wait = false;
						});

						axios.get(`{{route('core.units', ['task' => 'pull'])}}`)
							.then((data) => {
							self.pile.unit.list = data.data.list;

							self.pile.unit.wait = false;
						}).catch((fail) => {
							self.pile.unit.wait = false;
						});

						axios.get(`{{route('core.steps', ['task' => 'pull'])}}`)
							.then((data) => {
							self.pile.step.list = data.data.list;

							self.pile.step.wait = false;
						}).catch((fail) => {
							self.pile.step.wait = false;
						});

						self.pile.work.wait = true;

                        self.pile.unit.wait = true;

                        self.pile.step.wait = true;

	             		self.done = true;
	             	}, 500);
			    }
			});

			var plat = null;
  		});
  	</script>
@stop