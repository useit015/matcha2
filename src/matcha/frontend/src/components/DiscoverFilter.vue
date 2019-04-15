<template>
	<v-layout row justify-center align-center>
		<span>I'm looking for:</span>
		<v-layout row justify-center align-center>
			<v-btn-toggle v-model="gender">
				<v-btn flat color="indigo" value="left">
					Male
				</v-btn>
				<v-btn flat color="indigo" value="center">
					Female
				</v-btn>
			</v-btn-toggle>
			<v-text-field solo label="Prepend inner" prepend-inner-icon="place"></v-text-field>
		</v-layout>
	</v-layout>
</template>

<script>
export default {
	name: 'DiscoverFilter',
	data () {
		return {
			gender: null,
		}
	}
}
</script>
