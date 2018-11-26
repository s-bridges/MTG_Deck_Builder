<template>
    <div class="container">
        <div class="row justify-content-center">               
            <div class="container py-3">
              <div class="row">
                <div class="col-lg-3 col-md-3">
                    <select id="sets" class="form-control" v-model="filterBySet" v-on:change="setAPI()">
                    <option disabled value="">Search By Standard Sets</option>
                    <option v-for="option in setOptions" :value="option.set">{{option.label}}</option>
                    </select>
                </div>
                <!-- Second Drop Down for Non-standard -->
                <div class="col-lg-3 col-md-3">
                    <select id="sets" class="form-control" v-model="filterBySet" v-on:change="setAPI()">
                    <option disabled value="">Search By Non-Standard Sets</option>
                    <option v-for="option in setOptionsNS" :value="option.set">{{option.label}}</option>
                    </select>
                </div>
                <div class="col-sm-12 col-md-6">
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
      setOptionsNS: [
        {
          label: "15th Anniversary",
          set: "15A"
        },
        {
          label: "Aether Revolt",
          set: "AER"
        },
        {
          label: "Alara Reborn",
          set: "ARB"
        },
        {
          label: "Alliances",
          set: "AL"
        },
        {
          label: "Amonkhet",
          set: "AKH"
        },
        {
          label: "Anthologies",
          set: "ANT"
        },
        {
          label: "Antiquities",
          set: "AQ"
        },
        {
          label: "Apocalypse",
          set: "AP"
        },
        {
          label: "Arabian Nights",
          set: "AN"
        },
        {
          label: "Archenemy",
          set: "ARC"
        },
        {
          label: "Archenemy 'Schemes'",
          set: "ARS"
        },
        {
          label: "Archenemy: Nicol Bolas E01",
          set: "E01"
        },
        {
          label: "Archenemy: Nicol Bolas E01S",
          set: "E01S"
        },
        {
          label: "Arena League",
          set: "ARE"
        },
        {
          label: "Asia Pacific Land Progr",
          set: "APAC"
        },
        {
          label: "Avacyn Restored",
          set: "AVR"
        },
        {
          label: "Battle for Zendikar",
          set: "BFZ"
        },
        {
          label: "Battle Royale Box Set",
          set: "BR"
        },
        {
          label: "Battle the Horde",
          set: "TBTH"
        },
        {
          label: "Battlebond",
          set: "BBD"
        },
        {
          label: "Beatdown Box Set",
          set: "BD"
        },
        {
          label: "Betrayers of Kamigawa",
          set: "BOK"
        },
        {
          label: "Born of the Gods",
          set: "BNG"
        },
        {
          label: "Celebration Cards",
          set: "CC"
        },
        {
          label: "Champions of Kamigawa",
          set: "CHK"
        },
        {
          label: "Champs",
          set: "CHA"
        },
        {
          label: "Chronicles",
          set: "CH"
        },
        {
          label: "Coldsnap",
          set: "CS"
        },
        {
          label: "Coldsnap Theme Decks",
          set: "CST"
        },
        {
          label: "Commander",
          set: "CMD"
        },
        {
          label: "Commander 2013 Edition",
          set: "C13"
        },
        {
          label: "Commander 2014 Edition",
          set: "C14"
        },
        {
          label: "Commander 2015 Edition",
          set: "C15"
        },
        {
          label: "Commander 2016 Edition",
          set: "C16"
        },
        {
          label: "Commander 2017 Edition",
          set: "C17"
        },
        {
          label: "Commander 2018 Edition",
          set: "C18"
        },
        {
          label: "Commander Anthology",
          set: "CMA"
        },
        {
          label: "Commander Anthology ...",
          set: "CM2"
        },
        {
          label: "Commander's Arsenal",
          set: "CRS"
        },
        {
          label: "Conflux",
          set: "CFX"
        },
        {
          label: "Conspiracy",
          set: "CNS"
        },
        {
          label: "Conspiracy 'Conspiraci...'",
          set: "CNSC"
        },
        {
          label: "Conspiracy: Take the C...",
          set: "CN2"
        },
        {
          label: "Conspiracy: Take the C...",
          set: "CN2C"
        },
        {
          label: "Dark Ascension",
          set: "DKA"
        },
        {
          label: "Darksteel",
          set: "DS"
        },
        {
          label: "Deckmasters",
          set: "DM"
        },
        {
          label: "Defeat a God",
          set: "TDAG"
        },
        {
          label: "Dissension",
          set: "DIS"
        },
        {
          label: "Dragon's Maze",
          set: "DGM"
        },
        {
          label: "ADVD",
          set: "ADVD"
        },
        {
          label: "AEVG",
          set: "AEVG"
        },
        {
          label: "AGVL",
          set: "AGVL"
        },
        {
          label: "AJVC",
          set: "AJVC"
        },
        {
          label: "AVB",
          set: "AVB"
        },
        {
          label: "BVC",
          set: "BVC"
        },
        {
          label: "DVD",
          set: "DVD"
        },
        {
          label: "EVK",
          set: "EVK"
        },
        {
          label: "EVT",
          set: "EVT"
        },
        {
          label: "EVG",
          set: "EVG"
        },
        {
          label: "EVI",
          set: "EVI"
        },
        {
          label: "GVL",
          set: "GVL"
        },
        {
          label: "HVM",
          set: "HVM"
        },
        {
          label: "IVG",
          set: "IVG"
        },
        {
          label: "JVC",
          set: "JVC"
        },
        {
          label: "",
          set: "JVV"
        },
        {
          label: "",
          set: "KVD"
        },
        {
          label: "",
          set: "MVG"
        },
        {
          label: "",
          set: "MVM"
        },
        {
          label: "",
          set: "NVO"
        },
        {
          label: "",
          set: "PVC"
        },
        {
          label: "",
          set: "SVT"
        },
        {
          label: "",
          set: "SVC"
        },
        {
          label: "",
          set: "VVK"
        },
        {
          label: "",
          set: "ZVE"
        },
        {
          label: "Duels of the Planeswalker",
          set: "DPW"
        },
        {
          label: "Eighth Edition",
          set: "8E"
        },
        {
          label: "Eldritch Moon",
          set: "EMN"
        },
        {
          label: "Eternal Masters",
          set: "EMA"
        },
        {
          label: "European Land Program",
          set: "EUR"
        },
        {
          label: "Eventide",
          set: "EVE"
        },
        {
          label: "Exodus",
          set: "EX"
        },
        {
          label: "Explorers of Ixalan",
          set: "E02"
        },
        {
          label: "Face the Hydra",
          set: "TFTH"
        },
        {
          label: "Fallen Empires",
          set: "FE"
        },
        {
          label: "Fate Reforged",
          set: "FRF"
        },
        {
          label: "Fifth Dawn",
          set: "FD"
        },
        {
          label: "Fifth Edition",
          set: "5E"
        },
        {
          label: "Fourth Edition",
          set: "4E"
        },
        {
          label: "Friday Night Magic",
          set: "FNM"
        },
        {
          label: "From the Vault: Angels",
          set: "V15"
        },
        {
          label: "",
          set: "V14"
        },
        {
          label: "",
          set: "V08"
        },
        {
          label: "",
          set: "V09"
        },
        {
          label: "",
          set: "V11"
        },
        {
          label: "",
          set: "V16"
        },
        {
          label: "",
          set: "V12"
        },
        {
          label: "",
          set: "V10"
        },
        {
          label: "",
          set: "V17"
        },
        {
          label: "",
          set: "V13"
        },
        {
          label: "Future Sight",
          set: "FUT"
        },
        {
          label: "Game Night",
          set: "GNT"
        },
        {
          label: "Gatecrash",
          set: "GTC"
        },
        {
          label: "Gateway",
          set: "GTW"
        },
        {
          label: "Global Series: Jiang Ya",
          set: "GS1"
        },
        {
          label: "Grand Prix",
          set: "GPX"
        },
        {
          label: "Guildpact",
          set: "GP"
        },
        {
          label: "Guilds of Ravnica GK1",
          set: "GK1"
        },
        {
          label: "GNR MED",
          set: "MED"
        },
        {
          label: "Guru",
          set: "GUR"
        },
        {
          label: "Hachette UK",
          set: "PHUK"
        },
        {
          label: "Happy Holidays",
          set: "HHO"
        },
        {
          label: "Heroes of the Realm",
          set: "HTR"
        },
        {
          label: "Hero's Path",
          set: "THP"
        },
        {
          label: "Homelands",
          set: "HL"
        },
        {
          label: "Hour of Devastation",
          set: "HOU"
        },
        {
          label: "Ice Age",
          set: "IA"
        },
        {
          label: "Iconic Masters",
          set: "IMA"
        },
        {
          label: "Innistrad",
          set: "ISD"
        },
        {
          label: "Invasion",
          set: "IN"
        },
        {
          label: "Ixalan Treasure Chest",
          set: "PXTC"
        },
        {
          label: "Journey into Nyx",
          set: "JOU"
        },
        {
          label: "Judge Gift Program",
          set: "JGP"
        },
        {
          label: "Judgment",
          set: "JU"
        },
        {
          label: "Kaladesh",
          set: "KLD"
        },
        {
          label: "Khans of Tarkir",
          set: "KTK"
        },
        {
          label: "Legacy Championship",
          set: "OLGC"
        },
        {
          label: "Legendary Cube",
          set: "PZ1"
        },
        {
          label: "Legends",
          set: "LG"
        },
        {
          label: "Legions",
          set: "LE"
        },
        {
          label: "Limited Edition Alpha",
          set: "A"
        },
        {
          label: "Limited Edition Beta",
          set: "B"
        },
        {
          label: "Lorwyn",
          set: "LRW"
        },
        {
          label: "Magic 2010 Core Set",
          set: "M10"
        },
        {
          label: "Magic 2011 Core Set",
          set: "M11"
        },
        {
          label: "Magic 2012 Core Set",
          set: "M12"
        },
        {
          label: "Magic 2013 Core Set",
          set: "M13"
        },
        {
          label: "Magic 2014 Core Set",
          set: "M14"
        },
        {
          label: "Magic 2015 Core Set",
          set: "M15"
        },
        {
          label: "Magic Online Promos",
          set: "PRM"
        },
        {
          label: "Magic Origins",
          set: "ORI"
        },
        {
          label: "Magic Player Rewards",
          set: "REW"
        },
        {
          label: "Magic Premier Shop",
          set: "MPSH"
        },
        {
          label: "",
          set: "AKHM"
        },
        {
          label: "",
          set: "KLDM"
        },
        {
          label: "",
          set: "ZENM"
        },
        {
          label: "Masters 25",
          set: "A25"
        },
        {
          label: "Masters Edition",
          set: "ME1"
        },
        {
          label: "Masters Edition II",
          set: "ME2"
        },
        {
          label: "Masters Edition III",
          set: "ME3"
        },
        {
          label: "Masters Edition IV",
          set: "ME4"
        },
        {
          label: "Media Inserts",
          set: "MDI"
        },
        {
          label: "Mercadian Masques",
          set: "MM"
        },
        {
          label: "Mirage",
          set: "MI"
        },
        {
          label: "Mirrodin",
          set: "MR"
        },
        {
          label: "Mirrodin Besieged",
          set: "MBS"
        },
        {
          label: "Modern Event Deck 20...",
          set: "MD1"
        },
        {
          label: "Modern Masters",
          set: "MMA"
        },
        {
          label: "Modern Masters 2015 ...",
          set: "MM2"
        },
        {
          label: "Modern ...",
          set: "MM3"
        },
        {
          label: "Morningtide",
          set: "MOR"
        },
        {
          label: "Nemesis",
          set: "NE"
        },
        {
          label: "New Phyrexia",
          set: "NPH"
        },
        {
          label: "Ninth Edition",
          set: "9E"
        },
        {
          label: "Oath of the Gatewatch",
          set: "OGW"
        },
        {
          label: "Odyssey",
          set: "OD"
        },
        {
          label: "Onslaught",
          set: "ON"
        },
        {
          label: "Planar Chaos",
          set: "PLC"
        },
        {
          label: "Planechase",
          set: "PCH"
        },
        {
          label: "",
          set: "PCP"
        },
        {
          label: "",
          set: "PC2"
        },
        {
          label: "",
          set: "PP2"
        },
        {
          label: "",
          set: "PCA"
        },
        {
          label: "",
          set: "PCAP"
        },
        {
          label: "Planeshift",
          set: "PS"
        },
        {
          label: "Portal",
          set: "PT"
        },
        {
          label: "Portal Second Age",
          set: "P2"
        },
        {
          label: "Portal Three Kingdoms",
          set: "P3"
        },
        {
          label: "",
          set: "PD2"
        },
        {
          label: "",
          set: "PD3"
        },
        {
          label: "",
          set: "PD1"
        },
        {
          label: "Pro Tour Promos",
          set: "PRO"
        },
        {
          label: "Prophecy",
          set: "PY"
        },
        {
          label: "Ravnica: City of Guilds",
          set: "RAV"
        },
        {
          label: "Release Promos",
          set: "RLS"
        },
        {
          label: "Renaissance",
          set: "REN"
        },
        {
          label: "Return to Ravnica",
          set: "RTR"
        },
        {
          label: "Revised Edition",
          set: "R"
        },
        {
          label: "Rise of the Eldrazi",
          set: "ROE"
        },
        {
          label: "Saviors of Kamigawa",
          set: "SOK"
        },
        {
          label: "Scards of Mirrodin",
          set: "SOM"
        },
        {
          label: "Scourge",
          set: "SC"
        },
        {
          label: "Seventh Edition",
          set: "7E"
        },
        {
          label: "Shadowmoor",
          set: "SHM"
        },
        {
          label: "Shadows over Innistrad",
          set: "SOI"
        },
        {
          label: "Shards of Alara",
          set: "ALA"
        },
        {
          label: "",
          set: "SS1"
        },
        {
          label: "Sixth Edition",
          set: "6E"
        },
        {
          label: "Starter 1999",
          set: "ST"
        },
        {
          label: "Starter 2000",
          set: "S2"
        },
        {
          label: "Stronghold",
          set: "SH"
        },
        {
          label: "Summer of Magic",
          set: "SUM"
        },
        {
          label: "Super Series",
          set: "SS"
        },
        {
          label: "Tempest",
          set: "TE"
        },
        {
          label: "Tempest Remastered",
          set: "TPR"
        },
        {
          label: "Tenth Edition",
          set: "10E"
        },
        {
          label: "The Dark",
          set: "DK"
        },
        {
          label: "Theros",
          set: "THS"
        },
        {
          label: "",
          set: "3EB"
        },
        {
          label: "Time Spiral",
          set: "TSP"
        },
        {
          label: "Time Spiral 'Timeshifted'",
          set: "TSB"
        },
        {
          label: "Torment",
          set: "TO"
        },
        {
          label: "",
          set: "2HG"
        },
        {
          label: "Ugin's Fate Promos",
          set: "UGIN"
        },
        {
          label: "Unglued",
          set: "UG"
        },
        {
          label: "Unhinged",
          set: "UNH"
        },
        {
          label: "Unlimited Edition",
          set: "U"
        },
        {
          label: "Unstable",
          set: "UST"
        },
        {
          label: "Urza's Destiny",
          set: "UD"
        },
        {
          label: "Urza's Legacy",
          set: "UL"
        },
        {
          label: "Urza's Saga",
          set: "US"
        },
        {
          label: "Vintage Championship",
          set: "OVNC"
        },
        {
          label: "Vintage Masters",
          set: "VMA"
        },
        {
          label: "Visions",
          set: "VI"
        },
        {
          label: "Weatherlight",
          set: "WL"
        },
        {
          label: "Welcome Deck 2016",
          set: "W16"
        },
        {
          label: "Welcome Deck 2017",
          set: "W17"
        },
        {
          label: "Wizards Play Network",
          set: "WPN"
        },
        {
          label: "",
          set: "WCQ"
        },
        {
          label: "Worlds",
          set: "WRL"
        },
        {
          label: "Worldwake",
          set: "WWK"
        },
        {
          label: "You make the Cube",
          set: "PZ2"
        },
        {
          label: "Zendikar",
          set: "ZEN"
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
