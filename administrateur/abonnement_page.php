<!DOCTYPE html>
<html>

<head>
    <link href="../css/fonts-googleapis.css" rel="stylesheet">
    <link href="../css/materialdesignicons.min.css" rel="stylesheet">
    <link href="../css/vuetify.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">

</head>

<body style="background-color: black">
<div id="app">
    <v-app>
        <v-main>
            <v-container>
                <v-data-table :headers="headers" :items="abonnements" item-key="name" class="elevation-1"
                              :search="search">
                    <template v-slot:top>
                        <v-text-field v-model="search" label="Search" class="mx-4"></v-text-field>
                        <v-dialog v-model="dialog" max-width="500px">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn fab small right class="mb-2" v-bind="attrs" v-on="on"
                                       @click="action='add'"
                                       color="primary">
                                    <v-icon>mdi-plus</v-icon>
                                </v-btn>
                            </template>
                            <v-card>
                                <v-card-title>
                                    <span v-if="action=='add'" class="text-h5">Ajouter un abonnement</span>
                                    <span v-if="action=='edit'" class="text-h5">Editer un abonnement</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <v-form ref="form" v-model="valid">
                                            <v-text-field v-if="action=='edit'" :disabled="action=='edit'"
                                                          :rules="requiredRules"
                                                          :counter="25" v-model="abonnement.num_abonnement"
                                                          label="num_abonnement"
                                                          required></v-text-field>
                                            <v-menu
                                                    :rules="requiredRules"
                                                    ref="datePickerMenu"
                                                    v-model="datePickerMenu"
                                                    :close-on-content-click="false"
                                                    :nudge-right="40"
                                                    transition="scale-transition"
                                                    offset-y
                                                    max-width="290px"
                                                    min-width="290px"
                                            >
                                                <template v-slot:activator="{ on }">
                                                    <v-text-field
                                                            v-model="abonnement.date"
                                                            label="Date"
                                                            persistent-hint
                                                            v-on="on"
                                                            readonly
                                                    >
                                                    </v-text-field>
                                                </template>
                                                <v-date-picker v-model="abonnement.date" no-title
                                                               @input="datePickerMenu = false"></v-date-picker>
                                            </v-menu>
                                            <v-select
                                                    required
                                                    :rules="requiredRules"
                                                    v-model="abonnement.num_client"
                                                    v-on:change="getOffres"
                                                    :items="clients"
                                                    item-text="num"
                                                    item-value="num"
                                                    label="Numéro client"
                                            ></v-select>
                                            <v-select
                                                    required
                                                    :rules="requiredRules"
                                                    v-model="abonnement.designation_offre"
                                                    :items="offres"
                                                    item-text="designation_offre"
                                                    item-value="designation_offre"
                                                    label="Offre"
                                            ></v-select>
                                        </v-form>
                                    </v-container>
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="blue darken-1" @click="dialog=false" text>
                                        Annuler
                                    </v-btn>
                                    <v-btn color="blue darken-1" :disabled="!valid" @click="save()" text>
                                        Sauvgarder
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <v-icon small color="indigo" class="mr-2" @click="editItem(item)">
                            mdi-pencil
                        </v-icon>
                        <v-icon small @click="deleteItem(item)" color="error">
                            mdi-delete
                        </v-icon>
                    </template>
                </v-data-table>


            </v-container>
            <v-bottom-navigation
                    v-model="nav"
                    absolute
                    hide-on-scroll
                    horizontal
                    color="indigo"
                    scroll-target="#scroll-threshold-example"
                    scroll-threshold="500"
            >
                <v-btn @click="this.window.location.href = 'operateur_page.php'">
                    <span>Operateur</span>
                </v-btn>
                <v-btn @click="this.window.location.href = 'client_page.php'">
                    <span>Client</span>
                </v-btn>
                <v-btn @click="this.window.location.href = 'facture_page.php'">
                    <span>Facture</span>
                </v-btn>
                <v-btn @click="this.window.location.href = 'offre_page.php'">
                    <span>Offre</span>
                </v-btn>
                <v-btn @click="this.window.location.href = 'abonnement_page.php'">
                    <span>Abonnement</span>
                </v-btn>
            </v-bottom-navigation>
        </v-main>
    </v-app>
</div>

<script src="../js/vue.js"></script>
<script src="../js/vuetify.js"></script>
<script src="../js/axios.min.js"></script>

<script>
    var app = new Vue({
        el: '#app',
        vuetify: new Vuetify({theme: {dark: true},}),
        data: {
            search: '',
            dialog: false,
            valid: false,
            datePickerMenu: false,
            nav: 4,
            abonnement: {
                num_abonnement: '',
                date: '',
                designation_offre: '',
                num_client: '',
            },
            action: '',
            abonnements: [],
            clients: [],
            offres: [],
            requiredRules: [
                v => !!v || 'Ce champs est requis',
            ],

        },
        computed: {
            headers() {
                return [
                    {
                        text: 'Numero abonnement',
                        align: 'start',
                        value: 'num_abonnement',
                    },
                    {
                        text: 'Date',
                        align: 'start',
                        value: 'date',
                    },
                    {
                        text: 'Offre',
                        align: 'start',
                        value: 'designation_offre',
                    },
                    {
                        text: 'Numero client',
                        align: 'start',
                        value: 'num_client',
                    },
                    {
                        text: 'Actions',
                        value: 'actions',
                        sortable: false
                    },

                ]
            },
        },
        mounted: function () {
            this.getData()
            this.getClients()
        },
        methods: {
            getOffres(num_client) {
                this.abonnement.designation_offre = ''
                axios.get('../api/offre/get_by_client.php?num_client=' + num_client)
                    .then(function (response) {
                        app.offres = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            getClients() {
                axios.get('../api/client')
                    .then(function (response) {
                        app.clients = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            getData() {
                axios.get('../api/abonnement')
                    .then(function (response) {
                        app.abonnements = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            editItem(item) {
                this.getOffres(item.num_client)
                this.action = 'edit';
                this.dialog = true;
                this.abonnement = {
                    num_abonnement: item.num_abonnement,
                    date: item.date,
                    designation_offre: item.designation_offre,
                    num_client: item.num_client,
                };
            },
            deleteItem(item) {
                let formData = new FormData();

                formData.append('num_abonnement', item.num_abonnement)

                axios({
                    method: 'post',
                    url: '../api/abonnement/delete.php',
                    data: formData,
                    config: {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                })
                    .then(function (response) {
                        //handle success
                        console.log(response)
                        app.getData()
                    })
                    .catch(function (response) {
                        //handle error
                        console.log(response)
                        alert("Erreur : " + response.response.data)
                    });
            },
            save() {
                let formData = new FormData();

                formData.append('num_abonnement', this.abonnement.num_abonnement)
                formData.append('date', this.abonnement.date)
                formData.append('designation_offre', this.abonnement.designation_offre)
                formData.append('num_client', this.abonnement.num_client)

                var url = '';
                if (app.action === 'add') {
                    url = '../api/abonnement/add.php'
                } else {
                    url = '../api/abonnement/edit.php'
                }
                axios({
                    method: 'post',
                    url: url,
                    data: formData,
                    config: {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                })
                    .then(function (response) {
                        //handle success
                        console.log(response)
                        app.getData()
                        app.dialog = false

                    })
                    .catch(function (response) {
                        //handle error
                        console.log(response)
                        alert("Erreur : " + response.response.data)
                    });
            },
        },
    })
</script>
</body>

</html>
