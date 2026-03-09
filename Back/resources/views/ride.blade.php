@extends('team')

@section('page')
	<v-container class="pa-0">
		<v-row class="ma-0">
			<v-col class="pa-0">
				<v-layout class="white px-2">
					<v-list>
						<v-list-item class="px-1">
							<v-list-item-avatar
                                color="red"
                                size="36"
                                tile>
								<v-icon
                                    color="white">
									mdi-moped
								</v-icon>
							</v-list-item-avatar>
							<v-list-item-content
								class="pa-0">
								<v-list-item-title>
									Domicilios
								</v-list-item-title>
								<v-list-item-subtitle>
									<v-breadcrumbs
										class="pa-0"
										:items="[{text: 'Inicio', href: '{{route('dash')}}'}, {text: 'Domicilios'}]">
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
		<v-row class="ma-0 pa-0">
            <v-col class="ma-0 pa-0">
                <div
                    id="plat"
                    :class="{tiny: tiny}">
                </div>
			</v-col>
		</v-row>
        <v-row class="ma-0 pa-0">
            <v-col class="ma-0 pa-0">
                <v-data-table
                    :items="list.filter((stop) => (none(find, true, (stop) => ((~stop.name.toLocaleLowerCase().indexOf(find.toLocaleLowerCase())) || (~stop.town.toLocaleLowerCase().indexOf(find.toLocaleLowerCase())) || (~stop.address.toLocaleLowerCase().indexOf(find.toLocaleLowerCase()))), stop))).sort((item, next) => (none(item.done, 0, 1) - none(next?.done, 0, 1)))"
                    hide-default-header
                    hide-default-footer
                    loading>
                    <v-progress-linear
                        color="primary"
                        slot="progress"
                        v-show="wait"
                        indeterminate>
                    </v-progress-linear>
                    <template v-slot:header="{props}">
                        <tr>
                            <th class="font-weight-medium text-start pa-0">
                                <v-divider>
                                </v-divider>
                                <v-list-item>
                                    <v-list-item-content>
                                        <v-list-item-title>
                                            Paradas
                                        </v-list-item-title>
                                        <v-list-item-subtitle>
                                            @{{none(list.length, 'Sin paradas disponibles', (list) => (`${list.filter((stop) => (Boolean(stop.done))).length} de ${list.length}`), list)}}
                                        </v-list-item-subtitle>
                                    </v-list-item-content>
                                    <v-list-item-action>
                                        <v-btn
                                            @click="move(null)"
                                            icon>
                                            <v-icon>mdi-crosshairs-gps</v-icon>
                                        </v-btn>
                                    </v-list-item-action>
                                </v-list-item>
                                <v-divider>
                                </v-divider>
                                <v-text-field
                                    prepend-inner-icon="mdi-magnify"
                                    clear-icon="mdi-close-circle"
                                    background-color="grey lighten-4"
                                    class="font-weight-regular"
                                    label="Buscar"
                                    v-model="find"
                                    autocomplete="off"
                                    hide-details
                                    clearable
                                    solo
                                    flat>
                                </v-text-field>
                                <v-divider>
                                </v-divider>
                            </th>
                        </tr>
                    </template>
                    <template v-slot:item="{item}">
                        <tr>
                            <td>
                                <v-list-item active>
                                    <v-list-item-content @click="show(item)">
                                        <v-list-item-title >
                                            @{{item.name}}
                                        </v-list-item-title>
                                        <v-list-item-subtitle>
                                            @{{item.address}}, <b>@{{item.town}}</b>
                                        </v-list-item-subtitle>
                                    </v-list-item-content>
                                    <v-list-item-action>
                                        <template v-if="none(item.done)">
                                            <v-btn
                                                v-if="Boolean((item.mobile ?? item.phone))"
                                                @click="call(item)"
                                                icon>
                                                <v-icon>mdi-phone</v-icon>
                                            </v-btn>
                                            <v-btn
                                                @click="move(item)"
                                                icon>
                                                <v-icon>mdi-map</v-icon>
                                            </v-btn>
                                        </template>
                                        <template v-else>
                                            <v-icon class="mx-2" :color="same(item.done, 1, '#2E7D32', '#D32F2F')">@{{same(item.done, 1, 'mdi-check', 'mdi-close')}}</v-icon>
                                        </template>
                                    </v-list-item-action>
                                </v-list-item>
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
                                            @{{none(trim(find), 'No se encontraron paradas disponibles.', 'No se encontraron coincidencias.')}}
                                        </v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list>
                        </v-sheet>
                    </template>
                </v-data-table>
			</v-col>
		</v-row>
	</v-container>

    <v-bottom-sheet v-model="card.open">
        <v-card
            class="pb-1"
            flat
            tile>
            <v-card-title class="px-3">
                @{{card.item?.name}}
            </v-card-title>
            <v-card-subtitle class="px-3 pb-2">
                @{{card.item?.address}}, <b>@{{card.item?.town}}</b>
            </v-card-subtitle>
            <v-card-text class="px-3 pb-2">
                <v-row dense>
                    <v-col class="flex-grow-1">
                        <v-chip class="px-4" :color="{1: '#1B5E20', 2: '#FF6F00'}[card.item?.type]" label>
                            <v-icon left>
                                @{{{1: 'mdi-currency-usd', 2: 'mdi-package-variant-closed'}[card.item?.type]}}
                            </v-icon>
                            @{{{1: 'Efectivo', 2: 'Especie'}[card.item?.type]}}
                        </v-chip>
                    </v-col>
                    <v-col class="text-no-wrap flex-grow-0 text-h6">
                        @{{same(card.item?.type, 1, (item) => (`$${text(item?.load)}`), (item) => (`${text(item?.load)} Und`), card?.item)}}
                    </v-col>
                </v-row>
                <v-row
                    v-if="card.item?.indication"
                    dense>
                    <v-col>
                        @{{card.item?.indication}}
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions
                v-if="none(card.item?.done)"
                class="px-3">
                <v-row no-gutters>
                    <v-col class="flex-grow-1">
                        <v-btn
                            color="success"
                            :disabled="wait"
                            :loading="wait"
                            @click="card.done = 1"
                            block
                            large>
                            Confirmar
                        </v-btn>
                    </v-col>
                    <v-col class="flex-grow-0 ml-2">
                        <v-btn
                            color="error"
                            :disabled="wait"
                            @click="card.done = 2"
                            large>
                            <v-icon size="24">
                                mdi-close
                            </v-icon>
                        </v-btn>
                    </v-col>
                </v-row>
            </v-card-actions>
            <v-expand-transition>
                <v-card
                    v-if="Boolean(card.done)"
                    class="transition-fast-in-fast-out pb-1"
                    show
                    flat
                    tile>
                    <v-card-title  class="px-3">
                        @{{card.item?.name}}
                    </v-card-title>
                    <v-card-subtitle class="px-3 pb-2">
                        @{{card.item?.address}}, <b>@{{card.item?.town}}</b>
                    </v-card-subtitle>
                    <v-card-text class="px-3 pb-2">
                        <v-textarea
                            label="Detalles"
                            maxlength="256"
                            hide-details="auto"
                            autocomplete="off"
                            v-model="card.note"
                            :error-messages="[fail?.note].filter(Boolean)"
                            :value="card.note"
                            :disabled="wait"
                            auto-grow
                            counter
                            filled>
                        </v-textarea>
                    </v-card-text>

                    <v-card-action class="mx-3">
                        <v-row no-gutters>
                            <v-col class="flex-grow-0 mr-2">
                                <v-btn
                                    :disabled="wait"
                                    @click="card.done = null"
                                    large>
                                    <v-icon size="24">
                                        mdi-arrow-left
                                    </v-icon>
                                </v-btn>
                            </v-col>
                            <v-col class="flex-grow-1">
                                <v-btn
                                    :color="{1: 'success', 2: 'error'}[card.done]"
                                    :disabled="wait"
                                    :loading="wait"
                                    @click="save({done: card.done, note: card.note}, card.item)"
                                    block
                                    large>
                                    GUARDAR
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-card-actions>
                </v-card>
            </v-expand-transition>
        </v-card>
    </v-bottom-sheet>
