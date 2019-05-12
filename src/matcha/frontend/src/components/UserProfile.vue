<template>
	<v-layout column class="user_profile">
	<div class="parallax"></div>
	<v-layout class="py-0 strap grey lighten-3">
		<v-container py-0>
			<v-layout>
				<v-flex xs12 sm8 md4 class="avatar">
					<v-avatar slot="offset" class="mx-auto d-block" size="200">
						<img :src="profileImage" class="avatar__img">
					</v-avatar>
				</v-flex>
				<profile-tabs :active="activeTab" @change-tab="changeTab"></profile-tabs>
				<v-tooltip bottom>
					<template v-slot:activator="{ on }">
						<v-icon :color="`${user.status ? 'green' : 'grey'} lighten-2`" class="profile-status_icon mr-3 hidden-xs-only" small v-on="on">fiber_manual_record</v-icon>
					</template>
					<span>{{ lastSeen }}</span>
				</v-tooltip>
				<v-tooltip bottom>
					<template v-slot:activator="{ on }">
						<v-btn icon flat large color="primary" :disabled="userCantLike" @click="liked = !liked"  v-on="on" class="hidden-xs-only">
							<v-icon>{{ liked ? 'favorite' : 'favorite_border' }}</v-icon>
						</v-btn>
					</template>
					<span>{{ liked ? 'unmatch' : 'match' }}</span>
				</v-tooltip>
				<v-chip disabled outline small color="grey lighten-1" class="mt-3 ml-2 hidden-xs-only">{{ distance }}</v-chip>
			</v-layout>
		</v-container>
	</v-layout>
	<v-container fill-height grid-list-xl class="profile">
		<v-layout justify-center wrap>
			<v-flex xs12 sm8 md4>
				<profile-badge :user="user"></profile-badge>
			</v-flex>
			<v-flex xs12 sm10 md8 class="pa-0 grey--text main">
				<v-tabs-items v-model="activeTab">
					<v-tab-item value="tab-profile">
						<v-container>
							<h1 class="heading display-2 font-weight-thin py-3 mb-4 hidden-sm-and-down">Informations</h1>
							<v-layout column class="title text-capitalize">
								<v-container py-3 v-for="item in informations" :key="item.label">
									<v-layout>
										<v-flex xs6>{{ `${item.label}:` }}</v-flex>
										<v-flex xs6 class="infos">{{ item.content }}</v-flex>
									</v-layout>
								</v-container>
							</v-layout>
						</v-container>
					</v-tab-item>
					<v-tab-item value="tab-photo">
						<profile-gallery :images="user.images"></profile-gallery>
					</v-tab-item>
				</v-tabs-items>
			</v-flex>
		</v-layout>
	</v-container>
</v-layout>
</template>

<script>
import moment from 'moment'
import utility from '../utility.js'
import ProfileForm from './ProfileForm'
import ProfileTabs from './ProfileTabs'
import ProfileBadge from './ProfileBadge'
import ProfileGallery from './ProfileGallery'
import ProfileSettings from './ProfileSettings'

export default {
	name: 'UserProfile',
	components: {
		ProfileTabs,
		ProfileForm,
		ProfileBadge,
		ProfileGallery,
		ProfileSettings
	},
	data () {
		return {
			activeTab: 'tab-profile',
			user: {},
			liked: false
		}
	},
	created () {
		this.$http.get(`http://localhost:80/matcha/public/api/user/${this.$route.params.id}`)
			.then(res => {
				this.user = res.body[0]
				console.log('user here -->', this.user)
			})
			.catch(err => console.error(err))
	},
	computed: {
		profileImage () {
			return this.getFullPath(this.getProfileImage())
		},
		userCantLike () {
			const imgs = this.$store.getters.user.images
			return imgs ? !imgs.length : true
		},
		age () {
			return new Date().getFullYear() - new Date(this.user.birthdate).getFullYear()
		},
		lastSeen () {
			if (this.user.status) return 'online'
			if (this.user.tokenExpiration)
				return moment(this.user.tokenExpiration).subtract(2, 'hours').fromNow()
			return moment(this.user.created_at).fromNow()
		},
		informations () {
			return [
				{ label: 'Username', content: this.user.username },
				{ label: 'Fisrt Name', content: this.user.first_name },
				{ label: 'Last Name', content: this.user.last_name },
				{ label: 'Age', content: this.age },
				{ label: 'Gender', content: this.user.gender },
				{ label: 'Looking For', content: this.user.looking },
				{ label: 'Address', content: this.user.address },
				{ label: 'City', content: this.user.city },
				{ label: 'Country', content: this.user.country },
				{ label: 'Postal Code', content: this.user.postal_code },
				{ label: 'Phone Number', content: this.user.phone }
			]
		},
		location () {
			return { ...this.$store.getters.location }
		},
		distance () {
			const from = this.location
			const to = {
				lat: this.user.lat,
				lng: this.user.lng
			}
			return `${Math.round(utility.calculateDistance(from, to))} kms away`
		},
	},
	methods: {
		...utility,
		changeTab (tab) {
			this.activeTab = tab
		},
		getProfileImage () {
			if (!this.user || !this.user.images) return 'default.jpg'
			const image = this.user.images.filter(cur => cur.profile == true)[0]
			return image ? image.name : 'default.jpg'
		}
	}
}
</script>

<style>
.profile-status_icon {
	align-self: flex-start;
	margin-top: 1.5rem;
}

.user_profile {
	height: 100%;
}

.infos {
	color:#666;
}
</style>
