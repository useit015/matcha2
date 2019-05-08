import Vue from 'vue'

const isExternal = url => url.indexOf(':') > -1 || url.indexOf('//') > -1 || url.indexOf('www.') > -1

export default {
	getFullPath: file => isExternal(file) ? file : `http://localhost:80/matcha/uploads/${file ? file : 'default.jpg'}`,
	formatDate: (date, long) => {
		if (!date) return ''
		const d = new Date(date)
		const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
		return `${months[d.getMonth()]} ${long ? `${d.getDate()}, ` : ""}${d.getFullYear()}`
	},
	getLocationFromIp: f => {
		Vue.http.get('https://get.geojs.io/v1/ip/geo.json')
			.then(res => f(res))
			.catch(err => console.error(err))
	},
	calculateDistance: (from, to, mile) => {
		if (from.lat == to.lat && from.lng == to.lng)
			return 0
		else {
			const theta = from.lng - to.lng
			const radtheta = Math.PI * theta / 180
			from.rad = Math.PI * from.lat / 180
			to.rad = Math.PI * to.lat / 180
			let dist = Math.sin(from.rad) * Math.sin(to.rad) + Math.cos(from.rad) * Math.cos(to.rad) * Math.cos(radtheta)
			dist = dist > 1 ? 1 : dist
			dist = Math.acos(dist)
			dist = dist * 180 / Math.PI
			dist = dist * 60 * 1.1515
			return !mile ? dist * 1.609344 : dist
		}
	},
}
