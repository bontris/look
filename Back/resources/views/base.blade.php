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
        <link href="/styles/base.css?v=1.7" rel="stylesheet">
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
                    <v-main>
                        @yield('page')
                    </v-main>
                </v-app>
            </div>
        </div>
        <script src="/scripts/vue.js"></script>
        <script src="/scripts/i18n.min.js"></script>
        <script src="/scripts/axios.js"></script>
        <script src="/scripts/vuetify.js"></script>
        <script type="text/javascript">
            (function (time) {
                Vue.mixin({
					data: function () {
						return {
							tiny: this.$vuetify?.breakpoint?.mobile,
					  		busy: false,
					  		done: false,
							time: null
						};
					}
				});
            })(false)
        </script>
        @yield('code')
    </body>
</html>