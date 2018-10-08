<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Card Component</div>

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
        axios
        .get('https://api.magicthegathering.io/v1')
        .then(response => (this.info = response))
    },
    props: {
        data: {
        type: Object,
        required: false // set this to true if you need data passed to the component for it to work
        }
    },
    data() {
        return {
            cards: [this.data.cards]
        };
    },
    methods: {
        getAllCardsAPI() {
            // Do you api logic from a method you can call here as much as you want
        }
    }
    };
</script>