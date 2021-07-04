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
                    <v-data-table :headers="headers" :items="clients" item-key="name" class="elevation-1"
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
                                        <span v-if="action=='add'" class="text-h5">Ajouter un client</span>
                                        <span v-if="action=='edit'" class="text-h5">Editer un client</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-container>
                                            <v-form ref="form" v-model="valid">
                                                <v-text-field :rules="requiredRules"
                                                              v-model="client.num" label="Num"
                                                              required></v-text-field>
                                                <v-text-field :rules="requiredRules"
                                                              v-model="client.mot_passe" label="Mot_passe"
                                                              type="password"
                                                              required></v-text-field>
                                                <v-text-field :rules="[
                                                                v => !!v || 'E-mail est obligatoire',
                                                                v => /.+@.+/.test(v) || 'E-mail doit etre valide'
                                                              ]"
                                                              v-model="client.mail" label="E-mail"

                                                              required></v-text-field>
                                                <v-text-field :rules="requiredRules"
                                                              v-model="client.nom" label="Nom"
                                                              required></v-text-field>
                                                <v-text-field :rules="requiredRules"
                                                              v-model="client.prenom" label="Prenom"
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
                                                                v-model="client.date_naiss"
                                                                label="Date de naissance"
                                                                persistent-hint
                                                                v-on="on"
                                                                readonly
                                                        >
                                                        </v-text-field>
                                                    </template>
                                                    <v-date-picker v-model="client.date_naiss" no-title
                                                                   @input="datePickerMenu = false"></v-date-picker>
                                                </v-menu>

                                                <v-select
                                                        :rules="requiredRules"
                                                        v-model="client.pays"
                                                        :items="pays"
                                                        label="Pays"
                                                ></v-select>

                                                <v-select
                                                        required
                                                        :rules="requiredRules"
                                                        v-model="client.designation_op"
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
            valid: false,
            nav: 1,
            client: {
                num: '',
                mail: '',
                nom: '',
                prenom: '',
                date_naiss: '',
                pays: '',
                mot_passe: '',
                designation_op: '',
            },
            action: '',
            clients: [],
            requiredRules: [
                v => !!v || 'Ce champs est requis',
            ],
            datePickerMenu: false,
            pays: ['Algérie', 'Tunisie', 'Maroc'],
            operateurs: []
        },
        computed: {
            headers() {
                return [
                    {
                        text: 'Numero de telephone',
                        align: 'start',
                        value: 'num',
                    },
                    {
                        text: 'Mail',
                        align: 'start',
                        value: 'mail',
                    },
                    {
                        text: 'Nom',
                        align: 'start',
                        value: 'nom',
                    },
                    {
                        text: 'Prenom',
                        align: 'start',
                        value: 'prenom',
                    },
                    {
                        text: 'Date de naissance',
                        align: 'start',
                        value: 'date_naiss',
                    },
                    {
                        text: 'Pays',
                        align: 'start',
                        value: 'pays',
                    },
                    {
                        text: 'Mot de passe',
                        align: 'start',
                        value: 'mot_passe',
                    },
                    {
                        text: 'Operator',
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
            this.getClients()
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
            getClients() {
                axios.get('../api/client')
                    .then(function (response) {
                        app.clients = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            editItem(item) {
                this.action = 'edit';
                this.dialog = true;
                this.client = item;
            },
            deleteItem(item) {
                let formData = new FormData();

                formData.append('num', item.num)

                axios({
                    method: 'post',
                    url: '../api/client/delete.php',
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
                        app.getClients()
                    })
                    .catch(function (response) {
                        //handle error
                        console.log(response)
                        alert("Erreur : " + response.response.data)
                    });
            },
            save() {
                let formData = new FormData();

                formData.append('num', this.client.num)
                formData.append('mail', this.client.mail)
                formData.append('nom', this.client.nom)
                formData.append('prenom', this.client.prenom)
                formData.append('date_naiss', this.client.date_naiss)
                formData.append('pays', this.client.pays)
                formData.append('mot_passe', this.client.mot_passe)
                formData.append('designation_op', this.client.designation_op)
                var url = '';
                if (app.action === 'add') {
                    url = '../api/client/add.php'
                } else {
                    url = '../api/client/edit.php'
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
                        app.getClients()
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
