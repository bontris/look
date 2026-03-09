@extends('base')

@section('page')
    <div class="col-xxl-6 col-lg-6 col-12">
        <!-- Welcome Form -->
        <div class="nftmax-wc__form">
            <div class="nftmax-wc__form-inner">
                <div class="nftmax-wc__heading">
                    <h3 class="nftmax-wc__form-title nftmax-wc__form-title__one" style="background-image:url('img/heading-vector.png')"><img class="nftmax-logo__main" src="/assets/img/logo-white.png" alt="#"></h3>
                </div>
                <span style="color: red" v-if="fail.text">@{{fail.text}}</span>
                <!-- Sign in Form -->
                <form class="nftmax-wc__form-main" action="{{url('/sign')}}" method="POST" @submit.prevent="send(form)">
                    @csrf
                    <div class="form-group">
                        <label class="nftmax-wc__form-label">Contraseña</label>
                        <div class="form-group__input">
                            <span class="nftmax-wc__icon"><svg class="inline" width="18" height="21" viewBox="0 0 18 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.4467 7.1581V5.94904C14.4467 2.66455 11.7822 0 8.49771 0C5.21323 0 2.54867 2.66455 2.54867 5.94904V7.1581C1.00076 7.83194 -0.000366211 9.36059 -0.000366211 11.0471V16.149C0.0034843 18.494 1.90178 20.3961 4.25059 20.4H12.7525C15.0975 20.3961 16.9996 18.4978 17.0035 16.149V11.051C16.9919 9.36059 15.9908 7.83579 14.4467 7.1581ZM9.34482 14.451C9.34482 14.9207 8.96362 15.3019 8.49386 15.3019C8.0241 15.3019 7.6429 14.9207 7.6429 14.451V12.749C7.6429 12.2793 8.0241 11.8981 8.49386 11.8981C8.96362 11.8981 9.34482 12.2793 9.34482 12.749V14.451ZM12.7448 6.8H4.24289V5.94904C4.24289 3.60023 6.14505 1.69807 8.49386 1.69807C10.8427 1.69807 12.7448 3.60023 12.7448 5.94904V6.8Z" fill="#374557" fill-opacity="0.6"></path></svg></span>
                            <input class="nftmax-wc__form-input" v-model="form.pass" type="password" name="pass" maxlength="16" placeholder="Mínimo 8 y como máximo 12 caracteres, al menos una letra mayúscula, una letra minúscula, un número y un caracter especia.">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="nftmax-wc__form-label">Repetir contraseña</label>
                        <div class="form-group__input">
                            <span class="nftmax-wc__icon"><svg class="inline" width="18" height="21" viewBox="0 0 18 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.4467 7.1581V5.94904C14.4467 2.66455 11.7822 0 8.49771 0C5.21323 0 2.54867 2.66455 2.54867 5.94904V7.1581C1.00076 7.83194 -0.000366211 9.36059 -0.000366211 11.0471V16.149C0.0034843 18.494 1.90178 20.3961 4.25059 20.4H12.7525C15.0975 20.3961 16.9996 18.4978 17.0035 16.149V11.051C16.9919 9.36059 15.9908 7.83579 14.4467 7.1581ZM9.34482 14.451C9.34482 14.9207 8.96362 15.3019 8.49386 15.3019C8.0241 15.3019 7.6429 14.9207 7.6429 14.451V12.749C7.6429 12.2793 8.0241 11.8981 8.49386 11.8981C8.96362 11.8981 9.34482 12.2793 9.34482 12.749V14.451ZM12.7448 6.8H4.24289V5.94904C4.24289 3.60023 6.14505 1.69807 8.49386 1.69807C10.8427 1.69807 12.7448 3.60023 12.7448 5.94904V6.8Z" fill="#374557" fill-opacity="0.6"></path></svg></span>
                            <input class="nftmax-wc__form-input" v-model="form.same" type="password" name="same" maxlength="16" >
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="nftmax-wc__button">
                            <input class="ntfmax-wc__btn" type="submit">
                        </div>
                    </div>
                </form>
                <!-- End Sign in Form -->
            </div>
        </div>
        <!-- End Welcome Form -->
    </div>
@endsection

@section('code')
	<script type="text/javascript">
  		Vue.ready(function () {
  			var self = new Vue({
			  	vuetify: new Vuetify(),
				el: '#body',
			  	data: {
			  		wait: false,
			  		done: false,
			  		fail: {
			  			text: null,
			  		    list: {}
			  		},
			  		form: {
			  			pass: null,
                        same: null
			  		}
			    },
			    methods: {
			    	send: function (form) {
			    		var data = new FormData();
                        
		    		    data.append('pass', form.pass);

                        data.append('same', form.same);

			    		axios.post("{{route($name, ['hash' => $hash])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
                             .then(function (data) {console.log(data);
                            window.location.href = data.data.next;
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
@endsection