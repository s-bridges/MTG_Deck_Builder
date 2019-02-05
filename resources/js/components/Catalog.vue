<template>
  <div v-if="cards" class="container">
    <div class="row"> 
      <div class="container py-3">
        <div class="row">
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
            <button type="button" class="btn btn-primary float-right" v-on:click="savecatalog()" title="Save">Save</button>
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
              <div class="card full-width" v-if="unHide == true">
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
                        <i v-on:click="addNewCard(card)" class="material-icons noSelect add" title="catalog">add_circle</i>
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
                  <h4 class="mb-0">{{ catalog.name }} by {{ catalog.user.username }}</h4>
                </div>
                <div class="card-body">
                  <p v-if="catalog.description">{{catalog.description}}</p>
                  <p v-else>No description.</p>
                </div>
              </div>
              </br>
              <div class="card full-width">
                <div class="card-header d-flex justify-content-between">
                  <div class="col-sm-3">
                    
                      </div>  
                </div>
                <div v-bind:class="!toggleView ? 'row' : 'card-body'">
                    <div v-if="toggleView">
                      <h5 style="margin-bottom:0.5em;">
                        <span class="badge" v-bind:class="catalogCards > 60 ? 'badge-danger' : 'badge-secondary'">{{catalogCards.length}}</span>
                      </h5>
                      
                      <br />
                    </div>
                  <div v-for="card in myCatalog.cards" v-bind:class="!toggleView ? 'col col-lg-3 text-center addable removable': ''"> 
                    <!-- when the catalog is in card view -->
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
                      <div>
                        <i v-on:click="removeCard(card)" class="material-icons noSelect clickable">remove_circle</i>
                        <i v-on:click="addNewCard(card.card)" class="material-icons noSelect clickable">add_circle</i>
                      </div>
                    </span>
                    <span v-else>
                      <span v-show="activeImage == card.multiverse_id" class="modal-image">
                        <img class="card-image"
                          v-bind:src="'/images/cards/' + card.multiverse_id + '.jpg'"
                      /></span>
                      <!-- this is what shows when the catalog is in list view -->
                      <!-- <p style="margin: 0;">{{card.count}}x {{card.name}}</p> -->
                      <p class="catalog-list">
                        <i class="material-icons noSelect text-secondary" v-on:click="removeCard(card)">remove_circle</i> 
                          {{card.count}} 
                        <i class="material-icons noSelect text-primary" v-on:click="addNewCard(card.card)">add_circle</i> 
                        <span v-on:mouseover="popOn(card.multiverse_id)" v-on:mouseout="popOff(card.multiverse_id)" style="padding-left:0.5em; cursor:pointer;">{{card.name}}</span>
                        
                      </p>
                    </span>
                  </div>
                </div>
                </div>           
                </div>
                </br>             
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
  },
  props: {
    data: {
      type: Object,
      required: false,
    }
  },
  data() {
    return {
      cards: this.data.cards,
      catalog: this.data.catalog,
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
      catalogCards: [],
      unHide: false,
      filterPowerLevel: '',
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
        .get(`/card/list-set/${this.filterBySet}`)
        .then(response => {
          // be able to see your response to make sure you know what to set the mtgsetdata to
          this.cards = response.data.payload;
        })
        .catch(error => {});
    },
    saveCatalog() {
      let catalog = this.myCatalog;
      axios
        .put(`/catalog/save`, catalog)
        .then(response => {
          alert(response.data.message);
        })
        .catch(error => {});
    },
    addNewCard(card) {
      this.catalogCards.push(card);
    },
    removeCard(card) {
      let index = _.findIndex(this.catalogCards, function(c) { 
          return c.multiverse_id == card.multiverse_id; 
      });
      this.catalogCards = _.filter(this.catalogCards, function(item, i){
        return i !== index;
      });
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
    myCatalog() {
      let catalog = this.catalog;
      let cards = this.catalogCards;
      // group the cards by the card name so we can keep track of duplicates
      catalog.cards = _.chain(cards).groupBy('name').map(function(v, i) {
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
        }
      }).orderBy(['name'], ['asc']).value();
      return catalog;
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
  }
};
</script>
