<template>
    <div class="container">
        <div class="row justify-content-center">               
            <div class="container py-3">
              <div class="row">
                <div class="col-sm-12 col-md-9">
                </div>   
              </div>   
              <div class="card-header">
                                <h4 class="mb-0">My Decks</h4>
                            </div>
                <div class="row">
                            <div class="col-lg-3 col-md-3">
                                <ul class="list-group">
                                    <li v-for="deck in deckList" style="padding-bottom:2em;" class="list-group-item d-flex justify-content-between align-items-center">{{deck.name}}</li>
                                </ul>
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
      deckList: this.data.decks
    };
  },
  methods: {

  },
  computed: {
  }
};
</script>
