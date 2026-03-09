@extends('team')

@section('page')
	<v-layout column>
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
									mdi-view-dashboard
								</v-icon>
							</v-list-item-avatar>
							<v-list-item-content
								class="pa-0">
								<v-list-item-title>
									Reporte
								</v-list-item-title>
								<v-list-item-subtitle>
									<v-breadcrumbs
										class="pa-0"
										:items="[{text: 'Inicio', href: '{{route('dash')}}'}, {text: 'Reporte'}]">
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
				<v-row>
					<v-col
						class="mt-4"
						:cols="tiny ? 12 : 6">
						<v-card outlined>
							<v-list-item>
								<v-list-item-avatar
                                    class="mt-n9 elevation-2"
									color="blue lighten-4"
									size="80"
                                    tile>
									<v-icon
										color="blue"
										large>
										mdi-lightbulb-on
									</v-icon>
								</v-list-item-avatar>
								<v-list-item-content>
									<v-list-item-title>
                                        <span class="headline">@{{data.works}}</span>
									</v-list-item-title>
									<v-list-item-subtitle class="text-uppercase">
										Proyectos
									</v-list-item-subtitle>
								</v-list-item-content>
							</v-list-item>
						</v-card>
					</v-col>
					<v-col
						class="mt-4"
						:cols="tiny ? 12 : 6">
						<v-card outlined>
							<v-list-item>
								<v-list-item-avatar
                                    class="mt-n9 elevation-2"
									color="green lighten-4"
									size="80"
                                    tile>
									<v-icon
										color="green"
										large>
										mdi-office-building
									</v-icon>
								</v-list-item-avatar>
								<v-list-item-content>
									<v-list-item-title>
                                        <span class="headline">@{{text(data.units)}}</span>
									</v-list-item-title>
									<v-list-item-subtitle class="text-uppercase">
										Unidades
									</v-list-item-subtitle>
								</v-list-item-content>
							</v-list-item>
						</v-card>
					</v-col>
				</v-row>
			</v-col>
		</v-row>
	</v-layout>

	<v-dialog
		v-model="form.dump.show"
		width="40%"
		persistent
		outlined
		tile>
		<v-card>
			<v-card-title>
				Exportar registros
			</v-card-title>
			<v-progress-linear
				:active="form.dump.wait"
				:indeterminate="form.dump.wait">
            </v-progress-linear>
			<v-divider>
			</v-divider>
			<v-card-text class="mt-4">
				<v-row v-if="form.dump.fail.text">
					<v-col>
						<v-alert
							class="ma-0"
							type="error"
							tile>
							@{{form.dump.fail.text}}
						</v-alert>
					</v-col>
				</v-row>
				<v-row>
					<v-col>
						<v-menu
							v-model="form.dump.menu"
							:close-on-content-click="false"
							:nudge-right="40"
							transition="scale-transition"
							min-width="290px">
							<template v-slot:activator="{on}">
								<v-text-field
								    label="Fecha"
								    class="bind"
									v-on="on"
									:error-messages="((form.dump.fail.data.from || form.dump.fail.data.stop) ? [form.dump.fail.data.from || form.dump.fail.data.stop] : [])"
									:value="(form.dump.data.date[0] ? date(form.dump.data.date[0], 'DD/MM/YYYY') : '') + (form.dump.data.date[1] ? (' ~ ' + date(form.dump.data.date[1], 'DD/MM/YYYY')) : '')"
									:disabled="form.dump.wait"
									hide-details="auto"
									readonly
									filled>
								</v-text-field>
							</template>
							<v-date-picker
								locale="es"
								v-model="form.dump.data.date"
								@input="(form.dump.menu = ((form.dump.data.date[0] && form.dump.data.date[1]) ? false : true))"
								no-title
								range>
							</v-date-picker>
						</v-menu>
					</v-col>
				</v-row>
				<v-row>
					<v-col>
						<v-text-field
							type="text"
							label="Nombre"
							v-model="form.dump.data.name"
							:error-messages="(form.dump.fail.data['name']? [form.dump.fail.data['name']] : [])"
							:disabled="form.dump.wait"
							hide-details="auto"
							autocomplete="off"
							filled>
						</v-text-field>
					</v-col>
				</v-row>
			</v-card-text>
			<v-divider>
			</v-divider>
			<v-card-actions
				class="grey lighten-4 px-4">
				<v-spacer>
				</v-spacer>
				<v-btn
					color="secondary"
					@click="(form.dump.show = false)"
					:disabled="form.dump.wait"
					tile
					text>
					Cancelar
				</v-btn>
				<v-btn
					color="primary"
					@click="dump(form.dump.type, form.dump.data)"
					:disabled="form.dump.wait"
					tile
					text>
					Exportar
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
@endsection

