<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
            <div id="app">
                   <input type="text" v-model.lazy="keywords" placeholder="Search for card...">
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
        },
        data() {
            return {
                keywords: null,
                results: []
            };
        },
        watch: {
            keywords(after, before) {
                this.fetch();
            }
        },
        methods: {
            fetch() {
                axios.get(`https://api.magicthegathering.io/v1/cards`, { params: { keywords: this.keywords } })
                .then(response => this.results = response.data)
                .catch(error => {});
            }
        }
    };
</script>
