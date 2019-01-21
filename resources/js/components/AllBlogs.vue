<template>
    <div class="container">
    <div class="card-header">
        <h4 class="mb-0">All Blog Posts</h4>
        </div>
        <div class="row justify-content-center">        
            
            <paginate
                name="paginatedBlog"
                :list="postList"
                :per="12"
                tag="div"
                class="row card-body"
                >
                    <div v-for="posts in paginated('paginatedBlog')" class="col justify-col-center addable" style="padding-bottom:2em;">                                 
                        <div class="card" style="width: 18rem;">
                            <img class="card-image-top img-thumbnail"
                                v-bind:src="'/images/' + posts.image_url"
                            />
                            <div class="card-body">
                                <h5 class="card-title"> {{ posts.title }}</h5>
                                <p class="card-text">{{ posts.meta_description }}</p>
                                <span v-on:click=viewBlog(posts.slug) class="btn btn-primary">Go somewhere</span>
                            </div>
                        </div>                       
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
        }
    },
    computed: {

    }
};
</script>
