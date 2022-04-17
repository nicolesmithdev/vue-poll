<template>
    <div>
        <div v-if="loading">Loading...</div>
        <ul v-else class="poll">
            <li
                v-for="post in posts"
                @click="addVote"
                :id="post.id"
                :key="post.id"
            >
                {{ post.title.rendered }}
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    emits: ['voted'],
    data() {
        return {
            loading: true,
            posts: [],
        };
    },
    beforeMount() {
        this.fetchPoll();
    },
    methods: {
        async fetchPoll() {
            var url = '/wp-json/wp/v2/posts?filter[orderby]=date';
            const response = await fetch(url);
            const data = await response.json();
            this.posts = data;
            this.loading = false;
        },
        async addVote(event) {
            const postID = event.target.id;
            const url = '/wp-json/wp/v2/posts/' + postID;

            // First get how many votes are attached to the post ID
            const response = await fetch(url);
            const data = await response.json();

            // Next increment the vote
            const increment = await fetch(url, {
                method: 'POST',
                headers: {
                    Authorization:
                        'Basic ' +
                        btoa(specialObj.appUser + ':' + specialObj.appPass),
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': specialObj.security,
                },
                body: JSON.stringify({
                    votes: ++data.votes,
                }),
            });

            if (increment) {
                localStorage.setItem('VuePoll voted', postID);
                this.$emit('voted', postID);
            } else {
                console.log('error', err);
            }
        },
    },
};
</script>
