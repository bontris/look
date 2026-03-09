@extends('base')

@section('page')
    <v-layout justify-center>
        <v-card width="460"
                class="px-2"
                outlined>
            <v-card-text>
                <v-row>
                    <v-col>
                        <v-img class="my-4" src="/images/dark.png" height="64" contain/>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-list-item class="px-0">
                            <v-list-item-content>
                                <v-list-item-title class="headline">
                                    Registro completado
                                </v-list-item-title>
                                <v-list-item-subtitle>Gracias por hacer parte de nuestra plataforma, tu registro fue completado exitosamente.</v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>
                    </v-col>
                </v-row>
                <v-row dense>
                    <v-col>
                        <v-btn
                            href="{{route('sign')}}"
                            color="primary"
                            large
                            block>
                            Continuar
                        </v-btn>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
    </v-layout>
@stop

@section('code')
    <script type="text/javascript">
        $(document).ready(function () {
            var self = new Vue({
                vuetify: new Vuetify(),
                el: '#body',
                data: {
                    done: false
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