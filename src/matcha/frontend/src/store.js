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
		following: [],
		followers: [],
		blocked: [],
		blockedBy: []
	},
	getters: {
		user: state => state.user,
		status: state => state.status,
		blocked: state => state.blocked,
		location: state => state.location,
		following: state => state.following,
		followers: state => state.followers,
		blockedBy: state => state.blockedBy,
		matches: state => state.following.filter(cur => state.followers.indexOf(cur) != -1),
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
		syncMatches: (state, matches) => {
			state.followers = matches.followers
			state.following = matches.following
		},
		syncBlocked: (state, blacklist) => {
			console.log('i am the blacklist --> ', blacklist)
			state.blocked = blacklist.blocked
			state.blockedBy = blacklist.blockedBy
		}
	},
	actions: {
		updateUser: (context, user) => {
			context.dispatch('locate', user.id)
			context.commit('updateUser', user)
		},
		login:(context, user) => {
			context.dispatch('locate', user.id)
			context.dispatch('syncMatches', user.id)
			context.dispatch('syncBlocked', user.id)
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
				}, () => utility.getLocationFromIp(loc => {
					context.commit('locate', loc)
					utility.syncLocation(id, loc)
				}))
			} else {
				utility.getLocationFromIp(loc => {
					context.commit('locate', loc)
					utility.syncLocation(id, loc)
				})
			}
		},
		syncMatches: (context, id) => {
			utility.getMatches(res => context.commit('syncMatches', {
				following: res.body.filter(cur => cur.matcher == id).map(cur => cur.matched),
				followers: res.body.filter(cur => cur.matched == id).map(cur => cur.matcher)
			}), id)
		},
		syncBlocked: (context, id) => {
			utility.getBlocked(res => context.commit('syncBlocked', {
				blocked: res.body.filter(cur => cur.blocker == id).map(cur => cur.blocked),
				blockedBy: res.body.filter(cur => cur.blocked == id).map(cur => cur.blocker)
			}), id)
		}
	}
})
