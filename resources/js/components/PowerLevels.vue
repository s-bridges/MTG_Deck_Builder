<template>
    <div class="container">
        <div class="row justify-content-center">               
            <div class="container py-3" style="background-color:#c1c1c1;">
                <div class="row">
                    <div class="col-lg-3 col-md-3">
                        <!-- remove set API -->
                        <select id="sets" class="form-control" v-model="filterBySet">
                        <option v-for="option in setOptions" :value="option.set">{{option.label}}</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <input v-model="searchText" type="search" class="form-control" id="search" placeholder="Search by Name">
                    </div>
                    <div class="col-sm-12 col-md-6" style="padding-bottom:.5em;">
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
                <div class="col-sm-12 col-md-9">
                </div>   
              </div> 
                <div class="card-header header-with-btn" style="background-color: #848484;">
                    <h4 class="mb-0">Add Power Levels</h4>
                    <button type="button" v-on:click="saveCards()" class="btn btn-dark btn-sm noSelect">Save Cards</button>
                </div>  
                </br>      
                    
            <paginate v-if="filteredCards.length > 0"
                name="paginatedCards"
                :list="filteredCards"
                :per="12"
                tag="div"
                class="row card-body"
                >
                    <div v-for="card in paginated('paginatedCards')" class="col" style="padding-bottom:2em; margin: 0 auto;
    text-align: center;">                                 
                        <img class="card-image"
                            v-bind:src="'/images/cards/' + card.multiverse_id + '.jpg'"
                        />
                        <div class="form-group rankings">
                            <div v-for="level in card.power_levels" style="margin: 0.5em 0.5em;">
                                <input v-model="level.pivot.ranking" v-on:change="updateCards(level, card.id)" type="number" step="0.5" min="0" max="5.0" class="form-control" v-bind:id="level.name + '_' + card.id" placeholder="2.0">
                                <small v-bind:id="level.name + '_sm_' + card.id" class="form-text text-muted" style="color:black!important;">{{level.name}}</small>
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
    </div>
</template>

<script>
export default {
    mounted() {

    },
    props: {
        data: {
        type: Object,
        required: false
        }
    },
    data() {
        return {
            cardList: this.data.cards,
            powerLevels: this.data.power_levels,
            paginatedCards: [],
            paginate: ["paginatedCards"],
            updated: this.data.power_levels,
            filterBySet: 'ALL',
            filterByColor: [],
            searchText: '',
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
        }
    },
    methods: {
        updateCards(level, cardId) {
            // set card ranking
            let card = {
                'ranking': level.pivot.ranking
            };
            // set property on updated object of the card ranking
            this.updated[level.name][cardId] = card;
        },
        saveCards() {
            // this is only the cards that are being updated
            let cards = this.updated;
            axios.post(`/admin/cards/power-levels/update`, cards)
            .then(response => {
                console.log(response);
                return response;
                // alert('Your deck was saved!');
            }).then(response => {
                this.updated = response.data.payload;
                console.log(this.updated);
                alert('Card rankings updated!');
            }).catch(error => {
                console.log(error);
                alert('error occured!');
            });
        },
        cardsBySet() {
            // create a copy of this.cards to manipulate
            let cards_array = this.cardList;
            let set = this.filterBySet;
            if (set && set != 'ALL' ) {
                console.log('filtering out by set');
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
        }   
    },
    computed: {
        filteredCards() {
            // get all the cards by the set selected, defaults to all
            let colors = this.filterByColor;
            let cards_array = this.cardsBySet();
            let search = this.searchText;
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
        }
    }
};
</script>
