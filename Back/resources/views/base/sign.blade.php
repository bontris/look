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
                        <label class="nftmax-wc__form-label">Email</label>
                        <div class="form-group__input">
                            <span class="nftmax-wc__icon"><svg class="inline" xmlns="http://www.w3.org/2000/svg" width="22" height="20" viewBox="0 0 22 20" fill="none"><path d="M21.7764 4.12903L14.1237 11.7818C13.2704 12.6329 12.1143 13.1109 10.9091 13.1109C9.7039 13.1109 8.54787 12.6329 7.69457 11.7818L0.0418183 4.12903C0.029091 4.27267 0 4.40267 0 4.54539V15.4545C0.00144351 16.6596 0.480803 17.8149 1.33293 18.6671C2.18506 19.5192 3.34038 19.9985 4.54547 20H17.2728C18.4779 19.9985 19.6332 19.5192 20.4853 18.6671C21.3374 17.8149 21.8168 16.6596 21.8182 15.4545V4.54539C21.8182 4.40267 21.7892 4.27267 21.7764 4.12903Z" fill="#374557" fill-opacity="0.6"></path><path d="M12.8389 10.4964L21.1425 2.19182C20.7403 1.52484 20.1729 0.972764 19.4952 0.588847C18.8175 0.204931 18.0523 0.00212789 17.2734 0H4.5461C3.76721 0.00212789 3.00201 0.204931 2.3243 0.588847C1.6466 0.972764 1.07926 1.52484 0.677002 2.19182L8.98066 10.4964C9.493 11.0067 10.1867 11.2932 10.9098 11.2932C11.6329 11.2932 12.3265 11.0067 12.8389 10.4964Z" fill="#374557" fill-opacity="0.6"></path></svg></span>
                            <input class="nftmax-wc__form-input" v-model="form.name" type="text" name="email" placeholder="juan@tallera.co">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="nftmax-wc__form-label">Contraseña</label>
                        <div class="form-group__input">
                            <span class="nftmax-wc__icon"><svg class="inline" width="18" height="21" viewBox="0 0 18 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.4467 7.1581V5.94904C14.4467 2.66455 11.7822 0 8.49771 0C5.21323 0 2.54867 2.66455 2.54867 5.94904V7.1581C1.00076 7.83194 -0.000366211 9.36059 -0.000366211 11.0471V16.149C0.0034843 18.494 1.90178 20.3961 4.25059 20.4H12.7525C15.0975 20.3961 16.9996 18.4978 17.0035 16.149V11.051C16.9919 9.36059 15.9908 7.83579 14.4467 7.1581ZM9.34482 14.451C9.34482 14.9207 8.96362 15.3019 8.49386 15.3019C8.0241 15.3019 7.6429 14.9207 7.6429 14.451V12.749C7.6429 12.2793 8.0241 11.8981 8.49386 11.8981C8.96362 11.8981 9.34482 12.2793 9.34482 12.749V14.451ZM12.7448 6.8H4.24289V5.94904C4.24289 3.60023 6.14505 1.69807 8.49386 1.69807C10.8427 1.69807 12.7448 3.60023 12.7448 5.94904V6.8Z" fill="#374557" fill-opacity="0.6"></path></svg></span>
                            <input class="nftmax-wc__form-input" v-model="form.pass" type="password" name="password" placeholder="" maxlength="16" >
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="nftmax-wc__check-inline">
                            <div class="nftmax-wc__checkbox">
                                <input class="nftmax-wc__form-check" id="checkbox" name="checkbox" type="checkbox">
                                <label for="checkbox">Recordarme</label>
                            </div>
                            <div class="nftmax-wc__forgot">
                                <a href="{{route('lost')}}" class="forgot-pass">Olvidó su contraseña?</a>
                            </div>
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
						name: null,
			  			pass: null,
			  			show: null
			  		}
			    },
			    methods: {
			    	send: function (form) {
			    		var data = new FormData();

		    		    data.append('name', form.name);

		    		    data.append('pass', form.pass);

			    		axios.post("{{route('sign')}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
                             .then(function (data) {
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