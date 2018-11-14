<template>
   <html>
    <title>User Panel</title>
    <body>
        
    <div class="jumbotron">
    <h1 class="display-4"> User Panel </h1>
    <p class="lead">Hello, {{ user.name }}</p>
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
                users: this.data.users   

            }
        },
        methods: {
        },
        computed: {

        }
    }
</script>

