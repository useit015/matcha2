<template>
<v-container>
	<div class="users">
		<v-layout row wrap>
			<v-flex xs12 md6 class="py-2" v-for="user in users" :key="user.id">
				{{ user }}
			</v-flex>
		</v-layout>
	</div>
</v-container>
</template>

<script>
export default {
	name: 'Users',
	data () {
		return {
			users: []
		}
	},
	methods: {
		fetchUsers(){
			this.$http.get('http://localhost:80/matcha/public/api/users')
				.then(res => this.users = res.body)
				.catch(err => console.error(err))
		}
	},
	created: function(){
		this.fetchUsers()
	}
}
</script>

<style scoped>

</style>
