<template>
    <div class="container">
      <div>
        <div v-if="myDeckCards" class="row justify-content-center">             
            <div class="container py-3">  
              <div class="card-header">
                <h4 class="mb-0">{{ deck.name }}</h4>
              </div>
              <div class="row">
                <div v-for="card in myDeckCards" class="col col-lg-3 text-center" style="padding-top:.5em;"> 
                    <div v-if="card.pivot.count <= 4" style="height:40px; display:flex; justify-content:center; align-items:center">
                      <span v-for="n in card.pivot.count">
                        <i style="max-width: 24px;" class="material-icons">whatshot</i>
                      </span>
                    </div>
                    <div v-else style="height:40px; display:flex; justify-content:center; align-items:center"> 
                      <span><strong>{{card.pivot.count}}x</strong></span>
                    </div>                 
                    <v-lazy-image
                    v-bind:src="'http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=' + card.multiverse_id + '&type=card'"
                  />
                </div>
                </div>
                </div>
                <button type="button" class="btn btn-danger" v-on:click="saveDeck()">Save</button>
              </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  mounted() {
    console.log(this.data);
    // call methods here that you want done on page load, the methods are defined in the methods section below
    // this method below here we don't need to worry about right now
    // this.getCardsFromAPI();
  },
  props: {
    data: {
      type: Object,
      required: false
    }
  },
  data() {
    return {
      deck: this.data.deck
    };
  },
  methods: {
    saveDeck() {
      let deck = this.deck;
      axios
          .put(`/deck/edit`, deck)
          .then(response => {
            // get deck from the server that was added to and refresh it on the page
            this.deck = this.data.payload.deck
          })
          .catch(error => {});
    }
  },
  computed: {
    myDeckCards() {
      let selectedCards = this.deck.cards;
      // if we add any filtering it will go in here
      return selectedCards;
    },
  }
};
</script>
