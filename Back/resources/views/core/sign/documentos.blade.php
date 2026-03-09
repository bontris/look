@extends('core')

@section('page')
    <div class="col-xxl-12 col-12">
        <div class="nftmax-body">
            <!-- Dashboard Inner -->
            <div class="nftmax-dsinner" v-if="same(view, 1)">
                <div class="nftmax-inner__heading">
                    <h2 class="nftmax-inner__page-title">Listado de Firma de Documentos</h2>
                </div>
                <div class="nftmax-table mg-top-40">
                    <div class="nftmax-table__heading">
						<h3 class="nftmax-table__title mb-0">
                            Firma de Documentos <span class="nftmax-table__badge">@{{text(data.size)}}</span>
                        </h3>
                        <div class="nftmax-header__amount" @click.stop="open('post')">
							<div class="nftmax-amount__icon">
                                <img src="/assets/img/bag-icon.svg" alt="#">
                            </div>
							<div class="nftmax-amount__digit">
                                Solicitar
                            </div>
							<div class="nftmax-header__plus">
                                <i class="fa-solid fa-search"></i>
                            </div>
						</div>
                        <ul  class="nav nav-tabs  nftmax-dropdown__list" id="nav-tab" role="tablist">
                            <li class="nav-item dropdown">
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
                                        <th style="text-align: left; width: 180px">Asunto</th>
                                        <th style="text-align: left; width: 180px">Nombre</th>
                                        <th style="text-align: left; width: 180px">Correo</th>
                                        <th style="text-align: right; width: 180px">Fecha</th>
                                        <th style="text-align: right; width: 120px">&nbsp;</th>
                                    </tr>
                                </thead>
                                <!-- NFTMax Table Body -->
                                <tbody class="nftmax-table__body" v-if="Boolean(data?.list?.length)">
                                    <tr v-for="item in data.list">
                                        <td style="text-align: left">
                                            @{{item.hint}}
                                        </td>
                                        <td style="text-align: left">
                                            @{{item.name}}
                                        </td>
                                        <td style="text-align: left">
                                            @{{item.mail}}
                                        </td>
                                        <td style="text-align: right">
                                            @{{date(item.date, 'DD/MM/YYYY hh:mm:ss')}}
                                        </td>
                                        <td style="text-align: right">
                                            <a @click="open('data', item)" class="nftmax-table__action_btn" title="Reporte">
                                                <i class="fa-solid fa-search"></i>
                                            </a>
                                            <a @click="open('drop', item)" class="nftmax-table__action_btn" title="Eliminar">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody class="nftmax-table__body" v-else>
                                    <tr>
                                        <td style="text-align: center" colspan="3">
                                            No se encontraron registros disponibles.
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
                    <h2 class="nftmax-inner__page-title">@{{pick?.code}}</h2>
                </div>
                <div class="nftmax-table mg-top-40">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="table_1" role="tabpanel" aria-labelledby="table_1">
                            <!-- NFTMax Table -->
                            <table id="nftmax-table__main" class="nftmax-table__main nftmax-table__main-v1">
                                <!-- NFTMax Table Head -->
                                <thead class="nftmax-table__head">
                                    <tr>
                                        <th style="text-align: left; width: 260px">Campo</th>
                                        <th style="text-align: left">Valor</th>
                                    </tr>
                                </thead>
                                <!-- NFTMax Table Body -->
                                <tbody class="nftmax-table__body">
                                    <tr>
                                        <td style="text-align: left; vertical-align: top">
                                            Documento
                                        </td>
                                        <td style="text-align: left">
                                            @{{pick?.code ?? 'Ninguno'}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; vertical-align: top">
                                            Asunto
                                        </td>
                                        <td style="text-align: left">
                                            @{{pick?.hint ?? 'Ninguno'}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; vertical-align: top">
                                            Mensaje
                                        </td>
                                        <td style="text-align: left">
                                            @{{pick?.note ?? 'Ninguno'}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; vertical-align: top">
                                            Nombre
                                        </td>
                                        <td style="text-align: left">
                                            @{{pick?.name ?? 'Ninguno'}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; vertical-align: top">
                                            Correo
                                        </td>
                                        <td style="text-align: left">
                                            @{{pick?.mail ?? 'Ninguno'}}
                                        </td>
                                    </tr>
                                </tbody>
                                <!-- End NFTMax Table Body -->
                            </table>
                            <!-- End NFTMax Table -->
                        </div>
                    </div>
                    <div class="nftmax__item-button--group">
                        <button class="nftmax__item-button--single nftmax__item-button--cancel" data-bs-toggle="modal"  data-bs-target="#exit">Cerrar</button>
                    </div>
                </div>
            </div>
            <!-- End Dashboard Inner -->

            <div class="nftmax-preview__modal modal" id="post" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog  nftmax-close__modal-close">
                    <div class="modal-content nftmax-preview__modal-content">
                        <div class="modal-header nftmax__modal__header">
                            <h4 class="modal-title nftmax-preview__modal-title" id="CancelModalLabel">Solicitar</h4>
                        </div>
                        <div class="modal-body nftmax-modal__body modal-body nftmax-close__body">
                            <div class="nftmax-preview__close" v-if="wait">
                                <div class="nftmax-preview__close-img"><img src="/assets/img/timer.png" alt="#"></div>
								<h2 class="nftmax-preview__close-title">Enviando información...</h2>
                            </div>
                            <div class="nftmax-preview__close" v-else>
                                <div style="width: 100%; overflow: hidden">
                                    <div style="margin: 0px 0px 16px 0px">Por favor ingrese los datos para realizar la solicitud.</div>
                                    <div style="color: red; margin: 8px 0px" v-if="fail?.text">@{{fail?.text}}</div>
                                    <div class="nftmax__item-form--group">
                                        <input class="nftmax__item-input" style="width: 100%" type="text" placeholder="Nombre" v-model="form.name" required>
                                        <div style="color: red" v-if="fail?.list?.name">@{{fail?.list?.name}}</div>
                                    </div>
                                    <div class="nftmax__item-form--group">
                                        <input class="nftmax__item-input" style="width: 100%" type="text" placeholder="Correo" v-model="form.mail" required>
                                        <div style="color: red" v-if="fail?.list?.mail">@{{fail?.list?.mail}}</div>
                                    </div>
                                    <div class="nftmax__item-form--group">
                                        <input class="nftmax__item-input" style="width: 100%" type="text" placeholder="Asunto" v-model="form.hint" required>
                                        <div style="color: red" v-if="fail?.list?.hint">@{{fail?.list?.hint}}</div>
                                    </div>
                                    <div class="nftmax__item-form--group">
                                        <textarea class="nftmax__item-input nftmax__item-textarea" style="width: 100%; resize: none" placeholder="Mensaje" v-model="form.note" required></textarea>
                                        <div style="color: red" v-if="fail?.list?.note">@{{fail?.list?.note}}</div>
                                    </div>
                                    <div class="nftmax__file-upload">
                                        <div class="upload-files">
                                            <div class="body" id="drop">
                                                <img class="nftmax__file-upload--img" src="/assets/img/upload.png" alt="">
                                                <p class="pointer-none nftmax__file-text">
                                                    <b>Arrastre el documento aquí.</b>
                                                </p>
                                                <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered mt-4 bg radius">
                                                    <label for="load">Buscar documento</label>
                                                    <input
                                                        id="load"
                                                        type="file"
                                                        @change="(form.file = $event.target.files.item(0))"
                                                        accept="application/pdf" />
                                                </button>
                                                <p class="pt-2" v-if="form.file">
                                                    <b>@{{form.file?.name}}</b>
                                                </p>
                                                <p class="pt-2 mx-4">Sólo se admiten documentos en formato PDF.</p>
                                                <div style="color: red" v-if="fail?.list?.file">@{{fail?.list?.file}}</div>
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
                                <div class="nftmax__item-button--group">
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius" @click="post(form)" :disabled="wait">
                                        Solicitar
                                    </button>
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered--plus radius" :disabled="wait">
                                        <span class="ntfmax__btn-textgr" data-bs-dismiss="modal" aria-label="Cerrar">Cancelar</span> 
                                    </button>																
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nftmax-preview__modal modal fade" id="quit" tabindex="-1" aria-labelledby="CancelModalLabel" aria-hidden="true">
                <div class="modal-dialog  nftmax-close__modal-close">
                    <div class="modal-content nftmax-preview__modal-content">
                        <div class="modal-header nftmax__modal__header">
                            <h4 class="modal-title nftmax-preview__modal-title" id="CancelModalLabel">Confirmar</h4>
                        </div>
                        <div class="modal-body nftmax-modal__body modal-body nftmax-close__body">
                            <div class="nftmax-preview__close">
                                <div class="nftmax-preview__close-img"><img src="/assets/img/close.png" alt="#"></div>
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

            <div class="nftmax-preview__modal modal fade" id="exit" tabindex="-1" aria-labelledby="CancelModalLabel" aria-hidden="true">
                <div class="modal-dialog  nftmax-close__modal-close">
                    <div class="modal-content nftmax-preview__modal-content">
                        <div class="modal-header nftmax__modal__header">
                            <h4 class="modal-title nftmax-preview__modal-title" id="CancelModalLabel">Confirmar</h4>
                        </div>
                        <div class="modal-body nftmax-modal__body modal-body nftmax-close__body">
                            <div class="nftmax-preview__close">
                                <div class="nftmax-preview__close-img"><img src="/assets/img/close.png" alt="#"></div>
                                <h2 class="nftmax-preview__close-title">¿Está seguro de que quiere salir del reporte?</h2>
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

            <div class="nftmax-preview__modal modal fade" id="save" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog  nftmax-close__modal-close">
                    <div class="modal-content nftmax-preview__modal-content">
                        <div class="modal-header nftmax__modal__header">
                            <h4 class="modal-title nftmax-preview__modal-title" id="CancelModalLabel">Confirmar</h4>
                        </div>
                        <div class="modal-body nftmax-modal__body modal-body nftmax-close__body">
                            <div class="nftmax-preview__close">
                                <div class="nftmax-preview__close-img"><img src="/assets/img/close.png" alt="#"></div>
                                <h2 class="nftmax-preview__close-title">@{{(pick ? '¿Desea actualizar la marca?' : '¿Desea crear la nueva marca?')}}</h2>
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
                                <div class="nftmax-preview__close-img"><img src="/assets/img/close.png" alt="#"></div>
                                <h2 class="nftmax-preview__close-title">¿Desea eliminar el reporte de antecedentes?</h2>
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
                    quit: false,
			  		snap: null,
			  		pick: null,
			  		sync: null,
                    seek: [],
                    list: [],
					view: 1,
                    tone: {
                        5: '#E53935',
                        4: '#E65100',
                        3: '#FF8F00',
                        2: '#FDD835',
                        1: '#FFFFFF'
                    },
			  		data: {
                        size: 0,
                        page: 0,
                        take: 0,
                        list: []
                    },
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
                        name: null,
                        mail: null,
                        hint: null,
			  			note: null,
                        file: null,
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
			    	}
			    },
			    methods: {
			    	load: function (take, page, text, done) {
			    		axios.get("{{route('signs', ['type' => 'documentos', 'task' => 'load'])}}?take=" + (take ?? '') + '&page=' + (page ?? '') +
			    			                                                                                                   '&find=' + (text ?? ''), {})
				             .then(function (data) {
				            self.data.list = data.data.data || [];

				            self.data.size = data.data.size || 0;

				            self.data.page = data.data.page || 0;

				            self.data.take = data.data.take || 0;

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
			    				case 'data':
							        self.pick = item;

                                    self.view = 2;
			    					break;
                                case 'drop':
                                    $('#drop').modal('show');

                                    self.pick = item;
                                    break;
			    			}
			    		} else {
			    			switch (task) {
                                case 'post':
                                    self.form = {
                                        name: '',
                                        mail: '',
                                        hint: '',
                                        note: '',
                                        file: ''
                                    };

                                    $('#post').modal('show');
                                    break;
			    			}
			    		}
			    	},
			    	post: function (form) {
                        var data = new FormData();

                        self.fail = {text: null,
			    			         list: {}};
                        
                        data.append('name', form.name);

                        data.append('mail', form.mail);

                        data.append('hint', form.hint);

                        data.append('note', form.note);

                        data.append('file', form.file);

                        axios.post("{{route('signs', ['type' => 'documentos', 'task' => 'post'])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}', 'Content-Type': 'multipart/form-data'}})
	                             .then(function (data) {
                            $('#post').modal('hide');

                            self.data.list.unshift((self.pick = {
                                item: data.data.item,
                                hash: data.data.hash,
                                code: data.data.code,
                                name: form.name,
                                mail: form.mail,
                                hint: form.hint,
                                note: form.note
                            }));

                            self.data.size = self.data.size + 1;

                            self.wait = false;

                            self.view = 2;
                        })
                        .catch(function (fail) {
                            self.wait = false;

                            if (fail.response?.data?.text) {
                                self.fail = {text: fail.response.data.text,
                                             list: fail.response.data.list ?? {}};
                            } else {
                                self.fail.text = 'Se presentó un error inesperado.';
                            }
                        });

				    	self.wait = true;
			    	},
                    drop: function (item) {
			    		axios.get(`{{route('signs', ['type' => 'documentos', 'task' => 'drop'])}}/${item.hash}`, {})
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
                        self.load(0, 0, null, (fail) => {
                            self.done = true;
                        });
	             	}, 300);
			    }
			})
  		});
  	</script>
@stop