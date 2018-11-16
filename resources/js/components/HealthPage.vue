<template>
    <div class="container" style="display:flex; flex-direction:column; height:100%; min-height: 500px;">
        <div style="min-height: 800px;">
            <div style="display:flex; flex-direction:column; justify-content: space-between;">
                <div class="card health-flip" style="flex: 1; display:flex; flex-direction:column;padding:2em;">
                    <div style="flex: 5; text-align: center;">
                        <div v-if="topHealth <= 5">
                            <h1 class="card-title font-weight-bold text-danger">{{topHealth}}</h1>
                        </div>
                        <div v-else>                        
                            <h1 class="card-title font-weight-bold">{{topHealth}}</h1>
                        </div>
                    </div>
                    <div style="flex: 1; display: flex; flex-direction: row; justify-content: space-between;">
                        <button type="button" class="btn btn-danger" style="margin-right:2em;" v-on:click="topHealth -= 1">-</button>
                        <button type="button" class="btn btn-success" v-on:click="topHealth += 1">+</button>
                    </div>
                </div>
                <div class="card" style="flex: 1; display:flex; flex-direction:column;padding:2em;">
                    <div style="flex: 5; text-align: center;">
                        <div v-if="bottomHealth <= 5">
                            <h1 class="card-title font-weight-bold text-danger">{{bottomHealth}}</h1>
                        </div>
                        <div v-else>                        
                            <h1 class="card-title font-weight-bold">{{bottomHealth}}</h1>
                        </div>
                    </div>
                    <div style="flex: 1; display: flex; flex-direction: row; justify-content: space-between;">
                        <button type="button" class="btn btn-danger" style="margin-right:2em;" v-on:click="bottomHealth -= 1">-</button>
                        <button type="button" class="btn btn-success" v-on:click="bottomHealth += 1">+</button>
                    </div>
                </div>
            </div>
        </div>
    </div>   
</template>

<script>
export default {
  mounted() {
      //https://www.youtube.com/watch?v=jhFDyDgMVUI 1 second video
  },
  props: {
    data: {
      type: Object,
      required: false
    }
  },
  data() {
    return {
        topHealth: 20,
        bottomHealth: 20,
    };
  }, 
  methods: {
  },
  computed: {
      
  }
};
</script>
