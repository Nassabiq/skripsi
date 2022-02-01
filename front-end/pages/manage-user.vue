<template>
  <div class="margin-auth">
    <p class="text-3xl font-semibold text-blue-600">User Page</p>
  </div>
</template>

<script>
export default {
    name: "manage-user",
    layout: 'auth',
    auth: false,
    data(){
      return {
        route: this.$route.fullPath
      }
    },
    methods: {
    }
};
</script>
