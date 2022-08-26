<template>
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <strong><h1 class="lead">{{ !isEmpty(member) ? 'メンバー詳細を編集' : 'メンバーを追加' }}</h1></strong>
                    <div class="panel panel-default">
                        <div class="panel-body border p-3 mt-2 mb-2">
                            <form action="/member/save" method="post">
                                <input type="hidden" name="id" v-model="id">
                                <input type="hidden" name="_token" v-model="csrf">
                                <input type="hidden" name="referer" :value="referer">
                                <div class="form-group row">
                                    <label for="id" class="col-sm-3 col-form-label">管理ID</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="id" id="id" class="form-control" v-model="id">
                                        <strong class="error text-danger" v-for="(value, i) in this.errors.id" :key="i">{{ value }}</strong>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="name" class="col-sm-3 col-form-label">ニックネーム</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" id="name" class="form-control" v-model="name">
                                        <strong class="error text-danger" v-for="(value, i) in this.errors.name" :key="i">{{ value }}</strong>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="status" class="col-sm-3 col-form-label">ステータス</label>
                                    <div class="col-sm-9">
                                        <select class="custom-select" name="status" id="status" v-model="status">
                                            <option selected disabled :value="setStatus(status)">{{ !isEmpty(member) ? (setStatus(member.status)) : '選択する' }}</option>
                                            <option value=0>仮登録</option>
                                            <option value=1>通常</option>
                                            <option value=2>退会</option>
                                            <option value=3>停止</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="mail" class="col-sm-3 col-form-label">メールアドレス</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="mail" id="mail" class="form-control" v-model="mail">
                                        <strong class="error text-danger" v-for="(value, i) in this.errors.mail" :key="i">{{ value }}</strong>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a :href="last_member_page" class="btn btn-primary text-black" data-dismiss="modal">{{ '戻る' }}</a>
                                    <button type="submit" class="btn btn-warning text-black">{{ !isEmpty(member) ? '保存' : '追加' }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ["member", "last_member_page", "errors", 'old'],
        mounted() {
            console.log(this.member)
        },
        data: () => ({
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            referer: document.referrer,
            id: null,
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
                let st = parseInt(stat)
                switch (st) {
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
                if(!this.isEmpty(this.old)) {
                    this.id = this.old.id
                    this.name = this.old.name
                    this.mail = this.old.mail
                    this.status = this.old.status
                }else {
                    if(!this.isEmpty(this.member)) {
                        this.id = this.member.id
                        this.name = this.member.name
                        this.mail = this.member.mail
                        this.status = this.member.status
                    }
                }
            }  
        },
        created() {
            this.setValidation();
        }
    }
</script>