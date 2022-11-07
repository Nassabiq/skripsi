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
		script: [
			{
				src: "https://unpkg.com/flowbite@1.4.7/dist/datepicker.js",
			},
		],
	},

	// Global CSS: https://go.nuxtjs.dev/config-css
	css: [
		"@/assets/css/main.css",
		"quill/dist/quill.core.css",
		// for snow theme
		"quill/dist/quill.snow.css",
		// for bubble theme
		"quill/dist/quill.bubble.css",
	],

	// Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
	plugins: [{src: "@/plugins/modal.js"}, {src: "@/plugins/vue-html2pdf", mode: "client"}, {src: "~/plugins/vue-datepicker", ssr: false}, {src: "~plugins/nuxt-quill-plugin", ssr: false}],

	// Auto import components: https://go.nuxtjs.dev/config-components
	components: true,

	// Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
	buildModules: ["@nuxt/postcss8", "@nuxtjs/moment"],
	moment: {
		/* module options */
		locales: ["id"],
	},

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
		middleware: ["auth"],
	},
	axios: {
		// proxy: true,
		baseUrl: "http://127.0.0.1:8000",
		credentials: true,
	},
	// proxy: {
	// 	"/api": {
	// 		target: "https://127.0.0.1:8000",
	// 		pathRewrite: {"^/api": "/"},
	// 	},
	// },
	auth: {
		strategies: {
			"laravelJWT": {
				provider: "laravel/jwt",
				url: "http://127.0.0.1:8000",
				endpoints: {
					login: {url: "/api/login", method: "post"},
					logout: {url: "/api/logout", method: "post"},
					user: {url: "/api/user", method: "get"},
					// ...
				},
				token: {
					property: "access_token",
					maxAge: 60 * 60,
				},
				refreshToken: {
					maxAge: 20160 * 60,
				},
			},
		},
	},
};
