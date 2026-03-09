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
									mdi-account-multiple
								</v-icon>
							</v-list-item-avatar>
							<v-list-item-content class="pa-0">
								<v-list-item-title>
									Contactos
								</v-list-item-title>
								<v-list-item-subtitle>
									<v-breadcrumbs
										class="pa-0"
										:items="[{text: 'Inicio', href: '{{route('dash')}}'}, {text: 'Contactos'}]">
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
												LISTADO DE CONTACTOS
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
												@{{pick ? 'EDITAR CONTACTO' : 'NUEVO CONTACTO'}}
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
									{text: 'Foto', width: 80, align: 'start', sortable: false},
									{text: 'Documento', width: 180, align: 'start', sortable: false},
									{text: 'Nombre', align: 'start', sortable: false},
                                    {text: 'Correo', align: 'start', sortable: false},
                                    {text: 'Teléfono', width: 180, align: 'end', sortable: false},
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
                                                        :color="none(item.icon, `#${item.tone}`, null)"
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
											<div class="text--primary">
												@{{item.card}}
											</div>
										</td>
										<td v-if="none(tiny)">
											<div class="text--primary">
												@{{same(item.kind, 1, `${item.name} ${item.last}`, item.firm)}}
											</div>
										</td>
										<td v-if="none(tiny)">
											<div :class="none(item.mail, 'text--secondary', 'text--primary')">
                                                @{{none(item.mail, 'Ninguno', item.mail)}}
											</div>
										</td>
                                        <td
                                            v-if="none(tiny)"
                                            class="text-end">
											<div :class="none(item.work, 'text--secondary', 'text--primary')">
                                                @{{none(item.work, 'Ninguno', item.work)}}
											</div>
										</td>
										<td 
											class="text-center"
											v-if="none(tiny)">
											<div class="text--primary">
												<v-chip
                                                    text-color="white"
                                                    :color="{1: '#263238', 2: '#FF6F00', 3: '#E53935'}[item.sort]"
                                                    small>
													<v-icon left>
														@{{({1: 'mdi-account', 2: 'mdi-account-hard-hat', 3: 'mdi-account-tie', 4: 'mdi-account-tie'}[item.sort])}}
													</v-icon>
													@{{{1: 'Cliente', 2: 'Contratista', 3: 'Proveedor', 4: 'Transportista'}[item.sort]}}
												</v-chip>
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
										<v-col :cols="none(tiny, 3, 12)">
											<v-select
												item-value="item"
												item-text="text"
												:items="[{item: 1, text: 'Cliente'}, {item: 2, text: 'Contratista'}, {item: 3, text: 'Proveedor'}, {item: 4, text: 'Transportista'}]"
												v-model="form.sort"
												:error-messages="[fail?.sort].filter(Boolean)"
												label="Tipo"
                                                class="bind"
												:disabled="wait"
												hide-details="auto"
												filled> 
											</v-select>
										</v-col>
                                        <v-col :cols="none(tiny, 3, 12)">
											<v-select
												item-value="item"
												item-text="text"
												:items="[{item: 1, text: 'Natural'}, {item: 2, text: 'Jurídica'}]"
												v-model="form.kind"
                                                @change="(form.type = 0)"
												:error-messages="[fail?.kind].filter(Boolean)"
												label="Persona"
                                                class="bind"
												:disabled="wait"
												hide-details="auto"
												filled> 
											</v-select>
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
											<v-select
                                                no-data-text="No hay opciones"
												item-value="item"
												item-text="text"
												:items="[{item: 1, kind: 1, text: 'Cédula de ciudadanía'}, {item: 2, kind: 1, text: 'Cédula de extranjería'}, {item: 3, kind: 1, text: 'Pasaporte'}, {item: 4, kind: 2, text: 'Número de identificación tributaria'}].filter((item) => (item.kind == form.kind))"
												v-model="form.type"
												:error-messages="[fail?.type].filter(Boolean)"
												label="Tipo de documento"
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
												label="Número de documento"
												autocomplete="off"
												hide-details="auto"
												v-model="form.card"
												:error-messages="[fail?.card].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
									</v-row>
									<v-row
										v-if="same(form.kind, 1)"
										dense>
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
											<v-text-field
												type="text"
                                                class="bind"
												label="Apellido"
												autocomplete="off"
												hide-details="auto"
												v-model="form.last"
												:error-messages="[fail?.last].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
									</v-row>
									<v-row
										v-if="same(form.kind, 1)"
										dense>
										<v-col :cols="none(tiny, 6, 12)">
											<v-select
												item-value="item"
												item-text="text"
												:items="[
													{item: 1, text: 'Señor'},
													{item: 2, text: 'Señora'},
													{item: 3, text: 'Doctor'},
													{item: 4, text: 'Doctora'},
													{item: 5, text: 'Abogado'},
													{item: 6, text: 'Abogada'},
													{item: 7, text: 'Técnologo'},
													{item: 8, text: 'Técnologa'},
													{item: 9, text: 'Ingeniero'},
													{item: 10, text: 'Ingeniera'},
													{item: 11, text: 'Arquitécto'},
													{item: 12, text: 'Arquitécta'},
													{item: 13, text: 'Licenciado'},
													{item: 14, text: 'Licenciada'}
												]"
												v-model="form.rank"
												:error-messages="[fail?.rank].filter(Boolean)"
												label="Título"
												:disabled="wait"
												hide-details="auto"
												clear-icon="mdi-close-circle"
                                                clearable
												filled>
											</v-select>
										</v-col>
                                        <v-col :cols="none(tiny, 6, 12)">
											<v-menu
												:close-on-content-click="false"
												v-model="show.menu.form.date"
												transition="scale-transition"
												min-width="290px"
												nudge-right="20"
												nudge-top="-5"
												offset-y>
												<template v-slot:activator="{on}">
													<v-text-field
														v-on="on"
														:error-messages="[fail?.date].filter(Boolean)"
														hide-details="auto"
														clear-icon="mdi-close-circle"
														:value="(form.date ? date(form.date, 'DD/MM/YYYY') : '')"
														label="@lang('Fecha de nacimiento')"
														:disabled="wait"
														@click:clear="(event) => (form.date = null)"
														clearable
														readonly
														filled>
													</v-text-field>
												</template>
												<v-date-picker
													v-model="form.date"
													@input="(show.menu.form.date = false)"
													locale="{{App::getLocale()}}"
													scrollable
													no-title>
												</v-date-picker>
											</v-menu>
										</v-col>
									</v-row>
                                    <v-row
										v-if="same(form.kind, 2)"
										dense>
                                        <v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
                                                class="bind"
												label="Razón social"
												autocomplete="off"
												hide-details="auto"
												v-model="form.firm"
												:error-messages="[fail?.firm].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
                                        <v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
												label="Sector"
												placeholder="Comercio, Industria"
												autocomplete="off"
												hide-details="auto"
												v-model="form.line"
												:error-messages="[fail?.line].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
									</v-row>
									<v-row
										v-if="same(form.kind, 1)"
										dense>
										<v-col :cols="none(tiny, 6, 12)">
                                            <v-autocomplete
                                                no-data-text="No hay opciones"
                                                item-value="item"
												:items="pile.firm.list"
												label="Razón social"
												v-model="form.pick"
												hide-details="auto"
												clear-icon="mdi-close-circle"
												:filter="function (item, find, text) {return ((~item.card.indexOf(find.toLocaleUpperCase())) || (~item.firm.toLocaleLowerCase().indexOf(find.toLocaleLowerCase())))}"
												:error-messages="[fail?.pick].filter(Boolean)"
												:disabled="wait"
												clearable
												filled>
												<template v-slot:selection="data">
													@{{data.item.firm}}
												</template>
												<template v-slot:item="data">
													<v-list-item-content>
														<v-list-item-title>@{{data.item.firm}}</v-list-item-title>
                                                        <v-list-item-subtitle>@{{data.item.card}}</v-list-item-title>
													</v-list-item-content>
												</template>
											</v-autocomplete>
										</v-col>
										<v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
												label="Posición"
												autocomplete="off"
												hide-details="auto"
												v-model="form.role"
												:error-messages="[fail?.role].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
									</v-row>
									<v-row v-if="same(form.kind, 2)">
										<v-col>
											<v-sheet class="text-button">
												Representante
											</v-sheet>
											<v-divider>
											</v-divider>
										</v-col>
								    </v-row>
									<v-row
										v-if="same(form.kind, 2)"
										dense>
										<v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
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
											<v-text-field
												type="text"
												label="Apellido"
												autocomplete="off"
												hide-details="auto"
												v-model="form.last"
												:error-messages="[fail?.last].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
									</v-row>
									<v-row
										v-if="same(form.kind, 2)"
										dense>
										<v-col :cols="none(tiny, 6, 12)">
											<v-select
												item-value="item"
												item-text="text"
												:items="[
													{item: 1, text: 'Señor'},
													{item: 2, text: 'Señora'},
													{item: 3, text: 'Doctor'},
													{item: 4, text: 'Doctora'},
													{item: 5, text: 'Abogado'},
													{item: 6, text: 'Abogada'},
													{item: 7, text: 'Técnologo'},
													{item: 8, text: 'Técnologa'},
													{item: 9, text: 'Ingeniero'},
													{item: 10, text: 'Ingeniera'},
													{item: 11, text: 'Arquitécto'},
													{item: 12, text: 'Arquitécta'},
													{item: 13, text: 'Licenciado'},
													{item: 14, text: 'Licenciada'}
												]"
												v-model="form.rank"
												:error-messages="[fail?.rank].filter(Boolean)"
												label="Título"
												:disabled="wait"
												hide-details="auto"
												clear-icon="mdi-close-circle"
                                                clearable
												filled>
											</v-select>
										</v-col>
										<v-col :cols="none(tiny, 6, 12)">
											<v-text-field
												type="text"
												label="Posición"
												autocomplete="off"
												hide-details="auto"
												v-model="form.role"
												:error-messages="[fail?.role].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
									</v-row>
									<v-row dense>
										<v-col :cols="none(tiny, 6, 12)">
                                            <v-autocomplete
                                                no-data-text="No hay opciones"
                                                item-value="item"
												:items="pile.hand.list"
												label="Encargado"
												v-model="form.hook"
												hide-details="auto"
												clear-icon="mdi-close-circle"
												:filter="function (item, find, text) {return ((~item.card.indexOf(find.toLocaleUpperCase())) || (~item.name.toLocaleLowerCase().indexOf(find.toLocaleLowerCase())))}"
												:error-messages="[fail?.hook].filter(Boolean)"
												:disabled="wait"
												clearable
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
										<v-col :cols="none(tiny, 6, 12)">
                                            <v-autocomplete
                                                no-data-text="No hay opciones"
                                                item-value="item"
												:items="pile.user.list"
												label="Usuario"
												v-model="form.link"
												hide-details="auto"
												clear-icon="mdi-close-circle"
												:filter="function (item, find, text) {return ((~item.card.indexOf(find.toLocaleUpperCase())) || (~item.name.toLocaleLowerCase().indexOf(find.toLocaleLowerCase())))}"
												:error-messages="[fail?.link].filter(Boolean)"
												:disabled="wait"
												clearable
												filled>
												<template v-slot:selection="data">
													@{{data.item.name}} @{{data.item.last}}
												</template>
												<template v-slot:item="data">
													<v-list-item-content>
														<v-list-item-title>@{{data.item.name}} @{{data.item.last}}</v-list-item-title>
                                                        <v-list-item-subtitle>@{{data.item.mail}}</v-list-item-title>
													</v-list-item-content>
												</template>
											</v-autocomplete>
										</v-col>
									</v-row>
									<v-row>
										<v-col>
											<v-sheet class="text-button">
												Destalles
											</v-sheet>
											<v-divider>
											</v-divider>
										</v-col>
								    </v-row>
                                    <v-row dense>
										<v-col :cols="none(tiny, 4, 12)">
											<v-file-input
												accept="image/jpeg,image/png"
												label="Fotografía"
												@change="open('crop', pick, $event, 1, false)"
                                                clear-icon="mdi-close-circle"
												prepend-inner-icon="mdi-camera"
												:error-messages="[fail?.icon].filter(Boolean)"
                                                hide-details="auto"
												filled>
											</v-file-input>
										</v-col>
                                        <v-col :cols="none(tiny, 2, 12)">
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
										<v-col :cols="none(tiny, 3, 12)">
											<v-select
												item-value="item"
												:item-text="(item) => (item?.data?.name['ES'] ?? item.name)"
												:items="pile.text.list"
												v-model="form.text"
												:error-messages="[fail?.text].filter(Boolean)"
												label="Idioma"
												:disabled="wait"
												hide-details="auto"
												clear-icon="mdi-close-circle"
												clearable
												filled> 
											</v-select>
										</v-col>
										<v-col :cols="none(tiny, 3, 12)">
											<v-autocomplete
                                                no-data-text="No hay opciones"
                                                item-value="item"
												:items="pile.time.list"
												label="Zona horaria"
												v-model="form.time"
												hide-details="auto"
												clear-icon="mdi-close-circle"
												:filter="function (item, find, text) {return ((~item.code.indexOf(find.toLocaleUpperCase())) || (~(item?.data?.name['ES'] ?? item.name).toLocaleLowerCase().indexOf(find.toLocaleLowerCase())))}"
												:error-messages="[fail?.time].filter(Boolean)"
												:disabled="wait"
												clearable
												filled>
												<template v-slot:selection="data">
													@{{data.item.data?.name['ES'] ?? data.item.name}}
												</template>
												<template v-slot:item="data">
													<v-list-item-content>
														<v-list-item-title>@{{data.item.data?.name['ES'] ?? data.item.name}}</v-list-item-title>
													</v-list-item-content>
												</template>
											</v-autocomplete>
										</v-col>
									</v-row>
									<v-row dense>
										<v-col :cols="none(tiny, 6, 12)">
											<v-autocomplete
												:items="pile.land.list"
												item-value="item"
												no-data-text="No hay opciones"
												label="Residencia"
												v-model="form.stay"
												hide-details="auto"
												:filter="function (item, find, text) {return ((~item.code.indexOf(find.toLocaleUpperCase())) || (~item.name.toLocaleLowerCase().indexOf(find.toLocaleLowerCase())))}"
												:error-messages="[fail?.stay].filter(Boolean)"
												:disabled="wait"
												@change="(form.zone = form.town = null)"
												filled>
												<template v-slot:selection="data">
													@{{(data.item.data?.name['ES'] ?? data.item.name)}}
												</template>
												<template v-slot:item="data">
													<v-list-item-content>
														<v-list-item-title>@{{(data.item.data?.name['ES'] ?? data.item.name)}}</v-list-item-title>
													</v-list-item-content>
												</template>
											</v-autocomplete>
										</v-col>
										<v-col :cols="none(tiny, 6, 12)">
											<v-autocomplete
												:items="pile.land.list"
												item-value="item"
												no-data-text="No hay opciones"
												label="Nacionalidad"
												v-model="form.born"
												hide-details="auto"
												:filter="function (item, find, text) {return ((~item.code.indexOf(find.toLocaleUpperCase())) || (~item.name.toLocaleLowerCase().indexOf(find.toLocaleLowerCase())))}"
												:error-messages="[fail?.born].filter(Boolean)"
												:disabled="wait"
												filled>
												<template v-slot:selection="data">
													@{{(data.item.data?.name['ES'] ?? data.item.name)}}
												</template>
												<template v-slot:item="data">
													<v-list-item-content>
														<v-list-item-title>@{{(data.item.data?.name['ES'] ?? data.item.name)}}</v-list-item-title>
													</v-list-item-content>
												</template>
											</v-autocomplete>
										</v-col>
									</v-row>
                                    <v-row dense>
										<v-col :cols="none(tiny, 6, 12)">
											<v-autocomplete
												:items="pile.zone.list"
												item-value="item"
												no-data-text="No hay opciones"
												label="Departamento / Estado"
												v-model="form.zone"
												hide-details="auto"
												:filter="function (item, find, text) {return ((~item.code.indexOf(find.toLocaleUpperCase())) || (~item.name.toLocaleLowerCase().indexOf(find.toLocaleLowerCase())))}"
												:error-messages="[fail?.zone].filter(Boolean)"
												:disabled="wait"
												@change="(form.town = null)"
												filled>
												<template v-slot:selection="data">
													@{{(data.item.data?.name['ES'] ?? data.item.name)}}
												</template>
												<template v-slot:item="data">
													<v-list-item-content>
														<v-list-item-title>@{{(data.item.data?.name['ES'] ?? data.item.name)}}</v-list-item-title>
													</v-list-item-content>
												</template>
											</v-autocomplete>
										</v-col>
										<v-col :cols="none(tiny, 6, 12)">
											<v-autocomplete
												:items="pile.town.list"
												item-value="item"
												no-data-text="No hay opciones"
												label="Municipio / Ciudad"
												v-model="form.town"
												hide-details="auto"
												:filter="function (item, find, text) {return ((~item.code.indexOf(find.toLocaleUpperCase())) || (~item.name.toLocaleLowerCase().indexOf(find.toLocaleLowerCase())))}"
												:error-messages="[fail?.town].filter(Boolean)"
												:disabled="wait"
												filled>
												<template v-slot:selection="data">
													@{{(data.item.data?.name['ES'] ?? data.item.name)}}
												</template>
												<template v-slot:item="data">
													<v-list-item-content>
														<v-list-item-title>@{{(data.item.data?.name['ES'] ?? data.item.name)}}</v-list-item-title>
													</v-list-item-content>
												</template>
											</v-autocomplete>
										</v-col>
									</v-row>
                                    <v-row dense>
										<v-col :cols="none(tiny, 6, 12)">
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
                                        <v-col :cols="none(tiny, 3, 12)">
											<v-text-field
												type="text"
												label="Apartamento / Oficina"
												hide-details="auto"
												autocomplete="off"
												v-model="form.gate"
												:error-messages="[fail?.gate].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
										<v-col :cols="none(tiny, 3, 12)">
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
                                Eliminar la imágen del contacto.
							</v-list-item-subtitle>
						</v-list-item-content>
					</v-list-item>
				</v-list>
            </v-card-title>
          	<v-divider>
            </v-divider>
          	<v-card-text class="mt-4">
          		¿Desea eliminar la imágen del contacto <b>@{{pick?.name}} @{{pick?.last}} / @{{pick?.card}}</b>?
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
                    @click="icon(pick)"
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
                                Eliminar el contacto permanentemente.
							</v-list-item-subtitle>
						</v-list-item-content>
					</v-list-item>
				</v-list>
            </v-card-title>
          	<v-divider>
            </v-divider>
          	<v-card-text class="mt-4">
          		¿Desea eliminar el contacto <b>@{{pick?.name}} @{{pick?.last}} / @{{pick?.card}}</b>?
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
                                Cambiar el estado del contacto.
							</v-list-item-subtitle>
						</v-list-item-content>
					</v-list-item>
				</v-list>
            </v-card-title>
            <v-divider>
            </v-divider>
            <v-card-text class="mt-4">
                <template v-if="same(pick?.lock, 1)">
                    ¿Desea habilitar el contacto <b>@{{pick?.name}} @{{pick?.last}} / @{{pick?.card}}</b>?
                </template>
            	<template v-else>
                    ¿Desea bloquear el contacto <b>@{{pick?.name}} @{{pick?.last}} / @{{pick?.card}}</b>?
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
						user: {
							wait: false,
							text: null,
							list: []
						},
						hand: {
							wait: false,
							text: null,
							list: []
						},
						firm: {
							wait: false,
							text: null,
							list: []
						},
						text: {
							wait: false,
							text: null,
							list: []
						},
						land: {
							wait: false,
							text: null,
							list: []
						},
						zone: {
							wait: false,
							text: null,
							list: []
						},
						town: {
							wait: false,
							text: null,
							list: []
						},
						time: {
							wait: false,
							text: null,
							list: []
						},
						line: {
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
						sort: null,
						kind: null,
                        type: null,
                        rank: null,
						link: null,
						hook: null,
						pick: null,
						line: null,
						time: null,
						text: null,
						born: null,
						stay: null,
						zone: null,
						town: null,
                        icon: null,
						tone: null,
						code: null,
			  			card: null,
                        path: null,
                        post: null,
						gate: null,
                        last: null,
			  			name: null,
						firm: null,
			  			role: null,
			  			work: null,
			  			mail: null,
			  			note: null
			  		}
			    }),
			    watch: {
					'form.stay': {
						handler: (item) => {
							if (item) {
								axios.get(`{{route('core.chips', ['task' => 'pull', 'type' => 3])}}?link=${item}`)
							     .then((data) => {
									self.pile.zone.list = data.data.list;

									self.pile.zone.wait = false;
								}).catch((fail) => {
									self.pile.zone.wait = false;
								});

								self.pile.zone.wait = true;
							} else {
								self.pile.zone.list = [];
							}
					    }
					},
					'form.zone': {
						handler: (item) => {
							if (item) {
								axios.get(`{{route('core.chips', ['task' => 'pull', 'type' => 4])}}?link=${item}`)
							     .then((data) => {
									self.pile.town.list = data.data.list;

									self.pile.town.wait = false;
								}).catch((fail) => {
									self.pile.town.wait = false;
								});

								self.pile.town.wait = true;
							} else {
								self.pile.town.list = [];
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
			    		axios.get(`{{route('core.leads', ['task' => 'load'])}}?take=${take ?? ''}&page=${page ?? ''}&seek=${seek ?? ''}`, {})
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
			    				    axios.get(`{{route('core.leads', ['task' => 'load'])}}/${item.hash}`, {})
							             .then((data) => {
							            self.form = {
                                            icon: null,
                                            lock: data.data.lock,
											sort: data.data.sort,
                                            type: data.data.type,
                                            kind: data.data.kind,
											rank: data.data.rank,
                                            code: data.data.code,
                                            tone: data.data.tone,
                                            card: data.data.card,
                                            date: data.data.date,
                                            zone: data.data.zone,
                                            town: data.data.town,
                                            path: data.data.path,
                                            post: data.data.post,
											gate: data.data.gate,
                                            last: data.data.last,
                                            name: data.data.name,
											firm: data.data.firm,
                                            role: data.data.role,
                                            link: data.data.link,
											hook: data.data.hook,
											pick: data.data.pick,
											line: data.data.line,
											time: data.data.time,
											text: data.data.text,
											born: data.data.born,
											stay: data.data.stay,
											zone: data.data.zone,
											town: data.data.town,
                                            work: data.data.work,
                                            mail: data.data.mail,
                                            note: data.data.note
                                        };

										if (self.wait) {
											setTimeout(() => {
												self.wait = false;
											}, 200);
										} else {
											clearTimeout(self.time);
										}

										self.fail = null;

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
											default:
												self.form.icon = data;
												break;
										}
									} else {
										switch (type) {
											default:
												self.form.icon = null;
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
                                        lock: null,
										sort: null,
										kind: null,
										type: null,
										rank: null,
										link: null,
										hook: null,
										pick: null,
										line: null,
										time: null,
										text: null,
										born: null,
										stay: null,
										zone: null,
										town: null,
										icon: null,
										tone: null,
										code: null,
										card: null,
										path: null,
										post: null,
										gate: null,
										last: null,
										name: null,
										firm: null,
										role: null,
										work: null,
										mail: null,
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

		    		    data.append('lock', form.lock ?? '');

						data.append('sort', form.sort ?? '');

		    		    data.append('type', form.type ?? '');

                        data.append('kind', form.kind ?? '');

						data.append('rank', form.rank ?? '');

                        data.append('link', form.link ?? '');

						data.append('hook', form.hook ?? '');

						data.append('pick', form.pick ?? '');

						data.append('line', form.line ?? '');

						data.append('time', form.time ?? '');

						data.append('text', form.text ?? '');

						data.append('born', form.born ?? '');

						data.append('stay', form.stay ?? '');

						data.append('zone', form.zone ?? '');

						data.append('town', form.town ?? '');

                        data.append('tone', form.tone ?? '');

						data.append('icon', form.icon ?? '');

                        data.append('date', form.date ?? '');

						data.append('code', form.code ?? '');

		    		    data.append('card', form.card ?? '');

                        data.append('zone', form.zone ?? '');

						data.append('town', form.town ?? '');

						data.append('path', form.path ?? '');

                        data.append('post', form.post ?? '');

						data.append('gate', form.gate ?? '');

                        data.append('last', form.last ?? '');

		    		    data.append('name', form.name ?? '');

						data.append('firm', form.firm ?? '');

                        data.append('role', form.role ?? '');

		    	        data.append('work', form.work ?? '');

		    	        data.append('mail', form.mail ?? '');

		    	        data.append('note', form.note ?? '');

                        self.fail =  null;

		    	        if (item) {
				    		axios.post(`{{route('core.leads', ['task' => 'save'])}}/${item.hash}`, data, {'X-CSRF-TOKEN': '{{csrf_token()}}'})
	                             .then((data) => {
                                item.icon = data.data.icon ?? form.icon;

                                item.sort = form.sort;

                                item.card = form.card;

                                item.last = form.last;

                                item.name = form.name;

								item.firm = form.firm;

                                item.head = form.head;

				    	        item.mail = form.mail;

                                item.work = form.work;

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
				    		axios.post("{{route('core.leads', ['task' => 'make'])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
	                             .then((data) => {
	                            self.data.list.unshift((item = {
                                    item: data.data.item,
                                    hash: data.data.hash,
                                    code: data.data.code,
                                    tone: data.data.tone,
                                    icon: data.data.icon,
                                    lock: form.lock,
                                    sort: form.sort,
                                    card: form.card,
                                    last: form.last,
                                    name: form.name,
									firm: form.firm,
                                    head: form.head,
                                    mail: form.mail,
                                    work: form.work
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
								}
							})(type, snap));

							axios.post(`{{route('core.leads')}}/icon/${item.hash}`, data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
								.then((data) => {
								switch (type) {
									case 1:
										item.icon = data.data.file;
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
							}
						}
			    	},
			    	lock: (item, flag) => {
			    		axios.get(`{{route('core.leads', ['task' => 'lock'])}}/${item.hash}?flag=${flag}`, {})
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
			    		axios.get(`{{route('core.leads', ['task' => 'drop'])}}/${item.hash}`, {})
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
							self.pile.user.list = data.data.list;

							self.pile.user.wait = false;
						}).catch((fail) => {
							self.pile.user.wait = false;
						});

						axios.get(`{{route('core.hands', ['task' => 'pull'])}}`)
							.then((data) => {
							self.pile.hand.list = data.data.list;

							self.pile.hand.wait = false;
						}).catch((fail) => {
							self.pile.hand.wait = false;
						});

						axios.get(`{{route('core.leads', ['task' => 'pull', 'kind' => 2])}}`)
							.then((data) => {
							self.pile.firm.list = data.data.list;

							self.pile.firm.wait = false;
						}).catch((fail) => {
							self.pile.hand.wait = false;
						});

						axios.get(`{{route('core.chips', ['task' => 'pull', 'type' => 1])}}`)
							.then((data) => {
							self.pile.text.list = data.data.list;

							self.pile.text.wait = false;
						}).catch((fail) => {
							self.pile.text.wait = false;
						});

						axios.get(`{{route('core.chips', ['task' => 'pull', 'type' => 2])}}`)
							.then((data) => {
							self.pile.land.list = data.data.list;

							self.pile.land.wait = false;
						}).catch((fail) => {
							self.pile.land.wait = false;
						});

						axios.get(`{{route('core.chips', ['task' => 'pull', 'type' => 5])}}`)
							.then((data) => {
							self.pile.time.list = data.data.list;

							self.pile.time.wait = false;
						}).catch((fail) => {
							self.pile.time.wait = false;
						});

						axios.get(`{{route('core.chips', ['task' => 'pull', 'type' => 8])}}`)
							.then((data) => {
							self.pile.line.list = data.data.list;

							self.pile.line.wait = false;
						}).catch((fail) => {
							self.pile.land.wait = false;
						});

						self.pile.user.wait = true;

						self.pile.hand.wait = true;

						self.pile.text.wait = true;

						self.pile.land.wait = true;

						self.pile.time.wait = true;

						self.pile.line.wait = true;

	             		self.done = true;
	             	}, 500);
			    }
			})
  		});
  	</script>
@stop