export default {
	// Global page headers: https://go.nuxtjs.dev/config-head
	head: {
		title: "front-end",
		htmlAttrs: {
			lang: "en",
		},
		meta: [{charset: "utf-8"}, {name: "viewport", content: "width=device-width, initial-scale=1"}, {hid: "description", name: "description", content: ""}, {name: "format-detection", content: "telephone=no"}],
		link: [
			{rel: "icon", type: "image/x-icon", href: "/favicon.ico"},
			{rel: "stylesheet", href: "https://fonts.googleapis.com/css2?family=Nunito&family=Poppins&display=swap"},
		],
	},

	// Global CSS: https://go.nuxtjs.dev/config-css
	css: ["@/assets/css/main.css"],

	// Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
	plugins: ["@/plugins/modal.js"],

	// Auto import components: https://go.nuxtjs.dev/config-components
	components: true,

	// Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
	buildModules: ["@nuxt/postcss8"],

	// Modules: https://go.nuxtjs.dev/config-modules
	modules: ["@nuxtjs/axios", "@nuxtjs/auth-next", "vue-sweetalert2/nuxt"],

	// Build Configuration: https://go.nuxtjs.dev/config-build
	build: {
		postcss: {
			plugins: {
				tailwindcss: {},
				autoprefixer: {},
			},
		},
	},

	router: {
		// middleware: ["auth"],
	},
	axios: {
		// proxy: true,
		baseUrl: "http://127.0.0.1:8000",
		// credentials: true,
	},
	// proxy: {
	// 	"/api": {
	// 		target: "https://localhost:8000",
	// 		pathRewrite: {"^/api": "/"},
	// 	},
	// },
	auth: {
		strategies: {
			laravelSanctum: {
				provider: "laravel/sanctum",
				url: "http://127.0.0.1:8000/api",
			},
		},
	},
};
