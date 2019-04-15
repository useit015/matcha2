export default {
	getFullPath: file => `http://localhost:80/matcha/uploads/${file ? file : 'image-not-found.jpg'}`,
	formatDate: (date, long) => {
		if (!date) return ''
		const d = new Date(date)
		const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
		if (long)
			return `${months[d.getMonth()]} ${d.getDate()}, ${d.getFullYear()}`
		return `${months[d.getMonth()]} ${d.getFullYear()}`
	}
}