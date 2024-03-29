<template>
<v-container>
	<v-btn class="edit" color="grey lighten-5" fab small @click="isEditing = !isEditing">
		<v-icon v-if="isEditing">close</v-icon>
		<v-icon v-else>edit</v-icon>
	</v-btn>
	<h1 class="heading display-2 font-weight-thin pt-4 pb-3 mb-4 hidden-sm-and-down">Informations</h1>
	<v-form class="mt-4">
		<v-layout wrap>
			<v-flex xs12>
				<v-text-field :disabled="!isEditing" color="primary" label="Username" v-model="user.username"/>
			</v-flex>
			<v-flex xs12 sm6>
				<v-text-field :disabled="!isEditing" label="First Name" color="primary" v-model="user.first_name"/>
			</v-flex>
			<v-flex xs12 sm6>
				<v-text-field :disabled="!isEditing" label="Last Name" color="primary" v-model="user.last_name"/>
			</v-flex>
			<v-flex xs12 sm6>
				<v-text-field :disabled="!isEditing" label="Phone Number" color="primary" v-model="user.phone"/>
			</v-flex>
			<v-flex xs12 sm6>
				<v-menu v-model="menu" :close-on-content-click="false" :nudge-right="40" lazy transition="scale-transition" offset-y full-width min-width="290px">
					<template v-slot:activator="{ on }">
						<v-text-field :disabled="!isEditing" color="primary" :value="birthdate" label="Birth Date" readonly v-on="on"></v-text-field>
					</template>
					<v-date-picker color="primary" v-model="user.birthdate" @input="menu = false"></v-date-picker>
				</v-menu>
			</v-flex>
			<v-flex xs12 sm6>
				<v-select :disabled="!isEditing" color="primary" :items="genders" label="Gender" v-model="user.gender"></v-select>
			</v-flex>
			<v-flex xs12 sm6>
				<v-select :disabled="!isEditing" color="primary" :items="looking" label="Looking For" v-model="user.looking"></v-select>
			</v-flex>
			<v-flex xs12>
				<v-text-field :disabled="!isEditing" label="Address" color="primary" v-model="user.address"/>
			</v-flex>
			<v-flex xs12 sm4>
				<v-text-field :disabled="!isEditing" label="City" color="primary" v-model="user.city"/>
			</v-flex>
			<v-flex xs12 sm4>
				<v-text-field :disabled="!isEditing" label="Country" color="primary" v-model="user.country"/>
			</v-flex>
			<v-flex xs12 sm4>
				<v-text-field :disabled="!isEditing" color="primary" label="Postal Code" type="number" v-model="user.postal_code"/>
			</v-flex>
			<v-flex xs12>
				<vue-tags-input :disabled="!isEditing" v-model="tag" :tags="tags" @tags-changed="newTags => tags = newTags"/>
			</v-flex>
			<v-flex xs12>
				<v-textarea :disabled="!isEditing" color="primary" label="Bio" v-model="user.biography"/>
			</v-flex>
			<v-flex xs12 text-xs-right>
				<v-btn :disabled="!isEditing" class="mx-0 font-weight-light" color="primary" large dark @click.prevent="updateUser">Save</v-btn>
			</v-flex>
		</v-layout>
	</v-form>
</v-container>
</template>

<script>
import { VueTagsInput, createTags } from '@johmun/vue-tags-input'
import utility from '../utility.js'
import moment from 'moment'

export default {
	name: 'ProfileForm',
	components: { VueTagsInput },
	data() {
		return {
			isEditing: false,
			menu: false,
			tag: '',
			tags: [],
			genders: ['male', 'female'],
			looking: ['male', 'female', 'both'],
		}
	},
	props: {
		user: {
			type: Object,
			default: function() { return {} }
		}
	},
	watch: {
		user: {
			handler: 'syncUser',
			immediate: true
		},
		tags: function() {
			this.user.tags = this.tags.map(cur => cur.text.toLowerCase()).join(',')
		}
	},
	computed : {
		birthdate () {
			return moment(this.user.birthdate).format('MMMM D, YYYY')
		}
	},
	methods: {
		...utility,
		syncUser() {
			const list = this.user.tags ? this.user.tags.split(',') : []
			this.tags = list ? createTags(list) : []
			this.$emit('sync-user', this.user)
		},
		updateUser() {
			this.$emit('update-user', this.user)
		}
	}
}
</script>

<style>
.edit, .edit:hover, .edit:focus {
	position: absolute;
}

.edit {
	right: 0;
	transform: translate(-50%, 60%);
}

.vue-tags-input {
	background: transparent !important;
	max-width: 100% !important;
	font-size: 1.2em;
}

.vue-tags-input .ti-input::after {
	content: '';
	display: block;
	position: absolute;
	bottom: 0;
	left: 50%;
	transform: translate(-50%, 25%);
	width: 0;
	height: 1.5px;
	background: var(--color-primary);
	transition: width .2s ease-in-out;
}

.ti-tag {
	background: var(--color-primary) !important;
}

.vue-tags-input.ti-disabled > .ti-input > .ti-tags > .ti-tag {
	background: #c8c8c8 !important;
}

.vue-tags-input.ti-disabled > .ti-input {
	border-bottom: 1px dashed #c8c8c8;
}

.vue-tags-input.ti-focus .ti-input::after {
	width: 100%;
}

.vue-tags-input .ti-input {
	border: none;
	border-bottom: 1px solid grey;
}
</style>
