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
                                    mdi-flag
								</v-icon>
							</v-list-item-avatar>
							<v-list-item-content class="pa-0">
								<v-list-item-title>
									Fases
								</v-list-item-title>
								<v-list-item-subtitle>
									<v-breadcrumbs
										class="pa-0"
										:items="[{text: 'Inicio', href: '{{route('dash')}}'}, {text: 'Fases'}]">
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
												LISTADO DE FASES
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
												@{{pick ? 'EDITAR FASE' : 'NUEVA FASE'}}
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
									{text: 'Nombre', align: 'start', sortable: false},
                                    {text: 'Proyecto', align: 'start', sortable: false},
                                    {text: 'Duración', width: 140, align: 'center', sortable: false},
									{text: 'Avance', width: 140, align: 'center', sortable: false},
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
                                                    <v-list-item-content>
                                                        <v-list-item-title>
                                                            @{{item.name}}
                                                        </v-list-item-title>
                                                        <v-list-item-subtitle v-if="tiny">
                                                            @{{item.work}}
                                                        </v-list-item-subtitle>
                                                    </v-list-item-content>
												</v-list-item>
											</v-list>
										</td>
                                        <td v-if="none(tiny)">
											<div class="font-weight-medium">
												@{{item.work}}
											</div>
										</td>
                                        <td
                                            v-if="none(tiny)"
                                            class="text-center">
											<div class="font-weight-medium">
                                                @{{item.term}} / @{{{1: 'Horas', 2: 'Días'}[item.sort]}}
											</div>
										</td>
										<td
                                            v-if="none(tiny)"
                                            class="text-center">
											<div class="font-weight-medium">
                                                @{{item.rate}}%
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
                                            <v-autocomplete
												:items="pile.work.list"
												item-value="item"
												no-data-text="No hay opciones"
												label="Proyecto"
												v-model="form.bind"
												hide-details="auto"
												:filter="function (item, find, text) {return ((~item.code.indexOf(find.toLocaleUpperCase())) || (~item.name.toLocaleLowerCase().indexOf(find.toLocaleLowerCase())))}"
												:error-messages="[fail?.bind].filter(Boolean)"
												:disabled="wait"
												required
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
                                        <v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
												label="Código"
												autocomplete="off"
												hide-details="auto"
												v-model="form.code"
												:error-messages="[fail?.code].filter(Boolean)"
												:disabled="wait"
												:required="none(pick, false, true)"
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
												:items="pile.step.list.filter((next) => (same(next.bind, form.bind) && same(same(next.item, pick?.item), false)))"
												item-value="item"
												no-data-text="No hay opciones"
												clear-icon="mdi-close-circle"
												label="Antecesora"
												v-model="form.back"
												hide-details="auto"
												:filter="function (item, find, text) {return ((~item.code.indexOf(find.toLocaleUpperCase())) || (~item.name.toLocaleLowerCase().indexOf(find.toLocaleLowerCase())))}"
												:error-messages="[fail?.back].filter(Boolean)"
												:disabled="wait"
												clearable
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
                                        <v-col :cols="none(tiny, 3, 12)">
											<v-select
                                                class="bind"
												label="Avance"
												item-value="item"
												item-text="text"
												:items="[{item: 1, text: 'Nominal'}, {item: 2, text: 'Porcentual'}]"
												v-model="form.mode"
												:error-messages="[fail?.mode].filter(Boolean)"
												:disabled="wait"
												hide-details="auto"
												filled> 
											</v-select>
										</v-col>
                                        <v-col :cols="none(tiny, 3, 12)">
											<v-text-field
												type="text"
												label="Porcentaje"
												autocomplete="off"
												hide-details="auto"
												v-model="form.rate"
												:error-messages="[fail?.rate].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
										<v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
												label="Costo"
												autocomplete="off"
												hide-details="auto"
												v-model="form.cost"
												:error-messages="[fail?.cost].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
									</v-row>
                                    <v-row v-if="same(form.mode, 1)" dense>
										<v-col :cols="none(tiny, 6, 12)">
											<v-select
												item-value="item"
												:item-text="(item) => (item?.data?.name['ES'] ?? item.name)"
												:items="pile.unit.list"
												v-model="form.unit"
												:error-messages="[fail?.unit].filter(Boolean)"
												label="Unidad"
												:disabled="wait"
												hide-details="auto"
												filled> 
											</v-select>
										</v-col>
                                        <v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
												label="Cantidad"
												autocomplete="off"
												hide-details="auto"
												v-model="form.size"
												:error-messages="[fail?.size].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
									</v-row>
                                    <v-row dense>
                                        <v-col :cols="none(tiny, 3, 12)">
											<v-select
                                                class="bind"
												label="Período"
												item-value="item"
												item-text="text"
												:items="[{item: 1, text: 'Horas'}, {item: 2, text: 'Días'}]"
												v-model="form.sort"
												:error-messages="[fail?.sort].filter(Boolean)"
												:disabled="wait"
												hide-details="auto"
												filled> 
											</v-select>
										</v-col>
										<v-col :cols="none(tiny, 3, 12)">
											<v-text-field
												type="text"
                                                class="bind"
												label="Duración"
												autocomplete="off"
												hide-details="auto"
												v-model="form.term"
												:error-messages="[fail?.term].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
										<v-col :cols="none(tiny, 3, 12)">
											<v-select
												item-value="item"
												item-text="text"
												:items="[{item: 0, text: 'Sí'}, {item: 1, text: 'No'}]"
												v-model="form.pass"
												:error-messages="[fail?.pass].filter(Boolean)"
												label="Autorización"
												:disabled="wait"
												hide-details="auto"
												filled> 
											</v-select>
										</v-col>
										<v-col :cols="none(tiny, 3, 12)">
											<v-select
												item-value="item"
												item-text="text"
												:items="[{item: 1, text: 'Sí'}, {item: 0, text: 'No'}]"
												v-model="form.spot"
												:error-messages="[fail?.spot].filter(Boolean)"
												label="Localización"
												:disabled="wait"
												hide-details="auto"
												filled> 
											</v-select>
										</v-col>
									</v-row>
									<v-row>
										<v-col>
											<v-sheet class="text-button">
												Adjuntos
											</v-sheet>
											<v-divider>
											</v-divider>
										</v-col>
								    </v-row>
									<v-row
										v-for="item, slot in form.disk"
										dense>
										<v-col>
											<v-row dense>
												<v-col class="d-flex">
													<v-text-field
														type="text"
														class="bind mr-2"
														title="Nombre"
														placeholder="Nombre"
														autocomplete="off"
														v-model="item.name"
														hide-details="auto"
														:disabled="wait"
														filled
														dense>
													</v-text-field>
													<v-select
														class="bind mr-2"
														style="max-width: 160px"
														title="Tipo"
														item-value="item"
														item-text="text"
														:items="[{item: 1, text: 'Documento'},
														         {item: 2, text: 'Cálculo'},
														         {item: 3, text: 'Presentación'},
																 {item: 4, text: 'Dibujo'},
																 {item: 5, text: 'Imágen'},
																 {item: 6, text: 'Video'},
																 {item: 7, text: 'Audio'}]"
														v-model="item.type"
														placeholder="Tipo"
														hide-details="auto"
														:disabled="wait"
														filled
														dense>
													</v-select>
													<v-select
														class="bind mr-4"
														style="max-width: 160px"
														title="Requerido"
														item-value="item"
														item-text="text"
														:items="[{item: 1, text: 'Sí'},
																 {item: 0, text: 'No'}]"
														v-model="item.bind"
														placeholder="Requerido"
														hide-details="auto"
														:disabled="wait"
														filled
														dense>
													</v-select>
													<v-btn
														@click="form.disk.splice(slot, 1)"
														:disabled="(same(form.disk.length, 1) || wait)"
														style="width: 40px; height: 40px"
														title="Quitar"
														class="mr-2"
														outlined
														icon
														tile>
														<v-icon>
															M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z
														</v-icon>
													</v-btn>
													<v-btn
														@click="form.disk.splice(slot + 1, 0, {name: null, type: null, bind: null})"
														:disabled="(same(form.disk.length, 10) || wait)"
														style="width: 40px; height: 40px"
														title="Agregar"
														outlined
														icon
														tile>
														<v-icon>
															M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z
														</v-icon>
													</v-btn>
												</v-col>
											</v-row>
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
                                        <v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
												label="Etiquetas"
												autocomplete="off"
												hide-details="auto"
												v-model="form.mask"
												:error-messages="[fail?.mask].filter(Boolean)"
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
										<v-col>
											<v-textarea
												label="Notas"
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
						work: {
							wait: false,
							text: null,
							list: []
						},
                        step: {
							wait: false,
							text: null,
							list: []
						},
						unit: {
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
						pass: null,
						spot: null,
						disk: null,
                        sort: null,
                        mode: null,
                        bind: null,
						back: null,
                        term: null,
						lead: null,
			  			size: null,
                        rate: null,
                        cost: null,
						bond: null,
                        unit: null,
						code: null,
			  			name: null,
			  			note: null,
                        mask: null
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
			    		axios.get(`{{route('core.steps', ['task' => 'load'])}}?take=${take ?? ''}&page=${page ?? ''}&seek=${seek ?? ''}`, {})
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
			    				    axios.get(`{{route('core.steps', ['task' => 'load'])}}/${item.hash}`, {})
							             .then((data) => {
							            self.form = {
                                            lock: data.data.lock,
											pass: data.data.pass,
											spot: data.data.spot,
                                            sort: data.data.sort,
                                            mode: data.data.mode,
                                            bind: data.data.bind,
											back: data.data.back,
                                            tone: data.data.tone,
                                            cost: data.data.cost,
											bond: data.data.bond,
                                            term: data.data.term,
											lead: data.data.lead,
                                            size: data.data.size,
                                            rate: data.data.rate,
                                            unit: data.data.unit,
											code: data.data.code,
                                            name: data.data.name,
                                            note: data.data.note,
                                            mask: data.data.mask,
											disk: data.data.disk
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

									self.form.link = 0;

							        self.pick = item;
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
                                        lock: 0,
										pass: 0,
										bind: 0,
										spot: 1,
                                        sort: null,
										back: null,
                                        sort: null,
                                        mode: null,
                                        term: null,
										lead: null,
                                        size: null,
                                        rate: null,
                                        cost: null,
										bond: null,
                                        unit: null,
										code: null,
                                        name: null,
                                        note: null,
                                        mask: null,
										disk: [{name: null, type: null, bind: null}]
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

						data.append('pass', form.pass ?? 0);

						data.append('spot', form.spot ?? 0);

		    		    data.append('sort', form.sort ?? 0);

                        data.append('mode', form.mode ?? 0);

                        data.append('bind', form.bind ?? 0);

						data.append('back', form.back ?? 0);

                        data.append('tone', form.tone ?? '');

		    		    data.append('cost', form.cost ?? '');

						data.append('bond', form.bond ?? '');

                        data.append('term', form.term ?? '');

						data.append('lead', form.lead ?? '');

                        data.append('size', form.size ?? '');

                        data.append('rate', form.rate ?? '');

                        data.append('unit', form.unit ?? '');

						data.append('code', form.code ?? '');

                        data.append('name', form.name ?? '');

		    	        data.append('note', form.note ?? '');

                        data.append('mask', form.mask ?? '');

						data.append('disk', JSON.stringify(form.disk?.filter((item) => (
							item.name?.trim()
						))));
						
		    	        if (item) {
				    		axios.post(`{{route('core.steps', ['task' => 'save'])}}/${item.hash}`, data, {'X-CSRF-TOKEN': '{{csrf_token()}}'})
	                             .then((data) => {
                                item.sort = form.sort;

                                item.mode = form.mode;

                                item.term = form.term;

								item.lead = form.lead;

                                item.size = form.size;

                                item.rate = form.rate;

                                item.cost = form.cost;

								item.bond = form.bond;

                                item.unit = form.unit;

								item.code = form.code;

                                item.name = form.name;

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
				    		axios.post("{{route('core.steps', ['task' => 'make'])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
	                             .then((data) => {
	                            self.data.list.unshift((item = {
                                    item: data.data.item,
                                    hash: data.data.hash,
                                    code: data.data.code,
                                    tone: data.data.tone,
                                    lock: form.lock,
                                    sort: form.sort,
                                    mode: form.mode,
                                    cost: form.cost,
									bond: form.bond,
                                    term: form.term,
									lead: form.lead,
                                    size: form.size,
                                    rate: form.rate,
                                    unit: form.unit,
                                    name: form.name
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

						self.fail =  null;

				    	self.wait = true;
			    	},
			    	lock: (item, flag) => {
			    		axios.get(`{{route('core.steps', ['task' => 'lock'])}}/${item.hash}?flag=${flag}`, {})
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
			    		axios.get(`{{route('core.steps', ['task' => 'drop'])}}/${item.hash}`, {})
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

						axios.get(`{{route('core.steps', ['task' => 'pull'])}}`)
							.then((data) => {
							self.pile.step.list = data.data.list;

							self.pile.step.wait = false;
						}).catch((fail) => {
							self.pile.step.wait = false;
						});

						axios.get(`{{route('core.chips', ['task' => 'pull', 'type' => 7])}}`)
							.then((data) => {
							self.pile.unit.list = data.data.list;

							self.pile.unit.wait = false;
						}).catch((fail) => {
							self.pile.unit.wait = false;
						});

                        self.pile.work.wait = true;

						self.pile.step.wait = true;

						self.pile.unit.wait = true;

	             		self.done = true;
	             	}, 500);
			    }
			})
  		});
  	</script>
@stop