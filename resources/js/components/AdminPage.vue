<template>
   <html>
    <title>Admin Panel</title>
    <body>
    <div class="container">    
        <div class="jumbotron">
        <h1 class="display-4">Admin Panel</h1>
        <p class="lead">Hello, {{ admin.name }} / {{admin.username}}</p>
        <hr class="my-4">
        <p><a href="/import">Import CSV</a></p>
        <p><a href="/admin/import-cards">Import Cards</a></p>
        <p v-if="totalUsers">Users Registered: {{ totalUsers }}</p>
        <p v-if="totalDecks">Total Decks Built: {{ totalDecks }}</p>
        <p class="lead">
        </p>
        </div>
        <div id="accordion">
        <div class="card">
            <div class="card-header" id="headingOne">
            <h5 class="mb-0">
                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Deck of the Week is {{ selectedDeckId }} - Click to Edit
                </button>
            </h5>
            </div>

            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                <form>
                    <div class="form-group">
                        <input type="number" v-model="deckId" name="deckId" placeholder="New Deck ID #" class="mt-3">
                    </div>
                </form>
                <button v-on:click="saveDeckOfTheWeek()" class="btn btn-primary">Update</button>
            </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingTwo">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                Make Admin?
                </button>
            </h5>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
            <div class="card-body">
                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
            </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingThree">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Some other cool admin feature?
                </button>
            </h5>
            </div>
        </div>
        </div>





        </div>
    </div>
    </body>
    </html>
</template>

<script>
    export default {
        mounted() {
        },
        props: {
            data: {
                type: Object,
                required: false
            }
        },
        data() {
            return {
                users: this.data.users,
                selectedDeckId: this.data.dotw,
                admin: this.data.admin,
                deckId: ''   
            }
        },
        methods: {
            // set all DOTW to 0, then assign deckId = 1;
            saveDeckOfTheWeek() {
                let deckId = this.deckId;
                axios
                    .patch(`/admin/save/update-dotw/`, {deck_id: deckId})
                    .then(response => {
                        // set the deck id that loaded with the page
                        this.selectedDeckId = deckId;
                        // reset the deck id
                        this.deckId = '';
                    })
                    .catch(error => {});
            }
        },
        computed: {
            totalUsers() {
                // return total user count
                return this.users.length;
            },
            totalDecks() {
                let deckCount = 0; 
                // return total deck count
                _.forEach(this.users, function(user){
                    if (user.decks && user.decks.length > 0) {
                        deckCount = deckCount + user.decks.length;
                    }
                });
                return deckCount;
            },
            dotwID() {
                // return deckId of DOTW
                return this.deckId ? this.deckId : 'Not Set';
            },
        }
    }
</script>

