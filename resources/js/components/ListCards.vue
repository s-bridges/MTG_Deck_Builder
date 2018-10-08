<template>
    <div class="container">
        <div class="row justify-content-center">                
            <div class="container py-3">
                <div class="row">
                    <div class="mx-auto col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">All Cards</h4>
                            </div>
                            <thead scope="col">
                                <th scope>Search</th>
                                    <select id="set" class="form-control" v-model="filterSet" v-on:change="setAPI()">
                                        <option disabled value="">None Selected</option>
                                        <option v-for="option in setOptions" :value="option">{{option}}</option>
                                    </select>
                                </thead>
                            <div class="card-body">
                                <table class="table" v-if="cards.length > 0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Card Name</th>
                                            <th scope="col">Set</th>
                                            <th scope="col">Multiverse ID</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="card in cards">
                                            <td>{{card.name}}</td>
                                            <td>{{card.setName}}</td>
                                            <td>{{card.multiverseid}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
    axios.get(`https://api.magicthegathering.io/v1/cards`)
        .then(response => {
            this.cards = response.data.cards;
            console.log(this.cards);
        })
    this.setAPI();
    axios.get(`https://api.magicthegathering.io/v1/sets?name=${this.filterSet}`)
        .then(response => {
            this.cards = response.data.cards;
            console.log(this.cards);
        })
    .catch(error => {});
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
      filterSet: '',
      setOptions: ['Khans', 'Ixilan', 'Amonkhet']
    };
  },
  methods: {
    getCardsFromAPI() {
        // API stuff to mtgo
        
    }
    
  }

};
</script>
