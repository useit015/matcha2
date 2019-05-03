<template>
<div class="discover">
	<div class="filters white">
		<v-container py-0 px-5>
			<v-layout row align-center justify-space-between>
				<span>I'm looking for:</span>
				<v-flex xs8>
					<v-layout row align-center>
						<v-btn-toggle v-model="gender">
							<v-btn flat large outline color="primary" value="male">
								<svg viewBox="0 0 512 512" width="17" height="20" style="fill: var(--color-primary)">
									<path d="M376.264,290.173c66.314-66.293,66.314-174.16,0-240.453c-66.314-66.294-174.214-66.294-240.529,0 c-66.314,66.293-66.314,174.16,0,240.453c28.07,28.061,63.591,44.24,100.254,48.546v56.923h-40.018 c-11.051,0-20.009,8.956-20.009,20.003s8.958,20.003,20.009,20.003h40.018v56.348c0.001,11.047,8.959,20.003,20.011,20.003 c11.051,0,20.009-8.956,20.009-20.003v-56.348h40.019c11.051,0,20.009-8.956,20.009-20.003s-8.958-20.003-20.009-20.003h-40.019 V338.72C312.673,334.413,348.194,318.234,376.264,290.173z M164.033,261.884c-50.711-50.695-50.711-133.181,0-183.876 c50.71-50.693,133.221-50.696,183.934,0c50.711,50.695,50.711,133.181,0,183.876C297.256,312.578,214.744,312.578,164.033,261.884 z"/>
								</svg>
								<span class="px-1">Male</span>
							</v-btn>
							<v-btn flat large outline color="primary" value="female">
								<svg height="17" viewBox="0 0 384 384" width="20" style="fill: var(--color-primary)">
									<path d="m383.792969 13.9375c-.175781-1.378906-.480469-2.707031-.984375-3.953125-.015625-.03125-.015625-.074219-.023438-.113281 0-.007813-.007812-.015625-.015625-.023438-.554687-1.3125-1.3125-2.503906-2.167969-3.609375-.210937-.261719-.417968-.519531-.640624-.765625-.914063-1.03125-1.90625-1.984375-3.058594-2.761718-.03125-.023438-.070313-.03125-.101563-.054688-1.113281-.734375-2.34375-1.289062-3.632812-1.726562-.320313-.113282-.632813-.210938-.960938-.296876-1.351562-.367187-2.742187-.632812-4.207031-.632812h-112c-8.832031 0-16 7.167969-16 16s7.167969 16 16 16h73.367188l-95.496094 95.496094c-25.464844-20.367188-56.816406-31.496094-89.871094-31.496094-79.398438 0-144 64.601562-144 144s64.601562 144 144 144 144-64.601562 144-144c0-33.039062-11.121094-64.382812-31.503906-89.871094l95.503906-95.503906v73.375c0 8.832031 7.167969 16 16 16s16-7.167969 16-16v-112c0-.335938-.078125-.65625-.097656-.984375-.023438-.367187-.0625-.71875-.109375-1.078125zm-239.792969 338.0625c-61.761719 0-112-50.238281-112-112s50.238281-112 112-112c29.902344 0 58.054688 11.640625 79.222656 32.734375 21.136719 21.210937 32.777344 49.363281 32.777344 79.265625 0 61.761719-50.238281 112-112 112zm0 0"/>
								</svg>
								<span class="px-1">Female</span>
							</v-btn>
						</v-btn-toggle>
						<v-text-field v-model="age[0]" hide-details single-line type="number"></v-text-field>
						<v-range-slider v-model="age" max="80" min="18" step="5"></v-range-slider>
						<v-text-field v-model="age[1]" hide-details single-line type="number"></v-text-field>
						<v-text-field solo label="Prepend inner" prepend-inner-icon="place" v-model="location"></v-text-field>
					</v-layout>
				</v-flex>
				<v-btn @click="reset">Clear</v-btn>
			</v-layout>
		</v-container>
	</div>
	<v-container pa-5 grid-list-sm>
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
</div>
</template>

<script>
import countries from '../nats.json'

export default {
	name: 'Discover',
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
