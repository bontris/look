@extends('core')

@section('page')
    <div class="col-xxl-12 col-12">
        <div class="nftmax-body">
            <!-- Dashboard Inner -->
            <div class="nftmax-dsinner" v-if="same(view, 1)">
                <div class="nftmax-inner__heading">
                    <h2 class="nftmax-inner__page-title">Listado de solicitudes</h2>
                </div>
                <div class="nftmax-table mg-top-40">
                    <div class="nftmax-table__heading">
						<h3 class="nftmax-table__title mb-0">
                            Solicitudes <span class="nftmax-table__badge">@{{text(data.size)}}</span>
                        </h3>
						<div class="nftmax-header__amount">
							<div class="nftmax-amount__icon">
                                <img src="assets/img/bag-icon.svg" alt="#">
                            </div>
							<div class="nftmax-amount__digit">
                                Cargar gaceta
                            </div>
							<div class="nftmax-header__plus">
                                <label style="margin: 0px 4px 0px 0px; cursor: pointer">
                                    <img src="assets/img/plus-icon.svg" alt="#">
                                    <input
                                        id="load"
                                        type="file"
                                        @change="open('bulk', $event.target)"
                                        accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                        hidden />
                                </lable>
                            </div>
						</div>
                        <ul  class="nav nav-tabs  nftmax-dropdown__list" id="nav-tab" role="tablist">
                            <li class="nav-item dropdown ">
                                <a class="nftmax-sidebar_btn nftmax-heading__tabs nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Tipo <span class="nftmax-table__arrow--icon"><svg width="13" height="6" viewBox="0 0 13 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.7" d="M12.4124 0.247421C12.3327 0.169022 12.2379 0.106794 12.1335 0.0643287C12.0291 0.0218632 11.917 0 11.8039 0C11.6908 0 11.5787 0.0218632 11.4743 0.0643287C11.3699 0.106794 11.2751 0.169022 11.1954 0.247421L7.27012 4.07837C7.19045 4.15677 7.09566 4.219 6.99122 4.26146C6.88678 4.30393 6.77476 4.32579 6.66162 4.32579C6.54848 4.32579 6.43646 4.30393 6.33202 4.26146C6.22758 4.219 6.13279 4.15677 6.05312 4.07837L2.12785 0.247421C2.04818 0.169022 1.95338 0.106794 1.84895 0.0643287C1.74451 0.0218632 1.63249 0 1.51935 0C1.40621 0 1.29419 0.0218632 1.18975 0.0643287C1.08531 0.106794 0.990517 0.169022 0.910844 0.247421C0.751218 0.404141 0.661621 0.616141 0.661621 0.837119C0.661621 1.0581 0.751218 1.2701 0.910844 1.42682L4.84468 5.26613C5.32677 5.73605 5.98027 6 6.66162 6C7.34297 6 7.99647 5.73605 8.47856 5.26613L12.4124 1.42682C12.572 1.2701 12.6616 1.0581 12.6616 0.837119C12.6616 0.616141 12.572 0.404141 12.4124 0.247421Z" fill="#374557" fill-opacity="0.6"></path></svg></span></a>
                                <ul class="dropdown-menu nftmax-sidebar_dropdown">
                                    <a class="dropdown-item list-group-item" data-bs-toggle="tab" data-bs-target="#table_1" role="tab">Pública</a>
									<a class="dropdown-item list-group-item" data-bs-toggle="tab" data-bs-target="#table_2" role="tab">Privada</a>
									<a class="dropdown-item list-group-item"  data-bs-toggle="tab" data-bs-target="#table_3" role="tab">Mixta</a>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="table_1" role="tabpanel" aria-labelledby="table_1">
                            <!-- NFTMax Table -->
                            <table id="nftmax-table__main" class="nftmax-table__main nftmax-table__main-v1">
                                <!-- NFTMax Table Head -->
                                <thead class="nftmax-table__head">
                                    <tr>
                                        <th class="nftmax-table__column-1 nftmax-table__h1">Nombre</th>
                                        <th class="nftmax-table__column-6 nftmax-table__h6">Naturaleza</th>
                                        <th class="nftmax-table__column-7 nftmax-table__h7">Estado</th>
                                        <th width="60">&nbsp;</th>
                                    </tr>
                                </thead>
                                <!-- NFTMax Table Body -->
                                <tbody class="nftmax-table__body">
                                    <tr v-for="item in data.list">
                                        <td class="nftmax-table__column-1 nftmax-table__data-1">
                                            <div class="nftmax-table__product">
                                                <div class="nftmax-table__product-img">
                                                    <img :src="(item.icon ?? '/assets/img/person.png')" alt="#">
                                                </div>
                                                <div class="nftmax-table__product-content">
                                                    <h4 class="nftmax-table__product-title">
                                                        @{{item.text}}
                                                    </h4>
                                                    <p class="nftmax-table__product-desc">
                                                        Expediente <a href="#">@{{item.card ?? item.code}}</a>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="nftmax-table__column-7 nftmax-table__data-7">
                                            <div class="nftmax-table__status" :class="{1: 'nftmax-gbcolor', 2: 'nftmax-rbcolor', 3: 'nftmax-bbcolor'}[item.type]">@{{{1: 'Mixta', 2: 'Nominativa', 3: 'Figurativa', 4: '3D', 5: 'Sonido', 6: 'Tridimensional mixta'}[item.type]}}</div>
                                        </td>
                                        <td class="nftmax-table__column-7 nftmax-table__data-7">
                                            <div class="nftmax-table__status px-3" :class="{1: 'nftmax-gbcolor', 2: 'nftmax-rbcolor'}[item.rank]">@{{{1: 'Publicada'}[item.rank]}}</div>
                                        </td>
                                        <td>
                                            <a @click="open('drop', item)" class="nftmax-table__action_btn">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                                <!-- End NFTMax Table Body -->
                            </table>
                            <!-- End NFTMax Table -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="nftmax-dsinner" v-if="same(view, 2)">
                <div class="nftmax-inner__heading">
                    <h2 class="nftmax-inner__page-title">@{{(pick ? 'Actualizar solicitud' : 'Crear nueva solicitud')}}</h2>
                </div>
                <div class="nftmax__item">
                    <div class="nftmax__item-heading">
                        <h2 class="nftmax__item-title nftmax__item-title--psingle">Formularo</h2>
                        <p class="nftmax__item-text nftmax__item-text--single">Detalles de la compañía.</p>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="nftmax__item-box">
                                <div class="row nftmax-pcolumn">
                                    <div class="col-xxl-5 col-lg-5 col-12 nftmax-pcolumn__one">
                                        <div class="nftmax__file-top">
                                            <div class="nftmax__file-upload mb-4">
                                                <div class="upload-files">
                                                    <div class="body" id="file">
                                                        <img class="nftmax__file-upload--img" src="assets/img/upload.png" alt="">
                                                        <p class="pointer-none nftmax__file-text">
                                                            <b>Si tu solicitud implica la revisión de documentos arrastralos aquí.</b>
                                                        </p>
                                                        <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered mt-4 bg radius">
                                                            <label for="load">Buscar documentos</label>
                                                        </button>
                                                        <p class="mx-4">Sólo se admiten imágenes o documentos de tipo Excel, Word o PDF.</p>
                                                    </div>
                                                    <div class="nftmax__file-updated">
                                                        <div class="divider">
                                                            <span><a>FILES</a></span>
                                                        </div>
                                                        <div class="list-files"></div>
                                                        <button class="importar">UPDATE FILES</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-7 col-lg-7 col-12 nftmax-pcolumn__two">
                                        <div class="nftmax__item-form--main">
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Tipo</label>
                                                <select class="nftmax__item-select" v-model="form.type" required="required">
                                                    <option value="0">Seleccione una opción</option>
                                                    <option value="1">Pública</option>
                                                    <option value="2">Privada</option>
                                                    <option value="3">Mixta</option>
                                                </select>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Número</label>
                                                <input class="nftmax__item-input" type="text" placeholder="Número de identificación tributaria" required="required" v-model="form.card">
                                                <div style="color: red" v-if="fail?.list?.card">@{{fail?.list?.card}}</div>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Nombre</label>
                                                <input class="nftmax__item-input" type="text" placeholder="Razón social de la compañía" required="required" v-model="form.name">
                                                <div style="color: red" v-if="fail?.list?.name">@{{fail?.list?.name}}</div>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Correo</label>
                                                <input class="nftmax__item-input" type="text" placeholder="Correo electrónico" required="required" v-model="form.mail">
                                                <div style="color: red" v-if="fail?.list?.mail">@{{fail?.list?.mail}}</div>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Teléfono</label>
                                                <input class="nftmax__item-input" type="text" placeholder="Número telefónico" required="required" v-model="form.work">
                                                <div style="color: red" v-if="fail?.list?.work">@{{fail?.list?.work}}</div>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Página</label>
                                                <input class="nftmax__item-input" type="text" placeholder="Página web" required="required" v-model="form.page">
                                                <div style="color: red" v-if="fail?.list?.page">@{{fail?.list?.page}}</div>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Comentarios</label>
                                                <textarea class="nftmax__item-input nftmax__item-textarea" placeholder="Comentarios sobre la compañía" required="required" v-model="form.note"></textarea>
                                                <div style="color: red" v-if="fail?.list?.note">@{{fail?.list?.note}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="nftmax__item-button--group">
                                <button class="nftmax__item-button--single nftmax__item-button--cancel" data-bs-toggle="modal"  data-bs-target="#quit">Cancelar</button>
                                <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius nftmax-item__btn" data-bs-toggle="modal"  data-bs-target="#save">@{{(pick ? 'Actualizar compañía' : 'Crear compañía')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <!-- End Dashboard Inner -->

            <div class="nftmax-preview__modal modal fade" id="quit" tabindex="-1" aria-labelledby="CancelModalLabel" aria-hidden="true">
                <div class="modal-dialog  nftmax-close__modal-close">
                    <div class="modal-content nftmax-preview__modal-content">
                        <div class="modal-header nftmax__modal__header">
                            <h4 class="modal-title nftmax-preview__modal-title" id="CancelModalLabel">Confirmar</h4>
                        </div>
                        <div class="modal-body nftmax-modal__body modal-body nftmax-close__body">
                            <div class="nftmax-preview__close">
                                <div class="nftmax-preview__close-img"><img src="assets/img/close.png" alt="#"></div>
                                <h2 class="nftmax-preview__close-title">¿Está seguro de que quiere salir del formulario?</h2>
                                <div class="nftmax__item-button--group">
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius"  data-bs-dismiss="modal" @click="(view = 1)">
                                        Sí, salir
                                    </button>
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered--plus radius">
                                        <span class="ntfmax__btn-textgr" data-bs-dismiss="modal" aria-label="Cerrar">Ahora no</span> 
                                    </button>																
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nftmax-preview__modal modal fade" id="bulk" tabindex="-1" aria-labelledby="CancelModalLabel" aria-hidden="true">
                <div class="modal-dialog  nftmax-close__modal-close">
                    <div class="modal-content nftmax-preview__modal-content">
                        <div class="modal-header nftmax__modal__header">
                            <h4 class="modal-title nftmax-preview__modal-title" id="CancelModalLabel">Confirmar</h4>
                        </div>
                        <div class="modal-body nftmax-modal__body modal-body nftmax-close__body">
                            <div class="nftmax-preview__close">
                                <div class="nftmax-preview__close-img"><img src="assets/img/close.png" alt="#"></div>
                                <h2 class="nftmax-preview__close-title">¿Está seguro de que quiere cargar el documento <b>@{{file?.name}}</b>?</h2>
                                <div class="nftmax__item-button--group">
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius"  data-bs-dismiss="modal" @click="send(file)">
                                        @{{(wait ? 'Cargando...' : 'Sí, cargar')}}
                                    </button>
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered--plus radius">
                                        <span class="ntfmax__btn-textgr" data-bs-dismiss="modal" aria-label="Cerrar">Ahora no</span> 
                                    </button>																
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nftmax-preview__modal modal fade" id="save" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog  nftmax-close__modal-close">
                    <div class="modal-content nftmax-preview__modal-content">
                        <div class="modal-header nftmax__modal__header">
                            <h4 class="modal-title nftmax-preview__modal-title" id="CancelModalLabel">Confirmar</h4>
                        </div>
                        <div class="modal-body nftmax-modal__body modal-body nftmax-close__body">
                            <div class="nftmax-preview__close">
                                <div class="nftmax-preview__close-img"><img src="assets/img/close.png" alt="#"></div>
                                <h2 class="nftmax-preview__close-title">@{{(pick ? '¿Desea actualizar la solicitud' : '¿Desea crear la nueva solicitud?')}}</h2>
                                <div class="nftmax__item-button--group">
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius" @click="save(form, pick)" :disabled="wait">
                                        @{{(wait ? (pick ? 'Actualizando...' : 'Creando...') : (pick ? 'Sí, actualizar' : 'Sí, crear'))}}
                                    </button>
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered--plus radius" :disabled="wait">
                                        <span class="ntfmax__btn-textgr" data-bs-dismiss="modal" aria-label="Cerrar">Ahora no</span> 
                                    </button>																
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nftmax-preview__modal modal fade" id="drop" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog  nftmax-close__modal-close">
                    <div class="modal-content nftmax-preview__modal-content">
                        <div class="modal-header nftmax__modal__header">
                            <h4 class="modal-title nftmax-preview__modal-title" id="CancelModalLabel">Confirmar</h4>
                        </div>
                        <div class="modal-body nftmax-modal__body modal-body nftmax-close__body">
                            <div class="nftmax-preview__close">
                                <div class="nftmax-preview__close-img"><img src="assets/img/close.png" alt="#"></div>
                                <h2 class="nftmax-preview__close-title">¿Desea eliminar la solicitud?</h2>
                                <div class="nftmax__item-button--group">
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius" @click="drop(pick)" :disabled="wait">
                                        @{{(wait ? 'Eliminando...' : 'Sí, eliminar')}}
                                    </button>
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered--plus radius" :disabled="wait">
                                        <span class="ntfmax__btn-textgr" data-bs-dismiss="modal" aria-label="Cerrar">Ahora no</span> 
                                    </button>																
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('code')
    <script type="text/javascript">
  		Vue.ready(function () {
  			var self = new Vue({
			  	vuetify: new Vuetify(),
				el: '#body',
			  	data: {
			  		wait: false,
			  		menu: false,
			  		snap: null,
			  		pick: null,
                    file: null,
			  		sync: null,
					view: 1,
			  		take: 32,
			  		high: 0,
			  		page: 0,
			  		data: {!!json_encode($data)!!},
					pile: {
						role: {
							wait: false,
							text: null,
							list: []
						}
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
			  		fail: {
			  			text: null,
			  		    list: {}
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
                        type: 0,
                        card: null,
                        mail: null,
                        work: null,
                        page: null,
			  			name: null,
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
			    		axios.get("{{route('firms', ['task' => 'load'])}}?take=" + (take ?? '') + '&page=' + (page ?? '') +
			    			                                                                      '&find=' + (text ?? '') +
			    			                                                                      '&type=' + (type ?? ''), {})
				             .then(function (data) {
				            self.list = data.data.data || [];

				            self.high = data.data.high || 0;

				            self.page = data.data.page || 0;

				            self.take = data.data.take || 0;

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
			    		self.note = {show: false,
	                                 type: null,
	                                 text: null};

			    		self.fail = {text: null,
			    			         list: {}};

			    		if (item) {
			    			switch (task) {
			    				case 'edit':
			    				    axios.get(`{{route('firms', ['task' => 'load'])}}/${item.hash}`, {})
							             .then(function (data) {
							            self.form = {
                                            lock: data.data.lock,
                                            type: data.data.type,
                                            code: data.data.code,
                                            card: data.data.card,
                                            work: data.data.work,
                                            mail: data.data.mail,
                                            page: data.data.page,
                                            name: data.data.name,
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
                                case 'bulk':
                                    if (item.value) {
                                        self.file = item.files.item(0);

                                        $('#bulk').modal('show');

                                        item.value = null;
                                    }
                                    break;
                                case 'drop':
                                    $('#drop').modal('show');

                                    self.pick = item;
                                    break;
			    			}
			    		} else {
			    			switch (task) {
			    				case 'make':
			    					self.form = {lock: 0,
                                                 type: 0,
                                                 mail: null,
                                                 work: null,
                                                 page: null,
                                                 card: null,
                                                 name: null,
									  			 note: null};

					             	self.pick = null;

									self.view = 2;
			    					break;
			    			}
			    		}
			    	},
			    	save: function (form, item) {
                        var data = new FormData();

                        self.fail = {text: null,
			    			         list: {}};

                        data.append('type', (form.type ?? 0));

                        data.append('mail', (form.mail ?? ''));

                        data.append('work', (form.work ?? ''));

                        data.append('page', (form.page ?? ''));

                        data.append('card', (form.card ?? ''));

		    		    data.append('name', (form.name ?? ''));

		    	        data.append('note', (form.note ?? ''));

                        if (item) {
				    		axios.post(`{{route('firms', ['task' => 'save'])}}/${item.hash}`, data, {'X-CSRF-TOKEN': '{{csrf_token()}}'})
	                             .then(function (data) {
                                $('#save').modal('hide');

                                item.type = form.type;

                                item.card = form.card;

                                item.mail = form.mail;

                                item.name = form.name;

	                            self.wait = false;

                                self.view = 1;
								
	                            self.note = {
                                    show: true,
                                	type: 'done',
                                	text: data.data.text
                                };
	                        })
	                        .catch(function (fail) {
	                            $('#save').modal('hide');
                                
                                self.wait = false;

                                if (fail.response?.data?.text) {
                                    self.fail = {text: fail.response.data.text,
                                                 list: fail.response.data.list ?? {}};
                                } else {
                                    self.fail.text = 'Se presentó un error inesperado.';
                                }
	                        });
				    	} else {
                            axios.post("{{route('firms', ['task' => 'make'])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
	                             .then(function (data) {
                                $('#save').modal('hide');

                                self.data.list.unshift({
                                    item: data.data.item,
                                    hash: data.data.hash,
                                    code: data.data.code,
                                    icon: data.data.icon,
                                    tone: data.data.tone,
                                    sort: data.data.sort,
                                    type: form.type,
                                    name: form.name,
                                    text: form.text,
                                    lead: form.lead,
                                    head: form.head
                                });

                                self.data.size = self.data.size + 1;

                                self.note = {show: true,
                                             type: 'done',
                                             text: data.data.text};

                                self.wait = false;

                                self.view = 1;
                            })
                            .catch(function (fail) {
                                $('#save').modal('hide');
                                
                                self.wait = false;

                                if (fail.response?.data?.text) {
                                    self.fail = {text: fail.response.data.text,
                                                 list: fail.response.data.list ?? {}};
                                } else {
                                    self.fail.text = 'Se presentó un error inesperado.';
                                }
                            });
                        }

				    	self.wait = true;
			    	},
                    send: function (file) {
			    		var data = new FormData();

                        self.fail = {text: null,
			    			         list: {}};

		    		    data.append('file', file);

			    		axios.post("{{route('terms', ['task' => 'bulk'])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}', 'Content-Type': 'multipart/form-data'}})
	                             .then(function (data) {console.log(data.data);
                            $('#bulk').modal('hide');

                            self.data.list.unshift(...data.data.done);

                            self.data.size = self.data.size + data.data.done.length;

                            self.note = {show: true,
                            	         type: 'done',
                            	         text: data.data.text};

                            self.wait = false;
                        })
                        .catch(function (fail) {
                            self.wait = false;

                            if (fail.response.data.text) {
                            	self.fail = {text: fail.response.data.text,
                            		         list: fail.response.data.list || {}};
                            } else {
                            	self.fail.text = '@lang('Se presentó un error inesperado.')';
                            }
	                    });

	                    self.wait = true;
			    	},
                    drop: function (item) {
			    		axios.get(`{{route('terms', ['task' => 'drop'])}}/${item.hash}`, {})
				             .then(function (data) {
                            $('#drop').modal('hide');
                            self.wait = false;

                            self.show.drop = false;

                            self.data.size = self.data.size - 1;

                            self.data.list.splice(self.data.list.indexOf(item), 1);
				        }).catch(function (fail) {
				            self.wait = false;

				            if (fail.response?.data?.text) {
                                self.fail = {text: fail.response.data.text,
                                             list: fail.response.data.list ?? {}};
                            } else {
                                self.fail.text = 'Se presentó un error inesperado.';
                            }
				        });

				        self.wait = true;
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
@stop