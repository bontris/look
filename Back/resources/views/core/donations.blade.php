@extends('team')

@section('page')
	<v-layout column>
		<v-row class="ma-0">
			<v-col class="pa-0">
				<v-layout class="white px-2">
					<v-list>
						<v-list-item class="px-1">
							<v-list-item-avatar
                                color="#D32F2F"
                                size="36"
                                tile>
								<v-icon color="white">
									mdi-hand-heart
								</v-icon>
							</v-list-item-avatar>
							<v-list-item-content class="pa-0">
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
												@{{pick ? 'EDITAR DONACIÓN' : 'NUEVA DONACIÓN'}}
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
									{text: 'Municipio', align: 'start', sortable: false},
									{text: 'Dirección', align: 'start', sortable: false},
									{text: 'Programación', width: 160, align: 'center', sortable: false},
									{text: 'Tipo', width: 120, align: 'center', sortable: false},
									{text: 'Cantidad', width: 160, align: 'end', sortable: false},
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
														@click="open('spot', item)"
														:disabled="Boolean(bulk.list.length)">
														<v-list-item-icon>
															<v-icon>mdi-map</v-icon>
														</v-list-item-icon>
														<v-list-item-title>Ubicar</v-list-item-title>
													</v-list-item>
													<v-list-item
														@click="open('edit')"
														:disabled="Boolean(bulk.list.length)">
														<v-list-item-icon>
															<v-icon>mdi-pencil</v-icon>
														</v-list-item-icon>
														<v-list-item-title>Editar</v-list-item-title>
													</v-list-item>
													<v-list-item
														@click="open('drop')"
														:disabled="Boolean(bulk.list.length)">
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
													:disabled="none(bulk.list.length)"
													icon>
													<v-icon>mdi-pencil</v-icon>
												</v-btn>
												<v-btn
													@click="open('drop')"
													:disabled="none(bulk.list.length)"
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
                                                    :color="{0: '#78909B', 1: '#2E7D32', 2: '#D32F2F'}[item.done]"
                                                    small>
													<v-icon left>
														@{{({0: 'mdi-progress-clock', 1: 'mdi-check', 2: 'mdi-close'}[item.done])}}
													</v-icon>
													@{{{0: 'Pendiente', 1: 'Recogida', 2: 'Fillida'}[item.done]}}
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
											<div class="text--primary">
                                                @{{item.town}}
											</div>
										</td>
										<td v-if="none(tiny)">
											<div class="text--primary">
                                                @{{item.address}}
											</div>
										</td>
										<td
											v-if="none(tiny)"
											class="text-center">
											<div class="text--primary">
												@{{{0: 'Hoy', 1: 'Lunes', 2: 'Martes', 3: 'Miércoles', 4: 'Jueves', 5: 'Viernes', 6: 'Sábado', 7: 'Domingo'}[item.date]}}
											</div>
										</td>
                                        <td 
											class="text-center"
											v-if="none(tiny)">
											<v-chip
												text-color="white"
												:color="{1: '#1B5E20', 2: '#FF6F00'}[item.type]"
												small>
												<v-icon left>
													@{{({1: 'mdi-currency-usd', 2: 'mdi-package-variant-closed'}[item.type])}}
												</v-icon>
												@{{{1: 'Efectivo', 2: 'Especie'}[item.type]}}
											</v-chip>
										</td>
										<td
                                            v-if="none(tiny)"
                                            class="text-end">
											<div class="text--primary">
												@{{same(item.type, 1, (item) => (`$${text(item.load)}`), (item) => (`${text(item.load)} Und`), item)}}
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
									<v-row>
										<v-col>
											Buenos días/tardes.<br><br>
											Gracias por comunicarse con la <b>FUNDACION BANCO ARQUIDIOCESANO DE ALIMENTOS DE MEDELLÍN</b>.<br>
											Telealimentón convierte su dinero en alimento para más de 55 mil personas mensualmente, ¿con quién tengo el gusto de hablar?
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
												:error-messages="[fail?.name].filter(Boolean)"
												:disabled="wait"
												required
												filled>
											</v-text-field>
										</v-col>
									</v-row>
									<v-row>
										<v-col>
											Muchas gracias por llamarnos @{{none(form.name, 'Sra/Sr', `Sra/Sr <b>${form.name}</b>`)}}, ¿cómo puedo ayudarle?<br><br>
											Para poder programar de forma oportuna y efectiva el recaudo de su donación, debo solicitarle que me facilite algunos datos.<br>
											La <b>FUNDACION BANCO ARQUIDIOCESANO DE ALIMENTOS DE MEDELLÍN</b> le informa que, en caso de autorizarlo, sus datos personales serán tratados conforme a la política de tratamiento de datos disponible en www.bancodealimentos.co.<br><br>
                                            Muchas gracias por su autorización, entonces procedo a preguntarle lo siguiente:
										</v-col>
									</v-row>
									<v-row dense>
                                        <v-col>
											<v-text-field
												type="text"
												label="Celular"
												autocomplete="off"
												hide-details="auto"
												v-model="form.mobile"
												:error-messages="[fail?.mobile].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
									</v-row>
									<v-row dense>
                                        <v-col>
											<v-text-field
												type="text"
												label="Teléfono"
												autocomplete="off"
												hide-details="auto"
												v-model="form.phone"
												:error-messages="[fail?.phone].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
									</v-row>
									<v-row dense>
                                        <v-col>
											<v-text-field
												type="text"
												label="Correo electrónico"
												autocomplete="off"
												hide-details="auto"
												v-model="form.mail"
												:error-messages="[fail?.mail].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
									</v-row>
									<v-row dense>
                                        <v-col>
											<v-text-field
												type="text"
												label="Dirección"
												autocomplete="off"
												hide-details="auto"
												v-model="form.address"
												@input="(find = [trim($event), trim(form.town)].filter(Boolean).join(', '))"
												:error-messages="[fail?.address].filter(Boolean)"
												:disabled="wait"
												required
												filled>
											</v-text-field>
										</v-col>
									</v-row>
									<v-row dense>
                                        <v-col>
											<v-text-field
												type="text"
												label="Municipio"
												autocomplete="off"
												hide-details="auto"
												v-model="form.town"
												@input="(find = [trim(form.address), trim($event)].filter(Boolean).join(', '))"
												:error-messages="[fail?.town].filter(Boolean)"
												:disabled="wait"
												required
												filled>
											</v-text-field>
										</v-col>
									</v-row>
									<v-row dense>
                                        <v-col>
											<v-text-field
												type="text"
												label="Barrio"
												autocomplete="off"
												hide-details="auto"
												v-model="form.district"
												:error-messages="[fail?.district].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
									</v-row>
									<v-row dense>
                                        <v-col>
											<v-text-field
												type="text"
												label="Indicación"
												autocomplete="off"
												hide-details="auto"
												v-model="form.indication"
												:error-messages="[fail?.indication].filter(Boolean)"
												:disabled="wait"
												filled>
											</v-text-field>
										</v-col>
									</v-row>
									<v-row dense>
                                        <v-col>
											<v-select
												item-value="item"
												item-text="text"
												:items="[
													{item: 0, text: 'Hoy'},
													{item: 1, text: 'Lunes'},
													{item: 2, text: 'Martes'},
													{item: 3, text: 'Miércoles'},
													{item: 4, text: 'Jueves'},
													{item: 5, text: 'Viernes'},
													{item: 6, text: 'Sábado'},
													{item: 7, text: 'Domingo'}]"
												v-model="form.date"
												:error-messages="[fail?.date].filter(Boolean)"
												label="Donación programada para"
												:disabled="wait"
												hide-details="auto"
												required
												filled> 
											</v-select>
										</v-col>
									</v-row>
									<v-row dense>
                                        <v-col>
											<v-select
												item-value="item"
												item-text="text"
												:items="[
													{item: 1, text: 'Efectivo'},
													{item: 2, text: 'Especie'}
												]"
												v-model="form.type"
												:error-messages="[fail?.type].filter(Boolean)"
												label="Tipo de donación"
												:disabled="wait"
												hide-details="auto"
												required
												filled> 
											</v-select>
										</v-col>
									</v-row>
									<v-row
										v-if="form.type"
										dense>
                                        <v-col>
											<v-text-field
												type="text"
												:label="{1: 'Monto', 2: 'Cantidad'}[form.type]"
												autocomplete="off"
												hide-details="auto"
												v-model="form.load"
												:error-messages="[fail?.load].filter(Boolean)"
												:disabled="wait"
												required
												filled>
											</v-text-field>
										</v-col>
									</v-row>
									<v-row dense>
										<v-col>
											Muchas gracias Sr/Sra. <b>@{{form.name}}</b> Ya ha sido programado el recaudo de su donación, en el transcurso de día o en la hora indicada por usted.<br>¿Usted desearía programar su donación mensualmente o con otra frecuencia para que continúe apoyando nuestra obra?<br>Puede hacerlo a través del débito automático de su cuenta, si usted lo prefiere.
										</v-col>
									</v-row>
									<v-row dense>
                                        <v-col>
											<v-select
												item-value="item"
												item-text="text"
												:items="[
													{item: 1, text: 'Sí'},
													{item: 0, text: 'No'}]"
												v-model="form.echo"
												:error-messages="[fail?.echo].filter(Boolean)"
												label="Donación programada"
												:disabled="wait"
												hide-details="auto"
												filled> 
											</v-select>
										</v-col>
									</v-row>
									<v-row>
										<v-col>
										<v-text-field
											prepend-inner-icon="mdi-magnify"
											clear-icon="mdi-close-circle"
											autocomplete="off"
											height="52"
											v-model="find"
											label="Buscar dirección"
											hide-details
											single-line
											clearable
											filled>
										</v-text-field>
										</v-col>
									</v-row>
									<v-row dense>
										<v-col>
											<div
												id="plat"
												class="mark">
											</div>
										</v-col>
									</v-row>
									<v-row dense>
										<v-col>
											Nuestros hermanos más necesitados de alimentos agradecen su generosidad y buena voluntad.<br>¡Gracias por ayudarnos a quitar el hambre¡
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
                                Cambiar la ubicación de recogida de la donación.
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
						Ubicación: @{{form.spot?.[0]}}, @{{form.spot?.[1]}}
        			</v-col>
        		</v-row>
				<v-row dense>
					<v-col>
						<v-text-field
							prepend-inner-icon="mdi-magnify"
							clear-icon="mdi-close-circle"
							autocomplete="off"
							height="52"
							v-model="find"
							label="Buscar dirección"
							hide-details
							single-line
							clearable
							filled>
						</v-text-field>
					</v-col>
				</v-row>
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
					pile: {
						hand: {
							wait: false,
							text: null,
							list: []
						},
                        lead: {
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
						coin: {
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
                        type: null,
						done: null,
						echo: null,
						date: null,
						load: null,
			  			name: null,
			  			town: null,
						spot: null,
						mail: null,
						phone: null,
						mobile: null,
						address: null,
						district: null,
						indication: null
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
										center: new google.maps.LatLng(self.form.spot[0], self.form.spot[1]),
										zoom: 16
									});

									plat.addListener('center_changed', () => {
										self.form.spot = [plat.getCenter().lat(), plat.getCenter().lng()];
									})
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
									code.geocode({'address': `${find}, Colombia`}, (results, status) => {
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
			    		axios.get(`{{route('core.donations', ['task' => 'load'])}}?take=${take ?? ''}&page=${page ?? ''}&seek=${seek ?? ''}`, {})
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
			    				    axios.get(`{{route('core.donations', ['task' => 'load'])}}/${item.hash}`, {})
							             .then((data) => {
							            self.form = {
                                            lock: data.data.lock,
											done: data.data.done,
											echo: data.data.echo,
                                            type: data.data.type,
                                            date: data.data.date,
											load: data.data.load,
                                            tone: data.data.tone,
											code: data.data.code,
											mail: data.data.mail,
                                            name: data.data.name,
                                            town: data.data.town,
											spot: data.data.spot,
											phone: data.data.phone,
											mobile: data.data.mobile,
											address: data.data.address,
											district: data.data.district,
											indication: data.data.indication
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
											mapTypeControl: false,
                                            mapTypeId: google.maps.MapTypeId.ROADMAP,
											center: new google.maps.LatLng(self.form.spot[0], self.form.spot[1]),
											zoom: 16
                                        });

										plat.addListener('center_changed', () => {
											self.form.spot = [plat.getCenter().lat(), plat.getCenter().lng()];
										})
                                    }, 200);

									self.form.spot = item.spot ?? [6.2442876, -75.6162309];

									self.show.spot = true;

									self.pick = item;

									find = null;

									plat = null;
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
										indication: null,
										district: null,
										address: null,
										mobile: null,
										phone: null,
										type: null,
										echo: null,
										date: null,
										load: null,
										name: null,
										mail: null,
										town: null,
										code: null,
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
										})([6.2442034, -75.5812115]),
										done: 0,
										lock: 0
                                    };

					             	self.pick = null;

									self.view = 2;

									plat = null;
			    					break;
			    			}
			    		}
			    	},
			    	save: (form, item) => {
                        var data = new FormData();

		    		    data.append('lock', form.lock ?? 0);

						data.append('done', form.done ?? 0);

		    		    data.append('echo', form.echo ?? 0);

		    		    data.append('type', form.type ?? 0);

						data.append('date', form.date ?? 0);

						data.append('load', form.load ?? '');

		    		    data.append('code', form.code ?? '');

						data.append('town', form.town ?? '');

						data.append('mail', form.mail ?? '');

						data.append('name', form.name ?? '');

		    		    data.append('phone', form.phone ?? '');

		    	        data.append('mobile', form.mobile ?? '');

		    	        data.append('address', form.address ?? '');

                        data.append('district', form.district ?? '');

						data.append('indication', form.indication ?? '');

						data.append('spot[]', form.spot[0] ?? '');

                        data.append('spot[]', form.spot[1] ?? '');

                        self.fail =  null;

		    	        if (item) {
				    		axios.post(`{{route('core.donations', ['task' => 'save'])}}/${item.hash}`, data, {'X-CSRF-TOKEN': '{{csrf_token()}}'})
	                             .then((data) => {
                                item.type = form.type;

                                item.echo = form.echo;

								item.rank = form.rank;

								item.date = form.date;

								item.load = form.load;

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
				    		axios.post("{{route('core.donations', ['task' => 'make'])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
	                             .then((data) => {
	                            self.data.list.unshift((item = {
                                    item: data.data.item,
                                    hash: data.data.hash,
                                    code: data.data.code,
                                    tone: data.data.tone,
                                    lock: form.lock,
                                    type: form.type,
									rank: form.rank,
                                    echo: form.echo,
									date: form.date,
									load: form.load,
                                    name: form.name,
                                    town: form.town
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
					move: (item, spot) => {
			    		var data = new FormData();

                        data.append('spot[]', spot[0] ?? '');

                        data.append('spot[]', spot[1] ?? '');

			    		axios.post(`{{route('core.donations', ['task' => 'move'])}}/${item.hash}`, data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
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
			    		axios.get(`{{route('core.donations', ['task' => 'lock'])}}/${item.hash}?flag=${flag}`, {})
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
			    		axios.get(`{{route('core.donations', ['task' => 'drop'])}}/${item.hash}`, {})
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
						code =  new google.maps.Geocoder();

	             		self.done = true;
	             	}, 500);
			    }
			})
  		});
  	</script>
@stop