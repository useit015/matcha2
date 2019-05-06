import Vue from 'vue'
import Vuex, { mapGetters } from 'vuex'

Vue.use(Vuex)

export const store = new Vuex.Store({
	strict: true,
	state: {
		status: false,
		user: {},
		location: { lat: 0, lng: 0 }
	},
	getters: {
		user: state => state.user,
		status: state => state.status,
		location: state => state.location,
		profileImage: state => {
			if (!state.user.images) return ''
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
		}
	},
	actions: {
		updateUser: (context, user) => {
			context.commit('updateUser', user)
		},
		login:(context, user) => {
			localStorage.setItem('token', user.token)
			context.commit('login', user)
			context.dispatch('locate')
		},
		logout: (context) => {
			localStorage.removeItem('token')
			context.commit('logout')
		},
		locate: (context) => {
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(pos => {
					context.commit('locate', {
						lat: pos.coords.latitude,
						lng: pos.coords.longitude
					})
				})
			}
		}
	}
});
