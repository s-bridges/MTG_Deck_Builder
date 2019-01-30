<template>
    <div class="container"> 
    <!-- TCG ad -->
    <div class="row justify-content-center">
    <span v-on:click="viewAd()"><img src="https://magicdb.us/images/rna_wbn_key_728x90_en.jpg" class="img-fluid" alt="Responsive image"></span>
    </div>
          <div class="row justify-content-center">               
            <div class="container py-3">
              <div class="row">
                <div class="col-lg-3 col-md-3">
                  <!-- remove set API -->
                    <select id="sets" class="form-control" v-model="filterBySet">
                    <option v-for="option in setOptions" :value="option.set">{{option.label}}</option>
                    </select>
                </div>
                <div class="col-sm-12 col-md-7">
                  <form>
                    <div class="form-group">
                        <input v-model="searchText" type="search" class="form-control" id="search" placeholder="Search by Name">
                    </div>  
                  </form>
                </div>
                <div class="col-sm-12 col-md-2">
                  <form class="right-align">
                    <div class="form-group">
                      <!-- reset filter button -->
                      <button v-on:click="searchText=''; filterBySet='ALL';" type="button" class="btn btn-dark noSelect">Reset</button>
                    </div>  
                  </form>
                </div>
                <div class="col-sm-12 col-md-7" style="padding-bottom:.5em;">
                <!-- White = W, Blue = U, Black = B, Red = R, Green = G, Colorless = A, Land = L -->
                  <span v-on:click="toggleFilterColors('W')">
                    <img src="/images/magic_white.png" class="img-fluid" style="width:auto;height:50px;" alt="White" v-bind:class="{ opaque: filterByColor.indexOf('W') == -1 && filterByColor.length != 0}">
                  </span>
                  <span v-on:click="toggleFilterColors('U')">
                    <img src="/images/magic_blue.png" class="img-fluid" style="width:auto;height:50px;" alt="Blue" v-bind:class="{ opaque: filterByColor.indexOf('U') == -1 && filterByColor.length != 0}">
                  </span>
                  <span v-on:click="toggleFilterColors('B')">
                    <img src="/images/magic_black.png" class="img-fluid" style="width:auto;height:50px;" alt="Black" v-bind:class="{ opaque: filterByColor.indexOf('B') == -1 && filterByColor.length != 0}">
                  </span>
                  <span v-on:click="toggleFilterColors('R')">
                    <img src="/images/magic_red.png" class="img-fluid" style="width:auto;height:50px;" alt="Red" v-bind:class="{ opaque: filterByColor.indexOf('R') == -1 && filterByColor.length != 0}">
                  </span>
                  <span v-on:click="toggleFilterColors('G')">
                    <img src="/images/magic_green.png" class="img-fluid" style="width:auto;height:50px;" alt="Green" v-bind:class="{ opaque: filterByColor.indexOf('G') == -1 && filterByColor.length != 0}">
                  </span>
                  <span v-on:click="toggleFilterColors('A')">
                    <img src="/images/magic_colorless.png" class="img-fluid" style="width:auto;height:50px;" alt="Colorless" v-bind:class="{ opaque: filterByColor.indexOf('A') == -1 && filterByColor.length != 0}">
                  </span>
                  <span v-on:click="toggleFilterColors('L')">
                    <img src="/images/magic_land.png" class="img-fluid" style="width:auto;height:50px;" alt="Land" v-bind:class="{ opaque: filterByColor.indexOf('L') == -1 && filterByColor.length != 0}">
                  </span>
                  <span v-on:click="filterByColor = []" style="height:50px;width:auto;">
                    <button type="button" class="btn btn-dark btn-sm" style="display:inline-flex;">
                      <i class="material-icons noSelect">replay</i>
                    </button>
                  </span>
                </div>    
              </div>   
                <div class="row">
                    <div v-if="filteredCards.length > 0" class="mx-auto" v-bind:class="selectedCards.length > 0 || sideboardCards.length > 0 ? 'col-sm-9' : 'col-sm-12'">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">All Cards</h4>
                            </div>
                             <paginate
                                name="paginatedCards"
                                :list="filteredCards"
                                :per="15"
                                tag="div"
                                class="row card-body"
                                >
                                    <div v-for="card in paginated('paginatedCards')" class="col justify-col-center addable" style="padding-bottom:2em;">                                 
                                        <img class="card-image"
                                            v-bind:src="'/images/cards/' + card.multiverse_id + '.jpg'"
                                        />
                                        <div class="overlay">
                                            <div class="text">
                                              <i v-on:click="addCard(card)" class="material-icons add noSelect" title="Deck">add_circle</i>
                                              <i v-on:click="addSideboardCard(card)" class="material-icons add noSelect" title="Sideboard">tab</i>
                                              <i v-on:click="viewCard(card.id)" class="material-icons add noSelect" title="View Card">launch</i>
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
                              }"
                            ></paginate-links>
                            </div>                         
                        </div>
                        <!-- show this if sideboard or selected cards have been added --> 
                        <div v-if="selectedCards.length > 0 || sideboardCards.length > 0" class="col-sm-3">
                          <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">My Deck</h4>
                                <input v-model="deckForm.name" name="name" placeholder="Deck Name" class="form-control mt-3" required>

                                <input v-model="deckForm.description" name="description" placeholder="Description" class="form-control mt-3" required>
                            </div>
                            <div class="card-body">
                              <h5 style="margin-bottom:0.5em;"><span class="badge" v-bind:class="selectedCards.length > 60 ? 'badge-danger' : 'badge-secondary'">{{selectedCards.length}} / {{maxlength}}</span></h5>
                              <p>Creature: {{ instantCreatureCount }}<br>
                              Instant/Sorcery: {{instantSorceryCount}}<br>
                              Enchantment: {{ instantEnchantmentCount }}</br>
                              Land: {{ instantLandCount }}</p>
                              <div v-for="card in myDeck">
                                <span v-show="activeImage == card.multiverse_id" class="modal-image">
                                    <img class="card-image"
                                      v-bind:src="'/images/cards/' + card.multiverse_id + '.jpg'"
                                  /></span>
                                <p class="deck-list">
                                  <i class="material-icons text-secondary noSelect" v-on:click="removeCard(card.card)">remove_circle</i> {{card.count}} 
                                  <i class="material-icons text-primary" v-on:click="addCard(card.card)">add_circle</i> 
                                  <span v-on:mouseover="popOn(card.multiverse_id)" v-on:mouseout="popOff(card.multiverse_id)" style="padding-left:0.5em; cursor:pointer;">{{card.name}} </span></p>
                              </div>
                              <hr>
                              <p v-if="mySideboard.length > 0">Sideboard</p>
                              <div v-for="card in mySideboard">
                                <span v-show="activeSideboardImage == card.multiverse_id" class="modal-image">
                                    <img class="card-image"
                                      v-bind:src="'/images/cards/' + card.multiverse_id + '.jpg'"
                                  /></span>
                                <p class="deck-list">
                                  <i class="material-icons text-secondary noSelect" v-on:click="removeSideboardCard(card.card)">remove_circle</i> 
                                  {{card.count}} <i class="material-icons text-primary" v-on:click="addSideboardCard(card.card)">add_circle</i> 
                                  <span v-on:mouseover="popOn(card.multiverse_id, true)" v-on:mouseout="popOff(card.multiverse_id, true)" style="padding-left:0.5em; cursor:pointer;">{{card.name}} </span>
                                  </p>
                              </div>

                              <br />
                              <button type="button" class="btn btn-primary" title="Save" v-on:click="saveDeck()" :disabled="deckSubmitDisabled">Save</button>
                            </div>
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
  // console logging all of the cards that your grabbed from the DB in your controller function
  },
  props: {
    data: {
      type: Object,
      required: false
    }
  },
  data() {
    return {
      cards: this.data.cards,
      paginatedCards: [],
      filterBySet: "ALL", // setting default option to be all cards
      filterByColor: [],
      activeImage: false,
      activeSideboardImage: false, 
      setOptions: [
        {
          label: "All Standard Sets",
          set: "ALL"
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
      deckForm: {
        name: '',
        description: ''
      }
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
    // setAPI() {
    //   // use the filterBySet value which the select option if the v-model of
    //   axios
    //     .get(`/card/${this.filterBySet}`)
    //     .then(response => {
    //       // be able to see your response to make sure you know what to set the mtgsetdata to
    //       this.cards = response.data.payload;
    //     })
    //     .catch(error => {});
    // },
    saveDeck() {
      // this is where the .post where you save selected cards to a deck
      let deckForm = this.deckForm;
      deckForm.cards = this.selectedCards;
      deckForm.sideboard = this.sideboardCards;
       axios
         .post(`/card/save`, this.deckForm)
         .then(response => {
             alert('Your deck was saved!');
         })
         .catch(error => {});
    },
    addCard(card) {
      this.selectedCards.push(card);
    },
    addSideboardCard(card) {
      this.sideboardCards.push(card);
    },
    removeCard(card) {
      let index = _.findIndex(this.selectedCards, function(c) { 
          return c.multiverse_id == card.multiverse_id; 
      });
      this.selectedCards = _.filter(this.selectedCards, function(item, i){
        return i !== index;
      });
    },
    removeSideboardCard(card) {
      // basically the same as above, may be able to paramterize it in the future
      let index = _.findIndex(this.sideboardCards, function(c) { 
          return c.multiverse_id == card.multiverse_id; 
      });
      this.sideboardCards = _.filter(this.sideboardCards, function(item, i){
        return i !== index;
      });
    },
    cardsBySet() {
      // create a copy of this.cards to manipulate
      let cards_array = this.cards;
      let set = this.filterBySet;
      if (set && set != 'ALL' ) {
        return _.filter(cards_array, function(card) {
          // index of finds the string within the card.name property or within the card type
          return card.set == set;
        });
      }
      // return an empty array of cards if we aren't filtering by set
      return cards_array;
    },
    toggleFilterColors(color) {
      // a function for adding or removing colors using array.push if in not in array and array.filter if in
      let colors = this.filterByColor;
      let found = colors.indexOf(color) != -1 ? true : false;
      if (!found) {
        // if not found, push color onto colors array
        colors.push(color);
      } else {
        // color was found, filter it out
        colors = _.filter(colors, function(c) {
          return c != color;
        });
      }
      this.filterByColor = colors; 
    },
    viewAd() {
            window.location.href = "https://www.tcgplayer.com?partner=MAGICDB&utm_campaign=affiliate&utm_medium=MAGICDB&utm_source=RavnicaPromo";
    },
    viewCard(id) {
      console.log(id);
            window.location.href =  '/card/' + id + '/';
    }
  },
  computed: {
    deckSubmitDisabled() {
      return this.deckForm.name.length < 1 || this.deckForm.description.length < 1;
    },
    filteredCards() {
      // check the select option that the v-model was set like this.selectedSet or whatever
      // use that down below when you are filtering, or set up separate filter. using one filter with many conditions makes it so
      // you only go through the loop once
      let search = this.searchText;
      let colors = this.filterByColor;
      // get all the cards by the set selected, defaults to all
      let cards_array = this.cardsBySet();
      // get set cards here or check for them
      if (search != "") {
        // filter by the search field, make it lowercase
        search = search.toLowerCase();
        cards_array = _.filter(cards_array, function(card) {
          // index of finds the string within the card.name property or within the card type
          return card.name.toLowerCase().indexOf(search) > -1 || card.type.toLowerCase().indexOf(search) > -1;
        });
      }
      if (colors && colors.length > 0) {
        cards_array = _.filter(cards_array, function(card){
          // remove spaces from card colors because data isn't always the same lul wizards, then split on each character
          let cardColors = card.colors.replace(/ /g, '').split("");
          let match = _.findIndex(cardColors, function(color) {
            // see if each color the card is made up with is a match in filter by colors, return as soon
            // as it's true
            return colors.indexOf(color) != -1; 
          });
          return match != -1
        });
      }
      return cards_array;
    },
    myDeck() {
      let selectedCards = this.selectedCards;
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
      }).orderBy(['name'], ['asc']).value();
      return result;
    },
    mySideboard() {
      let sideboardCards = this.sideboardCards;
      // group the cards by the card name so we can keep track of duplicates
      let result = _.chain(sideboardCards).groupBy('name').map(function(v, i) {
        // get first card out of group of the same cards and set the card data
        let cardData = _.first(v);
        return {
          name: i,
          multiverse_id: cardData.multiverse_id,
          count: v.length,
          card: cardData
        }
      }).orderBy(['name'], ['asc']).value();
      return result;
    },
    sideboardMaxLength() {
      let sideboardCards = this.sideboardCards;
      return sideboardCards.length > 15 ? sideboardCards.length: 15;
    },
    maxlength() {
      let selectedCards = this.selectedCards;
      // if card length is higher than 60, let max length be higher otherwise set it to 60
      return selectedCards.length > 60 ? selectedCards.length: 60;
    },
    instantSorceryCount() {
      let i = 0;
      let selectedCards = this.selectedCards;
      _.forEach(selectedCards, function(card) {
        if (card.type == 'Instant' || card.type == 'Sorcery') {
          // increase the count of i if instant or sorcery
          i++;
        }
      });
      return i;
    },
    instantCreatureCount() {
      let j = 0;
      let selectedCards = this.selectedCards;
      _.forEach(selectedCards, function(card){
        if(card.type.includes('Creature')){
          j++;        
          }
      });
      return j;
    },
    instantLandCount() {
      let k = 0;
      let selectedCards = this.selectedCards;
      _.forEach(selectedCards, function(card){
        if(card.type.includes('Land')){
          k++;        
          }
      });
      return k;
    },
    instantEnchantmentCount() {
      let m = 0;
      let selectedCards = this.selectedCards;
      _.forEach(selectedCards, function(card){
        if(card.type.includes('Enchantment')){
          m++;
          }
      });
      return m;
    }
  }
};
</script>