<template>
<v-layout column class="settings">
	<div class="parallax"></div>
	<v-layout class="py-0 strap grey lighten-3">
		<v-container py-0>
			<v-layout>
				<v-flex xs12 sm8 md4 class="avatar">
					<v-avatar slot="offset" class="mx-auto d-block" size="200">
						<img :src="profileImage" class="avatar__img">
						<div class="avatar__btn">
							<v-fab-transition>
								<v-btn color="grey lighten-5" fab small @click.stop="openEditor">
									<v-icon>add_a_photo</v-icon>
								</v-btn>
							</v-fab-transition>
						</div>
					</v-avatar>
				</v-flex>
				<profile-tabs settings :active="activeTab" @change-tab="changeTab"></profile-tabs>
			</v-layout>
		</v-container>
	</v-layout>
	<v-container fill-height grid-list-xl class="profile">
		<v-layout justify-center wrap>
			<v-flex xs12 sm8 md4>
				<profile-badge :user="user" settings></profile-badge>
			</v-flex>
			<v-flex xs12 sm10 md8 class="main pa-0 grey--text">
				<profile-tabs settings :active="activeTab" @change-tab="changeTab" mobile></profile-tabs>
				<v-tabs-items v-model="activeTab">
					<v-tab-item value="tab-profile">
						<profile-form :user="user" @sync-user="syncUser" @update-user="updateUser"></profile-form>
					</v-tab-item>
					<v-tab-item value="tab-photo">
						<profile-gallery :images="user.images"></profile-gallery>
					</v-tab-item>
					<v-tab-item value="tab-history">
						<v-container>
							<h1 class="heading display-2 text-xs-center text-md-left font-weight-thin pt-4 pb-3 mb-4 hidden-sm-and-down">History</h1>
							<v-timeline align-top dense>
								<v-timeline-item color="primary" small v-for="(entry, i) in history" :key="i">
									<v-layout pt-3>
										<v-flex xs3>
											<strong>{{ fromNow(getDate(entry)) }}</strong>
										</v-flex>
										<v-flex>
											<span v-if="entry.type !== 'follower'">{{ getHistoryAction(entry.type) }}</span>
											<router-link :to="`/user/${entry.id}`" class="timeline_link d-inline-block" :style="`width:${getMaxWidth}ch`">{{ entry.username }}</router-link>
											<span v-if="entry.type === 'follower'">{{ getHistoryAction(entry.type) }}</span>
										</v-flex>
									</v-layout>
								</v-timeline-item>
							</v-timeline>
							<v-flex xs6 offset-xs3>
								<v-btn large color="primary" flat round class="my-4" @click="limit += 5">Load More</v-btn>
							</v-flex>
						</v-container>
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
import moment from 'moment'
import utility from '../utility.js'
import ProfileForm from './ProfileForm'
import ProfileTabs from './ProfileTabs'
import ProfileBadge from './ProfileBadge'
import ProfileEditor from './ProfileEditor'
import ProfileGallery from './ProfileGallery'
import ProfileSettings from './ProfileSettings'

export default {
	name: 'Settings',
	components: {
		Alert,
		ProfileTabs,
		ProfileForm,
		ProfileBadge,
		ProfileEditor,
		ProfileGallery,
		ProfileSettings
	},
	data: () => ({
		limit: 15,
		activeTab: 'tab-profile',
		alert: {
			state: false,
			color: '',
			text: ''
		}
	}),
	created () {
		this.$store.dispatch('syncHistory', this.$store.getters.user.id)
		this.$store.dispatch('syncMatches', this.$store.getters.user.id)
	},
	computed: {
		user: {
			get: function () { return { ...this.$store.getters.user } },
			set: function (user) {},
		},
		profileImage () {
			return this.getFullPath(this.$store.getters.profileImage)
		},
		history () {
			return this.$store.getters.history
					.sort((a, b) => new Date(this.getDate(b)) - new Date(this.getDate(a)))
					.slice(0, this.limit)
		}
	},
	watch: {
		user: {
			handler: 'checkIfLoggedIn',
			immediate: true
		}
	},
	methods: {
		...utility,
		updateUser () {
			this.$http.post(`http://localhost:80/matcha/public/api/user/update/${this.user.id}`, { ...this.user })
				.then(res => {
					if (res && res.body && res.body.ok) {
						this.showAlert('success', 'Your account has been updated successfuly')
						this.$store.dispatch('updateUser', this.user)
					} else {
						this.showAlert('red', 'Ouups something went wrong!')
						console.log(res)
					}
				}).catch(err => console.error(err))
		},
		updateImage (data) {
			const fd = new FormData()
			fd.append('image', data)
			fd.append('profile', 1)
			this.$http.post(`http://localhost:80/matcha/public/api/user/image/${this.user.id}`, fd)
				.then(res => {
					if (res && res.body && res.body.ok) {
						this.showAlert('success', 'You profile image has been updated successfuly')
						this.$store.commit('updateProfileImage', res.body.name)
					} else {
						this.showAlert('red', 'Ouups something went wrong!')
						console.log(res)
					}
				}).catch(err => console.error(err))
		},
		showAlert (color, text) {
			this.alert = {
				state: true,
				color,
				text
			}
		},
		syncUser (user) {
			this.user = user
		},
		changeTab (tab) {
			this.activeTab = tab
		},
		openEditor () {
			this.$refs.profile_editor.pickFile()
		},
		checkIfLoggedIn () {
			if (!this.user.token || this.user.token != localStorage.getItem('token'))
				this.$router.push('/');
		},
		fromNow (date) {
			return moment.utc(date).fromNow()
		},
		getMaxWidth () {
			Math.max(...history.map(cur => {
				if (cur.username) return cur.username.length
			}))
		},
		getHistoryAction (type) {
			switch (type) {
				case 'visit':
					return 'You visited'
				case 'follower':
					return 'Liked you'
				case 'following':
					return 'You liked'
			}
		},
		getDate (item) {
			switch (item.type) {
				case 'visit':
					return item.visit_date
				case 'follower':
					return item.match_date
				case 'following':
					return item.match_date
			}
		}
	}
}
</script>

<style>
.timeline_link {
	text-decoration: none;
}
</style>

