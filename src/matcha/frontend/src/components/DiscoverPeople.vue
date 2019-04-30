<template>
	<v-container fluid grid-list-sm>
		<v-layout row justify-center align-center>
			<span>I'm looking for:</span>
			<v-layout row justify-center align-center>
				<v-btn-toggle v-model="gender">
					<v-btn flat value="male">Male</v-btn>
					<v-btn flat value="female">Female</v-btn>
				</v-btn-toggle>
				<v-flex shrink style="width: 50px">
					<v-text-field v-model="age[0]" hide-details single-line type="number"></v-text-field>
				</v-flex>
				<v-flex>
					<v-range-slider v-model="age" :max="80" :min="18" :step="1"></v-range-slider>
				</v-flex>
				<v-flex shrink style="width: 50px">
					<v-text-field v-model="age[1]" hide-details single-line type="number"></v-text-field>
				</v-flex>
				<v-text-field solo label="Prepend inner" prepend-inner-icon="place" v-model="location"></v-text-field>
				<v-btn @click="reset">Clear</v-btn>
			</v-layout>
		</v-layout>
		<v-layout row wrap justify-center>
			<v-flex class="user" v-for="user in filtered" :key="user.login.uuid" lg2 md3 sm4 xs12 ma-3 grow>
				<v-card>
					<v-img :src="user.picture.large" aspect-ratio="1"></v-img>
				</v-card>
				<v-layout column justify-center align-center>
					<h5 class="headline text-capitalize">{{ user.name.first }}</h5>
					<div class="body-1 text-capitalize mb-0">{{ `${user.location.city}, ${nats[user.nat].toLowerCase()}` }}</div>
					<p class="caption text-capitalize">{{  }}</p>
				</v-layout>
			</v-flex>
		</v-layout>
	</v-container>
</template>

<script>
import countries from '../nats.json'

export default {
	name: 'DiscoverPeople',
	data () {
		return {
			users: [],
			gender: null,
			location: null,
			age: [18, 80],
			nats: countries
		}
	},
	computed: {
		filtered () {
			let tmp = this.users.filter(val => val.dob.age >= this.age[0] && val.dob.age <= this.age[1])
			if (this.gender)
				tmp = tmp.filter(val => val.gender === this.gender)
			if (this.location)
				tmp = tmp.filter(val => this.nats[val.nat].has(this.location)
									|| val.location.state.has(this.location)
									|| val.location.city.has(this.location))
			return tmp
		}
	},
	created () {
		this.$http.get('https://randomuser.me/api/?results=100')
			.then(res => {
				this.users = res.body.results
				console.dir(this.users)
			}).catch(err => console.err(err))
	},
	methods: {
		reset () {
			this.age = [18, 80]
			this.gender = null
			this.location = null
		}
	}
}
</script>

<style>
.user {
	overflow: hidden;
}

.v-card {
	box-shadow: none;
}

.v-card__title {
	background: none;
}
</style>

