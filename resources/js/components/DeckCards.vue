<template>            
  <div v-if="deck.cards" class="container">
    <div class="row"> 
      <div class="container py-3">
        <div v-if="editable" class="row">
          <div class="col-lg-3 col-md-3">
            <select v-if="unHide == true" id="sets" class="form-control" v-model="filterBySet" v-on:change="setAPI()">
              <option disabled value="">Search By Set</option>
              <option v-for="option in setOptions" :value="option.set">{{option.label}}</option>
            </select>
          </div>
          <div class="col-sm-12 col-md-6">
            <form v-if="unHide == true">
              <div class="form-group">
                <input v-model="searchText" type="search" class="form-control" id="search" placeholder="Search by Name">
              </div>  
            </form>
          </div>
          <div class="col-sm-12 col-md-3" style="display:flex; justify-content: space-between;">
            <button type="button" class="btn btn-primary float-right" v-on:click="unHide = !unHide">
              <span v-if="!unHide" title="Add Cards">Add Cards</span>
              <span v-else>Close</span>
            </button>            
            <button type="button" class="btn btn-primary float-right" v-on:click="saveDeck()" title="Save">Save</button>
            <div class="mat-btn btn-primary" v-on:click="toggleView = !toggleView">
              <i v-if="!toggleView" class="material-icons noSelect" title="List Mode">swap_horiz</i>
              <i v-else class="material-icons noSelect" title="Image Mode">swap_vert</i>
            </div>
          </div>
        </div>
        </br>
          <div class="row">
            <!-- first col -->
            <div v-bind:class="toggleView ? 'col-lg-9' : 'col-lg-12'">
              <div class="card full-width" v-if="unHide == true && cards.length > 0">
                <div class="card-header">
                  <h4 class="mb-0">All Cards</h4>
                </div>
                <paginate
                  name="paginatedCards"
                  :list="filteredCards"
                  :per="8"
                  tag="div"
                  class="row card-body">
                  <!-- <div v-for="card in paginated('paginatedCards')" class="col justify-col-center addable" style="padding-bottom:2em;" v-on:click="addCard(card)">                                  -->
                  <div v-for="card in paginated('paginatedCards')" class="col justify-col-center addable" style="padding-bottom:2em;">                                 
                    <img class="card-image" 
                        v-bind:src="'/images/cards/' + card.multiverse_id + '.jpg'"
                    />
                    <div class="overlay">
                      <div class="text">
                        <i v-on:click="addSearchedCard(card)" class="material-icons noSelect add" title="deck">add_circle</i>
                        <i v-on:click="addSearchedSideboardCard(card)" class="material-icons noSelect add" title="sideboard">tab</i>
                      </div>
                    </div>
                  </div>
                </paginate>
                <paginate-links
                  for="paginatedCards"
                  :classes="{
                    'ul': ['pagination', 'justify-content-center'],
                        'li': 'page-item',
                        'a': 'page-link'
                  }"
                  :simple="{
                    prev: 'Previous',
                    next: 'Next'
                  }">
                </paginate-links>
              </div>                             
            </div>
            <!-- end first col -->
            <!-- second col -->
            <div v-bind:class="toggleView ? 'col-lg-3' : 'col-lg-12'">
              <div class="card full width">
                <div class="card-header">
                  <h4 class="mb-0">{{ deck.name }} by {{ deck.user.username }}<button v-if="editable" type="button" class="btn btn-danger btn-sm float-right" v-on:click="deleteDeck(deck.id)" title="Delete">Delete</button></h4>
                </div>
                <div class="card-body">
                  <p v-if="deck.description">{{deck.description}}</p>
                  <p v-else>No description.</p>
                </div>
              </div>
              </br>
              <div class="card full-width">
                <div class="card-header">
                  <h4 class="mb-0">Main</h4>        
                </div>
                <div v-bind:class="!toggleView ? 'row' : 'card-body'">
                  <div v-for="card in deck.cards" v-bind:class="!toggleView ? 'col col-lg-3 text-center addable removable': ''"> 
                    <!-- when the deck is in card view -->
                    <span v-if="!toggleView"> 
                      <div v-if="card.pivot.count <= 4" style="height:40px; display:flex; justify-content:center; align-items:center">
                        <span v-for="n in card.pivot.count">
                          <i style="max-width: 24px;" class="material-icons noSelect">whatshot</i>
                        </span>
                      </div>
                      <div v-else style="height:40px; display:flex; justify-content:center; align-items:center"> 
                        <span><strong>{{card.pivot.count}}x</strong></span>
                      </div>                 
                      <img class="card-image"
                        v-bind:src="'/images/cards/' + card.multiverse_id + '.jpg'"
                      />
                      <div v-if="editable">
                        <i v-on:click="removeCard(card)" class="material-icons noSelect clickable">remove_circle</i>
                        <i v-on:click="addCard(card)" class="material-icons noSelect clickable">add_circle</i>
                      </div>
                    </span>
                    <span v-else>
                      <span v-show="activeImage == card.multiverse_id" class="modal-image">
                        <img class="card-image"
                          v-bind:src="'/images/cards/' + card.multiverse_id + '.jpg'"
                      /></span>
                      <!-- this is what shows when the deck is in list view -->
                      <!-- <p style="margin: 0;">{{card.pivot.count}}x {{card.name}}</p> -->
                      <p class="deck-list">
                        <i class="material-icons noSelect text-secondary" v-on:click="removeCard(card)">remove_circle</i> 
                          {{card.pivot.count}} 
                        <i class="material-icons noSelect text-primary" v-on:click="addCard(card)">add_circle</i> 
                        <span v-on:mouseover="popOn(card.multiverse_id)" v-on:mouseout="popOff(card.multiverse_id)" style="padding-left:0.5em; cursor:pointer;">{{card.name}}</span>
                      </p>
                    </span>
                  </div>
                </div>
                </div>
                </br>
                <!-- Sideboard -->   
              <div class="card full-width" v-if="deck.sideboard_cards.length > 0">
                <div class="card-header">
                  <h4 class="mb-0">Sideboard</h4>        
                </div>
                <div v-bind:class="!toggleView ? 'row' : 'card-body'">
                  <!-- each item in the loop of mysideboard cards is a card -->
                  <div v-for="card in deck.sideboard_cards" v-bind:class="!toggleView ? 'col col-lg-3 text-center addable removable': ''"> 
                    <!-- when the deck is in card view -->
                    <span v-if="!toggleView"> 
                      <div v-if="card.pivot.count <= 4" style="height:40px; display:flex; justify-content:center; align-items:center">
                        <span v-for="n in card.pivot.count">
                          <i style="max-width: 24px;" class="material-icons noSelect">whatshot</i>
                        </span>
                      </div>
                      <div v-else style="height:40px; display:flex; justify-content:center; align-items:center"> 
                        <span><strong>{{card.pivot.count}}x</strong></span>
                      </div>                 
                      <img class="card-image"
                        v-bind:src="'/images/cards/' + card.multiverse_id + '.jpg'"
                      />
                      <div v-if="editable">
                        <i v-on:click="removeSideboardCard(card)" class="material-icons noSelect clickable">remove_circle</i>
                        <i v-on:click="addCard(card)" class="material-icons noSelect clickable">add_circle</i>
                      </div>
                    </span>
                    <span v-else>
                      <span v-show="activeSideboardImage == card.multiverse_id" class="modal-image">
                        <img class="card-image"
                          v-bind:src="'/images/cards/' + card.multiverse_id + '.jpg'"
                      /></span>
                      <!-- this is what shows when the deck is in list view -->
                      <!-- <p style="margin: 0;">{{sideboardCards.count}}x {{card.name}}</p> -->
                      <p class="deck-list">
                        <i class="material-icons noSelect text-secondary" v-on:click="removeSideboardCard(card)">remove_circle</i> 
                          {{card.pivot.count}} 
                        <i class="material-icons noSelect text-primary" v-on:click="addCard(card)">add_circle</i> 
                        <span v-on:mouseover="popOn(card.multiverse_id, true)" v-on:mouseout="popOff(card.multiverse_id, true)" style="padding-left:0.5em; cursor:pointer;">{{card.name}}</span>
                      </p>
                    </span>
                  </div>
                </div>
                </div>
                </div>
                </br>             
            </div> 
            </div>            
            </br>
            <span v-on:click="tcgPlayer()" type="button" class="btn btn-primary btn-lg btn-block">Buy here through TCGPlayer</span>
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
      required: false,
    }
  },
  data() {
    return {
      deck: this.data.deck,
      editable: this.data.editable,
      cards: [],
      paginatedCards: [],
      filterBySet: "",
      toggleView: false,
      activeImage: false,
      activeSideboardImage: false,
      setOptions: [
        {
          label: "Core Set 2019",
          set: "M19"
        },
        {
          label: "Dominaria",
          set: "DOM"
        },        
        {
          label: "Guilds of Ravnica",
          set: "GRN"
        },
        {
          label: "Ixalan",
          set: "XLN"
        },
        {
          label: "Ravnica Allegiance",
          set: "RNA"
        },
        {
          label: "Rivals of Ixalan",
          set: "RIX"
        }
      ],
      mtgSetData: {},
      searchText: "",
      paginate: ["paginatedCards"],
      selectedCards: [],
      sideboardCards: [],
      unHide: false
    };
  }, 
  methods: {
    popOff(id, isSideboard = false) {
      // always reset active image
      this.activeImage = false;
      this.activeSideboardImage = false;
    },
    popOn(id, isSideboard = false) {
      if (this.activeImage != id && !isSideboard) {
        this.activeImage = id;
      }
      if (this.activeSideboardImage != id && isSideboard) {
        this.activeSideboardImage = id;
      }
    },
    setAPI() {
      // use the filterBySet value which the select option if the v-model of
      axios
        .get(`/card/${this.filterBySet}`)
        .then(response => {
          // be able to see your response to make sure you know what to set the mtgsetdata to
          this.cards = response.data.payload;
        })
        .catch(error => {});
    },
    saveDeck() {
      let deck = this.deck;
      axios
        .put(`/deck/edit`, deck)
        .then(response => {
          alert(response.data.message);
        })
        .catch(error => {});
    },
    addCard(card) {
      // this is used when a card exists in the deck already, not in search view
      // if card has property of pivot, card.pivot.count += 1 else, pivot.count = 1
      if (_.has(card, 'pivot')){
        card.pivot.count += 1;
      } else {
        card.pivot.count = 1;
      }
      console.log(card);
    },
    addSearchedCard(card) {
      // find the searched card in sideboard_cards
        let index = _.findIndex(this.deck.cards, function(c){
          return c.multiverse_id == card.multiverse_id;
        });
        if (index != -1) {
          this.addCard(this.deck.cards[index]);
          // this force updates the components to reflect the recently added card
          // this.$forceUpdate();
        } else {
          let cloneCard = card;
          cloneCard.pivot = {};
          cloneCard.pivot.count = 1;
          this.deck.cards.push(cloneCard);
        }
    },
    addSearchedSideboardCard(card) {
      // find the searched card in sideboard_cards
        let index = _.findIndex(this.deck.sideboard_cards, function(c){
          return c.multiverse_id == card.multiverse_id;
        });
        if (index != -1) {
          this.addCard(this.deck.sideboard_cards[index]);
          // this force updates the components to reflect the recently added card
          // this.$forceUpdate();
        } else {
          let cloneCard = card;
          cloneCard.pivot = {};
          cloneCard.pivot.count = 1;
          this.deck.sideboard_cards.push(cloneCard);
        }
    },
    removeCard(card) {
      // tick the card count down
      card.pivot.count -= 1;
      // remove card entirely if it is 0
      if (card.pivot.count <= 0) {
        // if the card count reached 0, filter it out of the deck cards
        this.deck.cards = _.filter(this.deck.cards, function(c) {
          return c.multiverse_id != card.multiverse_id;
        });
      }
    },
    removeSideboardCard(card) {
      // you pass in card up here, everything you reference should be card, that's why it's undefined
        card.pivot.count -= 1;
        if(card.pivot.count <= 0) {
          // efficiency! if the card count reached 0, filter it out of the sideboard
            this.deck.sideboard_cards = _.filter(this.deck.sideboard_cards, function(c) {
             return c.multiverse_id != card.multiverse_id;
            });
        }
    },
    deleteDeck(deckId){
      axios.delete(`/deck/${deckId}/delete`)
      .then(response => {
        // if successful redirect user, maybe show a toast
        if (response.data.status) {
          window.location = '/deck';         
        }
      })
      .catch(error => {
          
      });
    },
    tcgPlayer() {
      let selectedCards = this.deck.cards;
      console.log(selectedCards);
      // c is essentially your overall link you keep appending too, maybe change the variable name
      let link = '';
      _.forEach(selectedCards, function(card){
        // its better to only append in one line like so, and this variable is resent each time we go through the loop
        // because it's initialized within the loop, not outside like c was
        let tempVar = "||" + card.pivot.count.toString() + "%20" + encodeURI(card.name);
        // in one line you append what you just created above
        link = link.concat(tempVar);
        console.log(link);
      });
      // no need for a return
      let tcgLink = "http://store.tcgplayer.com/massentry?partner=MAGICDB&c=" + link;
      // lets console log before the redirect to make sure its set properly
      console.log(tcgLink);
      console.log(link);
      window.location.href = tcgLink;
    }
  },
  computed: {
    // myDeckCards() {
    //   let selectedCards = this.deck.cards;
    //   // if we add any filtering it will go in here
    //   return selectedCards;
    // },
    // mySideboardCards() {
    //   let sideboardCards = this.deck.sideboard_cards; // I think it's sideboard_cards
    //   return sideboardCards;
    // },
    filteredCards() {
      let search = this.searchText;
      let cards_array = this.cards;
      if (search != "") {
        // filter by the search field, make it lowercase
        search = search.toLowerCase();
        cards_array = _.filter(cards_array, function(card) {
          // index of finds the string within the card.name property or within the card type
          return (
            card.name.toLowerCase().indexOf(search) > -1 ||
            card.type.toLowerCase().indexOf(search) > -1
          );
        });
      }
      return cards_array;
    },    
  }
};
</script>
