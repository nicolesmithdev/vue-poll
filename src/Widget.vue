<template>
    <div class="poll-widget">
        <Poll v-if="!voted" @voted="onVote" />
        <Results v-else :selected-vote="vote" />
    </div>
</template>

<script>
import Poll from './components/Poll';
import Results from './components/Results';

export default {
    data() {
        return {
            voted: false,
            vote: '',
        };
    },
    components: {
        Poll,
        Results,
    },
    beforeMount() {
        let vote = localStorage.getItem('VuePoll voted');
        if (vote) {
            this.voted = true;
            this.vote = vote;
        }
    },
    methods: {
        onVote(postID) {
            this.voted = true;
            this.vote = postID;
        },
    },
};
</script>
