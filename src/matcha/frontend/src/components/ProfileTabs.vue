<template>
<v-flex md8 :class="mobile ? 'hidden-md-and-up pb-0 mt-5' : 'hidden-sm-and-down'">
	<v-tabs fixed-tabs centered v-model="activeTab" :color="`grey lighten-${mobile ? 5 : 3}`" slider-color="primary">
		<v-tab v-for="link in links" :key="link.route" :href="`#tab-${link.route}`">
			<v-icon :left="!mobile">{{ link.icon }}</v-icon>
			<span :class="mobile ? 'pl-3 hidden-xs-only' : ''">{{ link.text }}</span>
		</v-tab>
	</v-tabs>
</v-flex>
</template>

<script>
export default {
	name: 'ProfileTabs',
	data() {
		return {
			activeTab: null,
			links: [
				{ icon: 'person', text: 'Profile', route: 'profile' },
				{ icon: 'photo_camera', text: 'Pictures', route: 'photo' },
				{ icon: 'settings', text: 'Preferences', route: 'setting' },
			]
		}
	},
	props: {
		mobile: { type: Boolean, default: false },
		active: { type: String, default: '' },
	},
	watch: {
		active: {
			handler: 'sync',
			immediate: true
		},
		activeTab: function() {
			this.$emit('change-tab', this.activeTab)
		}
	},
	methods: {
		sync() {
			this.activeTab = this.active
		}
	}
}
</script>

<style>
.v-tabs__container {
	height: 4rem;
}
.v-tabs__item--active,
.v-tabs__item--active > .v-icon {
	color: var(--color-primary) !important;
}
</style>
