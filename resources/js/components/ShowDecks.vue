<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
            <div id="app">
                    <div class="search-wrapper">
                        <input type="text" v-model="search" placeholder="Search for card..."/>
                    </div>
                    <div class="wrapper">
                        <div class="card" v-for="post in filteredList">
                        <a v-bind:href="post.link" target="_blank">
                            <img v-bind:src="post.img"/>
                            <small>posted by: {{ post.author }}</small>
                            {{ post.title }}
                        </a>
                        </div>
                    </div>
                    </div>
                <div class="card card-default">
                    <div class="card-header">Search Results</div>
                    <div class="card-body">
                        I'm an example component.
                    </div>
                </div>
                <div class="card card-default">
                    <div class="card-header">My Deck</div>
                    <div class="card-body">
                        I'm an example component.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.getCardsFromAPI();
        },
        props: {
            data: {
                type: Object,
                required: false
            }
        },
        data() {
            return {
                cards: [],
                searchBy: '',
                searchForData: {}
            };
        },
        methods: {
            getCardsFromAPI() {
                axios.get(`https://api.magicthegathering.io/v1/cards`)
                .then(response => {
                    this.cards = response.data.cards;
                })
                .catch(error => {});
            }
        }
    };
</script>
