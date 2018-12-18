<template>
    <h1>UserList Component.</h1>
</template>

<script>
  export default {
    data: () => ({
    }),

    props: {
    },

    created() {
      console.log('UserList Component created.')
      this.initialize()
    },

    mounted() {
      console.log('UserList Component mounted.')
    },

    methods: {
      initialize: function() {
      },
    },

  }
</script>