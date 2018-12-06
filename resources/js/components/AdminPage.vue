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
            <div class="row">
            <p>Deck of the Week is {{ selectedDeckId }}</p>
            </div>
            <div class="row">
                <form>
                <div class="form-group">
                    <label for="dotw">Change Deck of the Week</label>
                    <input type="number" v-model="deckId" name="deckId" placeholder="Enter Deck ID #" class="mt-3">
                </div>
                </form>
                <p>
                    <button v-on:click="saveDeckOfTheWeek()" class="btn btn-primary">Update</button>
                </p>

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

