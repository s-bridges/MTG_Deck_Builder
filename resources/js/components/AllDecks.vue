<template>
    <div class="container">
    <!-- TCG ad -->
    <div class="row justify-content-center">
    <span v-on:click="viewAd()"><img src="https://tcgplayer-marketing.s3.amazonaws.com/content/magic/buy_all_singles_kb_seven_percent_affiliate_leaderboard_728x90_01252019.jpg" class="img-fluid" alt="Responsive image"></span>
    </div>
        <div class="row justify-content-center">               
            <div class="container py-3">
              <div class="row">
                <div class="col-sm-12 col-md-9">
                </div>   
              </div>   
              <div class="card-header">
                <h4 class="mb-0">All Decks</h4>
              </div>
                            </br>
                <div class="row">
                <paginate
                name="paginatedDecks"
                :list="deckList"
                :per="16"
                tag="div"
                class="row card-body"
                >
                            <div v-for="deck in paginated('paginatedDecks')" class="col-lg-3 text-center cube-wrap">
                                  <div class="cube" v-on:click="selectDeck(deck.id)">
                                      <div class="front-pane" v-bind:style="{backgroundImage: 'url(/images/cards/' + deck.cards[0].multiverse_id + '.jpg)' }"></div>
                                      <div class="back-pane" v-bind:style="{backgroundImage: 'url(/images/cards/' + deck.cards[0].multiverse_id + '.jpg)' }"></div>
                                      <div class="top-pane" v-bind:style="{backgroundImage: 'url(/images/cards/' + deck.cards[0].multiverse_id + '.jpg)' }"></div>
                                      <div class="bottom-pane" v-bind:style="{backgroundImage: 'url(/images/cards/' + deck.cards[0].multiverse_id + '.jpg)' }"></div>
                                      <div class="left-pane" v-bind:style="{backgroundImage: 'url(/images/cards/' + deck.cards[0].multiverse_id + '.jpg)' }"></div>
                                      <div class="right-pane" v-bind:style="{backgroundImage: 'url(/images/cards/' + deck.cards[0].multiverse_id + '.jpg)' }"></div>
                              </div>
                              <p>{{ deck.name }}</p>
                            </div> 
                        </paginate>
                        </div>
                        <paginate-links
                              for="paginatedDecks"
                              :classes="{
                                'ul': ['pagination', 'justify-content-center'],
                                    'li': 'page-item',
                                    'a': 'page-link'
                              }"
                              :simple="{
                                prev: 'Previous',
                                next: 'Next'
                              }"
                            ></paginate-links>                     
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
      deckList: this.data.decks,
      paginatedDecks: [],
      paginate:["paginatedDecks"]
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
