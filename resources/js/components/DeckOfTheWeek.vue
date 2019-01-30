<template>
    <div class="container">
        <div class="row justify-content-center">  
        <!-- TCG ad -->
    <div class="row justify-content-center">
    <span v-on:click="viewAd()"><img src="https://magicdb.us/images/rna_wbn_key_728x90_en.jpg" class="img-fluid" alt="Responsive image"></span>
    </div>             
            <div class="container py-3">
              <div class="row">
                <div class="col-sm-12 col-md-9">
                </div>   
              </div>   
              <div class="jumbotron">
                <h1 class="display-4">Deck of the Week!</h1>
                <p class="lead">{{deck.name}} was built by {{deck.user.username}}!</p>
                <hr class="my-4">
                <p>Each week, the MagicDB staff picks a deck to be featured here!</p>
                <p class="lead">
                    <a class="btn btn-primary btn-lg text-white" v-on:click="selectDeck(deck.id)" role="button">View Deck</a>
                </p>
            </div>
                            </br>
                <div class="row justify-content-center">
                            <div v-if="deck" class="col-lg-3 text-center cube-wrap">
                                  <div class="cube" v-on:click="selectDeck(deck.id)">
                                      <div class="front-pane" v-bind:style="{backgroundImage: 'url(/images/cards/' + deck.cards[0].multiverse_id + '.jpg)' }"></div>
                                      <div class="back-pane" v-bind:style="{backgroundImage: 'url(/images/cards/' + deck.cards[0].multiverse_id + '.jpg)' }"></div>
                                      <div class="top-pane" v-bind:style="{backgroundImage: 'url(/images/cards/' + deck.cards[0].multiverse_id + '.jpg)' }"></div>
                                      <div class="bottom-pane" v-bind:style="{backgroundImage: 'url(/images/cards/' + deck.cards[0].multiverse_id + '.jpg)' }"></div>
                                      <div class="left-pane" v-bind:style="{backgroundImage: 'url(/images/cards/' + deck.cards[0].multiverse_id + '.jpg)' }"></div>
                                      <div class="right-pane" v-bind:style="{backgroundImage: 'url(/images/cards/' + deck.cards[0].multiverse_id + '.jpg)' }"></div>
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
      deck: this.data.deck
    };
  },
  methods: {
      selectDeck(id) {
          // redirect using javascript to specific url
          window.location.href = '/deck/' + id + '/cards/';
      },
    viewAd() {
            window.location.href = "https://www.tcgplayer.com?partner=MAGICDB&utm_campaign=affiliate&utm_medium=MAGICDB&utm_source=RavnicaPromo";
    }
  },
  computed: {
      
  }
};
</script>
