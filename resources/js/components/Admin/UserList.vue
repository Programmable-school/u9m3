<template>
    <v-flex>
    <v-card xs12 class="m-3 px-3">

      <v-card-title class="title">
        <v-icon class="pr-2">{{ $route.meta.icon }}</v-icon> {{ $route.meta.name }} {{ /* 社員管理 */ }}
        <user-dialog ref="userDialog" @reload="reload" @setsearch="setsearch"></user-dialog>
        <v-spacer></v-spacer>
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          prepend-icon="search"
          label="Search"
          single-line
          hide-details
          clearable
        ></v-text-field>
      </v-card-title>
    </v-card>
  </v-flex>
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