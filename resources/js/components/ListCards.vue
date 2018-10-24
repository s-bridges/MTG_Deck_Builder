<template>
    <div class="container">
        <div class="row justify-content-center">               
            <div class="container py-3">
                <div class="col-lg-3 col-md-3">
                    <select id="sets" class="form-control" v-model="filterBySet" v-on:change="setAPI()">
                    <option disabled value="">Search By Set</option>
                    <option v-for="option in setOptions" :value="option.set">{{option.label}}</option>
                    </select>
                </div>      
                <form>
                <div class="form-group">
                    <input v-model="searchText" type="search" class="form-control" id="search" placeholder="Search by Name">
                </div>  
                </form>
                <div v-if="filteredCards.length > 0" class="row">
                    <div class="mx-auto col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">All Cards</h4>
                            </div>
                                                         
                            <div class="row" v-for="card in filteredCards">
                                <span v-for="card in filteredCards">   
                                    <div class="col-md-2" style="padding-bottom:1em;">                                 
                                        <lazy-image 
                                            v-bind:src="'http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=' + card.multiverse_id + '&type=card'"
                                            v-bind:placeholder='/images/loading.gif'  
                                        />
                                    </div>
                                </span>
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
      filterBySet: '',
      setOptions: [
        {
          label:'Guilds of Ravnica',
          set: 'grn'
        }
      ],
      mtgSetData: {},
      searchText: '',
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
    setAPI(){
      // use the filterBySet value which the select option if the v-model of 
        axios.get(`/card/${this.filterBySet}`)
        .then(response => {
          // be able to see your response to make sure you know what to set the mtgsetdata to
            this.cards = response.data.payload;
        })
        .catch(error => {});
    },
  },
 computed: {
        filteredCards() {
            let search = this.searchText;
            let cards_array = this.cards;
            if (search != '') {
              // filter by the search field
              cards_array = _.filter(cards_array, function(card) {
                // index of finds the string within the card.name property 
                return card.name.indexOf(search) > -1;
              });

            }
            return cards_array;
        }
    }
}
</script>