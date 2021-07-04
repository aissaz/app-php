<?php
session_start();
?>
<?php

//si le user est deja connecter on le renvoie vers la page espace_personnel
if (isset($_SESSION['user_name'])) {
    header("Location: ./espace_personnel.php");
    exit();
// si non on affiche le formulaire de conexion
} else {
    ?>


    <!DOCTYPE html>
    <html>

    <head>
        <link href="../css/fonts-googleapis.css" rel="stylesheet">
        <link href="../css/materialdesignicons.min.css" rel="stylesheet">
        <link href="../css/vuetify.min.css" rel="stylesheet">
        <meta name="viewport"
              content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">

    </head>

    <body style="background-color: black">
    <div id="app">
        <v-app>
            <v-main>
                <v-container>
                    <v-tabs
                            v-model="tab"
                            background-color="indigo"
                            centered
                    >
                        <v-tabs-slider></v-tabs-slider>

                        <v-tab href="#tab-1">
                            Authentification
                        </v-tab>

                        <v-tab href="#tab-2">
                            Mot de passe oublier
                        </v-tab>

                        <v-tab href="#tab-3">
                            Creation d'espace client
                        </v-tab>
                    </v-tabs>

                    <v-tabs-items v-model="tab">
                        <v-tab-item value="tab-1">
                            <v-card class="elevation-12">
                                <v-card-text>
                                    <!-------------------Authentication form ------------------------------------------>
                                    <v-form v-model="valid" ref="form">
                                        <v-text-field
                                                name="user_name"
                                                label="Numero de telephone"
                                                v-model="user_name"
                                                :rules="[v => !!v || 'Numero est obligatoir']"
                                                type="text"
                                        ></v-text-field>
                                        <v-text-field
                                                name="password"
                                                label="Mot de passe"
                                                v-model="password"
                                                :rules="[v => !!v || 'Le mots de passe est obligatoire']"
                                                type="password"
                                        ></v-text-field>
                                    </v-form>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="primary" :disabled="!valid" @click="login">Login</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-tab-item>
                        <v-tab-item value="tab-2">
                            <v-card flat>
                                <v-card-text>
                                    <!-------------------Mot de passe oublier form ------------------------------------------>
                                    <v-form v-model="valid" ref="form">
                                        <v-text-field
                                                name="user_name"
                                                label="Numero de telephone"
                                                v-model="user_name"
                                                :rules="[v => !!v || 'Numero est obligatoir']"
                                                type="text"
                                        ></v-text-field>
                                        <v-text-field
                                                name="password"
                                                label="Nouveau mot de passe"
                                                v-model="password"
                                                :rules="[v => !!v || 'Le mots de passe est obligatoire']"
                                                type="password"
                                        ></v-text-field>
                                    </v-form>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="primary" :disabled="!valid" @click="updatePassword">Confirmer</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-tab-item>
                        <v-tab-item value="tab-3">
                            <v-card>
                                <v-card-text>
                                    <v-container>
                                        <!-------------------Nouveau client form ------------------------------------------>
                                        <v-form ref="newClientform" v-model="newClientFormvalid">
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
                                                    lazy
                                                    transition="scale-transition"
                                                    offset-y
                                                    full-width
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
                                    <v-btn color="primary" :disabled="!newClientFormvalid" @click="createNewClient">
                                        Sauvgarder
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-tab-item>
                    </v-tabs-items>
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
                tab: null,
                valid: true,
                newClientFormvalid: true,
                user_name: '',
                password: '',
                datePickerMenu: false,
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
                pays: ['Algérie', 'Tunisie', 'Maroc'],
                requiredRules: [
                    v => !!v || 'Ce champs est requis',
                ],
                operateurs: []
            },
            computed: {},
            mounted: function () {
                // chargement des donnée au demarrage de la page
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
                createNewClient() {
                    let formData = new FormData();
                    formData.append('num', this.client.num)
                    formData.append('mail', this.client.mail)
                    formData.append('nom', this.client.nom)
                    formData.append('prenom', this.client.prenom)
                    formData.append('date_naiss', this.client.date_naiss)
                    formData.append('pays', this.client.pays)
                    formData.append('mot_passe', this.client.mot_passe)
                    formData.append('designation_op', this.client.designation_op)

                    axios({
                        method: 'post',
                        url: '../api/client/add.php',
                        data: formData,
                        config: {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    })
                        .then(function (response) {
                            // si la creation s'est bien passée on redirige vers l'authentification
                            window.location.href = "./authentification.php";
                        })
                        .catch(function (response) {
                            //handle error
                            console.log(response)
                            alert("Erreur : " + response.response.data)
                        });
                },
                updatePassword() {
                    if (this.$refs.form.validate()) {
                        let formData = new FormData();
                        formData.append('user_name', this.user_name)
                        formData.append('password', this.password)
                        axios({
                            method: 'post',
                            url: '../api/client/update_password.php',
                            data: formData,
                            config: {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            }
                        })
                            .then(function (response) {
                                console.log(response)
                                // si la mise a jour s'est bien passée on redirige vers l'authentification
                                app.tab = 'tab-1';
                            })
                            .catch(function (response) {
                                //handle error
                                console.log(response)
                                alert("Erreur : " + response.response.data)
                            });
                    }
                },
                login() {
                    if (this.$refs.form.validate()) {
                        let formData = new FormData();
                        formData.append('user_name', this.user_name)
                        formData.append('password', this.password)
                        axios({
                            method: 'post',
                            url: '../api/login.php',
                            data: formData,
                            config: {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            }
                        })
                            .then(function (response) {
                                console.log(response)
                                // si le login s'est bien passée on redirige vers l'espace personel
                                window.location.href = "./espace_personnel.php";
                            })
                            .catch(function (response) {
                                //handle error
                                console.log(response)
                                alert("Erreur : " + response.response.data)
                            });
                    }
                }
            }
        })
    </script>
    </body>

    </html>
    <?php
} ?>
