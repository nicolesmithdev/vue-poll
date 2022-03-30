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
        fetchPoll() {
            var url = '/wp-json/wp/v2/posts?filter[orderby]=date';
            fetch(url)
                .then((response) => {
                    return response.json();
                })
                .then((data) => {
                    this.posts = data;
                    this.loading = false;
                });
        },
        addVote(event) {
            const postID = event.target.id;
            const url = '/wp-json/wp/v2/posts/' + postID;

            // First get how many votes are attached to the post ID
            fetch(url)
                .then((response) => {
                    return response.json();
                })
                .then((data) => {
                    // Next increment the vote
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            Authorization:
                                'Basic ' +
                                btoa(
                                    specialObj.appUser +
                                        ':' +
                                        specialObj.appPass
                                ),
                            'Content-Type': 'application/json',
                            'X-WP-Nonce': specialObj.security,
                        },
                        body: JSON.stringify({
                            votes: ++data.votes,
                        }),
                    })
                        .then((res) => {
                            localStorage.setItem('VuePoll voted', postID);
                            this.$emit('voted', postID);
                        })
                        .catch((err) => {
                            console.log('error', err);
                        });
                });
        },
    },
};
</script>
