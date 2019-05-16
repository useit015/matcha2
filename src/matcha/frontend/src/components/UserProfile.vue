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
						<v-icon :color="`${user.status ? 'green' : 'grey'} lighten-2`" class="profile-status_icon mx-3 hidden-xs-only" small v-on="on">fiber_manual_record</v-icon>
					</template>
					<span>{{ lastSeen }}</span>
				</v-tooltip>
				<v-chip disabled outline small color="grey lighten-1" class="mt-3 ml-2 hidden-xs-only">{{ distance }}</v-chip>
				<v-btn icon flat large color="primary" :disabled="userCantLike" @click="match"  class="hidden-xs-only">
					<v-icon>{{ liked ? 'favorite' : 'favorite_border' }}</v-icon>
				</v-btn>
				<v-btn icon flat large color="primary" :disabled="!userCanChat" class="hidden-xs-only mx-0">
					<v-icon>{{ userCanChat ? 'chat_bubble' : 'chat_bubble_outline' }}</v-icon>
				</v-btn>
				<v-speed-dial v-model="fab" direction="bottom" transition="slide-y-reverse-transition" class="speed_list">
					<template v-slot:activator>
						<v-btn icon flat small large v-model="fab" color="primary">
							<v-icon>more_vert</v-icon>
						</v-btn>
					</template>
					<v-tooltip left>
						<template v-slot:activator="{ on }">
							<v-btn fab dark small color="primary darken-2" @click="action('reported')" v-on="on">
								<v-icon>warning</v-icon>
							</v-btn>
						</template>
						<span>Report</span>
					</v-tooltip>
					<v-tooltip left>
						<template v-slot:activator="{ on }">
							<v-btn fab dark small color="primary darken-2" @click="block" v-on="on">
								<v-icon>delete_forever</v-icon>
							</v-btn>
						</template>
						<span>Block</span>
					</v-tooltip>
				</v-speed-dial>
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
							<div v-if="user.biography">
								<h1 class="heading display-2 font-weight-thin py-3 mb-4 hidden-sm-and-down">About</h1>
								<v-container class="infos subheading py-2">
									{{ user.biography }}
								</v-container>
							</div>
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
			fab: false,
			items: [
				{ title: 'Block', handler: e => console.log('nigga1 -->', e) },
				{ title: 'Report', handler: e => console.log('nigga2 -->', e) }
			]
		}
	},
	created () {
		this.$http.get(`http://localhost:80/matcha/public/api/user/${this.$route.params.id}`)
			.then(res => {
				this.user = {
					...res.body[0],
					rating: Number(res.body[0].rating)
				}
				console.log('user here -->', this.user)
			}).catch(err => console.error(err))
	},
	watch: {
		blocked: {
			immediate: true,
			handler () {
				const id = this.$route.params.id
				if (Array.isArray(this.blocked) && Array.isArray(this.$store.getters.blockedBy))
					if (this.blocked.includes(id) || this.$store.getters.blockedBy.includes(id))
						this.$router.go(-1)
			}
		}
	},
	computed: {
		liked: {
			get () {
				for (let match of this.$store.getters.following)
					if (match == this.user.id)
						return true 
				return false
			},
			set () { this.$store.dispatch('syncMatches', this.$store.getters.user.id) }
		},
		blocked () {
			return this.$store.getters.blocked
		},
		profileImage () {
			return this.getFullPath(this.getProfileImage())
		},
		userCantLike () {
			const imgs = this.$store.getters.user.images
			return imgs ? !imgs.length : true
		},
		userCanChat () {
			return this.$store.getters.matches.includes(this.user.id)
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
			return `${Math.round(this.calculateDistance(from, to))} kms away`
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
		},
		match () {
			this.$http.post(`http://localhost:80/matcha/public/api/user/match/${this.$route.params.id}`, {
				matcher: this.$store.getters.user.id,
				liked: this.liked
			}).then(res => {
				this.liked = res.matched
			}).catch(err => console.error(err))
		},
		block () {
			this.$http.post(`http://localhost:80/matcha/public/api/user/block/${this.$route.params.id}`, {
				blocker: this.$store.getters.user.id
			}).then(res => {
				this.$store.dispatch('syncBlocked', this.$store.getters.user.id)
				this.$router.go(-1)
			}).catch(err => console.error(err))
		},
		action (s) {
			console.log(s, ' --> ', this.user.id)
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

.actions {
	box-shadow: none !important;
}

.more_icon {
	transform: scale(1.25);
}

.speed_list > .v-speed-dial__list {
	top: 4.25rem;
	z-index: 5;
}
</style>
