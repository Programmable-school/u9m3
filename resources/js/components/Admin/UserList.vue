<template>
    <v-flex>
    <v-card xs12 class="m-3 px-3">

      <v-card-title class="title">
        <v-icon class="pr-2">{{ $route.meta.icon }}</v-icon> {{ $route.meta.name }} {{ /* 勤務管理 */ }}
        <user-dialog ref="userDialog" @reload="reload" @setsearch="setsearch"></user-dialog>
        <v-spacer></v-spacer>
        <v-spacer></v-spacer>
        value is {{ $route.params.value }}
      </v-card-title>

      <v-data-table
        :headers="headers"
        :items="tabledata"
        :pagination.sync="pagination"
        :rows-per-page-items='[10,25,50,{"text":"All","value":-1}]'
        :loading="loading"
        :search="search"
        class="elevation-0 p-1"
      >
        <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>

        <template slot="items" slot-scope="props">
          <tr>
            <td class="text-xs-center" xs1>{{ (props.index + 1) + (pagination.page - 1) * pagination.rowsPerPage }}</td>
            <template v-for="n in (headers.length - 2)">
              <td :class="'text-xs-' + headers[n].align" style="white-space: nowrap;" v-text="props.item[headers[n].value]"></td>
            </template>
          </tr>
        </template>
      </v-data-table>
    </v-card>
  </v-flex>
</template>

<script>
  export default {
    name: 'UserList',

    components: {
    },

    props: {
    },

    data: () => ({
      loading: false,
      search: '',
      pagination: { sortBy: 'name', descending: false, },

      tabledata: [],
      headers: [
        { align: 'center', sortable: false, text: 'No',       },
        { align: 'left',   sortable: true,  text: '社員ID',   value: 'loginid' },
        { align: 'left',   sortable: true,  text: '氏名',     value: 'name' },
        { align: 'center', sortable: true,  text: '権限',     value: 'role' },
        { align: 'center', sortable: false, text: 'アクション',       },
      ],

      items: { 
        loginid: '',
        name: '',
        pass: '',
        role: false,
      },
      orig: {},
      error: {},
    }),

    created() {
      if (process.env.MIX_DEBUG) console.log('User Component created.')
      this.initialize()
    },

    methods: {
      initialize() {
        this.getUsers()
      },

      reload() {
        if (process.env.MIX_DEBUG) console.log('User Component reload')
        this.getUsers()
      },

      setsearch(id) {
        if (process.env.MIX_DEBUG) console.log('User Component set Search')
        this.search = id
      },

      getUsers() {
        if (process.env.MIX_DEBUG) console.log('User Component getUsers')
        this.loading = true
        axios.get('admin/user/show')

        .then( function (response) {
          this.loading = false
          if (process.env.MIX_DEBUG) console.log(response)
          if (response.data.users) {
            this.tabledata = response.data.users
            this.setRole()
          }
        }.bind(this))

        .catch(function (error) {
          this.loading = false
          console.log(error)
          if (error.response && [401, 419].includes(error.response.status)) {
            this.$emit('axios-logout')
          }
        }.bind(this))
      },

      setRole() {
        for (var i=0; i<this.tabledata.length; i++) {
          if (this.tabledata[i].role) {
            if (this.tabledata[i].role == 5) { this.tabledata[i].role = '管理者'  }
            if (this.tabledata[i].role == 10) { this.tabledata[i].role = 'ユーザ'  }
          }
        }
      },

      open(item) {
        if (process.env.MIX_DEBUG) console.log("User Dialog func open")

        // INIT VAR
        this.clearVar()
          // SET ITEM
        if (item) {
          if (item.loginid) this.items.loginid = item.loginid
          if (item.name) this.items.name = item.name
          if (item.role) {
            if (item.role == '管理者') {
              this.items.role = true
            }
          }
          // COPY ORIG
          this.orig = JSON.parse(JSON.stringify(this.items))
        }
      },
    },
  }
</script>
