<template>
    <div class="results">
        <div v-if="loading">Loading Results...</div>
        <ul v-else>
            <li
                v-for="post in posts"
                :key="post.id"
                :class="{ selected: post.id == selectedVote }"
            >
                <span
                    >{{ post.title.rendered }}
                    <span>{{ percentage(post.votes) }}</span></span
                >
                <span
                    class="percentage-bar"
                    :style="voteStyle(post.votes)"
                ></span>
            </li>
        </ul>
        <p class="total-votes">{{ getTotalVotes }} votes</p>
    </div>
</template>

<script>
export default {
    props: {
        selectedVote: {
            type: [String, Number],
            required: true,
        },
    },
    data() {
        return {
            loading: true,
            totalVotes: 0,
            posts: [],
        };
    },
    beforeMount() {
        this.fetchPosts();
    },
    computed: {
        getTotalVotes() {
            this.totalVotes = this.posts.reduce(
                (sum, { votes }) => parseInt(sum) + parseInt(votes),
                0
            );
            return this.totalVotes;
        },
    },
    methods: {
        fetchPosts() {
            var url = '/wp-json/wp/v2/posts';
            fetch(url)
                .then((response) => {
                    return response.json();
                })
                .then((data) => {
                    this.posts = data;
                    this.loading = false;
                });
        },
        percentage(count) {
            return Math.round((count / this.totalVotes) * 100) + '%';
        },
        voteStyle(count) {
            var percentage = (count / this.totalVotes) * 100;
            return 'width: ' + percentage + '%';
        },
    },
};
</script>
