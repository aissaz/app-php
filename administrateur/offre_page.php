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
                    <v-data-table :headers="headers" :items="offres" item-key="name" class="elevation-1"
                                  :search="search">
                        <template v-slot:top>
                            <v-text-field v-model="search" label="Search" class="mx-4"></v-text-field>
                            <v-spacer></v-spacer>

                            <v-dialog v-model="dialog" max-width="500px">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn fab  small class="mb-2" v-bind="attrs" v-on="on"
                                           @click="action='add'"
                                           color="primary">
                                        <v-icon>mdi-plus</v-icon>
                                    </v-btn>
                                </template>
                                <v-card>
                                    <v-card-title>
                                        <span v-if="action=='add'" class="text-h5">Ajouter une offre</span>
                                        <span v-if="action=='edit'" class="text-h5">Editer une offre</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-container>
                                            <v-form ref="form" v-model="valid">
                                                <v-text-field :disabled="action=='edit'" :rules="requiredRules"
                                                               v-model="offre.designation_offre" label="designation_offre"
                                                              required></v-text-field>
                                                <v-text-field :rules="requiredRules"
                                                              v-model="offre.appels_op" label="appels_op"
                                                              required></v-text-field>
                                                <v-text-field :rules="requiredRules"
                                                              v-model="offre.sms_op" label="sms_op"
                                                              required></v-text-field>
                                                <v-text-field :rules="requiredRules"
                                                              v-model="offre.appels_autre_op" label="appels_autre_op"
                                                              required></v-text-field>
                                                <v-text-field :rules="requiredRules"
                                                              v-model="offre.sms_autre_op" label="sms_autre_op"
                                                              required></v-text-field>
                                                <v-text-field :rules="requiredRules"
                                                              v-model="offre.internet" label="internet"
                                                              required></v-text-field>
                                                <v-text-field :rules="requiredRules"
                                                              v-model="offre.prix" label="prix"
                                                              type="number"
                                                              required></v-text-field>
                                                <v-select
                                                        required
                                                        :rules="requiredRules"
                                                        v-model="offre.designation_op"
                                                        :items="operateurs"
                                                        item-text="designation_op"
                                                        item-value="designation_op"
                                                        label="Operateur"
                                                ></v-select>
                                            </v-form>
                                        </v-container>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="blue darken-1" @click="dialog=false" text>
                                            Annuler
                                        </v-btn>
                                        <v-btn color="blue darken-1" :disabled="!valid" @click="saveOperateur()" text>
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
        vuetify: new Vuetify({theme: { dark: true },}),
        data: {
            search: '',
            dialog: false,
            valid: false,
            nav: 3,
            offre: {
                designation_offre: '',
                appels_op: '',
                sms_op: '',
                appels_autre_op: '',
                sms_autre_op: '',
                internet: '',
                prix: '',
                designation_op: '',
            },
            action: '',
            offres: [],
            operateurs: [],
            requiredRules: [
                v => !!v || 'Ce champs est requis',
            ],

        },
        computed: {
            headers() {
                return [
                    {
                        text: 'Designation de l\'offre',
                        align: 'start',
                        value: 'designation_offre',
                    },
                    {
                        text: 'Appels',
                        align: 'start',
                        value: 'appels_op',
                    },
                    {
                        text: 'SMS',
                        align: 'start',
                        value: 'sms_op',
                    },
                    {
                        text: 'Appels autre',
                        align: 'start',
                        value: 'appels_autre_op',
                    },
                    {
                        text: 'SMS autres',
                        align: 'start',
                        value: 'sms_autre_op',
                    },
                    {
                        text: 'Internet',
                        align: 'start',
                        value: 'internet',
                    },
                    {
                        text: 'Prix',
                        align: 'start',
                        value: 'prix',
                    },
                    {
                        text: 'Operateur',
                        align: 'start',
                        value: 'designation_op',
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
            this.getOffres()
            this.getOperateurs()
        },
        methods: {
            getOperateurs() {
                axios.get('../api/operateur')
                    .then(function (response) {
                        app.operateurs = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            getOffres() {
                axios.get('../api/offre')
                    .then(function (response) {
                        app.offres = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            editItem(item) {
                this.action = 'edit';
                this.dialog = true;
                this.offre = item;
            },
            deleteItem(item) {
                let formData = new FormData();

                formData.append('designation_offre', item.designation_offre)

                axios({
                    method: 'post',
                    url: '../api/offre/delete.php',
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
                        app.getOffres()
                    })
                    .catch(function (response) {
                        //handle error
                        console.log(response)
                        alert("Erreur : " + response.response.data)
                    });
            },
            saveOperateur() {
                let formData = new FormData();

                formData.append('designation_offre', this.offre.designation_offre)
                formData.append('appels_op', this.offre.appels_op)
                formData.append('sms_op', this.offre.sms_op)
                formData.append('appels_autre_op', this.offre.appels_autre_op)
                formData.append('sms_autre_op', this.offre.sms_autre_op)
                formData.append('internet', this.offre.internet)
                formData.append('prix', this.offre.prix)
                formData.append('designation_op', this.offre.designation_op)
                var url = '';
                if (app.action === 'add') {
                    url = '../api/offre/add.php'
                } else {
                    url = '../api/offre/edit.php'
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
                        app.getOffres()
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
