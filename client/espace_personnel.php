<?php
session_start();
?>
<?php

//si le user est deja connecter on affiche la page
if (isset($_SESSION['user_name'])) {
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
                    <!--------------------------- INFORMATIONS CLIENT -------------------------------------------->
                    <v-card class="mx-auto">
                        <v-card-title>
                            <h1>Espace de {{client.prenom + ' ' +client.nom}}</h1>
                            <v-fab-transition>
                                <v-btn @click="logout" color="pink" fab small absolute right>
                                    <v-icon>mdi-logout</v-icon>
                                </v-btn>
                            </v-fab-transition>
                        </v-card-title>
                        <v-list two-line>
                            <v-list-item>
                                <v-list-item-icon>
                                    <v-icon color="indigo">
                                        mdi-phone
                                    </v-icon>
                                </v-list-item-icon>

                                <v-list-item-content>
                                    <v-list-item-title>{{client.num}}</v-list-item-title>
                                    <v-list-item-subtitle>Numero</v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item>
                                <v-list-item-action></v-list-item-action>

                                <v-list-item-content>
                                    <v-list-item-title>{{client.designation_op}}</v-list-item-title>
                                    <v-list-item-subtitle>Operateur</v-list-item-subtitle>
                                </v-list-item-content>

                            </v-list-item>
                            <v-list-item>
                                <v-list-item-action></v-list-item-action>

                                <v-list-item-content>
                                    <v-list-item-title>{{client.date_naiss}}</v-list-item-title>
                                    <v-list-item-subtitle>Date de naissance</v-list-item-subtitle>
                                </v-list-item-content>

                            </v-list-item>
                            <v-divider inset></v-divider>
                            <v-list-item>
                                <v-list-item-icon>
                                    <v-icon color="indigo">
                                        mdi-email
                                    </v-icon>
                                </v-list-item-icon>

                                <v-list-item-content>
                                    <v-list-item-title>{{client.mail}}</v-list-item-title>
                                    <v-list-item-subtitle>Email</v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>
                            <v-divider inset></v-divider>
                            <v-list-item>
                                <v-list-item-icon>
                                    <v-icon color="indigo">
                                        mdi-map-marker
                                    </v-icon>
                                </v-list-item-icon>

                                <v-list-item-content>
                                    <v-list-item-title>{{client.pays}}</v-list-item-title>
                                    <v-list-item-subtitle>Pays</v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list>
                    </v-card>
                    <!--------------------------- ABONNEMENTS CLIENT -------------------------------------------->
                    <v-card class="mx-auto">
                        <v-card-title>
                            <h1>Abonnements</h1>
                        </v-card-title>

                        <v-card-text class="text--primary">
                            <v-list two-line>
                                <div v-for="abonnement in abonnements" :key="abonnement.num_abonnement">
                                    <v-list-item>
                                        <v-list-item-action></v-list-item-action>

                                        <v-list-item-content>
                                            <v-list-item-title>{{abonnement.num_abonnement}}</v-list-item-title>
                                            <v-list-item-subtitle>Numero abonnement</v-list-item-subtitle>
                                        </v-list-item-content>

                                        <v-list-item-content>
                                            <v-list-item-title>{{abonnement.date}}</v-list-item-title>
                                            <v-list-item-subtitle>Montant</v-list-item-subtitle>
                                        </v-list-item-content>
                                        <v-list-item-content>
                                            <v-list-item-title>{{abonnement.designation_offre}}</v-list-item-title>
                                            <v-list-item-subtitle>Offre</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-divider inset></v-divider>
                                </div>
                            </v-list>
                        </v-card-text>
                    </v-card>
                    <!--------------------------- FATURES CLIENT -------------------------------------------->
                    <v-card class="mx-auto">
                        <v-card-title>
                            <h1>Factures</h1>
                        </v-card-title>
                        <v-card-text class="text--primary">
                            <v-list two-line>
                                <div v-for="facture in factures" :key="facture.num_facture">
                                    <v-list-item>
                                        <v-list-item-action></v-list-item-action>

                                        <v-list-item-content>
                                            <v-list-item-title>{{facture.num_facture}}</v-list-item-title>
                                            <v-list-item-subtitle>Numero de facture</v-list-item-subtitle>
                                        </v-list-item-content>
                                        <v-list-item-content>
                                            <v-list-item-title>{{facture.num_abonnement}}</v-list-item-title>
                                            <v-list-item-subtitle>Numero abonnement</v-list-item-subtitle>
                                        </v-list-item-content>

                                        <v-list-item-content>
                                            <v-list-item-title>{{facture.montant}}</v-list-item-title>
                                            <v-list-item-subtitle>Montant</v-list-item-subtitle>
                                        </v-list-item-content>
                                        <v-list-item-content>
                                            <v-list-item-title>{{facture.date}}</v-list-item-title>
                                            <v-list-item-subtitle>Date</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-divider inset></v-divider>
                                </div>
                            </v-list>
                        </v-card-text>
                    </v-card>
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
                valid: true,
                user_name: '<?php echo $_SESSION['user_name']; ?>',
                client: {},
                abonnements: [],
                factures: [],
            },
            computed: {},
            mounted: function () {
                // chargement des infomation du client via ddes appel aux api php
                this.getClient();
                this.getAbonnement();
                this.getFacture();
            },
            methods: {
                logout() {
                    window.location.href = "./logout.php";
                },
                getClient() {
                    axios.get('../api/client/get_by_id.php?num=' + this.user_name)
                        .then(function (response) {
                            console.log(response);
                            app.client = response.data
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                },
                getAbonnement() {
                    axios.get('../api/abonnement/get_by_client.php?num=' + this.user_name)
                        .then(function (response) {
                            console.log(response);
                            app.abonnements = response.data
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                },
                getFacture() {
                    axios.get('../api/facture/get_by_client.php?num=' + this.user_name)
                        .then(function (response) {
                            console.log(response);
                            app.factures = response.data
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                },
            }
        })
    </script>
    </body>
    </html>
    <?php
// si n'est pas connecter on le renvoie a la page d'authentification
} else {
    header("Location: ./authentification.php");
    exit();
} ?>
