<template>
    <div class="container">
        <div class="row justify-content-center">                
            <div class="container py-3">
                <div class="col-lg-3 col-md-3">
                    <select id="colors" class="form-control" v-model="filterColors" v-on:change="colorAPI()">
                    <option disabled value="">Search By Mana</option>
                    <option v-for="option in colorOptions" :value="option">{{option}}</option>
                    </select>
                </div>
                <div class="row">
                    <div class="mx-auto col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">All Cards</h4>
                            </div>
                            
                            <div class="card-body">
                                <table class="table" v-if="cards.length > 0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Card Name</th>
                                            <th scope="col">Mana Color</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Rarity</th>
                                            <th scope="col">Set</th>
                                            <th scope="col">Multiverse ID</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="card in cards">
                                            <td>{{card.name}}</td>
                                            <td>{{card.colors.join(', ')}}</td>
                                            <td>{{card.type}}</td>
                                            <td>{{card.rarity}}</td>
                                            <td>{{card.set}}</td>
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
      // call methods here that you want done on page load, the methods are defined in the methods section below
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
      filterColors: '',
      colorOptions: ['Black', 'Blue', 'White', 'Red', 'Green'],
      mtgoColorData: {}
    };
  },
  methods: {
    getCardsFromAPI() {
        // API stuff to mtgo for all cards
        axios.get(`https://api.magicthegathering.io/v1/cards`)
        .then(response => {
            this.cards = response.data.cards;
        })
    },
    colorAPI(){
        axios.get(`https://api.magicthegathering.io/v1/cards?colors=${this.filterColors}`)
        .then(response => {
            this.cards = response.data.cards;

            console.log(this.cards);
        })
        .catch(error => {});
    },
    setAPI(){
        axios.get(`https://api.magicthegathering.io/v1/sets?name=${this.filterSet}`)
        .then(response => {
            this.mtgoSetData = response.data;

            console.log(this.mtgoSetData);
        })
        .catch(error => {});   
    }
  }

};
</script>