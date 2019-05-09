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
			</v-layout>
		</v-container>
	</v-layout>
	<v-container fill-height grid-list-xl class="profile">
		<v-layout justify-center wrap>
			<v-flex xs12 sm8 md4>
				<profile-badge :user="user"></profile-badge>
			</v-flex>
			<v-flex xs12 sm10 md8 class="main pa-0 grey--text">
				<profile-tabs :active="activeTab" @change-tab="changeTab" mobile></profile-tabs>
				<v-tabs-items v-model="activeTab">
					<v-tab-item value="tab-profile">
						test
					</v-tab-item>
					<v-tab-item value="tab-photo">
						<profile-gallery :images="user.images"></profile-gallery>
					</v-tab-item>
					<v-tab-item value="tab-setting">
						<profile-settings></profile-settings>
					</v-tab-item>
				</v-tabs-items>
			</v-flex>
		</v-layout>
	</v-container>
	<alert :data="alert"></alert>
	<profile-editor @update-image="updateImage" ref="profile_editor"></profile-editor>
</v-layout>
</template>

<script>
import Alert from './Alert'
import ProfileForm from './ProfileForm'
import ProfileTabs from './ProfileTabs'
import ProfileBadge from './ProfileBadge'
import ProfileEditor from './ProfileEditor'
import ProfileGallery from './ProfileGallery'
import ProfileSettings from './ProfileSettings'
import utility from '../utility.js'

export default {
	name: 'UserProfile',
		components: {
		Alert,
		ProfileTabs,
		ProfileForm,
		ProfileBadge,
		ProfileEditor,
		ProfileGallery,
		ProfileSettings
	},
	data () {
		return {
			activeTab: 'tab-profile',
			user: {}
		}
	},
	created () {
		this.$http.get(`http://localhost:80/matcha/public/api/user/${this.$route.params.id}`)
			.then(res => {
				this.user = res.body[0]
				console.log('user here')
				console.log(this.user)
			})
			.catch(err => console.error(err))
	},
	computed: {
		profileImage () {
			return utility.getFullPath(this.getProfileImage())
		}
	},
	methods: {
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
.heading {
	border-bottom: 1px solid #999;
}

.parallax {
	background-image: url(https://images.pexels.com/photos/96422/pexels-photo-96422.jpeg);
	background-position: center;
	background-size: cover;
	height: 25rem;
	width: 100%;
	background-attachment: fixed;
	background-repeat: no-repeat;
}

.strap {
	height: 4rem;
}

.avatar {
	transform: translateY(-8rem);
	position: relative;
}

.avatar__btn {
	position: absolute;
	top: 85%;
	left: 85%;
	transform: translate(-50%, -50%) scale(.95);
}

.v-btn--floating:not(.v-btn--depressed):not(.v-btn--flat),
.v-btn--floating:not(.v-btn--depressed):not(.v-btn--flat):focus {
	box-shadow: 0 0 3px rgba(0, 0, 0, .2) inset;
}

.avatar__img {
	box-shadow: 0 0 0 3px #f5f5f5,
		0 1px 5px rgba(0, 0, 0, .2);
}

.profile {
	padding-top: 4.5rem;
}

.main {
	margin-top: -2.75rem;
}
</style>
