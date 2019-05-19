<template>
	<v-container>
		<h1 class="heading display-2 text-xs-center text-md-left font-weight-thin pt-4 pb-3 mb-4 hidden-sm-and-down">History</h1>
		<v-timeline align-top dense>
			<timeline-item v-for="(entry, i) in history" :key="i" :entry="entry"/>
		</v-timeline>
		<v-flex xs6 offset-xs3>
			<v-btn v-if="moreToLoad" large color="primary" flat round class="my-4" @click="increaseLimit">Load More</v-btn>
		</v-flex>
	</v-container>
</template>

<script>
import TimelineItem from './TimelineItem'
import utility from '../utility.js'

export default {
	name: 'ProfileHistory',
	components: { TimelineItem },
	data: () => ({
		limit: 15
	}),
	computed: {
		history () {
			return this.$store.getters.history
					.filter(cur => !this.$store.getters.blocked.includes(cur.id))
					.sort((a, b) => new Date(this.getDate(b)) - new Date(this.getDate(a)))
					.slice(0, this.limit)
		},
		moreToLoad () {
			return this.limit < this.$store.getters.history.length - 1
		}
	},
	methods: {
		...utility,
		increaseLimit () {
			if (this.limit + 11 < this.$store.getters.history.length)
				this.limit += 10
			else
				this.limit = this.$store.getters.history.length - 1
		}
	}
}
</script>

<style>

</style>
