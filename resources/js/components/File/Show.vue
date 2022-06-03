<template>
    <div>
        <div class="row-cols-2">
            <h1><strong>Информация о файле</strong></h1>
            <div class="row" v-if="file">
                <p>
                    <strong>ID владельца файла: </strong>
                    {{ file.user_id}}
                </p>
                <p>
                    <strong>Источник файла: </strong>
                    {{ file.src}}
                </p>
                <p>
                    <strong>Формат файла: </strong>
                    {{ file.ext}}
                </p>
                <p>
                    <strong>Название файла: </strong>
                    {{ file.title}}
                </p>
                <p>
                    <strong>Размер файла: </strong>
                    {{ file.size}} КБайт
                </p>
                <p>
                    <strong>Тип файла: </strong>
                    {{ file.type}}
                </p>
                <p>
                    <strong>Закрытый файл: </strong>
                    {{ file.private ? 'Закрытый' : 'Открытый'}}
                </p>
                <p>
                    <strong>Дата создания файла: </strong>
                    {{ file.created_at ? file.created_at : 'Файл не удален'}}
                </p>
                <p>
                    <strong>Файл удален: </strong>
                    {{ file.deleted_at ? file.deleted_at : 'Файл не удален'}}
                </p>
                <p>
                    <strong>Публичная ссылка на файл: </strong>
                    {{ file.link ? file.link.link : 'Ссылка не создана'}}
                </p>
                <p>
                    <router-link :to="{ name: 'user.show', params: { user: file.user_id } }">
                        <input  class="btn btn-primary" value="Просмотреть данные пользователя" style="width: 250px">
                    </router-link>
                    <a href="#" @click.prevent="deleteFile(file.id)">
                        <input class="btn btn-danger" value="Удалить файл">
                    </a>
                </p>
            </div>
            <div class="row">
                <img src="" alt="">
            </div>
        </div>
    </div>
</template>

<script>
import router from "../../router";

export default {
    name: "Show",

    data() {
        return {
            file: null,
        }
    },

    mounted() {
        this.getFile()
    },

    methods: {
        getFile() {
            axios.get('/api/V1/files/' + this.$route.params.file)
                .then(res => {
                    this.file = res.data.data;
                })
        },

        deleteFile(file) {
            axios.delete(`/api/V1/files/` + file)
                .then(function (){
                    router.push({name: 'file.index'})
                    alert('Файл был удален')
                })
        }
    },
}
</script>

<style scoped>
</style>
