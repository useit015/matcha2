<template>
	<div class="discover container">
		<discover-filter></discover-filter>
		<!-- <discover-people></discover-people> -->
	</div>
</template>

<script>
import DiscoverFilter from './DiscoverFilter.vue'
// import DiscoverPeople from './DiscoverPeople.vue'

export default {
	name: 'Discover',
	components: {
		DiscoverFilter,
		// DiscoverPeople
	},
	data () {
		return {

		}
	}
}
</script>
