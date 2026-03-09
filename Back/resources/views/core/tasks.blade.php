@extends('core')

@section('page')
    <div class="col-xxl-12 col-12">
        <div class="nftmax-body">
            <!-- Dashboard Inner -->
            <div class="nftmax-dsinner" v-if="same(view, 1)">
                <div class="nftmax-inner__heading">
                    <h2 class="nftmax-inner__page-title">Listado de tareas</h2>
                </div>
                <div class="nftmax-table mg-top-40">
                    <div class="nftmax-table__heading">
						<h3 class="nftmax-table__title mb-0">
                            Tareas <span class="nftmax-table__badge">@{{text(data.length)}}</span>
                        </h3>
						<div class="nftmax-header__amount">
							<div class="nftmax-amount__icon">
                                <img src="assets/img/bag-icon.svg" alt="#">
                            </div>
							<div class="nftmax-amount__digit">
                                Solicitar servicio
                            </div>
							<div class="nftmax-header__plus">
                                <a @click.stop="open('make')">
                                    <img src="assets/img/plus-icon.svg" alt="#">
                                </a>
                            </div>
						</div>
                        <ul  class="nav nav-tabs  nftmax-dropdown__list" id="nav-tab" role="tablist">
                            <li class="nav-item dropdown ">
                                <a class="nftmax-sidebar_btn nftmax-heading__tabs nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Categorías <span class="nftmax-table__arrow--icon"><svg width="13" height="6" viewBox="0 0 13 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.7" d="M12.4124 0.247421C12.3327 0.169022 12.2379 0.106794 12.1335 0.0643287C12.0291 0.0218632 11.917 0 11.8039 0C11.6908 0 11.5787 0.0218632 11.4743 0.0643287C11.3699 0.106794 11.2751 0.169022 11.1954 0.247421L7.27012 4.07837C7.19045 4.15677 7.09566 4.219 6.99122 4.26146C6.88678 4.30393 6.77476 4.32579 6.66162 4.32579C6.54848 4.32579 6.43646 4.30393 6.33202 4.26146C6.22758 4.219 6.13279 4.15677 6.05312 4.07837L2.12785 0.247421C2.04818 0.169022 1.95338 0.106794 1.84895 0.0643287C1.74451 0.0218632 1.63249 0 1.51935 0C1.40621 0 1.29419 0.0218632 1.18975 0.0643287C1.08531 0.106794 0.990517 0.169022 0.910844 0.247421C0.751218 0.404141 0.661621 0.616141 0.661621 0.837119C0.661621 1.0581 0.751218 1.2701 0.910844 1.42682L4.84468 5.26613C5.32677 5.73605 5.98027 6 6.66162 6C7.34297 6 7.99647 5.73605 8.47856 5.26613L12.4124 1.42682C12.572 1.2701 12.6616 1.0581 12.6616 0.837119C12.6616 0.616141 12.572 0.404141 12.4124 0.247421Z" fill="#374557" fill-opacity="0.6"></path></svg></span></a>
                                <ul class="dropdown-menu nftmax-sidebar_dropdown">
                                    <a class="dropdown-item list-group-item" data-bs-toggle="tab" data-bs-target="#table_1" role="tab">Pago x Servicio</a>
									<a class="dropdown-item list-group-item" data-bs-toggle="tab" data-bs-target="#table_2" role="tab">Fee Mensual</a>
									<a class="dropdown-item list-group-item"  data-bs-toggle="tab" data-bs-target="#table_3" role="tab">Fee Ilimitado</a>
									<a class="dropdown-item list-group-item"  data-bs-toggle="tab" data-bs-target="#table_4" role="tab">Bolsa de Horas</a>
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
                                        <th class="nftmax-table__column-6 nftmax-table__h6">Consumo</th>
                                        <th class="nftmax-table__column-7 nftmax-table__h7">Estado</th>
                                    </tr>
                                </thead>
                                <!-- NFTMax Table Body -->
                                <tbody class="nftmax-table__body">
                                    <tr v-for="item in data">
                                        <td class="nftmax-table__column-1 nftmax-table__data-1">
                                            <div class="nftmax-table__product">
                                                <div class="nftmax-table__product-img">
                                                    <img :src="(item.lead.icon ?? '/assets/img/person.png')" alt="#">
                                                </div>
                                                <div class="nftmax-table__product-content">
                                                    <h4 class="nftmax-table__product-title">
                                                        @{{item.name}}
                                                    </h4>
                                                    <p class="nftmax-table__product-desc">
                                                    <a href="#">@{{item.deal.name}}</a>, @{{item.lead.role}} <a href="#">@{{item.lead.name}}</a>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="nftmax-table__column-6 nftmax-table__data-6">
                                            <p class="nftmax-table__text nftmax-table__time">@{{(item.time / 3600)}} hrs</p>
                                        </td>
                                        <td class="nftmax-table__column-7 nftmax-table__data-7">
                                            <div class="nftmax-table__status px-3" :class="item.lock ? 'nftmax-sbcolor' : 'nftmax-gbcolor'">@{{{2: 'Pendiente', 3: 'En curso', 4: 'Pendiente de revisión', 5: 'Completado', 6: 'Diferido'}[item.rank] ?? 'Pendiente'}}</div>
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
                    <h2 class="nftmax-inner__page-title">Crear nueva solicitud</h2>
                </div>
                <div class="nftmax__item">
                    <div class="nftmax__item-heading">
                        <h2 class="nftmax__item-title nftmax__item-title--psingle">Formularo</h2>
                        <p class="nftmax__item-text nftmax__item-text--single">Describenos en que consiste tu solicitud.</p>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="nftmax__item-box">
                                <div class="row nftmax-pcolumn">
                                    <div class="col-xxl-5 col-lg-5 col-12 nftmax-pcolumn__one">
                                        <div class="nftmax__file-top">
                                            <div class="nftmax__file-upload mb-4">
                                                <div class="upload-files">
                                                    <div class="body" id="drop">
                                                        <img class="nftmax__file-upload--img" src="assets/img/upload.png" alt="">
                                                        <p class="pointer-none nftmax__file-text">
                                                            <b>Si tu solicitud implica la revisión de documentos arrastralos aquí.</b>
                                                        </p>
                                                        <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered mt-4 bg radius">
                                                            <label for="load">Buscar documentos</label>
                                                            <input
                                                                id="load"
                                                                type="file"
                                                                @change="(form.load = $event.target.files)"
                                                                accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/pdf,image/jpeg,image/png"
                                                                multiple />
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
                                                <label class="nftmax__item-label">Nombre</label>
                                                <input class="nftmax__item-input" type="text" placeholder="Nueva solicitud" required="required" v-model="form.name">
                                                <div style="color: red" v-if="fail?.list?.name">@{{fail?.list?.name}}</div>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Description </label>
                                                <textarea class="nftmax__item-input nftmax__item-textarea" placeholder="Descripción de la solicitud" required="required" v-model="form.note"></textarea>
                                                <div style="color: red" v-if="fail?.list?.note">@{{fail?.list?.note}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="nftmax__item-button--group">
                                <button class="nftmax__item-button--single nftmax__item-button--cancel" data-bs-toggle="modal"  data-bs-target="#quit">Cancelar</button>
                                <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius nftmax-item__btn" data-bs-toggle="modal"  data-bs-target="#make">Solicitar servicio</button>
                                    
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

                                <div class="nftmax-preview__modal modal fade" id="make" tabindex="-1" aria-labelledby="Crear nuevo servicio" aria-hidden="true">
                                    <div class="modal-dialog  nftmax-close__modal-close">
                                        <div class="modal-content nftmax-preview__modal-content">
                                            <div class="modal-header nftmax__modal__header">
                                                <h4 class="modal-title nftmax-preview__modal-title" id="CancelModalLabel">Confirmar</h4>
                                            </div>
                                            <div class="modal-body nftmax-modal__body modal-body nftmax-close__body">
                                                <div class="nftmax-preview__close">
                                                    <div class="nftmax-preview__close-img"><img src="assets/img/close.png" alt="#"></div>
                                                    <h2 class="nftmax-preview__close-title">¿Desea solicitar el nuevo servicio?</h2>
                                                    <div class="nftmax__item-button--group">
                                                        <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius" @click="save(form, pick)" :disabled="wait">
                                                            @{{(wait ? 'Solicitando...' : 'Sí, solicitar')}}
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
                    </div>
                </div>
                
            </div>
            <!-- End Dashboard Inner -->
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
			  		sync: null,
					view: 1,
			  		take: 32,
			  		high: 0,
			  		page: 0,
			  		data: {!!json_encode($task)!!},
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
			  			note: null,
			  			load: []
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
			    		axios.get("{{route('tasks', ['task' => 'load'])}}?take=" + (take || '') + '&page=' + (page || '') +
			    			                                                                      '&find=' + (text || '') +
			    			                                                                      '&type=' + (type || ''), {})
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
			    				case 'find':
                                    break
			    			}
			    		} else {
			    			switch (task) {
			    				case 'make':
			    					self.form = {load: [],
                                                 name: null,
									  			 note: null};

					             	self.pick = null;

									self.view = 2;
			    					break;
			    			}
			    		}
			    	},
			    	save: function (form) {
                        var data = new FormData();

                        self.fail = {text: null,
			    			         list: {}};

		    		    data.append('name', (form.name ?? ''));

		    	        data.append('note', (form.note ?? ''));

                        self.walk(form.load, (file) => {
                            data.append('load[]', file);
                        });

		    	        axios.post("{{route('tasks', ['task' => 'make'])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
	                             .then(function (data) {
                            bootstrap.Modal.getInstance(document.querySelector('#make')).hide();

                            //self.data.list.unshift(data.data.task);

                            //self.data.size = self.data.size + 1;

                            self.note = {show: true,
                                            type: 'done',
                                            text: data.data.text};

                            self.wait = false;

                            self.view = 1;
                        })
                        .catch(function (fail) {
                            bootstrap.Modal.getInstance(document.querySelector('#make')).hide();
                            
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
			    mounted: function () {console.log('boot',bootstrap)
			    	setTimeout(function () {
	             			self.done = true;
	             	}, 500);
			    }
			})
  		});
  	</script>
@stop