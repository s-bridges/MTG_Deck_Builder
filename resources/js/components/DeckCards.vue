<template>            
  <div v-if="myDeckCards" class="container">
    <div class="row"> 
      <div class="container py-3">
        <div v-if="editable" class="row">
          <div class="col-lg-3 col-md-3">
            <select v-if="unHide == true" id="sets" class="form-control" v-model="filterBySet" v-on:change="setAPI()">
              <option disabled value="">Search By Set</option>
              <option v-for="option in setOptions" :value="option.set">{{option.label}}</option>
            </select>
          </div>
          <!-- Second Drop Down for Non-standard -->
          <div class="col-lg-3 col-md-3">
              <select v-if="unHide == true" id="sets" class="form-control" v-model="filterBySet" v-on:change="setAPI()">
              <option disabled value="">Search By Non-Standard Sets</option>
              <option v-for="option in setOptionsNS" :value="option.set">{{option.label}}</option>
              </select>
          </div>
          <div class="col-sm-12 col-md-3">
            <form v-if="unHide == true">
              <div class="form-group">
                <input v-model="searchText" type="search" class="form-control" id="search" placeholder="Search by Name">
              </div>  
            </form>
          </div>
          <div class="col-sm-12 col-md-3" style="display:flex;justify-content: space-between;">
            <button type="button" class="btn btn-danger float-right" v-on:click="unHide = !unHide">
              <span v-if="!unHide">Add Cards</span>
              <span v-else>Close</span>
            </button>            
            <button type="button" class="btn btn-primary float-right" v-on:click="saveDeck()">Save</button>
            <div class="mat-btn btn-primary" v-on:click="toggleView = !toggleView">
              <i v-if="!toggleView" class="material-icons">swap_horiz</i>
              <i v-else class="material-icons">swap_vert</i>
            </div>
          </div>
        </div>
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
                  <div v-for="card in paginated('paginatedCards')" class="col justify-col-center addable" style="padding-bottom:2em;" v-on:click="addCard(card)">                                 
                    <v-lazy-image 
                        v-bind:src="'http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=' + card.multiverse_id + '&type=card'"
                    />
                    <div class="overlay">
                      <div v-on:click="addCard(card)" class="text">
                        <i class="material-icons add">add_circle</i>
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
              <div class="card full-width">
                <div class="card-header">
                  <h4 class="mb-0">{{ deck.name }}</h4>          
                </div>
                <div v-bind:class="!toggleView ? 'row' : 'card-body'">
                  <div v-for="card in myDeckCards" v-bind:class="!toggleView ? 'col col-lg-3 text-center addable removable': ''" style="padding-top:.5em;"> 
                    <!-- when the deck is in card view -->
                    <span v-if="!toggleView"> 
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
                      <div v-if="editable">
                        <i v-on:click="removeCard(card)" class="material-icons clickable">remove_circle</i>
                        <i v-on:click="addCard(card)" class="material-icons clickable">add_circle</i>
                      </div>
                    </span>
                    <span v-else>
                      <span v-show="activeImage == card.multiverse_id" class="modal-image">
                        <v-lazy-image
                          v-bind:src="'http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=' + card.multiverse_id + '&type=card'"
                      /></span>
                      <!-- this is what shows when the deck is in list view -->
                      <!-- <p style="margin: 0;">{{card.pivot.count}}x {{card.name}}</p> -->
                      <p class="deck-list">
                        <i class="material-icons text-secondary" v-on:click="removeCard(card)">remove_circle</i> 
                          {{card.pivot.count}} 
                        <i class="material-icons text-primary" v-on:click="addCard(card)">add_circle</i> 
                        <span v-on:mouseover="popOn(card.multiverse_id)" v-on:mouseout="popOff(card.multiverse_id)" style="padding-left:0.5em; cursor:pointer;">{{card.name}}</span>
                      </p>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <!-- end second col -->
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
      deck: this.data.deck,
      editable: this.data.editable,
      cards: [],
      paginatedCards: [],
      filterBySet: "",
      toggleView: false,
      activeImage: false,
      setOptions: [
        {
          label: "Guilds of Ravnica",
          set: "GRN"
        },
        {
          label: "Core Set 2019",
          set: "M19"
        },
        {
          label: "Dominaria",
          set: "DOM"
        },
        {
          label: "Rivals of Ixalan",
          set: "RIX"
        },
        {
          label: "Ixalan",
          set: "XLN"
        }
      ],
      mtgSetData: {},
      searchText: "",
      paginate: ["paginatedCards"],
      selectedCards: [],
      unHide: false
    };
  },
  methods: {
    popOff(id) {
      // always reset active image
      this.activeImage = false;
    },
    popOn(id) {
      // need to add set timeout?
      // setTimeout(() => { 
      //   if (this.activeImage != id) {
      //     this.activeImage = id;
      //   }
      // }, 1000);
      if (this.activeImage != id) {
        this.activeImage = id;
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
          // get deck from the server that was added to and refresh it on the page
          this.deck = this.data.payload.deck;
        })
        .catch(error => {});
    },
    addCard(card) {
      // loop through the cards to see if the card exists within the deck cards array, if not found index = -1
      let index = _.findIndex(this.deck.cards, function(c) {
        return c.multiverse_id == card.multiverse_id;
      });
      // if card does not exist in deck, add it into this.deck.cards array using array push
      if (index == -1) {
        card.pivot = {
          count: 1
        };
        // now you push that card with its newly created pivot object with card property count set to 1 into your deck.cards array
        this.deck.cards.push(card);
      } else {
        this.deck.cards[index].pivot.count += 1;
      }
    },
    removeCard(card) {
      // tick the card count down
      card.pivot.count -= 1;
      // remove card entirely if it is 0
      if (card.pivot.count <= 0) {
        // find the card index in deck cards by the multiverse_id
        let index = _.findIndex(this.deck.cards, function(c) {
          return c.multiverse_id == card.multiverse_id;
        });
        // filter out the card
        this.deck.cards = _.filter(this.deck.cards, function(item, i) {
          return i !== index;
        });
      }
    }
  },
  computed: {
    myDeckCards() {
      let selectedCards = this.deck.cards;
      // if we add any filtering it will go in here
      return selectedCards;
    },
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
    }
  }
};
</script>
