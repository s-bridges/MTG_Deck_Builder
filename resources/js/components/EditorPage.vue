<template>
   <html>
    <title>Admin Panel</title>
    <body>
    <div class="container">    
        <div class="jumbotron">
        <h1 class="display-4">Editor Panel</h1>
        <p class="lead">Hello, {{ editor.name }} / {{editor.username}}</p>
        <hr class="my-4">
        <p><a href="/editor/blog">Create Blog Post</a></p>
        <p v-if="totalUsers">Users Registered: {{ totalUsers }}</p>
        <p v-if="totalDecks">Total Decks Built: {{ totalDecks }}</p>
        <p class="lead">
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
                editor: this.data.editor,
                deckId: ''   
            }
        },
        methods: {
            goToBlog() {
                window.location.href = '/admin/blog';
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
        }
    }
</script>

