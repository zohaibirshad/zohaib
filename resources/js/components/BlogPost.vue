<template>
    <div>
        <div v-for="post in posts.data" :key="post.id">
            <a :href="slug(post.slug)" class="blog-post">
                <!-- Blog Post Thumbnail -->
                <div class="blog-post-thumbnail">
                    <div class="blog-post-thumbnail-inner">
                        <span v-for="cat in post.catgories" :key="cat.id" class="blog-item-tag">
                            {{ cat.name }}
                        </span>
                        <img :src="image(post.media)" alt="">
                    </div>
                </div>
                <!-- Blog Post Content -->
                <div class="blog-post-content">
                    <span class="blog-post-date">{{ post.created_at }}</span>
                    <h3>{{ post.title }}</h3>
                    <p>{{ post.body }}</p>
                </div>
                <!-- Icon -->
                <div class="entry-icon"></div>
            </a>
            
        </div>
        <div class="clearfix"></div>
            <pagination :data="posts" @pagination-change-page="getResults"></pagination>
    </div>
</template>
<script>
export default {

	data() {
		return {
			// Our data object that holds the Laravel paginator data
			posts: {},
		}
	},

	mounted() {
		// Fetch initial results
		this.getResults();
	},

	methods: {
        image(media){
            if(media.length > 0)
            {
                return media[0].id + '/' + media[0].file_name; 
            }

            return 'assets/images/blog-01a.jpg';
        },
        slug(slug){
            return 'blog/' + slug;
        },
		// Our method to GET results from a Laravel endpoint
		getResults(page = 1) {
			axios.get('posts/?page=' + page)
				.then(response => {
					this.posts = response.data;
				});
		}
	}

}
</script>