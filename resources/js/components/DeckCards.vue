<template>
    <div class="container">
      <div v-if="viewable || editable">
        <div v-if="myDeckCards" class="row justify-content-center">             
            <div class="container py-3">  
              <div class="card-header">
                <h4 class="mb-0">{{ deck.name }}</h4>
              </div>
              <div class="row">
                <div v-for="card in myDeckCards" class="col col-lg-3 text-center" style="padding-top:.5em;"> 
                    <div v-if="card.count <= 4" style="height:40px; display:flex; justify-content:center; align-items:center">
                      <span v-for="item in card.count">
                        <i style="max-width: 24px;" class="material-icons">whatshot</i>
                      </span>
                    </div>
                    <div v-else style="height:40px; display:flex; justify-content:center; align-items:center"> 
                      <span><strong>{{card.count}}x</strong></span>
                    </div>                 
                    <v-lazy-image
                      v-bind:src="'http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=' + card.multiverse_id + '&type=card'"
                    />
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
      deck: this.data.deck,
      viewable: this.data.viewable,
      editable: this.data.editable
    };
  },
  methods: {

  },
  computed: {
    myDeckCards() {
      let selectedCards = this.deck.cards;
      // group the cards by the card name so we can keep track of duplicates
      let result = _.chain(selectedCards).groupBy('name').map(function(v, i) {
        // get first card out of group of the same cards and set the card data
        let cardData = _.first(v);
        return {
          name: i,
          multiverse_id: cardData.multiverse_id,
          count: v.length,
          card: cardData
        }
      }).value();
      return result;
    }
  }
};
</script>
