import Vue from 'vue'
import Vuex, { mapGetters } from 'vuex'
import utility from './utility.js'

Vue.use(Vuex)

export const store = new Vuex.Store({
	strict: true,
	state: {
		status: false,
		user: {},
		location: { lat: 0, lng: 0 },
		following: []
	},
	getters: {
		user: state => state.user,
		status: state => state.status,
		location: state => state.location,
		following: state => state.following,
		profileImage: state => {
			if (!state.user.images) return 'default.jpg'
			const image = state.user.images.filter(cur => cur.profile == true)[0]
			return image ? image.name : 'default.jpg'
		}
	},
	mutations: {
		updateTags: (state, tags) => state.user.tags = tags.map(cur => cur.text.toLowerCase()).join(','),
		updateUser: (state, user) => state.user = user,
		updateProfileImage: (state, image) => {
			state.user.images.filter(cur => cur.profile == true).forEach(cur => cur.profile = 0)
			state.user.images.push({ name: image, profile: 1 })
		},
		logout: state => {
			state.status = false
			state.user = {}
		},
		login: (state, user) => {
			state.status = true
			state.user = user
		},
		locate: (state, location) => {
			state.location = location
		},
		getFollowing: (state, following) => {
			state.following = following
		}
	},
	actions: {
		updateUser: (context, user) => {
			context.dispatch('locate', user.id)
			context.commit('updateUser', user)
		},
		login:(context, user) => {
			context.dispatch('locate', user.id)
			context.dispatch('getFollowing', user.id)
			localStorage.setItem('token', user.token)
			context.commit('login', user)
		},
		logout: (context) => {
			localStorage.removeItem('token')
			context.commit('logout')
		},
		locate: (context, id) => {
			let loc = {}
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(pos => {
					loc = {
						lat: pos.coords.latitude,
						lng: pos.coords.longitude
					}
					context.commit('locate', loc)
					utility.syncLocation(id, loc)
				}, () => utility.getLocationFromIp(res => {
					loc = {
						lat: Number(res.body.latitude),
						lng: Number(res.body.longitude)
					}
					context.commit('locate', loc)
					utility.syncLocation(id, loc)
				}))
			} else {
				utility.getLocationFromIp(res => {
					loc = {
						lat: Number(res.body.latitude),
						lng: Number(res.body.longitude)
					}
					context.commit('locate', loc)
					utility.syncLocation(id, loc)
				})
			}
		},
		getFollowing: (context, id) => {
			utility.getFollowing(res => context.commit('getFollowing', res.body.map(cur => cur.id)), id)
		}
	}
})
