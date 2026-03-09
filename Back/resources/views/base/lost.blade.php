@extends('base')

@section('page')
    <div class="col-xxl-6 col-lg-6 offset-lg-0 col-md-8 offset-md-2 col-12">
        <!-- Welcome Form -->
        <div class="nftmax-wc__form">
            <div class="nftmax-wc__form-inner">
                <div class="nftmax-wc__heading">
                    <h3 class="nftmax-wc__form-title nftmax-wc__form-title__three" style="background-image:url('img/heading-vector-3.png')">Recupera tu contraseña</h3>
                </div>
                <span :style="{color: (note.type == 'done') ? 'green' : 'red'}" v-if="note.text">@{{note.text}}</span>
                <!-- Sign in Form -->
                <form class="nftmax-wc__form-main" action="{{ route('lost') }}" method="post" @submit.prevent="send(form)">
                    @csrf
                    <div class="form-group">
                        <label class="nftmax-wc__form-label">Email</label>
                        <div class="form-group__input">
                            <span class="nftmax-wc__icon"><svg class="inline" xmlns="http://www.w3.org/2000/svg" width="22" height="20" viewBox="0 0 22 20" fill="none"><path d="M21.7764 4.12903L14.1237 11.7818C13.2704 12.6329 12.1143 13.1109 10.9091 13.1109C9.7039 13.1109 8.54787 12.6329 7.69457 11.7818L0.0418183 4.12903C0.029091 4.27267 0 4.40267 0 4.54539V15.4545C0.00144351 16.6596 0.480803 17.8149 1.33293 18.6671C2.18506 19.5192 3.34038 19.9985 4.54547 20H17.2728C18.4779 19.9985 19.6332 19.5192 20.4853 18.6671C21.3374 17.8149 21.8168 16.6596 21.8182 15.4545V4.54539C21.8182 4.40267 21.7892 4.27267 21.7764 4.12903Z" fill="#374557" fill-opacity="0.6"></path><path d="M12.8389 10.4964L21.1425 2.19182C20.7403 1.52484 20.1729 0.972764 19.4952 0.588847C18.8175 0.204931 18.0523 0.00212789 17.2734 0H4.5461C3.76721 0.00212789 3.00201 0.204931 2.3243 0.588847C1.6466 0.972764 1.07926 1.52484 0.677002 2.19182L8.98066 10.4964C9.493 11.0067 10.1867 11.2932 10.9098 11.2932C11.6329 11.2932 12.3265 11.0067 12.8389 10.4964Z" fill="#374557" fill-opacity="0.6"></path></svg></span>
                            <input class="nftmax-wc__form-input" v-model="form.mail" type="email" name="email" placeholder="juan@tallera.co" required="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="nftmax-wc__button">
                            <button class="ntfmax-wc__btn" type="submit">Enviar</button>
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
			  		note: {
			  			type: null,
			  			text: null,
			  		    list: {}
			  		},
			  		form: {
			  			mail: null
			  		}
			    },
			    methods: {
			    	send: function (form) {
			    		var data = new FormData();

		    		    data.append('mail', (form.mail ?? ''));

			    		axios.post("{{route('lost')}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
                             .then(function (done) {
                            self.wait = false;

                            self.form = {mail: null};
                            
                            self.note = {list: {},
                                         type: 'done',
                            	         text: done.data.text};
                        })
                        .catch(function (fail) {
                            self.wait = false;

                            if (fail.response.data.text) {
                            	self.note = {type: 'fail',
                            	             text: fail.response.data.text,
                            		         list: fail.response.data.list || {}};
                            } else {
                            	self.note = {list: {},
                            	             type: 'fail',
                            	             text: 'Se presentó un error inesperado.'};
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