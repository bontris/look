@extends('team')

@section('page')
	<v-layout column>
		<v-row class="ma-0">
			<v-col class="pa-0">
				<v-layout class="white px-2">
					<v-list>
						<v-list-item class="px-1">
							<v-list-item-avatar
                                color="#009688"
                                size="36"
                                tile>
								<v-icon color="white">
									mdi-domain
								</v-icon>
							</v-list-item-avatar>
							<v-list-item-content class="pa-0">
								<v-list-item-title>
									Empresas
								</v-list-item-title>
								<v-list-item-subtitle>
									<v-breadcrumbs
										class="pa-0"
										:items="[{text: 'Inicio', href: '{{route('dash')}}'}, {text: 'Empresas'}]">
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
												LISTADO DE EMPRESAS
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
												@{{pick ? 'EDITAR EMPRESA' : 'NUEVA EMPRESA'}}
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
									{text: 'Logo', width: 80, align: 'start', sortable: false},
									{text: 'NIT', width: 180, align: 'start', sortable: false},
									{text: 'Nombre', align: 'start', sortable: false},
									{text: 'Correo', align: 'start', sortable: false},
                                    {text: 'Tipo', width: 180, align: 'center', sortable: false},
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
										<td>
                                            <v-list>
												<v-list-item>
                                                    <v-list-item-avatar
                                                        :disabled="wait"
                                                        :color="(item.icon ? 'white' : `#${item.tone}`)"
                                                        size="36">
                                                        <label class="c-pointer">
                                                            <v-img
                                                                v-if="item.icon"
                                                                :src="`/snaps/${item.icon}/thumb`"
                                                                :width="36"
                                                                cover>
                                                            </v-img>
                                                            <span
                                                                class="font-weight-bold white--text caption"
                                                                v-else>
                                                                @{{item.name.charAt(0).toUpperCase()}}
                                                            </span>
                                                            <input class="d-none" type="file" accept="image/*" @change="open('crop', item, $event.target?.files.item(0), 1, true)">
                                                        </label>
                                                    </v-list-item-avatar>
                                                    <v-list-item-content v-if="tiny">
                                                        <v-list-item-title>
                                                            @{{item.name}}
                                                        </v-list-item-title>
                                                        <v-list-item-subtitle>
                                                            @{{item.card}}
                                                        </v-list-item-subtitle>
                                                    </v-list-item-content>
												</v-list-item>
											</v-list>
										</td>
										<td v-if="none(tiny)">
											<div class="font-weight-medium">
												@{{item.card}}
											</div>
										</td>
										<td v-if="none(tiny)">
											<div class="font-weight-medium">
												@{{item.name}}
											</div>
										</td>
										<td v-if="none(tiny)">
											<div class="font-weight-medium">
												@{{item.mail}}
											</div>
										</td>
                                        <td 
											class="text-center"
											v-if="none(tiny)">
											<div class="font-weight-medium">
												<v-chip
                                                    text-color="white"
                                                    :color="{1: '#546E7B', 2: '#7B1FB2', 3: '#1E88E5', 4: '#E53935', 5: '#388E3B', 6: '#FB8B00'}[item.type]"
                                                    small>
													<v-icon left>
														@{{({1: 'mdi-offer', 2: 'mdi-lipstick', 3: 'mdi-store', 4: 'mdi-bed', 5: 'mdi-truck', 6: 'mdi-hard-hat'}[item.type])}}
													</v-icon>
													@{{{1: 'Servicio', 2: 'Estética', 3: 'Minorista', 4: 'Hospedaje', 5: 'Transporte', 6: 'Construcción'}[item.type]}}
												</v-chip>
											</div>
										</td>
                                        <td
											v-if="none(tiny)"
											class="text-end">
											<div class="font-weight-medium">
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
															<v-icon>mdi-map-marker</v-icon>
														</v-list-item-icon>
														<v-list-item-title>Ubicar</v-list-item-title>
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
													<v-icon>mdi-map-marker</v-icon>
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
                                        <v-col :cols="none(tiny, 6, 12)">
											<v-select
												item-value="item"
												item-text="text"
												:items="[{item: 1, text: 'Servicio'}, {item: 2, text: 'Estética'}, {item: 3, text: 'Minorista'}, {item: 4, text: 'Hospedaje'}, {item: 5, text: 'Transporte'}, {item: 6, text: 'Construcción'}]"
												v-model="form.type"
												:error-messages="[fail?.type].filter(Boolean)"
												label="Tipo"
                                                class="bind"
												:disabled="wait"
												hide-details="auto"
												filled> 
											</v-select>
										</v-col>
                                        <v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
                                                class="bind"
												label="NIT"
												autocomplete="off"
												hide-details="auto"
												v-model="form.card"
												:error-messages="[fail?.card].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
									</v-row>
									<v-row dense>
										<v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
                                                class="bind"
												label="Nombre"
												autocomplete="off"
												hide-details="auto"
												v-model="form.name"
												:error-messages="[fail?.name].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
                                        <v-col :cols="none(tiny, 6, 12)">
                                            <v-autocomplete
												:items="pile.hand.list"
												item-value="item"
												no-data-text="No hay opciones"
												label="Gerente"
												v-model="form.hand"
												hide-details="auto"
												:filter="function (item, find, text) {return ((~item.card.indexOf(find.toLocaleUpperCase())) || (~`${item.name} ${item.last}`.toLocaleLowerCase().indexOf(find.toLocaleLowerCase())))}"
												:error-messages="[fail?.hand].filter(Boolean)"
												:disabled="wait"
												filled>
												<template v-slot:selection="data">
													@{{data.item.name}} @{{data.item.last}}
												</template>
												<template v-slot:item="data">
													<v-list-item-content>
														<v-list-item-title>@{{data.item.name}} @{{data.item.last}}</v-list-item-title>
                                                        <v-list-item-subtitle>@{{data.item.card}}</v-list-item-title>
													</v-list-item-content>
												</template>
											</v-autocomplete>
										</v-col>
									</v-row>
									<v-row dense>
										<v-col :cols="none(tiny, 6, 12)">
											<v-select
												item-value="item"
												item-text="text"
												:items="[{item: '-05:00', text: 'America / Bogotá'}, {item: '-04:00', text: 'America / Caracas'}, {item : '-03:00', text: 'America / Buenos Aires'}]"
												v-model="form.time"
												:error-messages="[fail?.time].filter(Boolean)"
												label="Zona horaria"
                                                class="bind"
												:disabled="wait"
												hide-details="auto"
												filled> 
											</v-select>
										</v-col>
                                        <v-col :cols="none(tiny, 6, 12)">
											<v-select
												item-value="item"
												item-text="text"
												:items="[{item: 'COP', text: 'Peso colombianos'}, {item: 'USD', text: 'Dólar estadounidenses'}]"
												v-model="form.coin"
												:error-messages="[fail?.coin].filter(Boolean)"
												label="Moneda"
                                                class="bind"
												:disabled="wait"
												hide-details="auto"
												filled> 
											</v-select>
										</v-col>
									</v-row>
                                    <v-row dense>
										<v-col :cols="none(tiny, 6, 12)">
											<v-file-input
												accept="image/jpeg,image/png"
												label="Logotipo"
												@change="open('crop', pick, $event, 1, false)"
                                                clear-icon="mdi-close-circle"
												prepend-inner-icon="mdi-camera"
												:error-messages="[fail?.icon].filter(Boolean)"
                                                hide-details="auto"
												filled>
											</v-file-input>
										</v-col>
										<v-col :cols="none(tiny, 6, 12)">
											<v-file-input
												accept="image/jpeg,image/png"
												label="Portada"
												@change="open('crop', pick, $event, 2, false)"
                                                clear-icon="mdi-close-circle"
												prepend-inner-icon="mdi-camera"
												:error-messages="[fail?.code].filter(Boolean)"
                                                hide-details="auto"
												filled>
											</v-file-input>
										</v-col>
									</v-row>
                                    <v-row dense>
										<v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
												label="Departamento"
												hide-details="auto"
												autocomplete="off"
												v-model="form.zone"
												:error-messages="[fail?.zone].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
										<v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
												label="Municipio"
												hide-details="auto"
												autocomplete="off"
												v-model="form.town"
												:error-messages="[fail?.town].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
									</v-row>
                                    <v-row dense>
										<v-col :cols="none(tiny, 4, 12)">
											<v-text-field
												type="text"
												label="Dirección"
												hide-details="auto"
												autocomplete="off"
												v-model="form.path"
												:error-messages="[fail?.path].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
                                        <v-col :cols="none(tiny, 2, 12)">
											<v-text-field
												type="text"
												label="Oficina"
												hide-details="auto"
												autocomplete="off"
												v-model="form.flat"
												:error-messages="[fail?.flat].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
										<v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
												label="Código postal"
												hide-details="auto"
												autocomplete="off"
												v-model="form.post"
												:error-messages="[fail?.post].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
									</v-row>
									<v-row dense>
										<v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
												label="Teléfono"
												hide-details="auto"
												autocomplete="off"
												v-model="form.work"
												:error-messages="[fail?.work].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
										<v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
												label="Correo"
												hide-details="auto"
												autocomplete="off"
												v-model="form.mail"
												:error-messages="[fail?.mail].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
									</v-row>
                                    <v-row dense>
                                        <v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
												label="Página"
												autocomplete="off"
												hide-details="auto"
												v-model="form.page"
												:error-messages="[fail?.page].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
                                        <v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
												label="Color"
												autocomplete="off"
												hide-details="auto"
												v-model="form.tone"
												:error-messages="[fail?.tone].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
									</v-row>
									<v-row dense>
										<v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
												label="Latitud"
												hide-details="auto"
												autocomplete="off"
												v-model="form.spot[0]"
												:error-messages="[fail?.spot].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
										<v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
												label="Longitud"
												hide-details="auto"
												autocomplete="off"
												v-model="form.spot[1]"
												:error-messages="[fail?.spot].filter(Boolean)"
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
												:error-messages="[fail?.note].filter(Boolean)"
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
		v-model="show.crop"
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
								mdi-image-edit
							</v-icon>
						</v-list-item-avatar>
						<v-list-item-content>
							<v-list-item-title class="text-uppercase">
                                Imágen
							</v-list-item-title>
							<v-list-item-subtitle>
                                Editar imágen seleccionada.
							</v-list-item-subtitle>
						</v-list-item-content>
                        <v-list-item-action v-if="tiny">
                            <v-btn
                                @click="(show.crop = false)"
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
        				<v-crop
							ref="snap"
							:boot="boot"
							:ratio="({1: 1.1, 2: 1.6}[show.type] ?? 1)">
						</v-crop>
        			</v-col>
        		</v-row>
			</v-card-text>
			<v-divider>
			</v-divider>
			<v-card-actions class="grey lighten-4">
				<v-btn
					class="mr-2"
					color="blue"
					@click="snap.zoom(0.1)"
                    :disabled="wait"
					icon>
					<v-icon>mdi-magnify-plus</v-icon>
				</v-btn>
				<v-btn
					color="blue"
					@click="snap.zoom(-0.1)"
                    :disabled="wait"
					icon>
					<v-icon>mdi-magnify-minus</v-icon>
				</v-btn>
				<v-btn
					class="mr-2"
					color="blue"
					@click="snap.rotate(90)"
                    :disabled="wait"
					icon>
					<v-icon>mdi-rotate-right</v-icon>
				</v-btn>
				<v-btn
					color="blue"
					@click="snap.rotate(-90)"
                    :disabled="wait"
					icon>
					<v-icon>mdi-rotate-left</v-icon>
				</v-btn>
				<v-spacer>
				</v-spacer>
				<v-btn
                    v-if="none(tiny)"
					class="mr-2"
					color="secondary"
					@click="(show.crop = false)"
					:disabled="wait"
					tile
					text>
					Cancelar
				</v-btn>
				<v-btn
					class="mr-2"
					color="success"
					@click="shot(pick, snap, show.type, show.post)"
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
		v-model="show.spot"
		:width="none(tiny, 768, '100%')"
		persistent
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
			<v-container>
        		<v-row dense>
        			<v-col>
						<div
							id="plat"
							class="mark"
							:style="{height: none(tiny, '480px', '360px')}">
						</div>
        			</v-col>
        		</v-row>
			</v-container>
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
          		¿Desea eliminar la imágen de la empresa <b>@{{pick?.name}} / @{{pick?.card}}</b>?
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
          		¿Desea eliminar la empresa <b>@{{pick?.name}} / @{{pick?.card}}</b>?
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
                                Cambiar el estado del registro.
							</v-list-item-subtitle>
						</v-list-item-content>
					</v-list-item>
				</v-list>
            </v-card-title>
            <v-divider>
            </v-divider>
            <v-card-text class="mt-4">
                <template v-if="same(pick?.lock, 1)">
                    ¿Desea habilitar la empresa <b>@{{pick?.name}} / @{{pick?.card}}</b>?
                </template>
            	<template v-else>
                    ¿Desea bloquear la empresa <b>@{{pick?.name}} / @{{pick?.card}}</b>?
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
					view: 1,
                    page: {
			  			page: 1,
			  			itemsPerPage: 32
			  		},
					pile: {
						hand: {
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
			  			view: false,
			  			crop: false,
			  			wipe: false,
			  			lock: false,
			  			drop: false,
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
			  			lock: null,
			  			test: null,
                        type: null,
			  			back: null,
                        icon: null,
			  			card: null,
                        zone: null,
                        town: null,
                        path: null,
                        post: null,
						flat: null,
						time: null,
						coin: null,
			  			name: null,
                        head: null,
			  			page: null,
			  			work: null,
			  			mail: null,
			  			note: null,
						spot: ((spot) => {
							if (navigator.geolocation) {
								navigator.geolocation.getCurrentPosition((data) => {
									spot[0] = data.coords.latitude;

									spot[1] = data.coords.longitude;
								}, (fail) => {
									switch (fail.code) {
										case fail.POSITION_UNAVAILABLE:
											self.note = {
												show: true,
												type: 'fail',
												text: 'La información de ubicación no está disponible.'
											};
											break
										case fail.PERMISSION_DENIED:
											self.note = {
												show: true,
												type: 'fail',
												text: 'Se denegó la solicitud de geolocalización.'
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

							return spot;
						})([null, null])
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
			    		axios.get(`{{route('core.shops', ['task' => 'load'])}}?take=${take ?? ''}&page=${page ?? ''}&seek=${seek ?? ''}`, {})
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
			    				    axios.get(`{{route('core.shops', ['task' => 'load'])}}/${item.hash}`, {})
							             .then((data) => {
							            self.form = {
                                            icon: null,
                                            back: null,
                                            lock: data.data.lock,
                                            test: data.data.test,
                                            type: data.data.type,
                                            code: data.data.code,
                                            tone: data.data.tone,
                                            card: data.data.card,
                                            zone: data.data.zone,
                                            town: data.data.town,
                                            path: data.data.path,
                                            post: data.data.post,
											flat: data.data.flat,
											time: data.data.time,
											coin: data.data.coin,
                                            name: data.data.name,
                                            head: data.data.head,
                                            work: data.data.work,
                                            mail: data.data.mail,
                                            page: data.data.page,
                                            note: data.data.note,
											spot: data.data.spot
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
								case 'spot':
									setTimeout(() => {
                                        plat = new google.maps.Map(document.getElementById('plat'), {
                                            zoom: 14,
                                            center: new google.maps.LatLng((item.spot ?? [])[0] ?? 0, (item.spot ?? [])[1] ?? 0),
                                            mapTypeId: google.maps.MapTypeId.ROADMAP,
											disableDoubleClickZoom: true
                                        });

										plat.addListener('center_changed', () => {
											self.form.spot = [plat.getCenter().lat(), plat.getCenter().lng()];
										})
                                    }, 200);

									self.form.spot = item.spot ?? [];

									self.show.spot = true;

									self.pick = item;
									break;
			    				case 'crop':
			    				    if (data) {
										var file = new FileReader();

										file.onload = (event) => {
											self.file = event.target.result;

											self.show.post = post;

											self.show.type = type;

											self.show.crop = true;

											self.pick = item;

											self.snap.replace(self.file, false);
										};

										file.readAsDataURL(data);

										switch (type) {
											case 1:
												self.form.icon = data;
												break;
											case 2:
												self.form.back = data;
												break;
										}
									} else {
										switch (type) {
											case 1:
												self.form.icon = null;
												break;
											case 2:
												self.form.back = null;
												break;
										}
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
			    					self.form = {
                                        lock: 0,
                                        test: 0,
                                        type: 0,
                                        head: 0,
										spot: [
											null,
											null
										],
                                        icon: null,
                                        back: null,
                                        code: null,
                                        card: null,
                                        zone: null,
                                        town: null,
                                        path: null,
                                        post: null,
										flat: null,
										time: null,
										coin: null,
                                        name: null,
                                        work: null,
                                        mail: null,
                                        page: null,
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

		    		    data.append('test', form.test ?? 0);

		    		    data.append('type', form.type ?? 0);

                        data.append('head', form.head ?? 0);

                        data.append('tone', form.tone ?? '');

						data.append('icon', form.icon ?? '');

						data.append('back', form.back ?? '');

		    		    data.append('code', form.code ?? '');

		    		    data.append('card', form.card ?? '');

                        data.append('zone', form.zone ?? '');

						data.append('town', form.town ?? '');

						data.append('path', form.path ?? '');

                        data.append('post', form.post ?? '');

						data.append('flat', form.flat ?? '');

						data.append('time', form.time ?? '');

						data.append('coin', form.coin ?? '');

		    		    data.append('name', form.name ?? '');

		    	        data.append('work', form.work ?? '');

		    	        data.append('mail', form.mail ?? '');

                        data.append('page', form.page ?? '');

		    	        data.append('note', form.note ?? '');

						data.append('spot[]', form.spot[0] ?? '');

                        data.append('spot[]', form.spot[1] ?? '');

                        self.fail =  null;

		    	        if (item) {
				    		axios.post(`{{route('core.shops', ['task' => 'save'])}}/${item.hash}`, data, {'X-CSRF-TOKEN': '{{csrf_token()}}'})
	                             .then((data) => {
                                item.icon = data.data.face ?? item.icon;

                                item.type = form.type;

                                item.code = form.code;

                                item.name = form.name;

				    	        item.mail = form.mail;

								item.spot = form.spot;

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
				    		axios.post("{{route('core.shops', ['task' => 'make'])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
	                             .then((data) => {
	                            self.data.list.unshift((item = {
                                    item: data.data.item,
                                    hash: data.data.hash,
                                    code: data.data.code,
                                    tone: data.data.tone,
                                    icon: data.data.icon,
                                    lock: form.lock,
                                    type: form.type,
                                    name: form.name,
                                    mail: form.mail,
									spot: form.spot
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
                    shot: (item, snap, type, post) => {
						if (post) {
							var data = new FormData();

							data.append('file', ((type, snap) => {
								switch (type) {
									case 1:
										return self.blob(snap.getCroppedCanvas({width: 64, height: 64}));
									case 2:
										return self.blob(snap.getCroppedCanvas({width: 640, height: 360}));
								}
							})(type, snap));

							axios.post(`{{route('core.shops')}}/$@{{1: 'icon', 2: 'back'}[type]}/${item.hash}`, data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
								.then((data) => {
								switch (type) {
									case 1:
										item.icon = data.data.file;
										break;
									case 2:
										item.back = data.data.file;
										break;
								}

								self.show.crop = false;

								self.wait = false;

								self.note = {
									show: true,
									type: 'done',
									text: data.data.text
								};
							})
							.catch((fail) => {
								self.show.wipe = false;

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
						} else {
							self.show.crop = false;

							switch (type) {
								case 1:
									self.form.icon = self.blob(snap.getCroppedCanvas({width: 64, height: 64}));
									break;
								case 2:
									self.form.back = self.blob(snap.getCroppedCanvas({width: 640, height: 360}));
									break;
							}
						}
			    	},
					move: (item, spot) => {
			    		var data = new FormData();

                        data.append('spot[]', spot[0] ?? '');

                        data.append('spot[]', spot[1] ?? '');

			    		axios.post(`{{route('core.shops', ['task' => 'move'])}}/${item.hash}`, data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
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
			    		axios.get(`{{route('core.shops', ['task' => 'lock'])}}/${item.hash}?flag=${flag}`, {})
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
			    		axios.get(`{{route('core.shops', ['task' => 'drop'])}}/${item.hash}`, {})
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
	             		self.done = true;
	             	}, 500);
			    }
			})
  		});
  	</script>
@stop