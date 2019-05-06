<template>
	<div>{{ }}</div>
</template>

<script>
export default {
	name: 'UserProfile',
	data () {
		return {
			user: {}
		}
	},
	created () {
		this.$http.get(`http://localhost:80/matcha/public/api/user/${this.$route.params.id}`)
			.then(res => {
				this.user = res.body[0]
				console.dir(this.user)
			}).catch(err => console.error(err))
	}
}
</script>

<style>

</style>
