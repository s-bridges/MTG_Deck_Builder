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
        <p v-if="totalUsers">Users Registered: {{ totalUsers }}</p>
        <p v-if="totalDecks">Total Decks Built: {{ totalDecks }}</p>
        <p class="lead">
        </p>
        </div>
            <div class="row">
            <p>Deck of the Week is {{ dotwID }}</p>
            </div>
            <div class="row">
                <form>
                <div class="form-group">
                    <label for="dotw">Change Deck of the Week</label>
                    <input type="text" class="form-control" id="dotw" aria-describedby="Change Deck of the Week" placeholder="Enter Deck ID #">
                </div>
                <button v-on:click="saveDeckOfTheWeek(deckId)" type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    </body>
    </html>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.');
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
                admin: this.data.admin   

            }
        },
        methods: {
            // set all DOTW to 0, then assign deckId = 1;
            saveDeckOfTheWeek() {
                let deckId = this.deckOfTheWeek;
                axios
                    .patch(`/admin/save/update-dotw/`, deckId)
                    .then(response => {
                        alert('Deck Of The Week was Saved!');
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
                return this.data.deckID;
            },
        }
    }
</script>

