<template>
    <div class="container">
        <div class="row justify-content-center">               
            <div class="container py-3">
              <div class="row">
                <div class="col-lg-3 col-md-3">
                    <select id="sets" class="form-control" v-model="filterBySet" v-on:change="setAPI()">
                    <option disabled value="">Search By Set</option>
                    <option v-for="option in setOptions" :value="option.set">{{option.label}}</option>
                    </select>
                </div>
                <div class="col-sm-12 col-md-9">
                  <form>
                    <div class="form-group">
                        <input v-model="searchText" type="search" class="form-control" id="search" placeholder="Search by Name">
                    </div>  
                  </form>
                </div>   
              </div>   
                <div class="row">
                    <div v-if="filteredCards.length > 0" class="mx-auto" v-bind:class="selectedCards.length > 0 ? 'col-sm-9' : 'col-sm-12'">
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
                                    <div v-for="card in paginated('paginatedCards')" class="col justify-col-center addable" style="padding-bottom:2em;" v-on:click="addCard(card)">                                 
                                        <v-lazy-image 
                                            v-bind:src="'http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=' + card.multiverse_id + '&type=card'"
                                        />
                                        <div class="overlay">
                                            <div class="text"><i class="material-icons add">add_circle</i></div>
                                        </div>
                                    </div>
                            </paginate>
                            <paginate-links :hide-single-page="true" for="paginatedCards" :show-step-links="true" 
                                :classes="{
                                    'ul': ['pagination', 'justify-content-center'],
                                    'li': 'page-item',
                                    'a': 'page-link',
                                    '.next > a': 'next-link',
                                    '.prev > a': ['prev-link', 'another-class'],
                                    '.active': 'teal'
                                }">
                            </paginate-links>
                            </div>                             
                        </div>
                        <div v-if="selectedCards.length > 0" class="col-sm-3">
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
                              Land: {{ instantLandCount }}</p>
                              <div v-for="card in myDeck">
                                <p class="deck-list"><i class="material-icons text-secondary" v-on:click="removeCard(card.card)">remove_circle</i> {{card.count}} <i class="material-icons text-primary" v-on:click="addCard(card.card)">add_circle</i> <span style="padding-left:0.5em;">{{card.name}}</span></p>
                              </div>
                              <br />
                              <button type="button" class="btn btn-primary" v-on:click="saveDeck()" :disabled="deckSubmitDisabled">Save</button>
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
      cards: [],
      paginatedCards: [],
      filterBySet: "",
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
      deckForm: {
        name: '',
        description: ''
      }
    };
  },
  methods: {
    // getCardsFromAPI() {
    //     // API stuff to mtgo for all cards
    //     axios.get({{ /card }})
    //     .then(response => {
    //         this.cards = response.data.cards;
    //     })
    // },
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
      // this is where the .post where you save selected cards to a deck
      let deckForm = this.deckForm;
      deckForm.cards = this.selectedCards;
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
    removeCard(card) {
      let index = _.findIndex(this.selectedCards, function(c) { 
          return c.multiverse_id == card.multiverse_id; 
      });
      this.selectedCards = _.filter(this.selectedCards, function(item, i){
        return i !== index;
      });
    }
  },
  computed: {
    deckSubmitDisabled() {
      return this.deckForm.name.length < 1 || this.deckForm.description.length < 1;
    },
    filteredCards() {
      let search = this.searchText;
      let cards_array = this.cards;
      if (search != "") {
        // filter by the search field, make it lowercase
        search = search.toLowerCase();
        cards_array = _.filter(cards_array, function(card) {
          // index of finds the string within the card.name property or within the card type
          return card.name.toLowerCase().indexOf(search) > -1 || card.type.toLowerCase().indexOf(search) > -1;
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
      }).value();
      return result;
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
  }
};
</script>
