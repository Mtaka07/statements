<template>
    <div class="container">
        <div class="row justify-content-center" style="margin:0;">
            <div class="filter card col-md-12">
                <h3 class="filter-title">フィルタ</h3>
                <div class="card-box" col-12>
                    <form action="/member" method="GET">
                        <div class="management-container">
                            <div class="container-fluid">
                                <label for="searchMemberId">管理ID:</label>
                            </div>
                            <div class="col-sm-auto mt-2">
                                <input v-model="id" id="searchMemberId" name="searchMemberId" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="management-container">
                            <div class="container-fluid">
                                <label for="searchName">名前:</label>
                            </div>
                            <div class="col-sm-auto mt-2">
                                <input v-model="name" id="searchName" name="searchName" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="management-container">
                            <div class="container-fluid">
                                <label for="searchStatus">ステータス:</label>
                            </div>
                            <div class="col-sm-auto mt-2">
                                <label for="searchStatus"><input v-model="status" value="0" id="searchStatus" name="searchStatus" type="radio" >仮登録</label>
                                <label for="searchStatus"><input v-model="status" value="1" id="searchStatus" name="searchStatus" type="radio" >通常</label>
                                <label for="searchStatus"><input v-model="status" value="2" id="searchStatus" name="searchStatus" type="radio" >退会</label>
                                <label for="searchStatus"><input v-model="status" value="3" id="searchStatus" name="searchStatus" type="radio" >停止</label>
                            </div>
                        </div>
                        <div class="col-md-12 text-center pt-2">
                            <button type="submit" @click="loader=true" class="search-btn">検索</button>
                            <button type="submit" @click="clearSearch" class="clear-btn">クリア</button>
                        </div>
                    </form>    
                </div>
            </div>
            <div :class="(loader) ? 'visible' : 'invisible'">
                <div class="d-flex justyfy-content-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="add-btn-area">
                <a :href="'/member/create'" mb-3 class="add-btn">追加</a>
            </div>
            <div v-if="members.data.length != 0" :class="(loader) ? 'invisible' : 'visible'" class="slider-table">
                <table id="memberTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">管理ID</th>
                            <th scope="col">名前</th>
                            <th scope="col">メール</th>
                            <th scope="col">状態</th>
                            <th scope="col">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="i in members.data" :key="i.id">
                            <td>{{ i.id }}</td>
                            <td>{{ i.name }}</td>
                            <td>{{ i.mail }}</td>
                            <td>{{ setMemberStatus(i.status) }}</td>
                            <td>
                                <a :href="'/member/' + i.id" class="info-btn">詳細</a>
                                <a :href="'/member/' + i.id + '/edit/'" class="edit-btn">編集</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item" v-for="(a, i) in members.links" :key="i">
                        <a v-if="a.url" :class="(page == a.label) ? 'page-link' : 'page-link'" :href="a.url + '&searchMemberId=' + ((id) ? id: '') + '&searchName=' + ((name) ? name: '') + '&searchStatus=' + ((status) ? status: '')">{{ (a.label != "pagenation.previous" && a.label != "pagenation.next") ? a.label : ((a.label == "pagination.previous") ? "前" : "次") }}</a>
                    </li>
                </ul>
            </nav>
            <div v-if="members.data.length == 0" :class="(loader) ? 'invisible lead text-center' : 'visible lead text-center'">
                <p>検索結果はありません</p>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props:['members','constants'],

        mounted() {
            console.log(this.members)
        },

        data:() => ({
            id: null,
            name: null, 
            status: null,
            loader: false,
            page: '1'
        }),
        methods: {
            sortTable: function(col) {
                if(this.sortColumn == col) {
                    this.ascending = !this.ascending;
                }else {
                    this.ascending = true;
                    this.sortColumn = col;
                }

                var ascending = this.ascendind;

                this.rows.sort(function(a, b) {
                    if(a[col] > b[col]) {
                        return ascending ? 1: -1
                    }else if (a[col] < b[col]) {
                        return ascending ? -1: 1
                    }
                    return 0;
                })
            },
            setMemberStatus: function(stat) {
                let st = parseInt(stat);
                
                switch (st) {
                    case this.constants.member.status.provisional:
                    return "仮登録"

                    case this.constants.member.status.general:
                    return "通常"

                    case this.constants.member.status.resigned:
                    return "退会"

                    case this.constants.member.status.stopped:
                    return "停止"
                }
            },
            getUrlParams: function() {
                let currobj = this;
                const queryString = window.location.search;
                const urlParams = new URLSearchParams(queryString);
                currobj.id = urlParams.get('searchMemberId');
                currobj.name = urlParams.get('searchName');
                currobj.status = urlParams.get('searchStatus');
                
                if(urlParams.get('page')) {
                    currobj.page = urlParams.get('page');
                }
            },
            clearSearch: function() {
                this.id = null
                this.name = null
                this.status = null

                location.reload();
            }
        },

        computed: {
            columns: function() {
                console.log(this.rows.length)
                if(this.rows.length == 0) {
                    return [];
                }
                return Object.keys(this.rows[0])
            },
            memberStatusOptions: function() {
                let options = {}
                let statuses = this.constants.member.status
                for( let key in statuses ) {
                    if(statuses[key] === this.status) {
                        continue
                    }
                    options[key] = statuses[key]
                }
                console.log(options)
                return options
            }
        },

        created() {
            this.getUrlParams();
        },
    }
</script>
<style scoped>
    .filter {
        position: relative;
        margin: 2em 0;
        padding: 0.5em 1em;
        border: solid 2px #DDDDDD;
        border-radius: 8px;
    }
    .filter .filter-title {
        position: absolute;
        display: inline-block;
        top: -13px;
        left: 10px;
        padding: 0 9px;
        line-height: 1;
        font-size: 19px;
        background: #FFF;
        color: #000000;
        font-weight: bold;
    }
    .management-container{
        margin: 30px auto;
        padding: 0;
    }
    .search-btn {
        background: #0000DD;
        border: none;
        color: #fff;
        width: 100%;
        margin-bottom: 5px;
    }
    .clear-btn {
        background: #DD0000;
        border: none;
        color: #fff;
        width: 100%;
    }
    .add-btn {
        background: #0000DD;
        max-width: 65px;
        text-decoration: none;
        color: #fff;
        padding: 5px;
        display: flex;
        justify-content: center;
    }
    .info-btn {
        background: #00ECFF;
        color: #fff;
        padding: 6px;
        border-radius: 5px;
        text-decoration: none;
    }
    .edit-btn {
        background: #FFD700;
        padding: 6px;
        border-radius: 5px;
        text-decoration: none;
        color: #000000;
    }
    .slider-table {
        overflow-x: scroll;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        white-space: nowrap;
    }
    .table th,
    .table td {
        border: 2px solid #eee;
        padding: 4px 8px;
    }

</style>