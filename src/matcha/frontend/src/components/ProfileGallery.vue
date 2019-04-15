<template>
<v-container grid-list-sm>
	<h1 class="heading display-2 text-xs-center text-md-left font-weight-thin pt-4 pb-3 mb-4 hidden-sm-and-down">Pictures</h1>
	<v-layout row wrap class="mt-4">
		<v-flex v-for="image in images" :key="image.id" xs4 grow>
			<img :src="`http://localhost:80/matcha/uploads/${image.name}`" class="image" width="100%" height="100%">
		</v-flex>
	</v-layout>
</v-container>
</template>

<script>
export default {
	name: 'ProfileTabs',
	data() {
		return {}
	},
	props: {
		images: { type: Array, default: function() { return [] } }
	}
}
</script>

<style>
.flex.xs4.grow {
	max-width: 100% !important;
}
</style>
