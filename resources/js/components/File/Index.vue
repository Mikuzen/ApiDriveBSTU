<template>
    <div>
        <h1>Все пользователи Диск.БГТУ</h1>

        <router-link :to="{ name: 'file.create' }" class="btn btn-success m-2">Добавить файл</router-link>

        <table class="table table-hover text-center table-striped table-dark">
            <thead>
            <tr>
                <th>ID</th>
                <th>Источник</th>
                <th>Формат файла</th>
                <th>Название файла</th>
                <th>Размер, КБайт</th>
                <th>Тип файла</th>
                <th>Доступ к файлу</th>
                <th>Файл удален</th>
                <th><i class="bi-pencil-square text-primary"></i></th>
                <th><i class="bi-trash-fill text-danger"></i></th>
            </tr>
            </thead>
            <tbody>
            <template v-for="file in displayedFiles">
                <tr>
                    <td>{{ file.id }}</td>
                    <td>{{ file.src }}</td>
                    <td>{{ file.ext }}</td>
                    <td>{{ file.title }}</td>
                    <td>{{ file.size }}</td>
                    <td>{{ file.type }}</td>
                    <td>{{ file.private ? 'Да' : 'Нет' }}</td>
                    <td>{{ file.deleted_at ? file.deleted_at : 'Файл не удален' }}</td>
                    <td>
                        <router-link :to="{ name: 'file.show', params: { file: file.id}}">
                            <i class="bi-pencil-square"></i>
                        </router-link>
                    </td>
                    <td>
                        <a href="#" @click.prevent="deleteFile(file.id)">
                            <i class="bi-trash-fill text-danger"></i>
                        </a>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>
        <div class="w-50">
            <button type="button" class="btn btn-primary" :disabled="page == 1"
                    @click.prevent="page = 1">First
            </button>
            <button type="button" class="btn btn-primary" :disabled="page == 1"
                    @click.prevent="page--">Prev
            </button>
            <button type="button" class="btn btn-primary mx-1"
                    v-for="pageNumber in pages.slice(page-1, page+5)" @click.prevent="page = pageNumber">
                {{ pageNumber }}
            </button>
            <button type="button" class="btn btn-primary" :disabled="page > pages.length - 1"
                    @click.prevent="page++">Next
            </button>
            <button type="button" class="btn btn-primary" :disabled="page > pages.length - 1 "
                    @click.prevent="page = pages[pages.length - 1]">Last
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "Index",

    data() {
        return {
            files: [],
            page: 1,
            perPage: 15,
            pages: [],
        }
    },

    computed: {
        displayedFiles() {
            return this.paginate(this.files)
        }
    },

    created() {
        this.getFiles()
    },

    watch: {
        files() {
            this.setPages()
        }
    },

    methods: {
        getFiles() {
            axios.get('/api/V1/files')
                .then(res => {
                    this.files = res.data.data
                })
        },

        deleteFile(file) {
            axios.delete(`/api/V1/files/` + file)
                .then(res => {
                    this.getFiles()
                })
        },

        setPages() {
            let countOfPages = Math.ceil(this.files.length / this.perPage)
            for (let i = 1; i <= countOfPages; i++) {
                this.pages.push(i)
            }
        },

        paginate(files) {
            let from = (this.page * this.perPage) - this.perPage
            let to = this.page * this.perPage

            return files.slice(from, to);
        }
    }
}
</script>

<style scoped>

</style>