@section('code')
    <script type="text/javascript">
  		Vue.ready(function () {
  			var self = new Vue({
			  	el: '#page',
			  	vuetify: new Vuetify(),
			  	data: {
			  		from: '{{date('Y-m-d')}}',
					land: {
						'AF': 'Afganistán',
						'AX': 'Islas Åland',
						'AL': 'Albania',
						'DZ': 'Argelia',
						'AS': 'Samoa Americana',
						'AD': 'Andorra',
						'AO': 'Angola',
						'AI': 'Anguila',
						'AQ': 'Antártida',
						'AG': 'Antigua y Barbuda',
						'AR': 'Argentina',
						'AM': 'Armenia',
						'AW': 'Aruba',
						'AU': 'Australia',
						'AT': 'Austria',
						'AZ': 'Azerbaiyán',
						'BS': 'Bahamas',
						'BH': 'Bahréin',
						'BD': 'Bangladesh',
						'BB': 'Barbados',
						'BY': 'Bielorrusia',
						'BE': 'Bélgica',
						'BZ': 'Belice',
						'BJ': 'Benin',
						'BM': 'Bermudas',
						'BT': 'Bután',
						'BO': 'Bolivia',
						'BA': 'Bosnia y Herzegovina',
						'BW': 'Botswana',
						'BV': 'Isla Bouvet',
						'BR': 'Brasil',
						'IO': 'Territorio Británico del Océano Índico',
						'BN': 'Brunei Darussalam',
						'BG': 'Bulgaria',
						'BF': 'Burkina Faso',
						'BI': 'Burundi',
						'KH': 'Camboya',
						'CM': 'Camerún',
						'CA': 'Canadá',
						'CV': 'Cabo Verde',
						'KY': 'Islas Caimán',
						'CF': 'República Centroafricana',
						'TD': 'Chad',
						'CL': 'Chile',
						'CN': 'China',
						'CX': 'Isla de Navidad',
						'CC': 'Islas Cocos (Keeling)',
						'CO': 'Colombia',
						'KM': 'Comoras',
						'CG': 'Congo',
						'CD': 'Congo, República Democrática del Congo',
						'CK': 'Islas Cook',
						'CR': 'Costa Rica',
						'CI': 'Costa de Marfil',
						'HR': 'Croacia',
						'CU': 'Cuba',
						'CY': 'Chipre',
						'CZ': 'República Checa',
						'DK': 'Dinamarca',
						'DJ': 'Djibouti',
						'DM': 'Dominica',
						'DO': 'República Dominicana',
						'EC': 'Ecuador',
						'EG': 'Egipto',
						'SV': 'El Salvador',
						'GQ': 'Guinea Ecuatorial',
						'ER': 'Eritrea',
						'EE': 'Estonia',
						'ET': 'Etiopía',
						'FK': 'Islas Malvinas (Falkland Islands)',
						'FO': 'Islas Feroe',
						'FJ': 'Fiji',
						'FI': 'Finlandia',
						'FR': 'Francia',
						'GF': 'Guayana Francesa',
						'PF': 'Polinesia Francesa',
						'TF': 'Territorios Australes Franceses',
						'GA': 'Gabón',
						'GM': 'Gambia',
						'GE': 'Georgia',
						'DE': 'Alemania',
						'GH': 'Ghana',
						'GI': 'Gibraltar',
						'GR': 'Grecia',
						'GL': 'Groenlandia',
						'GD': 'Granada',
						'GP': 'Guadalupe',
						'GU': 'Guam',
						'GT': 'Guatemala',
						'GG': 'Guernsey',
						'GN': 'Guinea',
						'GW': 'Guinea-Bissau',
						'GY': 'Guyana',
						'HT': 'Haití',
						'HM': 'Isla Heard e Islas Mcdonald',
						'VA': 'Santa Sede (Estado de la Ciudad del Vaticano)',
						'HN': 'Honduras',
						'HK': 'Hong Kong',
						'HU': 'Hungría',
						'IS': 'Islandia',
						'IN': 'India',
						'ID': 'Indonesia',
						'IR': 'Irán, República Islámica de Irán',
						'IQ': 'Irak',
						'IE': 'Irlanda',
						'IM': 'Isla de Man',
						'IL': 'Israel',
						'IT': 'Italia',
						'JM': 'Jamaica',
						'JP': 'Japón',
						'JE': 'Jersey',
						'JO': 'Jordania',
						'KZ': 'Kazajstán',
						'KE': 'Kenia',
						'KI': 'Kiribati',
						'KP': 'Corea, República Popular Democrática de Corea',
						'KR': 'República de Corea',
						'KW': 'Kuwait',
						'KG': 'Kirguistán',
						'LA': 'República Democrática Popular Lao',
						'LV': 'Letonia',
						'LB': 'Líbano',
						'LS': 'Lesoto',
						'LR': 'Liberia',
						'LY': 'Jamahiriya Árabe Libia',
						'LI': 'Liechtenstein',
						'LT': 'Lituania',
						'LU': 'Luxemburgo',
						'MO': 'Macao',
						'MK': 'Macedonia, Antigua República Yugoslava de Macedonia,',
						'MG': 'Madagascar',
						'MW': 'Malawi',
						'MY': 'Malasia',
						'MV': 'Maldivas',
						'ML': 'Mali',
						'MT': 'Malta',
						'MH': 'Islas Marshall',
						'MQ': 'Martinica',
						'MR': 'Mauritania',
						'MU': 'Mauricio',
						'YT': 'Mayotte',
						'MX': 'México',
						'FM': 'Micronesia, Estados Federados de Micronesia',
						'MD': 'Moldavia, República de Moldavia',
						'MC': 'Mónaco',
						'MN': 'Mongolia',
						'MS': 'Montserrat',
						'MA': 'Marruecos',
						'MZ': 'Mozambique',
						'MM': 'Myanmar',
						'NA': 'Namibia',
						'NR': 'Nauru',
						'NP': 'Nepal',
						'NL': 'Holanda',
						'AN': 'Antillas Holandesas',
						'NC': 'Nueva Caledonia',
						'NZ': 'Nueva Zelanda',
						'NI': 'Nicaragua',
						'NE': 'Níger',
						'NG': 'Nigeria',
						'NU': 'Niue',
						'NF': 'Isla Norfolk',
						'MP': 'Islas Marianas del Norte',
						'NO': 'Noruega',
						'OM': 'Omán',
						'PK': 'Pakistán',
						'PW': 'Palau',
						'PS': 'Territorio Palestino, Ocupado',
						'PA': 'Panamá',
						'PG': 'Papua Nueva Guinea',
						'PY': 'Paraguay',
						'PE': 'Perú',
						'PH': 'Filipinas',
						'PN': 'Pitcairn',
						'PL': 'Polonia',
						'PT': 'Portugal',
						'PR': 'Puerto Rico',
						'QA': 'Qatar',
						'RE': 'Reunión',
						'RO': 'Rumanía',
						'RU': 'Federación de Rusia',
						'RW': 'RWANDA',
						'SH': 'Santa Elena',
						'KN': 'San Cristóbal y Nieves',
						'LC': 'Santa Lucía',
						'PM': 'San Pedro y Miquelón',
						'VC': 'San Vicente y las Granadinas',
						'WS': 'Samoa',
						'SM': 'San Marino',
						'ST': 'Santo Tomé y Príncipe',
						'SA': 'Arabia Saudita',
						'SN': 'Senegal',
						'CS': 'Serbia y Montenegro',
						'SC': 'Seychelles',
						'SL': 'Sierra Leona',
						'SG': 'Singapur',
						'SK': 'Eslovaquia',
						'SI': 'Eslovenia',
						'SB': 'Islas Salomón',
						'SO': 'Somalia',
						'ZA': 'Sudáfrica',
						'GS': 'Georgia del Sur y las Islas Sandwich del Sur',
						'ES': 'España',
						'LK': 'Sri Lanka',
						'SD': 'Sudán',
						'SR': 'Surinam',
						'SJ': 'Svalbard y Jan Mayen',
						'SZ': 'Swazilandia',
						'SE': 'Suecia',
						'CH': 'Suiza',
						'SY': 'República Árabe Siria',
						'TW': 'Taiwán, provincia de China',
						'TJ': 'Tayikistán',
						'TZ': 'Tanzania, República Unida de Tanzania',
						'TH': 'Tailandia',
						'TL': 'Timor-Leste',
						'TG': 'Togo',
						'TK': 'Tokelau',
						'TO': 'Tonga',
						'TT': 'Trinidad y Tobago',
						'TN': 'Túnez',
						'TR': 'Turquía',
						'TM': 'Turkmenistán',
						'TC': 'Islas Turcas y Caicos',
						'TV': 'Tuvalu',
						'UG': 'Uganda',
						'UA': 'Ucrania',
						'AE': 'Emiratos Árabes Unidos',
						'GB': 'Reino Unido',
						'US': 'Estados Unidos',
						'UM': 'Islas menores alejadas de los Estados Unidos',
						'UY': 'Uruguay',
						'UZ': 'Uzbekistán',
						'VU': 'Vanuatu',
						'VE': 'Venezuela',
						'VN': 'Vietnam',
						'VG': 'Islas Vírgenes Británicas',
						'VI': 'Islas Vírgenes, EE. UU.',
						'WF': 'Wallis y Futuna',
						'EH': 'Sáhara Occidental',
						'YE': 'Yemen',
						'ZM': 'Zambia',
						'ZW': 'Zimbabue'
					},
			  		wait: false,
			  		menu: false,
			  		sync: null,
			  		crop: [],
			  		bill: [],
			  		bank: [],
					more: [],
					list: [],
			  		load: 0,
			  		cost: 0,
					data: {
						date: [{!!json_encode(date('Y-m-d', strtotime('monday this week', time())))!!}, {!!json_encode(date('Y-m-d', strtotime('sunday this week', time())))!!}],
						bond: {
							size: null,
							load: null
						},
						works: null,
						units: null
					},
					form: {
						dump: {
							show: false,
							menu: false,
							wait: false,
							type: null,
							fail: {
								text: null,
								data: {}
							},
							data: {
								name: null,
								date: null
							}
						}
					}
			    },
			    watch: {
					'data.stay.mode': function (mode) {
						this.show('stay', this.data.stay);
					},
					'data.stay.view': function (view) {
						this.show('stay', this.data.stay);
					},
					'data.book.mode': function (mode) {
						this.show('book', this.data.book);
					},
					'data.book.view': function (view) {
						this.show('book', this.data.book);
					},
					'data.pack.mode': function (mode) {
						this.show('pack', this.data.pack);
					},
					'data.pack.view': function (view) {
						this.show('pack', this.data.pack);
					}
			    },
			    methods: {
			    	pull: function (from, stop, busy, done) {
			    		var data = new FormData();

		    		    data.append('from', (from || ''));

						data.append('stop', (stop || ''));

			    		axios.post("{{route('dash', ['task' => 'load'])}}", data, {'X-CSRF-TOKEN': '{{csrf_token()}}'})
				             .then(function (data) {
							self.data.works = data.data.works ?? 0;

							self.data.units = data.data.units ?? 0;

				            setTimeout(function () {
				            	self.busy = false;
				            }, 300);

							if (done) {
				            	done(false);
				            }
				        }).catch(function (fail) {console.log(fail)
				            setTimeout(function () {
				            	self.busy = false;

				            	self.note = {show: true,
                            	             type: 'fail',
                            	             text: 'Se presentó un error inesperado.'};
				            }, 300);

							if (done) {
				            	done(true);
				            }
				        });

				        this.busy = busy;
			    	},
					open: function (task, item) {
						if (item) {

						} else {
							switch (task) {
								case 'dump':
									self.form.dump = {
										menu: false,
										wait: false,
										show: true,
										type: 1,
										fail: {
											text: null,
											data: {}
										},
										data: {
											name: null,
											date: []
										}
									}
									break;
							}
						}
					},
					show: function (type, item) {
						if (self.same(self.none(item.data), false)) {
							switch (type) {
								case 'stay':
									switch (item.view) {
										case 0:
											var size = this.list.reduce(function (hold, item) {
												return hold + ((item.type == 1) ? 1 : item.size);
											}, 0);

											item.show = {
												labels: ['1',
														'2',
														'3',
														'4',
														'5',
														'6',
														'7',
														'8',
														'9',
														'10',
														'11',
														'12',
														'13',
														'14',
														'15',
														'16',
														'17',
														'18',
														'19',
														'20',
														'21',
														'22',
														'23',
														'24',
														'25',
														'26',
														'27',
														'28',
														'29',
														'30',
														'31'],
												datasets: [{
													label: 'Ocupación',
													data: this.loop(1, 31, function (seek, list) {
														if (self.none(item.data[1][seek])) {
															list.push(0);
														} else {
															list.push(((item.data[1][seek].load * 100) / size).toFixed(2));
														}
													}, []),
													borderColor: '#1E88E5',
													backgroundColor: '#1E88E5'
												}]
											};
											break;
										case 1:
											var size = this.list.reduce(function (hold, item) {
												return hold + ((item.type == 1) ? 1 : item.size);
											}, 0) * 7;

											item.show = {
												labels: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
												datasets: [{
													label: 'Ocupación',
													data: this.loop(1, 12, function (seek, list) {
														if (self.none(item.data[2][seek])) {
															list.push(0);
														} else {
															list.push(((item.data[2][seek].load * 100) / size).toFixed(2));
														}
													}, []),
													borderColor: '#1E88E5',
													backgroundColor: '#1E88E5'
												}]
											};
											break;
										case 2:
											var size = this.list.reduce(function (hold, item) {
												return hold + ((item.type == 1) ? 1 : item.size);
											}, 0) * 30;

											item.show = {
												labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
												datasets: [{
													label: 'Ocupación',
													data: this.loop(1, 12, function (seek, list) {
														if (self.none(item.data[0][seek])) {
															list.push(0);
														} else {
															list.push(((item.data[0][seek].load * 100) / size).toFixed(2));
														}
													}, []),
													borderColor: '#1E88E5',
													backgroundColor: '#1E88E5'
												}]
											};
											break;
									}

									switch (item.mode) {
										case 0:
											item.type = 'line';
											break;
										case 1:
											item.type = 'bar';
											break;
									}
									break;
								case 'book':
									switch (item.view) {
										case 0:
											item.show = {
												labels: ['00',
														'01',
														'02',
														'03',
														'04',
														'05',
														'06',
														'07',
														'08',
														'09',
														'10',
														'11',
														'12',
														'13',
														'14',
														'15',
														'16',
														'17',
														'18',
														'19',
														'20',
														'21',
														'22',
														'23'],
												datasets: [{
													label: 'Confirmadas',
													data: item.data[0][1],
													fill: false,
													borderColor: '#2E7D32',
													backgroundColor: '#2E7D32'
												},
												{
													label: 'Decliandas',
													data: item.data[0][2],
													fill: false,
													borderColor: '#EF6B00',
													backgroundColor: '#EF6B00'
												},
												{
													label: 'Canceladas',
													data: item.data[0][3],
													fill: false,
													borderColor: '#BF360B',
													backgroundColor: '#BF360B'
												}]
											};
											break;
										case 1:
											item.show = {
												labels: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
												datasets: [{
													label: 'Confirmadas',
													data: item.data[1][1],
													fill: false,
													borderColor: '#2E7D32',
													backgroundColor: '#2E7D32'
												},
												{
													label: 'Decliandas',
													data: item.data[1][2],
													fill: false,
													borderColor: '#EF6B00',
													backgroundColor: '#EF6B00'
												},
												{
													label: 'Canceladas',
													data: item.data[1][3],
													fill: false,
													borderColor: '#BF360B',
													backgroundColor: '#BF360B'
												}]
											};
											break;
										case 2:
											item.show = {
												labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
												datasets: [{
													label: 'Confirmadas',
													data: item.data[2][1],
													borderColor: '#2E7D32',
													backgroundColor: '#2E7D32'
												},
												{
													label: 'Decliandas',
													data: item.data[2][2],
													borderColor: '#EF6B00',
													backgroundColor: '#EF6B00'
												},
												{
													label: 'Canceladas',
													data: item.data[2][3],
													borderColor: '#BF360B',
													backgroundColor: '#BF360B'
												}]
											};
											break;
									}

									switch (item.mode) {
										case 0:
											item.type = 'line';
											break;
										case 1:
											item.type = 'bar';
											break;
									}
									break;
								case 'pack':
									switch (item.view) {
										case 0:
											item.show = {
												labels: ['00',
														'01',
														'02',
														'03',
														'04',
														'05',
														'06',
														'07',
														'08',
														'09',
														'10',
														'11',
														'12',
														'13',
														'14',
														'15',
														'16',
														'17',
														'18',
														'19',
														'20',
														'21',
														'22',
														'23'],
												datasets: this.walk(this.list, function (pack, next, list) {
													if ((item.data[0][pack.item] && item.data[0][pack.item].reduce(function (data, item) {
														return data + item;
													}, 0))) {
														list.push({
															label: pack.name,
															data: item.data[0][pack.item],
															borderColor: `#${pack.tone}`,
															backgroundColor: `#${pack.tone}`
														});
													}
												}, [])
											};
											break;
										case 1:
											item.show = {
												labels: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
												datasets: this.walk(this.list, function (pack, next, list) {
													if ((item.data[1][pack.item] && item.data[1][pack.item].reduce(function (data, item) {
														return data + item;
													}, 0))) {
														list.push({
															label: pack.name,
															data: item.data[1][pack.item],
															borderColor: `#${pack.tone}`,
															backgroundColor: `#${pack.tone}`
														});
													}
												}, [])
											};
											break;
										case 2:
											item.show = {
												labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
												datasets: this.walk(this.list, function (pack, next, list) {
													if ((item.data[2][pack.item] && item.data[2][pack.item].reduce(function (data, item) {
														return data + item;
													}, 0))) {
														list.push({
															label: pack.name,
															data: item.data[2][pack.item],
															borderColor: `#${pack.tone}`,
															backgroundColor: `#${pack.tone}`
														});
													}
												}, [])
											};
											break;
									}

									switch (item.mode) {
										case 0:
											item.type = 'line';
											break;
										case 1:
											item.type = 'bar';
											break;
									}
									break;
								case 'male':
									switch (item.mode) {
										case 0:
											item.type = 'bar';

											item.show = {
												labels: ['Hombres', 'Mujeres', 'Otros'],
												datasets: [{
													data: [item.data[1], item.data[2], item.data[0]],
													backgroundColor: ['#42B5F5', '#D81B60', '#78909B']
												}]
											};
											break;
										case 1:
											item.type = 'pie';

											item.show = {
												labels: ['Hombres', 'Mujeres', 'Otros'],
												datasets: [{
													data: [item.data[1], item.data[2], item.data[0]],
													backgroundColor: ['#42B5F5', '#D81B60', '#78909B']
												}]
											};
											break;
									}
									break;
								case 'plat':
									item.show = {
										labels: item.data.map(function (d) {
											return self.land[d.properties['Alpha-2']] || d.properties.name;
										}),
										datasets: [{
											label: 'Países',
											outline: item.data,
											data: item.data.map(function (d) {
												var item = self.data.most.from.list.find(function (item) {
													return item.code == d.properties['Alpha-2'];
												});

												if (item) {
													return {feature: d, value: item.load};
												} else {
													return {feature: d, value: 0};
												}
											}),
										}]
									};
									break;
							}
						}
					},
					dump: function (type, form, busy) {
						var data = new FormData();

						self.form.dump.fail = {
							text: null,
							data: {}
						}

						data.append('type', (type || 1));

						data.append('from', (form.date[0] || ''));

						data.append('stop', (form.date[1] || ''));

						axios.post("{{route('dash', ['task' => 'dump'])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}, responseType: 'blob'})
							 .then(function (data) {
							var link = document.createElement('a');

                            link.setAttribute('download', `${((form.name || '').trim() ? form.name.trim() : moment().format('YYYYMMDDHHmmss'))}.xlsx`);

                            link.href = window.URL.createObjectURL(new Blob([data.data]));

                            document.body.appendChild(link);

					        link.click();

					        link.remove();

							setTimeout(function () {
				            	self.busy = false;
				            }, 300);
						})
						.catch(function (fail) {
							self.form.dump.wait = false;

							if (fail.response.data) {
								var reader = new FileReader();

								reader.addEventListener('loadend', function (event) {
									var data = JSON.parse(event.srcElement.result);

									setTimeout(function () {
										self.busy = false;

										self.note = {show: true,
													type: 'fail',
													text: data.text};
									}, 300);
								});

								reader.readAsText(fail.response.data);
							} else {
								setTimeout(function () {
									self.busy = false;

									self.note = {show: true,
												type: 'fail',
												text: 'Se presentó un error inesperado.'};
								}, 300);
							}
						});

						this.busy = busy;
					}
			    },
			    mounted: function () {
					this.pull(this.data.date[0], this.data.date[1], false, function (fail) {
						setTimeout(function () {
							self.done = true;
					
						}, 300);
					});
				}
			})
		  });

  	</script>
@stop