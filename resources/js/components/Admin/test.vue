<template>
    <div>
        <div>
            <h2><router-link to="/user/foo">/user/foo</router-link>
    <router-link to="/user/bar">/user/bar</router-link></h2>
            <p>
                <router-view></router-view>
            </p>
        </div>
    </div>
</template>

<script>
    export default {
        // メソッドの実行
        created() {
            this.getArticle()
        },
        // メソッド&テンプレート内で使う変数を定義
        data() {
            return {
                article: ''
            }
        },
        // (読み込み時に)実行するメソッド
        methods: {
            getArticle() {
                // this.$route.params.idをaxiosで非同期接続するパスのパラメータに設定している
                axios.get('/api/admin/user' + this.$route.params.id).then(res =>  {
                    this.article = res.data
                });
            }
        }
    }
</script>
