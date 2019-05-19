<template>
	<nav>
		<v-toolbar flat app>
			<v-toolbar-side-icon class="grey--text" @click="drawer = !drawer"></v-toolbar-side-icon>
			<v-toolbar-title class="text-uppercase grey--text">
				<span>M</span><span class="font-weight-light">atcha</span>
			</v-toolbar-title>
			<v-spacer></v-spacer>
			<div v-if="isLoggedIn">
				<v-btn flat color="grey" @click="logout">
					<span>Logout</span>
					<v-icon dark right>exit_to_app</v-icon>
				</v-btn>
			</div>
			<div v-if="!isLoggedIn">
				<v-btn flat color="grey" router to="/login">Login</v-btn>
				<v-btn flat color="grey" router to="/register">Sign Up</v-btn>
			</div>
		</v-toolbar>
		<v-navigation-drawer v-model="drawer" app fixed class="primary">
			<v-list>
				<v-list-tile avatar>
					<v-layout align-center justify-center v-if="isLoggedIn">
						<v-list-tile-avatar>
							<img :src="profileImage">
						</v-list-tile-avatar>
						<v-list-tile-content>
							<v-list-tile-title class="white--text text-capitalize font-weight-light subheading">{{ whoIsLoggedIn.username }}</v-list-tile-title>
						</v-list-tile-content>
					</v-layout>
					<v-list-tile-action class="ml-auto">
						<v-btn icon @click.stop="drawer = !drawer" class="ml-auto">
							<v-icon class="white--text">chevron_left</v-icon>
						</v-btn>
					</v-list-tile-action>
				</v-list-tile>
				<v-divider></v-divider>
				<v-list-tile v-for="link in links" :key="link.text" router :to="link.route" >
					<v-list-tile-action v-if="link.public || isLoggedIn">
						<v-icon class="white--text">{{ link.icon }}</v-icon>
					</v-list-tile-action>
					<v-list-tile-content v-if="link.public || isLoggedIn">
						<v-list-tile-title class="white--text">{{ link.text }}</v-list-tile-title>
					</v-list-tile-content>
				</v-list-tile>
				<v-list-tile v-if="isLoggedIn" @click="logout">
					<v-list-tile-action>
						<v-icon class="white--text">exit_to_app</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title class="white--text">Logout</v-list-tile-title>
					</v-list-tile-content>
				</v-list-tile>
			</v-list>
		</v-navigation-drawer>
	</nav>
</template>

<script>
import utility from '../utility.js'

export default {
	name: 'Navbar',
	data: () => ({
		drawer: false,
		links: [
			{ icon: 'dashboard', text: 'Home', route: '/', public: true },
			{ icon: 'people', text: 'Discover', route: '/discover', public: false },
			{ icon: 'chat', text: 'Chat', route: '/chat', public: false },
			{ icon: 'notification_important', text: 'Notifications', route: '/notifications', public: false },
			{ icon: 'settings', text: 'Settings', route: '/settings', public: false },
		]
	}),
	created () {
		this.$http.post('http://localhost:80/matcha/public/api/user/isloggedin', {
				token: localStorage.getItem("token")
			}).then(res => {
				if (res.body.tokenExpiration && Date.parse(res.body.tokenExpiration) >= Date.now()) {
					this.$store.dispatch('login', res.body)
					this.updateLocation(res.body.id)
				}
			}).catch(err => console.error(err))
	},
	computed: {
		isLoggedIn () {
			return !!this.$store.getters.status;
		},
		whoIsLoggedIn () {
			return this.$store.getters.user;
		},
		profileImage () {
			return this.getFullPath(this.$store.getters.profileImage)
		}
	},
	methods: {
		...utility,
		logout () {
			this.$http.post('http://localhost:80/matcha/public/api/user/logout')
				.then(res => res.body.ok ? this.$store.dispatch('logout') : 1)
				.catch(err => console.error(err))
		}
	}
}
</script>
