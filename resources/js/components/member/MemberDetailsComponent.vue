<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <strong><h1 class="lead">メンバー詳細</h1></strong>
                        <div class="panel panel-default">
                            <div class="panel-body border p-3 mt-2 mb-2">
                                <form>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-4 col-form-label">管理ID</label>
                                        <div class="col-sm-8">
                                        <input type="text" readonly class="form-control-plaintext" :value="id">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-4 col-form-label">Line管理ID</label>
                                        <div class="col-sm-8">
                                        <input type="text" readonly class="form-control-plaintext" :value="line_user_id">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-4 col-form-label">ニックネーム</label>
                                        <div class="col-sm-8">
                                        <input type="text" readonly class="form-control-plaintext" :value="name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-4 col-form-label">ステータス</label>
                                        <div class="col-sm-8">
                                        <input type="text" readonly class="form-control-plaintext" :value="setStatus(status)">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-4 col-form-label">メールアドレス</label>
                                        <div class="col-sm-8">
                                        <input type="text" readonly class="form-control-plaintext" :value="mail">
                                        </div>
                                    </div>
                                </form>
                                <a :href="last_member_page"><button type="button" class="btn btn-primary text-black">{{ '戻る' }}</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ["member", "last_member_page"],
        mounted() {
            console.log(this.member)
        },
        data: () => ({
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            referer: document.referer,
            id: null,
            line_user_id: null,
            name: null,
            mail: null,
            status: null
        }),
        methods: {
            isEmpty: function(obj){
                for(let i in obj) {
                    return false;
                }
                return true;
            },
            setStatus: function(stat) {
                switch (stat) {
                    case 0:
                    return "仮登録";

                    case 1:
                    return "通常";

                    case 2:
                    return "退会";

                    case 3:
                    return "停止";
                }
            },
            setValidation: function() {
                if(!this.isEmpty(this.member)) {
                    this.id = this.member.id
                    this.line_user_id = this.member.line_user_id
                    this.name = this.member.name
                    this.mail = this.member.mail
                    this.status = this.member.status
                }
            },  
        },
        filters: {
                dateTime: (date) => {
                    return moment(date).format('YYYY/MM/DD HH:mm');
                },
                date: (date) => {
                    return date ? moment(date).format('YYYY-MM-DD') : ""
                }
            },
            created() {
                this.setValidation()
            }
    }
</script>