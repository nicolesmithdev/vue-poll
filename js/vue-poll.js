/*
 * Poll Component
*/
var Poll = Vue.component('Poll', {
	props: ['posts'],
	template: '<ul class="poll"><li v-for="post in posts" @click="addVote" :id="post.id" :key="post.id">{{ post.title.rendered }}</li></ul>',
	methods: {
		addVote(event) {
			var postID = event.target.id;
			
			// First get how many votes are attached to the post ID
			var url = '/wp-json/wp/v2/posts/' + postID;
			fetch(url).then((response) => {
				return response.json();
			}).then((data) => {
				// Next increment the vote
				fetch(url, {
					method: 'POST',
					headers: {
						"Content-Type": "application/json",
						"X-WP-Nonce": specialObj.security
					},
					body: JSON.stringify({
						votes: ++data.votes
					})
				}).then((response) => {
					return response.json();
				}).then((data) => {
					this.$emit('voted', data.votes);
				});
			});
		}
	}
});

/*
 * Results Component
*/
var Results = Vue.component('Results', {
	data() {
		return { totalVotes: 0, posts: [] };
	},
	template: '<div class="results">\
		<ul>\
			<li v-for="post in posts" :key="post.id">\
				<span>{{ post.title.rendered }} <span>{{ percentage(post.votes) }}</span></span>\
				<span class="percentage-bar" :style="voteStyle(post.votes)"></span>\
			</li>\
		</ul>\
		<p class="total-votes">{{ getTotalVotes }} votes</p>\
	</div>',
	methods: {
		percentage(count) {
			return Math.round(( count / this.totalVotes) * 100) + '%';
		},
		voteStyle(count) {
			var percentage = (count / this.totalVotes) * 100;
			return 'width: ' + percentage + '%';
		}
	},
	computed: {
		getTotalVotes() {
			this.totalVotes = this.posts.reduce((sum, {votes}) => parseInt(sum) + parseInt(votes), 0 );
			return this.totalVotes;
		},
	},
	created: function() {
		console.log("Results mounted");
		var url='/wp-json/wp/v2/posts';
		fetch(url).then((response) => {
			return response.json();
		}).then((data) => {
			this.posts = data;
		});
	}
});

/*
 * App Component
*/
( function() {
	var vm = new Vue({
		el: document.querySelector('#app'),
		data: {
			posts: [],
			voted: false
		},
		components: {
			Poll,
			Results
		},
		template: `<div class="poll-widget">
			<Poll v-if="voted === false" :posts="posts" @voted="onVote" />
			<Results v-else/>
		</div>`,
		methods: {
			onVote() {
				this.voted = true;
			}
		},
		mounted: function() {
			var url = '/wp-json/wp/v2/posts?filter[orderby]=date';
			fetch(url).then((response) => {
				return response.json();
			}).then((data) => {
				this.posts = data;
			});
		},
	});
})();