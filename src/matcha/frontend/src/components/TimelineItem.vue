<template>
	<v-timeline-item color="primary" small>
		<v-layout py-3 class="history_item">
			<v-flex xs3>
				<v-tooltip right>
					<template v-slot:activator="{ on }">
						<strong class="mt-2 d-block" v-on="on">{{ fromNow(getDate(entry)) }}</strong>
					</template>
					<span>{{ formatTime(entry) }}</span>
				</v-tooltip>
			</v-flex>
			<v-flex pt-0>
				<v-chip disabled class="bubble grey lighten-5 px-2 py-2">
					<router-link :to="`/user/${entry.id}`">
						<v-avatar>
							<img :src="getFullPath(entry.profile_image)" :alt="entry.username">
						</v-avatar>
					</router-link>
					<span v-if="entry.type !== 'follower' && entry.type !== 'visitor'" class="mr-1">{{ getHistoryAction(entry.type) }}</span>
					<span>
						<router-link :to="`/user/${entry.id}`" class="timeline_link">{{ entry.username }}</router-link>
					</span>
					<span v-if="entry.type === 'follower' || entry.type === 'visitor'" class="ml-1">{{ getHistoryAction(entry.type) }}</span>
					<span v-if="entry.type === 'visited'">'s profile</span>
				</v-chip>
			</v-flex>
		</v-layout>
	</v-timeline-item>
</template>

<script>
import moment from 'moment'
import utility from '../utility.js'

export default {
	name: 'TimelineItem',
	props: {
		entry: { type: Object, default: () => { return {} } }
	},
	methods: {
		...utility,
		fromNow (date) {
			return moment.utc(date).fromNow()
		},
		formatTime (entry) {
			return moment(this.getDate(entry)).format('MMM Do YY, h:mm a')
		}
	}
}
</script>

<style>
.timeline_link {
	text-decoration: none;
}

.bubble.grey {
	border-radius: 5rem;
	border: 1px solid rgba(0, 0, 0, .1) !important;
	transition: all .3s ease-out;
}

.bubble.grey:hover {
	border: 1px solid rgba(0, 0, 0, .25) !important;
}

.v-timeline-item__body {
	margin-top: -.8rem !important;
}
</style>
