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
                <div>
                    <v-data-table :headers="headers" :items="factures" item-key="name" class="elevation-1"
                                  :search="search">
                        <template v-slot:top>
                            <v-text-field v-model="search" label="Search" class="mx-4"></v-text-field>
                            <v-spacer></v-spacer>

                            <v-dialog v-model="dialog" max-width="500px">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn fab small class="mb-2" v-bind="attrs" v-on="on"
                                           @click="action='add'"
                                           color="primary">
                                        <v-icon>mdi-plus</v-icon>
                                    </v-btn>
                                </template>
                                <v-card>
                                    <v-card-title>
                                        <span v-if="action=='add'" class="text-h5">Ajouter un facture</span>
                                        <span v-if="action=='edit'" class="text-h5">Editer un facture</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-container>
                                            <v-form ref="form" v-model="valid">
                                                <v-text-field v-if="action=='edit'" :disabled="action=='edit'"
                                                              :rules="requiredRules"
                                                              v-model="facture.num_facture"
                                                              label="num_facture"
                                                              required></v-text-field>
                                                <v-text-field :rules="requiredRules"
                                                              type="number"
                                                              v-model="facture.montant" label="montant"
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
                                                                v-model="facture.date"
                                                                label="Date"
                                                                persistent-hint
                                                                v-on="on"
                                                                readonly
                                                        >
                                                        </v-text-field>
                                                    </template>
                                                    <v-date-picker v-model="facture.date" no-title
                                                                   @input="datePickerMenu = false"></v-date-picker>
                                                </v-menu>

                                                <v-select
                                                        required
                                                        :rules="requiredRules"
                                                        v-model="facture.num_client"
                                                        v-on:change="getAbonnement"
                                                        :items="clients"
                                                        item-value="num"
                                                        item-text="num"
                                                        label="Numéro client"
                                                ></v-select>
                                                <v-select
                                                        required
                                                        :rules="requiredRules"
                                                        v-model="facture.num_abonnement"
                                                        :items="abonnements"
                                                        item-value="num_abonnement"
                                                        item-text="num_abonnement"
                                                        label="Numéro abonnement"
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
                    <v-bottom-navigation
                            v-model="nav"
                            absolute
                            hide-on-scroll
                            horizontal
                            color="indigo"
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
                </div>
            </v-container>
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
            datePickerMenu: false,
            valid: false,
            nav: 2,
            facture: {
                num_facture: '',
                montant: '',
                date: '',
                num_client: '',
                num_abonnement: '',
            },
            action: '',
            factures: [],
            clients: [],
            abonnements: [],
            requiredRules: [
                v => !!v || 'Ce champs est requis',
            ],

        },
        computed: {
            headers() {
                return [
                    {
                        text: 'Numero facture',
                        align: 'start',
                        value: 'num_facture',
                    },
                    {
                        text: 'Montant',
                        align: 'start',
                        value: 'montant',
                    },
                    {
                        text: 'Date',
                        align: 'start',
                        value: 'date',
                    },
                    {
                        text: 'Numero client',
                        align: 'start',
                        value: 'num_client',
                    },
                    {
                        text: 'Numero abonnement',
                        align: 'start',
                        value: 'num_abonnement',
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
            getAbonnement(num_client) {
                this.facture.num_abonnement = ''
                axios.get('../api/abonnement/get_by_client.php?num=' + num_client)
                    .then(function (response) {
                        console.log(response);
                        app.abonnements = response.data
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
                axios.get('../api/facture')
                    .then(function (response) {
                        app.factures = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            editItem(item) {
                this.getAbonnement(item.num_client)
                this.action = 'edit';
                this.dialog = true;
                this.facture = {
                    num_facture: item.num_facture,
                    montant: item.montant,
                    date: item.date,
                    num_client: item.num_client,
                    num_abonnement: item.num_abonnement,
                };
            },
            deleteItem(item) {
                let formData = new FormData();

                formData.append('num_facture', item.num_facture)

                axios({
                    method: 'post',
                    url: '../api/facture/delete.php',
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

                formData.append('num_facture', this.facture.num_facture)
                formData.append('montant', this.facture.montant)
                formData.append('date', this.facture.date)
                formData.append('num_client', this.facture.num_client)
                formData.append('num_abonnement', this.facture.num_abonnement)

                var url = '';
                if (app.action === 'add') {
                    url = '../api/facture/add.php'
                } else {
                    url = '../api/facture/edit.php'
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
