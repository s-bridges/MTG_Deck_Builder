<template>
    <div class="container">
        <div class="row justify-content-center">               
            <div class="container py-3">
              <div class="row">
                <div class="col-sm-12 col-md-9">
                </div>   
              </div>   
              <div class="card-header">
                <h4 class="mb-0">My Decks</h4>
              </div>
                            </br>
                <div class="row">
                            <div v-for="deck in deckList" class="col-lg-3 text-center">
                                <div v-on:click="selectDeck(deck.id)" style="margin:0 auto; cursor: pointer; width:178px;height:130px; background-position: 40% 20%;" v-bind:style="{backgroundImage: 'url(http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid='+ deck.cards[0].multiverse_id + '&type=card)' }">
                                </div>
                                <p>{{ deck.name }}</p>
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
      deckList: this.data.decks
    };
  },
  methods: {
      selectDeck(id) {
          // redirect using javascript to specific url
          window.location.href = '/deck/' + id + '/cards/';
      }
  },
  computed: {
  }
};
</script>
