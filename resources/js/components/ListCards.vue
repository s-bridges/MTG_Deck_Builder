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
                                :per="16"
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
                            </div>
                            <div class="card-body">
                              <h5 style="margin-bottom:0.5em;"><span class="badge badge-secondary">{{selectedCards.length}} / 60</span></h5>
                              <div v-for="card in selectedCards">
                                <p style="margin-bottom:0;">{{card.name}}</p>
                              </div>
                              <br />
                              <a href="#" class="btn btn-primary">Save</a>
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
      selectedCards: []
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
    addCard(card) {
      if (this.selectedCards.length < 60) {
        this.selectedCards.push(card);
      }
    }
  },
  computed: {
    filteredCards() {
      let search = this.searchText;
      let cards_array = this.cards;
      if (search != "") {
        // filter by the search field
        cards_array = _.filter(cards_array, function(card) {
          // index of finds the string within the card.name property
          return card.name.indexOf(search) > -1;
        });
      }
      return cards_array;
    }
  }
};
</script>

