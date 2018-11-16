<template>
    <div class="container" style="display:flex; flex-direction:column; height:100%; min-height: 500px;">
        <div style="min-height: 800px;">
            <div style="display:flex; flex-direction:column; justify-content: space-between;">
                <div class="card health-flip" style="flex: 1; display:flex; flex-direction:column;padding:2em;">
                    <div style="flex: 5; text-align: center;">
                        <div v-if="topHealth <= 5">
                            <h1 class="card-title font-weight-bold text-danger">{{topHealth}}</h1>
                        </div>
                        <div v-else-if="bottomHealth <= 0">
                            <h1 class="card-title font-weight-bold text-success">You Win!</h1>
                        </div>
                        <div v-else>                        
                            <h1 class="card-title font-weight-bold">{{topHealth}}</h1>
                        </div>
                    </div>
                    <div style="flex: 1; display: flex; flex-direction: row; justify-content: space-between;">
                        <button type="button" class="btn btn-danger btn-lg" style="margin-right:2em;" v-on:click="topHealth -= 1">-</button>
                        <button type="button" class="btn btn-success btn-lg" v-on:click="topHealth += 1">+</button>
                    </div>
                </div>
                <div class="card" style="flex: 1; display:flex; flex-direction:column;padding:2em;">
                    <div style="flex: 5; text-align: center;">
                        <div v-if="bottomHealth <= 5">
                            <h1 class="card-title font-weight-bold text-danger">{{bottomHealth}}</h1>
                        </div>
                        <div v-else-if="topHealth <= 0">
                            <h1 class="card-title font-weight-bold text-success">You Win!</h1>
                        </div>
                        <div v-else>                        
                            <h1 class="card-title font-weight-bold">{{bottomHealth}}</h1>
                        </div>
                    </div>
                    <div style="flex: 1; display: flex; flex-direction: row; justify-content: space-between;">
                        <button type="button" class="btn btn-danger btn-lg" style="margin-right:2em;" v-on:click="bottomHealth -= 1">-</button>
                        <button type="button" class="btn btn-success btn-lg" v-on:click="bottomHealth += 1">+</button>
                    </div>
                </div>
                <span id="fakeClick" style="display:none;">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/jhFDyDgMVUI?loop=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </span>
            </div>
        </div>
    </div>   
</template>

<script>
export default {
  mounted() {
      //https://www.youtube.com/watch?v=jhFDyDgMVUI 1 second video
      // the below can pretty much stay as it calls a function every second
        this.$nextTick(function () {
            window.setInterval(() => {
                let someLink = document.querySelector('#fakeClick');
                // thiis is potentially what we change and the function we need called every second
                this.simulateClick(someLink);
            },1000);
        });
      
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
      simulateClick(elem) {
        try {
            console.log('is this working');
            var evt = document.createEvent('UIEvent');
            evt.initUIEvent('touchstart', true, true);
            // document.createEvent('TouchEvent');
        } catch (e) {
            console.log('No touching!');
        }
      },      
  },
  computed: {
      
  }
};
</script>