@endsection

@section('code')
    <script type="text/javascript">
  		Vue.ready(function () {
            var plat = null;

            var spot = null;

  			var self = new Vue({
			  	el: '#page',
			  	vuetify: new Vuetify(),
			  	data: {
			  		wait: false,
			  		menu: false,
                    full: true,
                    pick: null,
                    find: null,
					list: [],
			  		load: 0,
			  		cost: 0,
                    card: {
                        open: false,
                        done: null,
                        item: null,
                        note: null
                    }
			    },
			    watch: {
                    list: {
						handler: (list) => {
                            list.forEach((stop) => {
                                var spot = new google.maps.Marker({
                                    position: new google.maps.LatLng(stop.spot[0], stop.spot[1]),
                                    optimized: true,
                                    icon: {
                                        path: 'M12 2C8.1 2 5 5.1 5 9C5 14.2 12 22 12 22S19 14.2 19 9C19 5.1 15.9 2 12 2M14.5 13L12 11.5L9.5 13L10.2 10.2L8 8.3L10.9 8.1L12 5.4L13.1 8L16 8.3L13.8 10.2L14.5 13Z',
                                        fillColor: {0: '#1E88E5', 1: '#388E3D', 2: '#D32F2F'}[stop.done],
                                        fillOpacity: 1,
                                        strokeColor: '#134B58',
                                        strokeWeight: 0.5,
                                        scale: 1.5,
                                        anchor: new google.maps.Point(24, 24)
                                    },
                                    map: plat,
                                    id: stop.item
                                });

                                spot.addListener('click', (event) => {
                                    self.show(stop);
                                });
                            });
                        }
                    }
			    },
			    methods: {
			    	pull: (busy, done) => {
			    		var data = new FormData();

			    		axios.post("{{route('ride', ['task' => 'load'])}}", data, {'X-CSRF-TOKEN': '{{csrf_token()}}'})
				             .then((data) => {
							self.list = data.data ?? [];

				            setTimeout(() => {
				            	self.busy = false;
				            }, 300);

							if (done) {
				            	done(false);
				            }
				        }).catch((fail) => {
				            setTimeout(() => {
				            	self.busy = false;

				            	self.note = {
                                    show: true,
                            	    type: 'fail',
                            	    text: 'Se presentó un error inesperado.'
                                };
				            }, 300);

							if (done) {
				            	done(true);
				            }
				        });

				        this.busy = busy;
			    	},
                    call: (item) => {
                        window.open(`tel: ${(stop.mobile ?? stop.phone)}`);
                    },
					show: (item) => {
                        self.card = {
                            open: true,
                            done: null,
                            note: null,
                            item: item
                        };
					},
                    save: (form, item) => {
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition((spot) => {
                                var data = new FormData();

                                data.append('done', form.done ?? '');

                                data.append('note', form.note ?? '');

                                [spot.coords.latitude, spot.coords.longitude].forEach((part) => {
                                    data.append('spot[]', part);
                                });

                                axios.post(`{{route('ride', ['task' => 'done'])}}/${item.hash}`, data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
	                                 .then((data) => {
                                    self.card.open = false;

                                    item.done = form.done;

                                    self.wait = false;

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
                            }, (fail) => {
                                self.wait = false;

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

                            self.wait = true;
                        } else {
                            self.note = {
                                show: true,
                                type: 'fail',
                                text: 'La geolocalización no está disponible.'
                            };
                        }
                    },
                    move: (item) => {
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
					}
			    },
			    mounted: function () {
                    plat = new google.maps.Map(document.getElementById('plat'), {
                        mapTypeControl: false,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        center: new google.maps.LatLng(6.2442876, -75.6162309),
                        zoom: 16
                    });

                    spot = new google.maps.Marker({
                        position: new google.maps.LatLng(6.2442876, -75.6162309),
                        icon: {
                            path: 'M12,2C15.31,2 18,4.66 18,7.95C18,12.41 12,19 12,19C12,19 6,12.41 6,7.95C6,4.66 8.69,2 12,2M12,6A2,2 0 0,0 10,8A2,2 0 0,0 12,10A2,2 0 0,0 14,8A2,2 0 0,0 12,6M20,19C20,21.21 16.42,23 12,23C7.58,23 4,21.21 4,19C4,17.71 5.22,16.56 7.11,15.83L7.75,16.74C6.67,17.19 6,17.81 6,18.5C6,19.88 8.69,21 12,21C15.31,21 18,19.88 18,18.5C18,17.81 17.33,17.19 16.25,16.74L16.89,15.83C18.78,16.56 20,17.71 20,19Z',
                            fillColor: '#D32F2F',
                            fillOpacity: 1,
                            strokeColor: '#134B58',
                            strokeWeight: 0.5,
                            scale: 1.5,
                            anchor: new google.maps.Point(24, 24)
                        },
                        map: plat
                    });

					this.pull(false, (fail) => {
						setTimeout(() => {
							self.done = true;
						}, 300);
					});
				}
			})
		  });

  	</script>
@stop