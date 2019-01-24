<template>
    <div class="container">
    <!-- TCG ad -->
    <div class="row justify-content-center">
    <span v-on:click="viewAd()"><img src="https://tcgplayer-marketing.s3.amazonaws.com/content/magic/buy_all_singles_kb_seven_percent_affiliate_leaderboard_728x90_01252019.jpg" class="img-fluid" alt="Responsive image"></span>
    </div>
        <div class="row justify-content-center">               
            <div class="container py-3">
              <div class="row">
                <div class="col">
                </div>   
              </div> 
                <div class="card-header">
                <h4 class="mb-0">All Blog Posts</h4>
                </div>  
                </br>      
                    
            <paginate
                name="paginatedBlog"
                :list="postList"
                :per="12"
                tag="div"
                class="col"
                >
                
                <div v-for="posts in paginated('paginatedBlog')" class="col addable">  
                    <div class="media" v-on:click=viewBlog(posts.slug)>
                        <img v-bind:src="'/images/' + posts.image_url" class="mr-3 all-blog-img" alt="posts.title">
                        <div class="media-body">
                            <h5 class="mt-0">{{ posts.title }}</h5>
                            {{posts.meta_description}}
                        </div>
                    </div>
                    <hr/>
                </div>

                   
            </paginate>
                            
            <paginate-links
                for="paginatedBlog"
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
            posts: this.data.posts,
            postList: this.data.posts,
            paginatedCards: [],
            paginate: ["paginatedBlog"],
        }
    },
    methods: {
        viewBlog(slug) {
            window.location.href = '/blog/' + slug + '/';
        },
    viewAd() {
            window.location.href = "https://www.tcgplayer.com?partner=MAGICDB&utm_campaign=affiliate&utm_medium=MAGICDB&utm_source=RavnicaPromo";
    }
    },
    computed: {

    }
};
</script>
