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
		blockedBy: [],
		visitor: [],
		visited: []
	},
	getters: {
		user: state => state.user,
		status: state => state.status,
		history: state => {
			return [
				...state.visitor.map(cur => ({
					...cur,
					type: 'visitor'
				})),
				...state.visited.map(cur => ({
					...cur,
					type: 'visited'
				})),
				...state.followers.map(cur => ({
					...cur,
					type: 'follower'
				})),
				...state.following.map(cur => ({
					...cur,
					type: 'following'
				}))
			]
		},
		blocked: state => state.blocked,
		location: state => state.location,
		following: state => state.following,
		followers: state => state.followers,
		blockedBy: state => state.blockedBy,
		matches: state => state.following.filter(cur => {
			for (const follower of state.followers)
				if (follower.id == cur.id)
					return true
			return false
		}),
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
		syncHistory: (state, history) => {
			state.visitor = history.visitor
			state.visited = history.visited
		},
		syncMatches: (state, matches) => {
			state.followers = matches.followers
			state.following = matches.following
		},
		syncBlocked: (state, blacklist) => {
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
			context.dispatch('syncHistory', user.id)
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
			utility.getMatches(res => {
				context.commit('syncMatches', {
					following: res.body.filter(cur => cur.matched_id)
										.map(cur => ({
											id: cur.matched_id,
											match_date: cur.match_date,
											username: cur.username,
											profile_image: cur.profile_image
										})),
					followers: res.body.filter(cur => cur.matcher_id)
										.map(cur => ({
											id: cur.matcher_id,
											match_date: cur.match_date,
											username: cur.username,
											profile_image: cur.profile_image
										}))
				})
			}, id)
		},
		syncBlocked: (context, id) => {
			utility.getBlocked(res => context.commit('syncBlocked', {
				blocked: res.body.filter(cur => cur.blocker == id).map(cur => cur.blocked),
				blockedBy: res.body.filter(cur => cur.blocked == id).map(cur => cur.blocker)
			}), id)
		},
		syncHistory: (context, id) => {
			utility.syncHistory(res => context.commit('syncHistory', {
				visitor: res.body.filter(cur => cur.visitor_id)
									.map(cur => ({
										id: cur.visitor_id,
										visit_date: cur.visit_date,
										username: cur.username,
										profile_image: cur.profile_image
									})),
				visited: res.body.filter(cur => cur.visited_id)
									.map(cur => ({
										id: cur.visited_id,
										visit_date: cur.visit_date,
										username: cur.username,
										profile_image: cur.profile_image
									}))
			}), id)
		}
	}
})
