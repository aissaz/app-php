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
                    <!----------------------------- Table de données ----------------------------------------------------------------->
                    <v-data-table :headers="headers" :items="operateurs" item-key="name" class="elevation-1"
                                  :search="search">
                        <template v-slot:top>
                            <!----------------------------- Champs de recherche ----------------------------------------------------------------->
                            <v-text-field v-model="search" label="Search" class="mx-4"></v-text-field>
                            <v-dialog v-model="dialog" max-width="500px">
                                <template v-slot:activator="{ on, attrs }">
                                    <!----------------------------- Bouton d'ajout ----------------------------------------------------------------->
                                    <v-btn fab small top right class="mb-2" v-bind="attrs" v-on="on"
                                           @click="action='add'"
                                           color="primary">
                                        <v-icon>mdi-plus</v-icon>
                                    </v-btn>
                                </template>
                                <!----------------------------- Formulaire d'ajout et d'edition ----------------------------------------------------------------->
                                <v-card>
                                    <v-card-title>
                                        <span v-if="action=='add'" class="text-h5">Ajouter un operateur</span>
                                        <span v-if="action=='edit'" class="text-h5">Editer un operateur</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-container>
                                            <v-form ref="form" v-model="valid">
                                                <v-text-field :disabled="action=='edit'" :rules="requiredRules"
                                                               v-model="operateur.designation_op"
                                                              label="Operateur" required></v-text-field>
                                                <v-text-field :rules="requiredRules"
                                                              v-model="operateur.adresse" label="Adress"
                                                              required></v-text-field>
                                                <v-select
                                                        :rules="requiredRules"
                                                        v-model="operateur.pays"
                                                        :items="pays"
                                                        label="Pays"
                                                ></v-select>
                                                <v-text-field :rules="[
                                                                v => !!v || 'E-mail est obligatoire',
                                                                v => /.+@.+/.test(v) || 'E-mail doit etre valide'
                                                              ]"
                                                              v-model="operateur.mail" label="E-mail"
                                                              required></v-text-field>
                                                <v-text-field :rules="requiredRules"
                                                              v-model="operateur.num" label="Numéro de téléphone"
                                                              required></v-text-field>
                                                <v-text-field :rules="requiredRules"
                                                              v-model="operateur.fb" label="Facebook"
                                                              required></v-text-field>
                                                <v-text-field :rules="requiredRules"
                                                              v-model="operateur.linkedin" label="linkedin"
                                                              required></v-text-field>
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
                        <!----------------------------- Bouton d'edition et de suppresion ----------------------------------------------------------------->
                        <template v-slot:item.actions="{ item }">
                            <v-icon small color="indigo" class="mr-2" @click="editItem(item)">
                                mdi-pencil
                            </v-icon>
                            <v-icon small @click="deleteItem(item)" color="error">
                                mdi-delete
                            </v-icon>
                        </template>
                    </v-data-table>

                    <!----------------------------- Menu de navigation ----------------------------------------------------------------->
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
            // champ de recherche
            search: '',
            // varible pour afficher le formulaire ou non
            dialog: false,
            // varible pour savoir si le formulaire est valid eou non
            valid: false,
            //position dans le menu de navigation
            nav: 0,
            // object qui represente le formualire d'edition et d'ajout
            operateur: {
                designation_op: '',
                adresse: '',
                pays: '',
                mail: '',
                num: '',
                fb: '',
                linkedin: '',
            },
            pays: ['Algérie', 'Tunisie', 'Maroc'],
            // action effectuer par le formulaire add= Ajout edit = edition
            action: '',
            //liste des données contenue dans le table
            operateurs: [],
            // regles qui dit qu'un champ du formulaire est obligatoire
            requiredRules: [
                v => !!v || 'Ce champs est requis',
            ],

        },
        mounted: function () {
            // chargement des donnée au demarrage de la page
            this.getOperateurs()
        },
        methods: {
            // fonction pour recuperer les données depuis la db via une api php
            getOperateurs() {
                axios.get('../api/operateur')
                    .then(function (response) {
                        app.operateurs = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            // fonction qui fait apparaitre le formulaire en mode edition
            editItem(item) {
                this.action = 'edit';
                this.dialog = true;
                this.operateur = item;
            },
            // supprimer un element en base de donnée
            deleteItem(item) {
                let formData = new FormData();
                formData.append('designation_op', item.designation_op)
                axios({
                    method: 'post',
                    url: '../api/operateur/delete.php',
                    data: formData,
                    config: {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                }).then(function (response) {
                        //handle success
                        console.log(response)
                        app.getOperateurs()
                    })
                    .catch(function (response) {
                        //handle error
                        console.log(response)
                        alert("Erreur : " + response.response.data)
                    });
            },
            // sauvgarde l'object en fonction de l'action ajoute ou met a jour
            saveOperateur() {
                let formData = new FormData();

                formData.append('designation_op', this.operateur.designation_op)
                formData.append('adresse', this.operateur.adresse)
                formData.append('pays', this.operateur.pays)
                formData.append('mail', this.operateur.mail)
                formData.append('num', this.operateur.num)
                formData.append('fb', this.operateur.fb)
                formData.append('linkedin', this.operateur.linkedin)
                var url = '';
                if (app.action === 'add') {
                    url = '../api/operateur/add.php'
                } else {
                    url = '../api/operateur/edit.php'
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
                        app.getOperateurs()
                        app.dialog = false

                    })
                    .catch(function (response) {
                        //handle error
                        console.log(response)
                        alert("Erreur : " + response.response.data)
                    });
            },
        },
        computed: {
            // titre des colonne du tableau html
            headers() {
                return [{
                    text: 'Operateur',
                    align: 'start',
                    value: 'designation_op',
                },
                    {
                        text: 'Adresse',
                        align: 'start',
                        value: 'adresse',
                    },
                    {
                        text: 'Pays',
                        align: 'start',
                        value: 'pays',
                    },
                    {
                        text: 'Addresse email',
                        align: 'start',
                        value: 'mail',
                    },
                    {
                        text: 'Numéro de telephone',
                        align: 'start',
                        value: 'num',
                    },
                    {
                        text: 'Facebook',
                        align: 'start',
                        value: 'fb',
                    },
                    {
                        text: 'Linkedin',
                        align: 'start',
                        value: 'linkedin',
                    },
                    {
                        text: 'Actions',
                        value: 'actions',
                        sortable: false
                    },

                ]
            },
        },
    })
</script>
</body>

</html>
