<template>
	<v-card>
		<v-layout column justify-center align-center class="pt-1">
			<v-layout justify-space-between class="top pa-2">
				<v-chip disabled outline small color="grey lighten-1" class="ml-2">2 kms away</v-chip>
				<v-icon :color="`${user.status ? 'green' : 'grey'} lighten-2`" class="status_icon mr-3" small>fiber_manual_record</v-icon>
			</v-layout>
			<v-avatar size="120">
				<v-img :src="user.picture.large" aspect-ratio="1"></v-img>
			</v-avatar>
			<h5 class="name headline text-capitalize mt-2 mb-4">{{ user.name.first }}</h5>
			<v-layout align-start justify-center>
				<p class="caption text-capitalize rating_value">{{ user.fame.toFixed(1) }}</p>
				<v-rating readonly dense small :value="user.fame" half-increments class="rating"></v-rating>
			</v-layout>
			<v-layout justify-center align-center class="body-1 text-capitalize bottom mb-0 mt-auto py-2 px-4 grey lighten-5">
				<v-icon color="primary" class="cake_icon px-1" small>cake</v-icon>
				<span class="pr-1">{{ user.dob.age }}</span>
				<v-icon color="primary lighten-1" class="location_icon px-1" small>place</v-icon>
				<span class="text-truncate">{{ `${user.nat}, ${user.location.city}` }}</span>
			</v-layout>
		</v-layout>
	</v-card>
</template>

<script>
export default {
	name: 'UserCard',
	props: {
		user: {
			type: Object,
			default: function() { return {} }
		}
	},
	data () {
		return {

		}
	}
}
</script>

<style>
.name {
	color: #828282;
	position: relative;
}

.name:after {
	display: block;
	content: '';
	position: absolute;
	top: 125%;
	left: 50%;
	width: 40%;
	height: 1px;
	background: var(--color-primary);
	transform: translate(-50%, -50%);
}

.v-card {
	height: 100%;
	box-shadow: none;
	border: 1px solid rgba(0, 0, 0, .1) !important;
}

.v-card__title {
	background: none;
}

.top, .bottom {
	width: 100%;
}

.rating {
	transform: scale(.8) translateY(-1px);
}

.rating_value {
	margin-left: 7px;
	margin-right: -3px;
}

.location_icon {
	transform: scale(1.1);
	margin-bottom: 2px;
}

.cake_icon {
	margin-bottom: 3px;
}

.love {
	transform: translate(-5px, -3px);
}

.v-responsive.v-image {
	border: 3px solid rgba(0, 0, 0, .1) !important;
}
</style>
