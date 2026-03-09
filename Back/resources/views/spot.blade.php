<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{csrf_token()}}">
        <title>{{config('app.name', 'Test')}}</title>
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
        <link href="/styles/material.min.css?v=1.2" rel="stylesheet">
        <link href="/styles/vuetify.css?v=1.4" rel="stylesheet">
        <link href="/styles/spot.css?v=1.7" rel="stylesheet">
        <link rel="icon" type="image/png" href="/icon.png">
    </head>
    <body>
        <div id="body">
            <div class="load" :done="done">
                <svg viewBox="0 0 60 60">
                    <circle cx="30" cy="30" r="25" ring></circle>
                </svg>
                <div>
                    <b>Cargando</b>
                    <p>Por favor espere...</p>
                </div>
            </div>
            <div class="page">
                <v-app>
                    <v-snackbar
						v-model="note.show"
						:color="{done: 'success', warn: 'warning', fail: 'error'}[note.type]"
						timeout="3000"
						multi-line
						tile
						top>
						@{{note.text}}
						<template v-slot:action="{attrs}">
							<v-btn
								v-bind="attrs"
								@click="(note.show = false)"
								icon>
								<v-icon>mdi-close</v-icon>
							</v-btn>
						</template>
					</v-snackbar>
                    <v-main>
                        <v-layout column>
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
                                                                    Puntos
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
                                                                    @{{pick ? 'Editar' : 'Nuevo'}}
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
                                                    :disabled="wait"
                                                    icon>
                                                    <v-icon>mdi-plus</v-icon>
                                                </v-btn>
                                            </v-sheet>
                                            <v-sheet v-else>
                                                <v-btn
                                                    class="mr-1"
                                                    :color="pane == 1 ? 'primary' : 'secondary'"
                                                    @click.stop="pane = 1"
                                                    :disabled="wait"
                                                    icon>
                                                    <v-icon>mdi-android</v-icon>
                                                </v-btn>
                                                <v-btn
                                                    class="mr-1"
                                                    :color="pane == 2 ? 'primary' : 'secondary'"
                                                    @click.stop="pane = 2"
                                                    :disabled="wait"
                                                    icon>
                                                    <v-icon>mdi-apple-ios</v-icon>
                                                </v-btn>
                                                <v-btn
                                                    class="mr-1"
                                                    color="success"
                                                    @click.stop="save(form.main, pick)"
                                                    :disabled="wait"
                                                    icon>
                                                    <v-icon>mdi-check</v-icon>
                                                </v-btn>
                                                <v-btn
                                                    class="mr-4"
                                                    color="primary"
                                                    @click.stop="view = 1"
                                                    :disabled="wait"
                                                    icon>
                                                    <v-icon>mdi-close</v-icon>
                                                </v-btn>
                                            </v-sheet>
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
                                                                label="Buscar"
                                                                v-model="menu.text"
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
                                                </v-menu>
                                            </v-sheet>
                                            <v-sheet v-if="view == 1">
                                                <v-data-table
                                                    :server-items-length="data.high"
                                                    :footer-props="{showFirstLastPage: true,
                                                                    itemsPerPageOptions: [16],
                                                                    firstIcon: 'mdi-page-first',
                                                                    lastIcon: 'mdi-page-last',
                                                                    prevIcon: 'mdi-chevron-left',
                                                                    nextIcon: 'mdi-chevron-right',
                                                                    pageText: '{0} a {1} de {2}'}"
                                                    :options.sync="data"
                                                    :loading="true"
                                                    :headers="[{text: 'Tipo', align: 'start', width: 60, sortable: false},
                                                               {text: 'Nombre', align: 'start', sortable: false},
                                                               {sortable: false}]"
                                                    :items="list"
                                                    hide-default-header
                                                    bordered>
                                                    <v-progress-linear
                                                        color="primary"
                                                        slot="progress"
                                                        v-show="wait"
                                                        indeterminate>
                                                    </v-progress-linear>
                                                    <template v-slot:header="{props}">
                                                        <thead class="v-data-table-header">
                                                            <th 
                                                                scope="col"
                                                                role="columnheader"
                                                                :class="`text-${head.align}`"
                                                                :width="head.width"
                                                                v-for="head in props.headers"
                                                                v-if="head.text">
                                                                <span>@{{head.text.toUpperCase()}}</span>
                                                            </th>
                                                            <th width="90">&nbsp;</th>
                                                        </thead>
                                                    </template>
                                                    <template v-slot:item="{item}">
                                                        <tr :class="((bulk.list.length && (!~bulk.list.indexOf(item))) && 'lock')">
                                                            <td>
                                                                <div class="font-weight-medium">
                                                                    <v-icon>@{{{1: 'mdi-map-marker', 2: 'mdi-postage-stamp', 3: 'mdi-axis-y-arrow', 4: 'mdi-play'}[item.type]}}</v-icon>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="font-weight-medium grey--text">
                                                                    @{{item.code}}
                                                                </div>
                                                                <div class="font-weight-medium">
                                                                    @{{item.name}}
                                                                </div>
                                                            </td>
                                                            <td class="px-0">
                                                                <v-btn
                                                                    class="mr-2"
                                                                    :disabled="bulk.list.length ? true : false"
                                                                    :color="item.lock ? 'red' : 'green'"
                                                                    @click="open('lock', item)"
                                                                    icon>
                                                                    <v-icon>
                                                                        mdi-lock-reset
                                                                    </v-icon>
                                                                </v-btn>
                                                                <v-menu>
                                                                    <template v-slot:activator="{on, bind}">
                                                                        <v-btn
                                                                            v-bind="bind"
                                                                            v-on="on"
                                                                            color="#4B5D65"
                                                                            icon>
                                                                            <v-icon>mdi-dots-vertical</v-icon>
                                                                        </v-btn>
                                                                    </template>
                                                                    <v-list>
                                                                        <v-list-item
                                                                            :disabled="bulk.list.length ? true : false"
                                                                            @click="open('edit', item)"
                                                                            link>
                                                                            <v-list-item-icon>
                                                                                <v-icon>mdi-pencil</v-icon>
                                                                            </v-list-item-icon>
                                                                            <v-list-item-title>Editar</v-list-item-title>
                                                                        </v-list-item>
                                                                        <v-list-item
                                                                            :disabled="bulk.list.length ? true : false"
                                                                            @click="open('drop', item)"
                                                                            link>
                                                                            <v-list-item-icon>
                                                                                <v-icon>mdi-delete</v-icon>
                                                                            </v-list-item-icon>
                                                                            <v-list-item-title>Eliminar</v-list-item-title>
                                                                        </v-list-item>
                                                                    </v-list>
                                                                </v-menu>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                    <template slot="loading">
                                                        <tr>
                                                            <th colspan="3" v-if="wait">
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
                                                            </th>
                                                            <th colspan="3" v-else>
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
                                                                                No se encontraron elementos.
                                                                            </v-list-item-subtitle>
                                                                        </v-list-item-content>
                                                                    </v-list-item>
                                                                </v-list>
                                                            </th>
                                                        </tr>
                                                    </template>
                                                </v-data-table>
                                            </v-sheet>
                                            <v-sheet v-else>
                                                <v-layout
                                                    class="mx-4 my-1"
                                                    column>
                                                    <v-sheet>
                                                        <v-row
                                                            v-if="fail.text"
                                                            dense>
                                                            <v-col>
                                                                <v-alert
                                                                    class="mt-3"
                                                                    type="error"
                                                                    tile>
                                                                    @{{fail.text}}
                                                                </v-alert>
                                                            </v-col>
                                                        </v-row>
                                                    </v-sheet>
                                                    <v-sheet>
                                                        <v-row>
                                                            <v-col>
                                                                <v-select
                                                                    item-value="item"
                                                                    item-text="text"
                                                                    :items="[{item: 1, text: 'Punto'},
                                                                             {item: 2, text: 'Imagen'},
                                                                             {item: 3, text: 'Modelo'},
                                                                             {item: 4, text: 'Video'}]"
                                                                    v-model="form.main.type"
                                                                    :error-messages="(fail.list.type ? [fail.list.type] : [])"
                                                                    label="Tipo"
                                                                    hide-details="auto"
                                                                    :disabled="wait"
                                                                    filled>	
                                                                </v-select>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Código"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.code"
                                                                    :error-messages="(fail.list.code ? [fail.list.code] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Nombre"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.name"
                                                                    :error-messages="(fail.list.name ? [fail.list.name] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Ruta"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.path"
                                                                    :error-messages="(fail.list.path ? [fail.list.path] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Archivo"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.file"
                                                                    :error-messages="(fail.list.file ? [fail.list.file] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Altitud"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.altitude"
                                                                    :error-messages="(fail.list.altitude ? [fail.list.altitude] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Latitud"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.latitude"
                                                                    :error-messages="(fail.list.latitude ? [fail.list.latitude] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Longitud"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.longitude"
                                                                    :error-messages="(fail.list.longitude ? [fail.list.longitude] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col>
                                                                <v-textarea
                                                                    auto-grow="true"
                                                                    label="Descripción"
                                                                    maxlength="256"
                                                                    v-model="form.description"
                                                                    :error-messages="(fail.list.description ? [fail.list.description] : [])"
                                                                    :value="form.description"
                                                                    :disabled="wait"
                                                                    hide-details="auto"
                                                                    counter
                                                                    filled>
                                                                </v-textarea>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 1">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Tamaño"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.sza"
                                                                    :error-messages="(fail.list.sza ? [fail.list.sza] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 1">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Distancia"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.swa"
                                                                    :error-messages="(fail.list.swa ? [fail.list.swa] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 1">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Escala / X"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.scx"
                                                                    :error-messages="(fail.list.scx ? [fail.list.scx] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 1">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Escala / Y"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.scy"
                                                                    :error-messages="(fail.list.scy ? [fail.list.scy] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 1">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Escala / Z"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.scz"
                                                                    :error-messages="(fail.list.scz ? [fail.list.scz] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 1">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Rotación / X"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.rtx"
                                                                    :error-messages="(fail.list.rtx ? [fail.list.rtx] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 1">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Rotación / Y"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.rty"
                                                                    :error-messages="(fail.list.rty ? [fail.list.rty] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 1">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Rotación / Z"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.rtz"
                                                                    :error-messages="(fail.list.rtz ? [fail.list.rtz] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 1">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Traslado / X"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.trx"
                                                                    :error-messages="(fail.list.trx ? [fail.list.trx] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 1">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Traslado / Y"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.try"
                                                                    :error-messages="(fail.list.try ? [fail.list.try] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 1" >
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Traslado / Z"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.trz"
                                                                    :error-messages="(fail.list.trz ? [fail.list.trz] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 1">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Traslado / L"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.trl"
                                                                    :error-messages="(fail.list.trl ? [fail.list.trl] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 1">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Traslado / T"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.trt"
                                                                    :error-messages="(fail.list.trt ? [fail.list.trt] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 2">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Tamaño"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.sza"
                                                                    :error-messages="(fail.list.szi ? [fail.list.szi] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 2">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Distancia"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.swi"
                                                                    :error-messages="(fail.list.swi ? [fail.list.swi] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 2">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Escala / X"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.sca"
                                                                    :error-messages="(fail.list.sca ? [fail.list.sca] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 2">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Escala / Y"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.scb"
                                                                    :error-messages="(fail.list.scb ? [fail.list.scb] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 2">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Escala / Z"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.scc"
                                                                    :error-messages="(fail.list.scc ? [fail.list.scc] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 2">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Rotación / X"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.rta"
                                                                    :error-messages="(fail.list.rta ? [fail.list.rta] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 2">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Rotación / Y"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.rtb"
                                                                    :error-messages="(fail.list.rtb ? [fail.list.rtb] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 2">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Rotación / Z"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.rtc"
                                                                    :error-messages="(fail.list.rtc ? [fail.list.rtc] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 2">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Traslado / X"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.tra"
                                                                    :error-messages="(fail.list.tra ? [fail.list.tra] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 2">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Traslado / Y"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.trb"
                                                                    :error-messages="(fail.list.trb ? [fail.list.trb] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 2">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Traslado / Z"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.trc"
                                                                    :error-messages="(fail.list.trc ? [fail.list.trc] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 2">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Traslado / L"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.trl"
                                                                    :error-messages="(fail.list.trl ? [fail.list.trl] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="pane == 2">
                                                            <v-col>
                                                                <v-text-field
                                                                    type="text"
                                                                    label="Traslado / T"
                                                                    autocomplete="off"
                                                                    hide-details="auto"
                                                                    v-model="form.main.trt"
                                                                    :error-messages="(fail.list.trt ? [fail.list.trt] : [])"
                                                                    :disabled="wait"
                                                                    filled>
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                    </v-sheet>
                                                </v-layout>
                                            </v-sheet>
                                        </v-card-text>
                                    </v-card>
                                </v-col>
                            </v-row>
                        </v-layout>

                        <v-dialog v-model="wipe" persistent outlined tile>
                            <v-card>
                                <v-card-title class="headline">Eliminar elemento</v-card-title>
                                <v-divider></v-divider>
                                <v-card-text class="mt-4">¿Desea eliminar el elemento <b>@{{pick ? pick.name : null}} / @{{pick ? pick.code : null}}</b>?</v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="secondary" text @click="(wipe = false)" :disabled="wait">Cancelar</v-btn>
                                    <v-btn color="red" text @click="drop(pick)" :disabled="wait">Eliminar</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>

                        <v-dialog v-model="swap" persistent outlined tile>
                            <v-card>
                                <v-card-title class="headline">@{{((pick && pick.lock) ? 'Habilitar elemento' : 'Bloquear elemento')}}</v-card-title>
                                <v-divider></v-divider>
                                <v-card-text class="mt-4">¿Desea @{{((pick && pick.lock) ? 'habilitar' : 'bloquear')}} el elemento <b>@{{pick ? pick.name : null}} / @{{pick ? pick.code : null}}</b>?</v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="secondary" text @click="(swap = false)" :disabled="wait">Cancelar</v-btn>
                                    <v-btn :color="((pick && pick.lock) ? 'success' : 'error')" text @click="lock(pick)" :disabled="wait">@{{((pick && pick.lock) ? 'Habilitar' : 'Bloquear')}}</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-main>
                </v-app>
            </div>
        </div>
        <script src="/scripts/vue.min.js"></script>
        <script src="/scripts/i18n.min.js"></script>
        <script src="/scripts/axios.min.js"></script>
        <script src="/scripts/jquery.min.js"></script>
        <script src="/scripts/vuetify.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                var self = new Vue({
                    el: '#body',
                    vuetify: new Vuetify(),
                    data: {
                        lands: [],
                        zones: [],
                        wait: false,
                        show: false,
                        wipe: false,
                        swap: false,
                        menu: false,
                        busy: false,
					  	done: false,
                        pick: null,
                        time: null,
                        take: 32,
                        high: 0,
                        page: 0,
                        view: 1,
                        pane: 1,
                        list: [],
                        user: {
                            live: 0,
                            type: 1,
                            face: null,
                            mail: null,
                            last: null,
                            name: null
                        },
                        help: {
                            show: false,
                            name: 'Test',
                            data: 'This is a test'
                        },
                        note: {
                            show: false,
                            type: null,
                            text: null
                        },
                        seek: {
                            drop: false,
                            text: null,
                            last: [{icon: 'mdi-history', text: 'juan gomez'}, {icon: 'mdi-history', text: 'esperanza'}],
                            list: []
                        },
                        fail: {
                            text: null,
                            list: {}
                        },
                        sort: {
                            text: null,
                            zone: null
                        },
                        data: {
                            page: 0,
                            high: 0,
                            itemsPerPage: 32
                        },
                        bulk: {
                            show: false,
                            list: [],
                            data: {
                                note: null
                            }
                        },
                        menu: {
                            show: false,
                            text: null,
                            pick: null,
                            date: null,
                            form: [],
                            list: [],
                            data: []
                        },
                        form: {
                            main: {
                                lock: null,
                                land: null,
                                zone: null,
                                code: null,
                                name: null
                            },
                            dump: {
                                show: false,
                                menu: false,
                                type: null,
                                fail: {
                                    text: null,
                                    data: {}
                                },
                                data: {
                                    land: null,
                                    zone: null,
                                    name: null
                                }
                            }
                        }
                    },
                    watch: {
                        'menu.text': function (text) {
                            if (self.time) {
                                clearTimeout(self.time);
                            }

                            self.time = setTimeout(function () {
                                self.load(self.data.itemsPerPage, null, text);
                            }, 500);
                        },
                        data: {
                            handler: function (data) {
                            self.load(data.itemsPerPage, data.page, self.menu.text);
                            },
                            deep: true
                        }
                    },
                    methods: {
                        load: function (take, page, text, done) {
                            axios.get("{{route('spot', ['task' => 'load'])}}?take=" + (take || '') + '&page=' + (page || '') + '&find=' + (text || ''), {})
                                .then(function (data) {
                                self.data.high = data.data.high;

                                self.data.page = data.data.page;

                                self.list = data.data.data;

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
                        open: function (task, item) {
                            self.note = {show: false,
                                        type: null,
                                        text: null};

                            self.fail = {text: null,
                                        list: {}};

                            if (item) {
                                switch (task) {
                                    case 'edit':
                                        axios.get("{{route('spot', ['task' => 'load'])}}/" + item.hash, {})
                                            .then(function (data) {
                                            self.form.main = {description: data.data.description,
                                                              longitude: data.data.longitude,
                                                              latitude: data.data.latitude,
                                                              altitude: data.data.altitude,
                                                              lock: data.data.lock,
                                                              type: data.data.type,
                                                              code: data.data.code,
                                                              name: data.data.name,
                                                              file: data.data.file,
                                                              path: data.data.path,
                                                              sza: data.data.sza,
                                                              szi: data.data.szi,
                                                              swa: data.data.swa,
                                                              swi: data.data.swi,
                                                              sca: data.data.sca,
                                                              scb: data.data.scb,
                                                              scc: data.data.scc,
                                                              scx: data.data.scx,
                                                              scy: data.data.scy,
                                                              scz: data.data.scz,
                                                              rta: data.data.rta,
                                                              rtb: data.data.rtb,
                                                              rtc: data.data.rtc,
                                                              rtx: data.data.rtx,
                                                              rty: data.data.rty,
                                                              rtz: data.data.rtz,
                                                              tra: data.data.tra,
                                                              trb: data.data.trb,
                                                              trc: data.data.trc,
                                                              trx: data.data.trx,
                                                              try: data.data.try,
                                                              trz: data.data.trz,
                                                              trl: data.data.trl,
                                                              trt: data.data.trt};

                                            if (self.wait) {
                                                setTimeout(function () {
                                                    self.wait = false;
                                                }, 200);
                                            } else {
                                                clearTimeout(self.time);
                                            }

                                            self.view = 2;
                                        }).catch(function (fail) {
                                            if (fail.response.data.text) {
                                                self.note = {show: true,
                                                            type: 'fail',
                                                            text: fail.response.data.text};
                                            } else {
                                                self.note = {show: true,
                                                            type: 'fail',
                                                            text: 'Se presentó un error inesperado.'};
                                            }

                                            if (self.wait) {
                                                setTimeout(function () {
                                                    self.wait = false;
                                                }, 200);
                                            } else {
                                                clearTimeout(self.time);
                                            }
                                        });

                                        this.time = setTimeout(function () {
                                            self.wait = true;
                                        }, 100);

                                        this.pick = item;
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
                                        self.form.main = {description: null,
                                                         longitude: null,
                                                         latitude: null,
                                                         altitude: null,
                                                         lock: null,
                                                         type: null,
                                                         code: null,
                                                         name: null,
                                                         file: null,
                                                         path: null,
                                                         sza: null,
                                                         szi: null,
                                                         swa: null,
                                                         swi: null,
                                                         sca: null,
                                                         scb: null,
                                                         scc: null,
                                                         scx: null,
                                                         scy: null,
                                                         scz: null,
                                                         rta: null,
                                                         rtb: null,
                                                         rtc: null,
                                                         rtx: null,
                                                         rty: null,
                                                         rtz: null,
                                                         tra: null,
                                                         trb: null,
                                                         trc: null,
                                                         trx: null,
                                                         try: null,
                                                         trz: null,
                                                         trl: null,
                                                         trt: null};

                                        self.pick = null;

                                        self.view = 2;
                                        break;
                                    case 'dump':
                                        self.form.dump = {
                                            menu: false,
                                            show: true,
                                            type: 1,
                                            fail: {
                                                text: null,
                                                data: {}
                                            },
                                            data: {
                                                land: null,
                                                zone: null,
                                                name: null
                                            }
                                        }
                                        break;
                                }
                            }
                                
                        },
                        save: function (form, item) {
                            var data = new FormData();

                            self.fail = {text: null,
                                        list: {}};
                            
                            data.append('description', form.description);

                            data.append('longitude', form.longitude);

                            data.append('latitude', form.latitude);

                            data.append('altitude', form.altitude);

                            data.append('type', form.type);

                            data.append('code', form.code);

                            data.append('name', form.name);

                            data.append('file', form.file);

                            data.append('path', form.path);

                            data.append('sza', form.sza);

                            data.append('szi', form.szi);

                            data.append('swa', form.swa);

                            data.append('swi', form.swi);

                            data.append('sca', form.sca);

                            data.append('scb', form.scb);

                            data.append('scc', form.scc);

                            data.append('scx', form.scx);

                            data.append('scy', form.scy);

                            data.append('scz', form.scz);

                            data.append('rta', form.rta);

                            data.append('rtb', form.rtb);

                            data.append('rtc', form.rtc);

                            data.append('rtx', form.rtx);

                            data.append('rty', form.rty);

                            data.append('rtz', form.rtz);

                            data.append('tra', form.tra);

                            data.append('trb', form.trb);

                            data.append('trc', form.trc);

                            data.append('trx', form.trx);

                            data.append('try', form.try);

                            data.append('trz', form.trz);

                            data.append('trl', form.trl);

                            data.append('trt', form.trt);

                            if (item) {
                                axios.post("{{route('spot', ['task' => 'save'])}}/" + item.hash, data, {'X-CSRF-TOKEN': '{{csrf_token()}}'})
                                    .then(function (data) {
                                    self.wait = false;

                                    self.note = {show: true,
                                                type: 'done',
                                                text: data.data.text};

                                    item.zone = self.find(self.zones, 'item', form.zone, 'name');

                                    item.lock = form.lock;

                                    item.code = form.code;

                                    item.name = form.name;

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
                                axios.post("{{route('spot', ['task' => 'make'])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
                                    .then(function (data) {
                                    self.list.unshift((item = {zone: self.find(self.zones, 'item', form.zone, 'name'),
                                                            item: data.data.item,
                                                            hash: data.data.hash,
                                                            code: data.data.code,
                                                            hand: data.data.hand,
                                                            lock: form.lock,
                                                            name: form.name}));

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
                            axios.get("{{route('spot', ['task' => 'lock'])}}/" + item.hash, {})
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
                            axios.get("{{route('spot', ['task' => 'drop'])}}/" + item.hash, {})
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
                        },
                        same: function (one, two) {
							return one == two;
						},
				  		find: function (list, test, data, name, none) {
							var item = list.findIndex(function(item) {
								if ((item[test] == data)) {
									return true;
								}
							});

							if ((~item)) {
								if (name) {
									return list[item][name];
								} else {
									return list[item];
								}
							}

							return none;
						},
						text: function (value, length, decimals, thousands) {
		                  	length = isFinite(+length) ? Math.abs(length) : 0;

		                  	point = (typeof point === 'undefined') ? '.' : point;

		                  	value = (value + '').replace(/[^0-9+\-Ee.]/g, '');

		                  	value = isFinite(+value) ? +value : 0;

		                  	var base = Math.pow(10, length);

		                  	var string = (length ? '' + (Math.round(value * base) / base) : '' + Math.round(value)).split('.');

		                  	if ((string[0].length > 3)) {
		                      	string[0] = string[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, (thousands === undefined) ? ',' : thousands);
		                  	}

		                  	if ((string[1] || '').length < length) {
		                      	string[1] = string[1] || '';

		                      	string[1] = string[1] + new Array(length - string[1].length + 1).join('0');
		                  	}

		                  	return string.join((typeof decimals === 'undefined') ? '.' : decimals);
		                },
		                date: function (date, text) {
		                	return moment(date).format(text);
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
    </body>
</html>