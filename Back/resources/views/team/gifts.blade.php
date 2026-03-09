@extends('team')

@section('page')
	<v-layout column>
		<v-row class="ma-0">
			<v-col class="pa-0">
				<v-layout class="white px-2">
					<v-list>
						<v-list-item class="px-1">
							<v-list-item-avatar
                                color="#2E7D32"
                                size="36"
                                tile>
								<v-icon color="white">
									mdi-ticket-percent
								</v-icon>
							</v-list-item-avatar>
							<v-list-item-content class="pa-0">
								<v-list-item-title>
									Descuentos
								</v-list-item-title>
								<v-list-item-subtitle>
									<v-breadcrumbs
										class="pa-0"
										:items="[{text: 'Inicio', href: '{{route('dash')}}'}, {text: 'Descuentos'}]">
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
												LISTADO DE DESCUENTOS
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
												@{{pick ? 'EDITAR DECUENTO' : 'NUEVO DECUENTO'}}
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
											clear-icon="mdi-close-circle"
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
								:headers="none(tiny, [
									{sortable: false},
									{text: 'Imágen', width: 80, align: 'start', sortable: false},
									{text: 'Código', width: 180, align: 'start', sortable: false},
									{text: 'Nombre', align: 'start', sortable: false},
                                    {text: 'Empresa', align: 'start', sortable: false},
									{text: 'Valor', width: 80, align: 'center', sortable: false},
                                    {text: 'Fecha', width: 180, align: 'end', sortable: false},
									{sortable: false}
								], [
									{sortable: false},
									{text: 'Nombre', align: 'start', sortable: false},
									{sortable: false}
								])"
								:items="list"
								:server-items-length="high"
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
								:options.sync="data"
								:page.sync="page"
								:loading="true"
								:hide-default-footer="none(tiny, false, true)"
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
											<v-menu
												transition="slide-y-transition"
												offset-y
												right>
												<template v-slot:activator="{on, attrs}">
													<v-list>
														<v-list-item>
															<v-list-item-avatar
																:disabled="wait"
																:color="(item.snap ? 'white' : `#${item.tone}`)"
																size="36"
																v-bind="attrs"
																v-on="on">
																<img
                                                                    v-if="item.snap"
                                                                    :src="`/snaps/${item.snap}/thumb`">
                                                                <span
                                                                    class="font-weight-bold white--text caption"
                                                                    v-else>
                                                                    @{{item.code.charAt(0).toUpperCase()}}
                                                                </span>
															</v-list-item-avatar>
															<v-list-item-content v-if="tiny">
																<v-list-item-title>
																	@{{item.name}}
																</v-list-item-title>
																<v-list-item-subtitle>
																	@{{item.code}}
																</v-list-item-subtitle>
															</v-list-item-content>
														</v-list-item>
													</v-list>
												</template>
												<v-list>
													<label>
														<v-list-item link>
															<v-list-item-icon>
																<v-icon>mdi-camera</v-icon>
															</v-list-item-icon>
															<v-list-item-title>@{{item.snap ? 'Cambiar imágen' : 'Agregar imágen'}}</v-list-item-title>
														</v-list-item>
														<input class="d-none" type="file" accept="image/*" @change="open('crop', item, $event.target)">
													</label>
													<v-list-item v-if="item.snap" link>
														<v-list-item-icon>
															<v-icon>mdi-image-outline</v-icon>
														</v-list-item-icon>
														<v-list-item-title>Mostrar imágen</v-list-item-title>
													</v-list-item>
													<v-divider v-if="item.snap">
													</v-divider>
													<v-list-item v-if="item.snap" @click="open('wipe', item)">
														<v-list-item-icon>
															<v-icon>mdi-delete</v-icon>
														</v-list-item-icon>
														<v-list-item-title>Eliminar imágen</v-list-item-title>
													</v-list-item>
												</v-list>
											</v-menu>
										</td>
										<td v-if="none(tiny)">
											<div class="font-weight-medium">
												@{{item.code}}
											</div>
										</td>
										<td v-if="none(tiny)">
											<div class="font-weight-medium">
												@{{item.name}}
											</div>
										</td>
										<td v-if="none(tiny)">
											<div class="font-weight-medium">
												@{{item.firm}}
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
												@{{date(item.made, 'DD/MM/YYYY')}}
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
														:disabled="bulk.list.length ? true : false"
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
													:disabled="bulk.list.length ? true : false"
													:color="item.lock ? 'red' : 'green'"
													@click="open('lock', item)"
													icon>
													<v-icon>mdi-lock-reset</v-icon>
												</v-btn>
												<v-btn
													@click="open('edit', item)"
													icon>
													<v-icon>mdi-pencil</v-icon>
												</v-btn>
												<v-btn
													@click="open('drop', item)"
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
							<v-divider v-if="tiny && take">
                            </v-divider>
							<v-pagination
								v-if="tiny && take"
								total-visible="5"
								class="my-3"
								v-model="page"
								:length="Math.ceil(high / take)"
								outlined>
							</v-pagination>
						</v-sheet>
						<v-sheet v-else>
							<v-layout
								class="mx-4 my-1 mt-4"
								column>
								<v-sheet>
									<v-row dense>
										<v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
												label="Código"
                                                class="bind"
												autocomplete="off"
												hide-details="auto"
												v-model="form.code"
												:error-messages="[fail?.code].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
                                        <v-col :cols="none(tiny, 6, 12)">
                                            <v-autocomplete
                                                class="bind"
												:items="pile.firm.list"
												item-value="item"
												no-data-text="No hay opciones"
												label="Empresa"
												v-model="form.firm"
												hide-details="auto"
												:filter="function (item, find, text) {return ((~item.card.indexOf(find.toLocaleUpperCase())) || (~item.name.toLocaleLowerCase().indexOf(find.toLocaleLowerCase())))}"
												:error-messages="[fail?.firm].filter(Boolean)"
												:disabled="wait"
												filled>
												<template v-slot:selection="data">
													@{{data.item.name}} 
												</template>
												<template v-slot:item="data">
													<v-list-item-content>
														<v-list-item-title>@{{data.item.name}}</v-list-item-title>
                                                        <v-list-item-subtitle>@{{data.item.card}}</v-list-item-title>
													</v-list-item-content>
												</template>
											</v-autocomplete>
										</v-col>
									</v-row>
									<v-row dense>
										<v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
												label="Nombre"
                                                class="bind"
												autocomplete="off"
												hide-details="auto"
												v-model="form.name"
												:error-messages="[fail?.name].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
                                        <v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
												label="Valor (%)"
                                                class="bind"
												autocomplete="off"
												hide-details="auto"
												v-model="form.rate"
												:error-messages="[fail?.rate].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
									</v-row>
                                    <v-row dense>
										<v-col>
											<v-text-field
												type="text"
												label="Términos"
                                                class="bind"
												hide-details="auto"
												autocomplete="off"
												v-model="form.term"
												:error-messages="[fail?.term].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-textarea>
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
                                Cambiar la imágen del registro.
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
			<v-container>
        		<v-row dense>
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
					@click="face(pick, snap)"
					:disabled="wait"
                    :icon="none(tiny, false, true)"
                    :tile="none(tiny)"
					:text="none(tiny)">
                    <template v-if="none(tiny)">
                        Guardar
                    </template>
                    <template v-else>
                        <v-icon>mdi-content-save<v-icon>
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
          		¿Desea eliminar la imágen del descuento <b>@{{pick?.name}} / @{{pick?.code}}</b>?
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
							<v-list-item-subtitle class="text-truncate">
                                Eliminar el registro permanentemente.
							</v-list-item-subtitle>
						</v-list-item-content>
					</v-list-item>
				</v-list>
            </v-card-title>
          	<v-divider>
            </v-divider>
          	<v-card-text class="mt-4">
          		¿Desea eliminar el descuento <b>@{{pick?.name}} / @{{pick?.code}}</b>?
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
                    ¿Desea habilitar el descuento <b>@{{pick?.name}} / @{{pick?.code}}</b>?
                </template>
            	<template v-else>
                    ¿Desea bloquear el descuento <b>@{{pick?.name}} / @{{pick?.code}}</b>?
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
                    fail: {},
					pile: {
						firm: {
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
			  			lock: null,
			  			test: null,
                        snap: null,
                        firm: null,
                        rate: null,
			  			code: null,
			  			name: null,
                        term: null,
                        wait: null,
			  			stop: null,
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
			    		axios.get(`{{route('team.gifts', ['task' => 'load'])}}?take=${take ?? ''}&page=${page ?? ''}&find=${text ?? ''}&type=${type ?? ''}`, {})
				             .then(function (data) {
				            self.list = data.data.data ?? [];

				            self.high = data.data.high ?? 0;

				            self.page = data.data.page ?? 0;

				            self.take = data.data.take ?? 0;

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
			    		if (item) {
			    			switch (task) {
			    				case 'edit':
			    				    axios.get(`{{route('team.gifts', ['task' => 'load'])}}/${item.hash}`, {})
							             .then(function (data) {
							            self.form = {
                                            lock: data.data.lock,
                                            test: data.data.test,
                                            firm: data.data.firm,
                                            rate: data.data.rate,
                                            code: data.data.code,
                                            snap: data.data.snap,
                                            name: data.data.name,
                                            term: data.data.term,
                                            wait: data.data.wait,
                                            stop: data.data.stop,
                                            note: data.data.note
                                        };

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
                                    self.pick = null;

			    					self.form = {
                                        lock: 0,
                                        test: 0,
                                        firm: null,
                                        rate: null,
                                        snap: null,
                                        code: null,
                                        name: null,
                                        term: null,
                                        wait: null,
                                        stop: null,
                                        note: null
                                    };

									self.view = 2;
			    					break;
			    			}
			    		}
			    	},
			    	save: function (form, item) {
                        var data = new FormData();

		    		    data.append('lock', form.lock ?? 0);

		    		    data.append('test', form.test ?? 0);

		    		    data.append('firm', form.firm ?? '');

                        data.append('rate', form.rate ?? '');

		    		    data.append('code', form.code ?? '');

		    		    data.append('name', form.name ?? '');

                        data.append('term', form.term ?? '');

                        data.append('wait', form.wait ?? '');

		    	        data.append('stop', form.stop ?? '');

		    	        data.append('note', form.note ?? '');

                        self.fail =  {};

		    	        if (item) {
				    		axios.post(`{{route('team.gifts', ['task' => 'save'])}}/${item.hash}`, data, {'X-CSRF-TOKEN': '{{csrf_token()}}'})
	                             .then(function (data) {
                                item.firm =  data.data.firm;

                                item.code = form.code;

                                item.name = form.name;

				    	        item.rate = form.rate;

	                            self.wait = false;

                                self.view = 1;
								
	                            self.note = {
                                    show: true,
                                	type: 'done',
                                	text: data.data.text
                                };
	                        })
	                        .catch(function (fail) {
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
				    	} else {
				    		axios.post("{{route('team.gifts', ['task' => 'make'])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
	                             .then(function (data) {
	                            self.list.unshift((item = {
                                    item: data.data.item,
                                    hash: data.data.hash,
                                    tone: data.data.tone,
                                    snap: data.data.snap,
                                    firm: data.data.firm,
                                    lock: form.lock,
                                    rate: form.rate,
                                    code: form.code,
                                    name: form.name,
                                    wait: form.wait,
                                    stop: form.stop
                                }));

                                self.note = {
                                    show: true,
                                	type: 'done',
                                	text: data.data.text
                                };

                                self.wait = false;

								self.view = 1;
	                        })
	                        .catch(function (fail) {
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
			    	face: function (item, snap) {
			    		var data = new FormData();

						data.append('file', snap ? self.blob(snap.getCroppedCanvas({width: 640, height: 640})) : '');

			    		axios.post(`{{route('team.gifts', ['task' => 'face'])}}/${item.hash}`, data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
                             .then(function (data) {
                            item.snap = data.data.file ?? null;

                            self.show.crop = false;

                            self.show.wipe = false;

                            self.wait = false;

                            self.note = {
                                show: true,
                                type: 'done',
                                text: data.data.text
                            };
                        })
                        .catch(function (fail) {
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
			    	},
			    	lock: function (item, flag) {
			    		axios.get(`{{route('team.gifts', ['task' => 'lock'])}}/${item.hash}?flag=${flag}`, {})
				             .then(function (data) {
				            self.show.lock = false;

				            self.show.wait = false;

                            self.wait = false;

			             	item.lock = flag;

		             		self.note = {
                                show: true,
                            	type: 'done',
                            	text: data.data.text
                            };
				        }).catch(function (fail) {
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
			    	drop: function (item) {
			    		axios.get(`{{route('team.gifts', ['task' => 'drop'])}}/${item.hash}`, {})
				             .then(function (data) {
				            self.wait = false;

				            self.show.drop = false;

			             	self.list.splice(self.list.indexOf(item), 1);

		             		self.note = {
                                show: true,
                            	type: 'done',
                            	text: data.data.text
                            };
				        }).catch(function (fail) {
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
			    	boot: function(data) {
						self.snap = data;

						self.snap.replace(self.file, false);
					}
			    },
			    mounted: function () {
                    axios.get("{{route('team.firms', ['task' => 'pull'])}}", {})
                         .then(function (firms) {
                        self.pile.firm.list = firms.data.data;

                        setTimeout(function () {
                            self.done = true;
                        }, 500);
                    });
			    }
			})
  		});
  	</script>
@stop