<template>
<v-app class="grey lighten-5 app">
	<Navbar/>
	<v-content>
		<router-view/>
	</v-content>
	<v-fab-transition>
		<v-btn color="primary" dark fab fixed bottom right>
			<v-icon>keyboard_arrow_up</v-icon>
		</v-btn>
	</v-fab-transition>
</v-app>
</template>

<script>
import Navbar from '@/components/Navbar'
export default {
	name: 'App',
	components: { Navbar }
}
</script>

<style>
:root {
	--color-primary: #03a9f4;
	--color-accent: #01579b;
	--color-white: #FFF;
	--color-black: #111;
	--color-grey-light: #f3f3f3;
	--color-grey-dark: #575757;
	--color-green: #00ad53;
	--color-red: #ff0015;
}

* {
	margin: 0;
	padding: 0;
}

*,
*::after,
*::before {
	box-sizing: inherit;
}

html {
	box-sizing: border-box;
}

::selection {
	background-color: var(--color-primary);
	color: var(--color-white);
}

.app {
	overflow-x: hidden;
}
</style>
