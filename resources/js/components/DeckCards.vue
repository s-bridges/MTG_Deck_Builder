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

          <div v-if="unHide == true" class="col-sm-12 col-md-7" style="padding-bottom:.5em;">
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
                  <div v-for="card in paginated('paginatedCards')" class="col justify-col-center addable" style="padding-bottom:2em;">                                 
                    <img class="card-image" 
                        v-bind:src="'/images/cards/' + card.multiverse_id + '.jpg'"
                    />
                    <div class="overlay">
                      <div class="text">
                        <i v-on:click="addNewCard(card)" class="material-icons noSelect add" title="deck">add_circle</i>
                        <i v-on:click="addNewSideboard(card)" class="material-icons noSelect add" title="sideboard">tab</i>
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
                  <h4 class="mb-0">{{ deck.name }} by {{ deck.user.username }}
                    <button type="button" class="btn btn-primary btn-sm float-right" v-on:click="downloadFile()" title="Export">Export</button>
                    <button style="margin-right:1em;" v-if="editable" type="button" class="btn btn-danger btn-sm float-right" v-on:click="deleteDeck(deck.id)" title="Delete">Delete</button></h4>
                </div>
                <div class="card-body">
                  <p v-if="deck.description">{{deck.description}}</p>
                  <p v-else>No description.</p>
                  <p>Looking to download this deck list? <a v-on:click="downloadFile()" href="#">Click here.</a> *Currently there is a bug in MTG Arena where basic lands sometimes cause issues with import.
                  </p>
                </div>
              </div>
              </br>
              <div class="card full-width">
                <div class="card-header d-flex justify-content-between">
                  <h4 class="mb-0">Main Power Level: {{calculateDeckPower}}</h4>
                  <div class="col-sm-3">
                  <select id="levels" class="form-control" v-model="filterPowerLevel">
                        <option disabled value="">Power Level by Format</option>
                        <option :alt="option.description" v-for="option in powerLevels" :value="option.name">{{option.name}}</option>
                      </select>    
                      </div>  
                </div>
                <div v-bind:class="!toggleView ? 'row' : 'card-body'">
                    <div v-if="toggleView">
                      <h5 style="margin-bottom:0.5em;">
                        <span class="badge" v-bind:class="deckCards > 60 ? 'badge-danger' : 'badge-secondary'">{{deckCards.length}} / {{maxlength}}</span>
                      </h5>
                      <p>
                        Creature: {{ instantCreatureCount }}<br>
                        Instant/Sorcery: {{instantSorceryCount}}<br>
                        Enchantment: {{ instantEnchantmentCount }}</br>
                        Land: {{ instantLandCount }}
                      </p>
                      <select id="levels" class="form-control" v-model="filterPowerLevel">
                        <option disabled value="">Power Level by Format</option>
                        <option :alt="option.description" v-for="option in powerLevels" :value="option.name">{{option.name}}</option>
                      </select>
                      <br />
                    </div>
                  <div v-for="card in myDeck.cards" v-bind:class="!toggleView ? 'col col-lg-3 text-center addable removable': ''"> 
                    <!-- when the deck is in card view -->
                    <span v-if="!toggleView"> 
                      <div v-if="card.count <= 4" style="height:40px; display:flex; justify-content:center; align-items:center">
                        <span v-for="n in card.count">
                          <i style="max-width: 24px;" class="material-icons noSelect">whatshot</i>
                        </span>
                      </div>
                      <div v-else style="height:40px; display:flex; justify-content:center; align-items:center"> 
                        <span><strong>{{card.count}}x</strong></span>
                      </div>                 
                      <img class="card-image"
                        v-bind:src="'/images/cards/' + card.multiverse_id + '.jpg'"
                      />
                      <div v-if="editable">
                        <i v-on:click="removeCard(card)" class="material-icons noSelect clickable">remove_circle</i>
                        <i v-on:click="addNewCard(card.card)" class="material-icons noSelect clickable">add_circle</i>
                      </div>
                    </span>
                    <span v-else>
                      <span v-show="activeImage == card.multiverse_id" class="modal-image">
                        <img class="card-image"
                          v-bind:src="'/images/cards/' + card.multiverse_id + '.jpg'"
                      /></span>
                      <!-- this is what shows when the deck is in list view -->
                      <!-- <p style="margin: 0;">{{card.count}}x {{card.name}}</p> -->
                      <p class="deck-list">
                        <i class="material-icons noSelect text-secondary" v-on:click="removeCard(card)">remove_circle</i> 
                          {{card.count}} 
                        <i class="material-icons noSelect text-primary" v-on:click="addNewCard(card.card)">add_circle</i> 
                        <span v-on:mouseover="popOn(card.multiverse_id)" v-on:mouseout="popOff(card.multiverse_id)" style="padding-left:0.5em; cursor:pointer;">{{card.name}}</span>
                        <span v-for="level in card.powerLevels">
                          <span v-if="level.name == filterPowerLevel">, {{level.ranking}}</span>
                        </span>
                      </p>
                    </span>
                  </div>
                </div>
                </div>
                </br>
                <!-- Sideboard -->   
              <div class="card full-width" v-if="deckSideboard.length > 0">
                <div class="card-header">
                  <h4 class="mb-0">Sideboard</h4>        
                </div>
                <div v-bind:class="!toggleView ? 'row' : 'card-body'">
                  <!-- each item in the loop of mysideboard cards is a card -->
                  <div v-for="card in myDeck.sideboard_cards" v-bind:class="!toggleView ? 'col col-lg-3 text-center addable removable': ''"> 
                    <!-- when the deck is in card view -->
                    <span v-if="!toggleView"> 
                      <div v-if="card.count <= 4" style="height:40px; display:flex; justify-content:center; align-items:center">
                        <span v-for="n in card.count">
                          <i style="max-width: 24px;" class="material-icons noSelect">whatshot</i>
                        </span>
                      </div>
                      <div v-else style="height:40px; display:flex; justify-content:center; align-items:center"> 
                        <span><strong>{{card.count}}x</strong></span>
                      </div>                 
                      <img class="card-image"
                        v-bind:src="'/images/cards/' + card.multiverse_id + '.jpg'"
                      />
                      <div v-if="editable">
                        <i v-on:click="removeSideboardCard(card)" class="material-icons noSelect clickable">remove_circle</i>
                        <i v-on:click="addNewSideboard(card.card)" class="material-icons noSelect clickable">add_circle</i>
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
                          {{card.count}} 
                        <i class="material-icons noSelect text-primary" v-on:click="addNewSideboard(card.card)">add_circle</i> 
                        <span v-on:mouseover="popOn(card.multiverse_id, true)" v-on:mouseout="popOff(card.multiverse_id, true)" style="padding-left:0.5em; cursor:pointer;">{{card.name}}</span>
                        <span v-for="level in card.powerLevels">
                          <span v-if="level.name == filterPowerLevel">, {{level.ranking}}</span>
                        </span>
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
      filterByColor: [],
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
      unHide: false,
      powerLevels: this.data.power_levels,
      filterPowerLevel: '',
      deckCards: this.data.cards,
      deckSideboard: this.data.sideboard
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
    downloadFile() {
        // <amount> <Card Name> (<Set>) <Collector Number>
        let text = '';
        _.each(this.myDeck.cards, function(card){
          // make a card string 
          let cardString = card.count + ' ' + card.name + ' (' + card.card.set + ') ' + card.card.collector_number;
          text += cardString + '\n';
        });
        // sideboard is using '\n' as separator
        text += '\n';
        _.each(this.myDeck.sideboard_cards, function(card){
          let cardString = card.count + ' ' + card.name + ' (' + card.card.set + ') ' + card.card.collector_number;
          text += cardString + '\n';
        });
        var filename = this.deck.name + '.txt';
        var blob = new Blob([text], {type: 'data:text/plain;charset=utf-8'});
        if (window.navigator && window.navigator.msSaveOrOpenBlob) {
            window.navigator.msSaveOrOpenBlob(blob, filename);
        } else{
            var e = document.createEvent('MouseEvents'),
            a = document.createElement('a');
            a.download = filename;
            a.href = window.URL.createObjectURL(blob);
            a.dataset.downloadurl = ['data:text/plain;charset=utf-8', a.download, a.href].join(':');
            e.initEvent('click', true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
            a.dispatchEvent(e);
        }
    },
    setAPI() {
      // use the filterBySet value which the select option if the v-model of
      axios
        .get(`/card/list-set/${this.filterBySet}`)
        .then(response => {
          // be able to see your response to make sure you know what to set the mtgsetdata to
          this.cards = response.data.payload;
        })
        .catch(error => {});
    },
    saveDeck() {
      let deck = this.myDeck;
      axios
        .put(`/deck/edit`, deck)
        .then(response => {
          alert(response.data.message);
        })
        .catch(error => {});
    },
    addNewCard(card) {
      this.deckCards.push(card);
    },
    addNewSideboard(card) {
      this.deckSideboard.push(card);
    },
    removeCard(card) {
      let index = _.findIndex(this.deckCards, function(c) { 
          return c.multiverse_id == card.multiverse_id; 
      });
      this.deckCards = _.filter(this.deckCards, function(item, i){
        return i !== index;
      });
    },
    removeSideboardCard(card) {
      // basically the same as above, may be able to paramterize it in the future
      let index = _.findIndex(this.deckSideboard, function(c) { 
          return c.multiverse_id == card.multiverse_id; 
      });
      this.deckSideboard = _.filter(this.deckSideboard, function(item, i){
        return i !== index;
      });
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
          console.log(error);
      });
    },
    tcgPlayer() {
      let selectedCards = this.deck.cards;
      // c is essentially your overall link you keep appending too, maybe change the variable name
      let link = '';
      _.forEach(selectedCards, function(card){
        // its better to only append in one line like so, and this variable is resent each time we go through the loop
        // because it's initialized within the loop, not outside like c was
        let tempVar = "||" + card.pivot.count.toString() + "%20" + encodeURI(card.name);
        // in one line you append what you just created above
        link = link.concat(tempVar);
      });
      // no need for a return
      let tcgLink = "http://store.tcgplayer.com/massentry?partner=MAGICDB&c=" + link;
      // lets console log before the redirect to make sure its set properly
      window.location.href = tcgLink;
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
  },
  computed: {
    sideboardMaxLength() {
      let sideboardCards = this.deck.sideboard_cards;
      return sideboardCards.length > 15 ? sideboardCards.length: 15;
    },
    maxlength() {
      let selectedCards = this.deck.cards;
      // if card length is higher than 60, let max length be higher otherwise set it to 60
      return selectedCards.length > 60 ? selectedCards.length: 60;
    },
    myDeck() {
      let deck = this.deck;
      let cards = this.deckCards;
      let sideboard = this.deckSideboard;
      // group the cards by the card name so we can keep track of duplicates
      deck.cards = _.chain(cards).groupBy('name').map(function(v, i) {
        // get first card out of group of the same cards and set the card data
        let cardData = _.first(v);
        let power_levels = _.chain(cardData.power_levels).map(function(i, k){
          return {
            name: i.name,
            ranking: i.pivot.ranking
          }
        }).keyBy('name').value();
        return {
          name: i,
          multiverse_id: cardData.multiverse_id,
          count: v.length,
          card: cardData,
          powerLevels: power_levels
        }
      }).orderBy(['name'], ['asc']).value();
      deck.sideboard_cards = _.chain(sideboard).groupBy('name').map(function(v, i) {
        // get first card out of group of the same cards and set the card data
        let cardData = _.first(v);
        let power_levels = _.chain(cardData.power_levels).map(function(i, k){
          return {
            name: i.name,
            ranking: i.pivot.ranking
          }
        }).keyBy('name').value();
        return {
          name: i,
          multiverse_id: cardData.multiverse_id,
          count: v.length,
          card: cardData,
          powerLevels: power_levels
        }
      }).orderBy(['name'], ['asc']).value();
      return deck;
    },
    calculateDeckPower() {
      let powerRanking = this.filterPowerLevel;
      if (powerRanking) {
        let cards = this.deckCards;
        let ranking = _.chain(cards).filter(function(card){
          card.power_levels = _.keyBy(card.power_levels, 'name');
          return card.power_levels && card.power_levels.hasOwnProperty(powerRanking);
        }).map(function(card){
          return parseFloat(card.power_levels[powerRanking].pivot.ranking);
        }).value();
        if (ranking.length > 0) {
          return ranking = _.round(_.sum(ranking) / ranking.length, 2);
        } else {
          return 'N/A';
        }
      } 
      return 'N/A';
    },
    filteredCards() {
      let search = this.searchText;
      let cards_array = this.cards;
      let colors = this.filterByColor;
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
    instantSorceryCount() {
      let i = 0;
      let selectedCards = this.deckCards;
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
      let selectedCards = this.deckCards;
      _.forEach(selectedCards, function(card){
        if(card.type.includes('Creature')){
          j++;        
          }
      });
      return j;
    },
    instantLandCount() {
      let k = 0;
      let selectedCards = this.deckCards;
      _.forEach(selectedCards, function(card){
        if(card.type.includes('Land')){
          k++;        
          }
      });
      return k;
    },
    instantEnchantmentCount() {
      let m = 0;
      let selectedCards = this.deckCards;
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
