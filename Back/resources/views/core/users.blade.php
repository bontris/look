@extends('core')

@section('page')
    <div class="col-xxl-12 col-12">
        <div class="nftmax-body">
            <!-- Dashboard Inner -->
            <div class="nftmax-dsinner">
                <div class="nftmax-table mg-top-40">
                    <div class="nftmax-table__heading">
                        <h3 class="nftmax-table__title mb-0">Usuarios <span class="nftmax-table__badge">@{{ high }}</span></h3>
                        <ul  class="nav nav-tabs  nftmax-dropdown__list" id="nav-tab" role="tablist">
                            <li class="nav-item dropdown ">
                                <a class="nftmax-sidebar_btn nftmax-heading__tabs nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Categorías <span class="nftmax-table__arrow--icon"><svg width="13" height="6" viewBox="0 0 13 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.7" d="M12.4124 0.247421C12.3327 0.169022 12.2379 0.106794 12.1335 0.0643287C12.0291 0.0218632 11.917 0 11.8039 0C11.6908 0 11.5787 0.0218632 11.4743 0.0643287C11.3699 0.106794 11.2751 0.169022 11.1954 0.247421L7.27012 4.07837C7.19045 4.15677 7.09566 4.219 6.99122 4.26146C6.88678 4.30393 6.77476 4.32579 6.66162 4.32579C6.54848 4.32579 6.43646 4.30393 6.33202 4.26146C6.22758 4.219 6.13279 4.15677 6.05312 4.07837L2.12785 0.247421C2.04818 0.169022 1.95338 0.106794 1.84895 0.0643287C1.74451 0.0218632 1.63249 0 1.51935 0C1.40621 0 1.29419 0.0218632 1.18975 0.0643287C1.08531 0.106794 0.990517 0.169022 0.910844 0.247421C0.751218 0.404141 0.661621 0.616141 0.661621 0.837119C0.661621 1.0581 0.751218 1.2701 0.910844 1.42682L4.84468 5.26613C5.32677 5.73605 5.98027 6 6.66162 6C7.34297 6 7.99647 5.73605 8.47856 5.26613L12.4124 1.42682C12.572 1.2701 12.6616 1.0581 12.6616 0.837119C12.6616 0.616141 12.572 0.404141 12.4124 0.247421Z" fill="#374557" fill-opacity="0.6"></path></svg></span></a>
                                <ul class="dropdown-menu nftmax-sidebar_dropdown">
                                    <a class="dropdown-item list-group-item" data-bs-toggle="tab" data-bs-target="#table_1" role="tab">Categoría 1</a>
                                    <a class="dropdown-item list-group-item" data-bs-toggle="tab" data-bs-target="#table_2" role="tab">Categoría 2</a>
                                    <a class="dropdown-item list-group-item"  data-bs-toggle="tab" data-bs-target="#table_3" role="tab">Categoría 3</a>
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
                                        <th class="nftmax-table__column-1 nftmax-table__h1">Usuario</th>
                                        <th class="nftmax-table__column-5 nftmax-table__h5">Correo</th>
                                        <th class="nftmax-table__column-6 nftmax-table__h6">Último acceso</th>
                                        <th class="nftmax-table__column-7 nftmax-table__h7">Status</th>
                                    </tr>
                                </thead>
                                <!-- NFTMax Table Body -->
                                <tbody class="nftmax-table__body">
                                    <tr v-for="item in list">
                                        <td class="nftmax-table__column-1 nftmax-table__data-1">
                                            <div class="nftmax-table__product">
                                                <div class="nftmax-table__product-img">
                                                    <img src="/assets/img/nft-table-img1.png" alt="#">
                                                </div>
                                                <div class="nftmax-table__product-content">
                                                    <h4 class="nftmax-table__product-title">@{{ item.name }} @{{ item.last }}</h4>
                                                    <p class="nftmax-table__product-desc">Código  <a href="#">@{{ item.code }}</a></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="nftmax-table__column-5 nftmax-table__data-5">
                                            <p class="nftmax-table__text nftmax-table__bid-text">@{{ item.mail }}</p>
                                        </td>
                                        <td class="nftmax-table__column-6 nftmax-table__data-6">
                                            <p class="nftmax-table__text nftmax-table__time">@{{(item.seen ? date(item.seen, 'DD/MM/YYYY HH:mm') : 'Nunca')}}</p>
                                        </td>
                                        <td class="nftmax-table__column-7 nftmax-table__data-7">
                                            <div class="nftmax-table__status" :class="item.lock ? 'nftmax-sbcolor' : 'nftmax-gbcolor'">@{{ item.lock ? 'Bloqueado' : 'Activo' }}</div>
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
			  		list: [],
					pile: {
						role: {
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
			  			lock: null,
			  			test: null,
			  			type: null,
			  			code: null,
			  			nick: null,
			  			name: null,
			  			last: null,
			  			cell: null,
			  			mail: null,
			  			pass: null,
			  			note: null,
			  			show: null
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
			    		axios.get("{{route('users', ['task' => 'load'])}}?take=" + (take || '') + '&page=' + (page || '') +
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
			    				case 'edit':
			    				    axios.get(`{{route('users', ['task' => 'load'])}}/${item.hash}`, {})
							             .then(function (data) {
							            self.form = {lock: data.data.lock,
							            	         test: data.data.test,
													 role: data.data.role,
					             			         code: data.data.code,
					             			         nick: data.data.nick,
					             			         last: data.data.last,
			             			    	         name: data.data.name,
			             			    	         cell: data.data.cell,
			             			    	         mail: data.data.mail,
			             			    	         note: data.data.note,
			             			    	         show: false,
			             			    	         pass: null};

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
			                            	self.note = {show: true,
			                            	             type: 'fail',
			                            	             text: fail.response.data.text};
			                            } else {
			                            	self.note = {show: true,
			                            	             type: 'fail',
			                            	             text: 'Se presentó un error inesperado.'};
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
			    					self.form = {lock: 0,
			    						         test: 0,
			    						         role: 0,
			    						         code: null,
									  			 nick: null,
									  			 pass: null,
									  			 last: null,
									  			 name: null,
									  			 cell: null,
									  			 mail: null,
									  			 note: null,
									  			 show: false};

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

		    		    data.append('lock', (form.lock ? 1 : 0));

		    		    data.append('test', (form.test ? 1 : 0));

		    		    data.append('type', (form.type || 0));

		    		    data.append('code', (form.code || ''));

		    		    data.append('nick', (form.nick || ''));

		    		    data.append('pass', (form.pass || ''));

		    		    data.append('last', (form.last || ''));

		    		    data.append('name', (form.name || ''));

		    	        data.append('cell', (form.cell || ''));

		    	        data.append('mail', (form.mail || ''));

		    	        data.append('note', (form.note || ''));

		    	        if (item) {
				    		axios.post("{{route('users', ['task' => 'save'])}}/" + item.hash, data, {'X-CSRF-TOKEN': '{{csrf_token()}}'})
	                             .then(function (data) {
								self.view = 1;

	                            self.wait = false;
								
	                            self.note = {show: true,
                                	         type: 'done',
                                	         text: data.data.text};

                                item.type = form.type;

                                item.code = form.code;

                                item.nick = form.nick;

                                item.last = form.last;

                                item.name = form.name;

				    	        item.cell = form.cell;

				    	        item.mail = form.mail;

				    	        item.note = form.note;
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
				    		axios.post("{{route('users', ['task' => 'make'])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
	                             .then(function (data) {
	                            self.list.unshift((item = {item: data.data.item,
                                	                       hash: data.data.hash,
                                	                       code: data.data.code,
                                	                       tone: data.data.tone,
                                	                       face: data.data.face,
                            		                       lock: form.lock,
                            		                       type: form.type,
                            		                       nick: form.nick,
                            		                       last: form.last,
                            		                       name: form.name,
                            		                       mail: form.mail,
                            		                       seen: null}));

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
			    	face: function (item, snap) {
			    		var data = new FormData();

						if (snap) {
							data.append('file', self.blob(snap.getCroppedCanvas({width: 640, height: 640})));
						}

			    		axios.post("{{route('users', ['task' => 'face'])}}/" + item.hash, data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
                             .then(function (data) {
                            item.face = data.data.file;

                            self.note = {show: true,
                            	         type: 'done',
                            	         text: data.data.text};
                            
                            self.show.crop = false;

                            self.show.wipe = false;

                            self.wait = false;
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

                        self.wait = true;
			    	},
			    	lock: function (item, wait) {
			    		axios.get("{{route('users')}}/" + (wait ? 'wait' : 'lock') + '/' + item.hash, {})
				             .then(function (data) {
				            self.wait = false;

				            self.show.lock = false;

				            self.show.wait = false;

				            item.wait = wait ? null :
				                               item.wait;

			             	item.lock = wait ? item.lock :
			             	                   data.data.lock;

		             		self.note = {show: true,
                            	         type: 'done',
                            	         text: data.data.text};
				        }).catch(function (fail) {
				            self.wait = false;

				            self.show.lock = false;

				            self.show.wait = false;

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
			    		axios.get("{{route('users', ['task' => 'drop'])}}/" + item.hash, {})
				             .then(function (data) {
				            self.wait = false;

				            self.show.drop = false;

			             	self.list.splice(self.list.indexOf(item), 1);

		             		self.note = {show: true,
                            	         type: 'done',
                            	         text: data.data.text};
				        }).catch(function (fail) {
				            self.wait = false;

				            self.show.drop = false;

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
			    	boot: function(data) {
						self.snap = data;

						self.snap.replace(self.file, false);
					}
			    },
			    mounted: function () {
			    	this.load(this.take, this.page, this.sort.text, this.sort.type, function (fail) {
	             		setTimeout(function () {
	             			self.done = true;
	             		}, 500);
	             	});
			    }
			})
  		});
  	</script>
@stop