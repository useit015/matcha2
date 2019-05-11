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
				<v-btn icon flat large color="primary" :disabled="userCantLike" @click="liked = !liked">
					<v-icon>{{ liked ? 'favorite' : 'favorite_border'}}</v-icon>
				</v-btn>
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
		}
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
.user_profile {
	height: 100%;
}
</style>
