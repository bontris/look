@extends('team')

@section('page')
	<v-layout column>
		<v-row class="ma-0">
			<v-col class="pa-0">
				<v-layout class="white px-2">
					<v-list>
						<v-list-item class="px-1">
							<v-list-item-avatar
                                color="#1E88E5"
                                size="36"
                                tile>
								<v-icon color="white">
                                    mdi-map-marker-path
								</v-icon>
							</v-list-item-avatar>
							<v-list-item-content class="pa-0">
								<v-list-item-title>
									Rutas
								</v-list-item-title>
								<v-list-item-subtitle>
									<v-breadcrumbs
										class="pa-0"
										:items="[{text: 'Inicio', href: '{{route('dash')}}'}, {text: 'Rutas'}]">
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
							<v-sheet v-if="same(view, 1)">
								<v-list>
									<v-list-item>
										<v-list-item-content>
											<v-list-item-title>
												LISTADO DE RUTAS
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
												@{{pick ? 'EDITAR RUTA' : 'NUEVA RUTA'}}
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
									{sortable: false},
									{text: 'Estado', width: 120, align: 'center', sortable: false},
									{text: 'Nombre', align: 'start', sortable: false},
									{text: 'Domiciliario', align: 'start', sortable: false},
                                    {text: 'Paradas', width: 180, align: 'center', sortable: false},
									{text: 'Efectivo', width: 160, align: 'end', sortable: false},
                                    {text: 'Especie', width: 160, align: 'end', sortable: false},
                                    {text: 'Creación', width: 180, align: 'end', sortable: false},
									{sortable: false}
								], [
									{sortable: false},
									{text: 'Nombre', align: 'start', sortable: false},
									{sortable: false}
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
												:disabled="wait"
												@change="$event ? bulk.list.push(item) : bulk.list.splice(bulk.list.indexOf(item), 1)"
												:input-value="~bulk.list.indexOf(item)">
											</v-checkbox>
										</td>
										<td v-if="tiny">
                                            <v-list>
												<v-list-item>
                                                    <v-list-item-content>
                                                        <v-list-item-title>
                                                            @{{item.name}}
                                                        </v-list-item-title>
                                                        <v-list-item-subtitle>
                                                            @{{item.code}}
                                                        </v-list-item-subtitle>
                                                    </v-list-item-content>
												</v-list-item>
											</v-list>
										</td>
										<td
											v-if="none(tiny)"
											class="text-center">
											<div class="font-weight-medium">
												<v-chip
                                                    text-color="white"
                                                    :color="{0: '#78909B', 1: '#0277BD', 2: '#2E7D32', 3: '#D32F2F'}[item.pass]"
                                                    small>
                                                    <v-icon left>
														@{{({0: 'mdi-progress-clock', 1: 'mdi-lightning-bolt', 2: 'mdi-check', 3: 'mdi-close'}[item.pass])}}
													</v-icon>
													@{{{0: 'Pendiente', 1: 'En camino', 2: 'Aprobada', 3: 'Rechazada'}[item.pass]}}
												</v-chip>
												
											</div>
										</td>
										<td v-if="none(tiny)">
											<div class="text--primary">
												@{{item.name}}
											</div>
                                            <div class="text--secondary">
                                                @{{item.code}}
											</div>
										</td>
										<td v-if="none(tiny)">
											<div :class="none(item.pick, 'text--secondary', 'text--primary')">
                                                @{{item.pick ?? 'Sin asignar'}}
											</div>
										</td>
                                        <td
                                            v-if="none(tiny)"
                                            class="text-center">
											<div class="text--primary">
                                                @{{`${item.done} de ${item.size}`}}
											</div>
										</td>
										<td
                                            v-if="none(tiny)"
                                            class="text-end">
											<div :class="none(item.rate, 'text--secondary', 'text--primary')">
                                                @{{none(item.rate, 'Ninguno', `$${text(item.rate)}`)}}
											</div>
										</td>
                                        <td
                                            v-if="none(tiny)"
                                            class="text-end">
											<div :class="none(item.load, 'text--secondary', 'text--primary')">
												@{{none(item.load, 'Ninguna', `${text(item.load)} Und`)}}
											</div>
										</td>
                                        <td
											v-if="none(tiny)"
											class="text-end">
											<div class="text--primary">
												@{{date(item.made, 'DD/MM/YYYY hh:mm:ss')}}
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
														:disabled="Boolean(bulk.list.length)"
														@click="open('spot', item)">
														<v-list-item-icon>
															<v-icon>mdi-map</v-icon>
														</v-list-item-icon>
														<v-list-item-title>Ubicar</v-list-item-title>
													</v-list-item>
                                                    <v-list-item
														:disabled="Boolean(bulk.list.length)"
														@click="open('pass', item)">
														<v-list-item-icon>
															<v-icon>mdi-check-decagram</v-icon>
														</v-list-item-icon>
														<v-list-item-title>Aprobar</v-list-item-title>
													</v-list-item>
													<v-list-item
														:disabled="Boolean(bulk.list.length)"
														@click="open('lock', item)">
														<v-list-item-icon>
															<v-icon>mdi-lock-reset</v-icon>
														</v-list-item-icon>
														<v-list-item-title>@{{item.lock ? 'Hablilitar' : 'Bloquear'}}</v-list-item-title>
													</v-list-item>
													<v-divider>
													</v-divider>
													<v-list-item
														@click="open('edit', item)">
														<v-list-item-icon>
															<v-icon>mdi-pencil</v-icon>
														</v-list-item-icon>
														<v-list-item-title>Editar</v-list-item-title>
													</v-list-item>
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
													:disabled="Boolean(bulk.list.length)"
													@click="open('spot', item)"
													title="Ubicar"
													icon>
													<v-icon>mdi-map</v-icon>
												</v-btn>
                                                <v-btn
													:disabled="(Boolean(bulk.list.length) || same(item.pass, 2) || none(item.pass))"
													@click="open('pass', item)"
													title="Calificar"
													icon>
													<v-icon>mdi-check-decagram</v-icon>
												</v-btn>
												<v-btn
													:disabled="Boolean(bulk.list.length)"
													:color="item.lock ? 'red' : 'green'"
													@click="open('lock', item)"
													:title="(item.lock ? 'Hablilitar' : 'Bloquear')"
													icon>
													<v-icon>mdi-lock-reset</v-icon>
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
                                        <v-col>
											<v-text-field
												type="text"
												label="Nombre"
												autocomplete="off"
												hide-details="auto"
												v-model="form.name"
												:error-messages="[fail?.name].filter(Boolean)"
												:disabled="wait"
												required
												filled>
											</v-text-field>
										</v-col>
									</v-row>
									<v-row dense>
                                        <v-col>
                                            <v-autocomplete
												:items="pile.pick.list"
												item-value="item"
												no-data-text="No hay opciones disponibles"
												label="Domiciliario"
												v-model="form.pick"
												hide-details="auto"
												:filter="(item, find, text) => (((~item.code.indexOf(find.toLocaleUpperCase())) || (~`${item.name} ${item.last}`.toLocaleLowerCase().indexOf(find.toLocaleLowerCase()))))"
												:error-messages="[fail?.pick].filter(Boolean)"
												:disabled="wait"
												filled>
												<template v-slot:selection="data">
													@{{data.item.name}} @{{data.item.last}}
												</template>
												<template v-slot:item="data">
													<v-list-item-content>
														<v-list-item-title>@{{data.item.name}} @{{data.item.last}}</v-list-item-title>
                                                        <v-list-item-subtitle>@{{data.item.mail ?? data.item.code}}</v-list-item-title>
													</v-list-item-content>
												</template>
											</v-autocomplete>
										</v-col>
									</v-row>
                                    <v-row dense>
                                        <v-col>
                                            <v-autocomplete
												:items="pile.stop.list"
												item-value="hash"
												label="Paradas"
                                                v-model="form.list"
                                                no-data-text="No hay opciones disponibles"
												hide-details="auto"
                                                clear-icon="mdi-close-circle"
                                                :error-messages="[fail?.list].filter(Boolean)"
												:filter="(item, find, text) => ((~item.name.toLocaleLowerCase().indexOf(find.toLocaleLowerCase())) || (~item.town.toLocaleLowerCase().indexOf(find.toLocaleLowerCase())) || (~item.address.toLocaleLowerCase().indexOf(find.toLocaleLowerCase())))"
												:disabled="wait"
                                                clearable
                                                multiple
												filled>
												<template v-slot:selection="data">
                                                    <v-chip
                                                        v-bind="data.attrs"
                                                        @click="mark(data.item)"
                                                        @click:close="form.list.splice(form.list.indexOf(data.item.hash), 1)"
                                                        label
                                                        close>
                                                        @{{data.item.name}} 
                                                    </v-chip>
												</template>
												<template v-slot:item="data">
													<v-list-item-content>
														<v-list-item-title>
                                                            @{{data.item.name}}
                                                        </v-list-item-title>
                                                        <v-list-item-subtitle>
                                                            @{{data.item.address}}, <b>@{{data.item.town}}</b>
                                                        </v-list-item-title>
													</v-list-item-content>
												</template>
											</v-autocomplete>
										</v-col>
									</v-row>
									<v-row dense>
										<v-col>
											<div id="plat">
											</div>
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
                                mdi-check-decagram
							</v-icon>
						</v-list-item-avatar>
						<v-list-item-content>
							<v-list-item-title>
                                Aprobar
							</v-list-item-title>
							<v-list-item-subtitle>
                                Aprobar culminación de ruta.
							</v-list-item-subtitle>
						</v-list-item-content>
						<v-list-item-action>
                            <v-btn
                                @click="show.pass = false"
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
						¿Desea aprobar la culminación de la ruta <b>@{{pick?.name}}</b>?
					</v-col>
				</v-row>
				<v-row
					v-if="fail?.text"
					dense>
					<v-col>
						<v-alert
							type="error"
							tile>
							@{{fail?.text}}
						</v-alert>
					</v-col>
				</v-row>
				<v-row dense>
					<v-col>
						<v-textarea
							label="Comentario"
							maxlength="256"
							hide-details="auto"
							autocomplete="off"
							v-model="show.form.note"
							:error-messages="[fail?.list?.note].filter(Boolean)"
							:value="show.form.note"
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
					v-if="same(pick?.pass, 1)"
                    color="error" 
                    @click="pass(pick, 2, show.form.note)"
                    :disabled="wait"
                    text>
                    Rechazar
                </v-btn>
                <v-btn
                    color="success" 
                    @click="pass(pick, 1, show.form.note)"
                    :disabled="wait"
                    text>
                    Aprobar
                </v-btn>
            </v-card-actions>
        </v-card>
	</v-dialog>

	<v-dialog
		v-model="show.spot"
		:width="none(tiny, 768, '100%')"
		persistent
        scrollable
		outlined
		tile>
		<v-card>
            <v-card-title class="pa-0">
				<v-list>
					<v-list-item>
						<v-list-item-avatar>
							<v-icon class="grey lighten-4">
								mdi-map
							</v-icon>
						</v-list-item-avatar>
						<v-list-item-content>
							<v-list-item-title class="text-uppercase">
                                Ubicación
							</v-list-item-title>
							<v-list-item-subtitle>
                                Cambiar la ubicación de la empresa.
							</v-list-item-subtitle>
						</v-list-item-content>
                        <v-list-item-action v-if="tiny">
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
					class="mr-2"
					color="secondary"
					@click="(show.spot = false)"
					:disabled="wait"
					tile
					text>
					Cancelar
				</v-btn>
				<v-btn
					class="mr-2"
					color="success"
					@click="move(pick, form.spot)"
					:disabled="wait"
                    :icon="none(tiny, false, true)"
                    :tile="none(tiny)"
					:text="none(tiny)">
                    <template v-if="tiny">
                        <v-icon>mdi-check<v-icon>
                    </template>
                    <template v-else>
                        Guardar
                    </template>
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>

    <v-dialog
        v-model="show.wipe"
        :width="none(tiny, 640, '100%')"
        persistent
        outlined
        tile>
        <v-card>
            <v-card-title class="pa-0">
				<v-list>
					<v-list-item>
						<v-list-item-avatar>
							<v-icon class="grey lighten-4">
								mdi-image-remove
							</v-icon>
						</v-list-item-avatar>
						<v-list-item-content>
							<v-list-item-title class="text-uppercase">
                                Imágen
							</v-list-item-title>
							<v-list-item-subtitle>
                                Eliminar la imágen del registro.
							</v-list-item-subtitle>
						</v-list-item-content>
					</v-list-item>
				</v-list>
            </v-card-title>
          	<v-divider>
            </v-divider>
          	<v-card-text class="mt-4">
          		¿Desea eliminar la imágen del proyecto <b>@{{pick?.name}} / @{{pick?.code}}</b>?
          	</v-card-text>
          	<v-card-actions>
            	<v-spacer>
                </v-spacer>
            	<v-btn
                    color="secondary" 
                    @click="(show.wipe = false)"
                    :disabled="wait"
                    text>
                    Cancelar
                </v-btn>
            	<v-btn
                    color="red"
                    @click="face(pick)"
                    :disabled="wait"
                    text>
                    Eliminar
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
				<v-list>
					<v-list-item>
						<v-list-item-avatar>
							<v-icon class="grey lighten-4">
								mdi-delete
							</v-icon>
						</v-list-item-avatar>
						<v-list-item-content>
							<v-list-item-title class="text-uppercase">
                                Eliminar
							</v-list-item-title>
							<v-list-item-subtitle>
                                Eliminar el registro permanentemente.
							</v-list-item-subtitle>
						</v-list-item-content>
					</v-list-item>
				</v-list>
            </v-card-title>
          	<v-divider>
            </v-divider>
          	<v-card-text class="mt-4">
          		¿Desea eliminar el registro <b>@{{pick?.name}} / @{{pick?.code}}</b>?
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

	<v-dialog
        v-model="show.lock"
        :width="none(tiny, 640, '100%')"
        persistent
        outlined
        tile>
        <v-card>
            <v-card-title class="pa-0">
				<v-list>
					<v-list-item>
						<v-list-item-avatar>
							<v-icon class="grey lighten-4">
                                mdi-lock-reset
							</v-icon>
						</v-list-item-avatar>
						<v-list-item-content>
							<v-list-item-title class="text-uppercase">
                                @{{same(pick?.lock, 1, 'Habilitar', 'Bloquear')}}
							</v-list-item-title>
							<v-list-item-subtitle>
                                @{{same(pick?.lock, 1, 'Habilitar registro.', 'Bloquear registro.')}}
							</v-list-item-subtitle>
						</v-list-item-content>
					</v-list-item>
				</v-list>
            </v-card-title>
            <v-divider>
            </v-divider>
            <v-card-text class="mt-4">
                <template v-if="same(pick?.lock, 1)">
                    ¿Desea habilitar el registro <b>@{{pick?.name}} / @{{pick?.code}}</b>?
                </template>
            	<template v-else>
                    ¿Desea bloquear el registro <b>@{{pick?.name}} / @{{pick?.code}}</b>?
                </template>
            </v-card-text>
            <v-card-actions>
                <v-spacer>
                </v-spacer>
                <v-btn
                    color="secondary"
                    @click="(show.lock = false)"
                    :disabled="wait"
                    text>
                    Cancelar
                </v-btn>
                <v-btn
                    :color="same(pick?.lock, 1, 'success', 'error')" 
                    @click="lock(pick, same(pick?.lock, 1, 0, 1))"
                    :disabled="wait"
                    text>
                    @{{same(pick?.lock, 1, 'Habilitar', 'Bloquear')}}
                </v-btn>
            </v-card-actions>
        </v-card>
	</v-dialog>
@stop

@section('code')
    <script type="text/javascript">
  		Vue.ready(() => {
			var plat = null;

			var code = null;

            var line = null;

            var info = null;

            var tool = null;

  			var self = new Vue({
			  	vuetify: new Vuetify(),
				el: '#page',
			  	data: () => ({
					seek: {!!json_encode($seek)!!},
			  		wait: false,
			  		menu: false,
			  		snap: null,
			  		pick: null,
                    fail: null,
					find: null,
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
						pick: {
							wait: false,
							text: null,
							list: []
						},
                        stop: {
							wait: false,
							text: null,

							list: []
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
                        form: {
                            note: null
                        },
						menu: {
							form: {
								date: false
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
                        code: null,
                        name: null,
                        pick: null,
						list: null
			  		}
			    }),
			    watch: {
					view: {
						handler: (view) => {
							if (self.same(view, 2)) {
								setTimeout(() => {
									plat = new google.maps.Map(document.getElementById('plat'), {
                                        mapTypeControl: false,
                                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                                        center: new google.maps.LatLng(6.2442876, -75.6162309),
                                        zoom: 12
                                    });

                                    code = new google.maps.Geocoder();

                                    info = new google.maps.InfoWindow({
                                        content: ''
                                    });

                                    line = new google.maps.Polyline({
										path: [],
										geodesic: true,
										strokeColor: "#FF0000",
										strokeOpacity: 1.0,
										strokeWeight: 2
									});

                                    tool = new google.maps.drawing.DrawingManager({
                                        drawingMode: null,
                                        drawingControl: false,
                                        drawingControlOptions: {
                                            position: google.maps.ControlPosition.TOP_CENTER,
                                            drawingModes: [google.maps.drawing.OverlayType.POLYLINE]
                                        }
                                    });

                                    self.pile.stop.list.forEach((stop) => {
                                        var spot = new google.maps.Marker({
                                            position: new google.maps.LatLng(stop.spot[0], stop.spot[1]),
                                            icon: {
                                                path: 'M20 17Q20.86 17 21.45 17.6T22.03 19L14 22L7 20V11H8.95L16.22 13.69Q17 14 17 14.81 17 15.28 16.66 15.63T15.8 16H13L11.25 15.33L10.92 16.27L13 17H20M16 3.23Q17.06 2 18.7 2 20.06 2 21 3T22 5.3Q22 6.33 21 7.76T19.03 10.15 16 13Q13.92 11.11 12.94 10.15T10.97 7.76 10 5.3Q10 3.94 10.97 3T13.31 2Q14.91 2 16 3.23M.984 11H5V22H.984V11Z',
                                                fillColor: '#F53739',
                                                fillOpacity: 1,
                                                strokeColor: '#000000',
                                                strokeWeight: 1,
                                                scale: 1.6,
                                                anchor: new google.maps.Point(24, 24)
                                            },
                                            map: plat,
                                            id: stop.item
                                        });

                                        var item = self.form.list.indexOf(stop.hash);
                                        
                                        if ((~item)) {
                                            spot.setIcon({
                                                path: 'M20 17Q20.86 17 21.45 17.6T22.03 19L14 22L7 20V11H8.95L16.22 13.69Q17 14 17 14.81 17 15.28 16.66 15.63T15.8 16H13L11.25 15.33L10.92 16.27L13 17H20M16 3.23Q17.06 2 18.7 2 20.06 2 21 3T22 5.3Q22 6.33 21 7.76T19.03 10.15 16 13Q13.92 11.11 12.94 10.15T10.97 7.76 10 5.3Q10 3.94 10.97 3T13.31 2Q14.91 2 16 3.23M.984 11H5V22H.984V11Z',
                                                fillColor: '#5EB85B',
                                                fillOpacity: 1,
                                                strokeColor: '#000000',
                                                strokeWeight: 1,
                                                scale: 1.6,
                                                anchor: new google.maps.Point(16, 16)
                                            });
                                        }

                                        spot.addListener('mouseover', (event) => {
                                            info.setContent(`<h2>${stop.name}</h2><p>${stop.address}, <b>${stop.town}</b></p>`);

                                            info.open(plat, spot);
                                        });

                                        spot.addListener('click', (event) => {
                                            var item = self.form.list.indexOf(stop.hash);

                                            if ((~item)) {
                                                self.form.list.splice(item, 1);

                                                spot.setIcon({
                                                    path: 'M20 17Q20.86 17 21.45 17.6T22.03 19L14 22L7 20V11H8.95L16.22 13.69Q17 14 17 14.81 17 15.28 16.66 15.63T15.8 16H13L11.25 15.33L10.92 16.27L13 17H20M16 3.23Q17.06 2 18.7 2 20.06 2 21 3T22 5.3Q22 6.33 21 7.76T19.03 10.15 16 13Q13.92 11.11 12.94 10.15T10.97 7.76 10 5.3Q10 3.94 10.97 3T13.31 2Q14.91 2 16 3.23M.984 11H5V22H.984V11Z',
                                                    fillColor: '#F53739',
                                                    fillOpacity: 1,
                                                    strokeColor: '#000000',
                                                    strokeWeight: 1,
                                                    scale: 1.6,
                                                    anchor: new google.maps.Point(24, 24)
                                                });
                                            } else {
                                                self.form.list.push(stop.hash);

                                                spot.setIcon({
                                                    path: 'M20 17Q20.86 17 21.45 17.6T22.03 19L14 22L7 20V11H8.95L16.22 13.69Q17 14 17 14.81 17 15.28 16.66 15.63T15.8 16H13L11.25 15.33L10.92 16.27L13 17H20M16 3.23Q17.06 2 18.7 2 20.06 2 21 3T22 5.3Q22 6.33 21 7.76T19.03 10.15 16 13Q13.92 11.11 12.94 10.15T10.97 7.76 10 5.3Q10 3.94 10.97 3T13.31 2Q14.91 2 16 3.23M.984 11H5V22H.984V11Z',
                                                    fillColor: '#5EB85B',
                                                    fillOpacity: 1,
                                                    strokeColor: '#000000',
                                                    strokeWeight: 1,
                                                    scale: 1.6,
                                                    anchor: new google.maps.Point(24, 24)
                                                });
                                            }
                                        });

                                        spot.addListener('mouseout', () => {
                                            info.close();
                                        });
                                    });

                                    tool.setMap(plat);
								}, 200);
							}
					    }
					},
					find: {
						handler: (find) => {
							if ((find = self.trim(find))) {
								if (self.time) {
									clearTimeout(self.time);
								}

								self.time = setTimeout(() => {
									code.geocode({'address': find}, (results, status) => {
										if (self.same(status, 'OK')) {
											let spot = results[0].geometry.location;

											self.form.spot = [spot.lat(), spot.lng()];

											plat.setCenter(spot);
										}
									});
								}, 500);
							}
					    }
					},
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
			    		axios.get(`{{route('core.paths', ['task' => 'load'])}}?take=${take ?? ''}&page=${page ?? ''}&seek=${seek ?? ''}`, {})
				             .then((data) => {
				            self.data.list = data.data.list ?? [];

				            self.data.size = data.data.size ?? 0;

				            self.data.page = data.data.page ?? 0;

				            self.data.take = data.data.take ?? 0;

				            self.wait = false;
				        }).catch((fail) => {
				            self.wait = false;
				        });

				        this.wait = true;
			    	},
			    	open: (task, item, data, type, post) => {
			    		if (item) {
			    			switch (task) {
			    				case 'edit':
			    				    axios.get(`{{route('core.paths', ['task' => 'load'])}}/${item.hash}`, {})
							             .then((data) => {
							            self.form = {
                                            list: data.data.list?.map((stop) => (stop.gift.hash)),
                                            lock: data.data.lock,
											pass: data.data.pass,
                                            pick: data.data.pick,
											code: data.data.code,
                                            tone: data.data.tone,
                                            name: data.data.name
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

                                    plat = null;
			    					break;
								case 'spot':
									setTimeout(() => {
                                        plat = new google.maps.Map(document.getElementById('plat'), {
                                            mapTypeControl: false,
                                            mapTypeId: google.maps.MapTypeId.ROADMAP,
                                            center: new google.maps.LatLng(self.form.spot[0], self.form.spot[1]),
                                            zoom: 12
                                        });

										plat.addListener('center_changed', () => {
											self.form.spot = [plat.getCenter().lat(), plat.getCenter().lng()];
										})
                                    }, 200);

									self.form.spot = item.spot ?? [6.2442876, -75.6162309];

									self.show.spot = true;

									self.pick = item;
									break;
                                case 'pass':
                                    self.show.form.note = null;

			    			        self.show.pass = true;
                                    
			    			    	self.pick = item;

									self.more = null;
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
			    			}
			    		} else {
			    			switch (task) {
			    				case 'make':
			    					self.form = {
                                        code: null,
										name: null,
										pick: null,
										list: [],
                                        lock: 0
                                    };

					             	self.pick = null;

									self.view = 2;

                                    plat = null;
			    					break;
			    			}
			    		}
			    	},
                    mark: (item) => {
                        if (item) {
                            plat.setCenter(new google.maps.LatLng(item.spot[0], item.spot[1]));
                        } else {
                            if (navigator.geolocation) {
                                navigator.geolocation.getCurrentPosition((data) => {
                                    spot.setPosition(new google.maps.LatLng(data.coords.latitude, data.coords.longitude));
                                    
                                    plat.setCenter(spot.getPosition());
                                }, (fail) => {
                                    switch (fail.code) {
                                        case fail.POSITION_UNAVAILABLE:
                                            self.note = {
                                                show: true,
                                                type: 'fail',
                                                text: 'La geolocalización no está disponible.'
                                            };
                                            break
                                        case fail.PERMISSION_DENIED:
                                            self.note = {
                                                show: true,
                                                type: 'fail',
                                                text: 'Se denegó la geolocalización.'
                                            };
                                            break
                                        default:
                                            self.note = {
                                                show: true,
                                                type: 'fail',
                                                text: 'No se pudo obtener la geolocalización.'
                                            };
                                    }
                                });
                            }
                        }
					},
			    	save: (form, item) => {
                        var data = new FormData();

		    		    data.append('lock', form.lock ?? 0);

                        data.append('pick', form.pick ?? 0);

		    		    data.append('code', form.code ?? '');

						data.append('name', form.name ?? '');

                        form.list.forEach((hash) => {
                            data.append('list[]', hash);
                        });

                        self.fail =  null;

		    	        if (item) {
				    		axios.post(`{{route('core.paths', ['task' => 'save'])}}/${item.hash}`, data, {'X-CSRF-TOKEN': '{{csrf_token()}}'})
	                             .then((data) => {
                                item.type = form.type;

                                item.echo = form.echo;

								item.date = form.date;

                                item.code = form.code;

                                item.name = form.name;

								item.town = form.town;

	                            self.wait = false;

                                self.view = 1;
								
	                            self.note = {
                                    show: true,
                                	type: 'done',
                                	text: data.data.text
                                };
	                        })
	                        .catch((fail) => {
	                            if (fail.response?.data?.text) {
                                    self.note = {
                                        show: true,
                                        type: 'fail',
                                        text: fail.response.data.text
                                    };

	                            	self.fail = fail.response.data.list ?? {};
	                            } else {
                                    self.note = {
                                        show: true,
                                        type: 'fail',
                                        text: 'Se presentó un error inesperado.'
                                    };
	                            }

                                self.wait = false;
	                        });
				    	} else {
				    		axios.post("{{route('core.paths', ['task' => 'make'])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
	                             .then((data) => {
	                            self.data.list.unshift((item = {
                                    item: data.data.item,
                                    hash: data.data.hash,
                                    code: data.data.code,
                                    tone: data.data.tone,
                                    lock: form.lock,
                                    type: form.type,
                                    echo: form.echo,
									date: form.date,
                                    name: form.name,
                                    town: form.town
                                }));

                                self.form = {
                                    code: null,
                                    name: null,
                                    pick: null,
                                    list: [],
                                    lock: 0
                                };

                                self.note = {
                                    show: true,
                                	type: 'done',
                                	text: data.data.text
                                };

                                self.wait = false;
	                        })
	                        .catch((fail) => {
	                            self.wait = false;

	                            if (fail.response?.data?.text) {
                                    self.note = {
                                        show: true,
                                        type: 'fail',
                                        text: fail.response.data.text
                                    };

	                            	self.fail = fail.response.data.list ?? {};
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
			    	},
                    pass: (item, pass, note) => {
						var data = new FormData();

		    		    data.append('pass', pass ?? 0);

						data.append('note', note ?? '');

			    		axios.post(`{{route('core.paths', ['task' => 'pass'])}}/${item.hash}`, data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
				             .then((data) => {
                            item.pass = data.data.pass;

                            self.show.pass = false;

                            self.wait = false;

                            self.note = {
                                show: true,
                                type: 'done',
                                text: data.data.text
                            };
				        }).catch((fail) => {
				            self.wait = false;

				            if (fail.response?.data?.text) {
								self.fail = {
									text: fail.response.data.text,
									list: fail.response.data.list
								};
                            } else {
								self.fail.text = 'Se presentó un error inesperado.';
                            }
				        });

				        self.wait = true;

						self.fail = {
							text: null,
							list: null
						};
			    	},
					move: (item, spot) => {
			    		var data = new FormData();

                        data.append('spot[]', spot[0] ?? '');

                        data.append('spot[]', spot[1] ?? '');

			    		axios.post(`{{route('core.paths', ['task' => 'move'])}}/${item.hash}`, data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
                             .then((data) => {
                            self.show.spot = false;

                            self.wait = false;

							item.spot = spot;

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
                            } else {
                                self.note = {
                                    show: true,
                                    type: 'fail',
                                    text: 'Se presentó un error inesperado.'
                                };
                            }
                        });

                        self.wait = true;
			    	},
			    	lock: (item, flag) => {
			    		axios.get(`{{route('core.paths', ['task' => 'lock'])}}/${item.hash}?flag=${flag}`, {})
				             .then((data) => {
                            self.show.lock = false;

                            self.show.wait = false;

                            self.wait = false;

                            item.lock = flag;

                            self.note = {
                                show: true,
                                type: 'done',
                                text: data.data.text
                            };
				        }).catch((fail) => {
				            self.wait = false;

				            self.show.lock = false;

				            self.show.wait = false;

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

				        self.wait = true;
			    	},
			    	drop: (item) => {
			    		axios.get(`{{route('core.paths', ['task' => 'drop'])}}/${item.hash}`, {})
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
                        axios.get(`{{route('core.users', ['task' => 'pull', 'type' => 3])}}`)
							.then((data) => {
							self.pile.pick.list = data.data.list;

							self.pile.pick.wait = false;
						}).catch((fail) => {
							self.pile.pick.wait = false;
						});

                        axios.get(`{{route('core.donations', ['task' => 'pull'])}}`)
							.then((data) => {
							self.pile.stop.list = data.data.list;

							self.pile.stop.wait = false;
						}).catch((fail) => {
							self.pile.spot.wait = false;
						});

						code = new google.maps.Geocoder();

                        self.pile.pick.wait = true;

                        self.pile.stop.wait = true;

	             		self.done = true;
	             	}, 500);
			    }
			})
  		});
  	</script>
@stop