<template>
	<v-layout justify-center py-0>
		<v-flex xs12 md8>
			<h2 class="title text-capitalize mb-2">{{ `${user.first_name} ${user.last_name}` }}</h2>
			<h4 class="font-weight-thin mb-2">{{ `@${user.username}` }}</h4>
			<p class="mb-3">{{ user.biography }}</p>
			<p class="font-weight-light mb-2">
				<v-icon small>calendar_today</v-icon>
				<span class="ml-2">{{ `Joined ${formatDate(user.created_at, false)}` }}</span>
			</p>
			<p v-if="user.birthdate" class="font-weight-light">
				<v-icon small>child_care</v-icon>
				<span class="ml-2">{{ `Born ${formatDate(user.birthdate, true)}` }}</span>
			</p>
		</v-flex>
	</v-layout>
</template>

<script>
import utility from '../utility.js'
export default {
	name: 'ProfileBadge',
	props: {
		user: {
			type: Object,
			default: function() {
				return {}
			}
		}
	},
	methods: {
		...utility
	}
}
</script>
