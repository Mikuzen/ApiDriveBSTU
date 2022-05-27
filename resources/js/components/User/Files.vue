<template>
    <div>
        <table class="table table-striped table-dark" style="border: 2px solid black">
            <thead class="text-center">
            <tr>
                <th>ID</th>
                <th>Источник</th>
                <th>Формат файла</th>
                <th>Название файла</th>
                <th>Размер, КБайт</th>
                <th>Тип файла</th>
                <th>Доступ к файлу</th>
                <th>Дата удаления</th>
                <th><i class="bi-pencil-square text-primary"></i></th>
                <th><i class="bi-trash-fill text-danger"></i></th>
            </tr>
            </thead>
            <tbody class="text-center">
            <tr v-for="file in displayedFiles">
                <td>{{ file.id }}</td>
                <td>{{ file.src }}</td>
                <td>{{ file.ext }}</td>
                <td>{{ file.title }}</td>
                <td>{{ file.size }}</td>
                <td>{{ file.type }}</td>
                <td>{{ file.private ? 'Закрыт' : 'Открыт' }}</td>
                <td>{{ file.deleted_at ? file.deleted_at : 'Файл не удален' }}</td>
                <td><a href="#" @click.prevent=""><i class="bi-pencil-square text-primary"></i></a></td>
                <td><a href="#" @click.prevent=""><i class="bi-trash-fill text-danger"></i></a></td>
            </tr>
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
    name: "Files",

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

    props: {
        initialFiles: {
            type: Array,
            default() {
              return [];
            },
        },
    },

    watch: {
        initialFiles() {
            this.files = this.initialFiles;
        },

        files() {
            this.setPages()
        }
    },

    methods: {
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
table {
    border: 2px solid black;
}

th {
    font-size: 12pt;
}

td {
    border: 2px solid black;
    text-align-all: center;
    font-size: 10pt;
    white-space: normal;
}
</style>
