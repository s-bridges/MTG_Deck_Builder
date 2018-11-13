<template>
   <html>
    <title>Admin Panel</title>
    <body>
        
    <div class="jumbotron">
    <h1 class="display-4"> Admin Panel </h1>
    <p class="lead">Hello, {{ admin.name }}</p>
    <hr class="my-4">
    <p v-if="totalUsers">Users Registered: {{ totalUsers }}</p>
    <p v-if="totalDecks">Total Decks Built: {{ totalDecks }}</p>
    <p class="lead">
    </p>
    </div>
    </body>
    </html>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
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
            }
        }
    }
</script>

