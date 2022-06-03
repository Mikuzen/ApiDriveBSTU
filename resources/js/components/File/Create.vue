<template>
    <div class="w-25 m-auto mt-3 text-center">
        <template v-if="errors" v-for="error in errors">
            <span class="text-danger">{{ error }}</span>
        </template>
        <h3>Добавление нового файла</h3>
        <div class="mb-3">
            <input type="text" v-model="user_id" class="form-control" placeholder="ID пользователя">
        </div>
        <div class="mb-3">
            <input type="file" name="files" @change="onChange" class="form-control" placeholder="" multiple>
        </div>
        <div class="mb-3">
            <input type="submit" @click.prevent="store" value="Добавить файл" class="btn btn-primary">
        </div>
    </div>
</template>

<script>
import router from "../../router";

export default {
    name: "Create",

    data() {
        return {
            user_id: null,
            files: [],
            status: null,
            errors: null,
        }
    },
    methods: {
        store() {
            let formData = new FormData();
            formData.append('user_id', this.user_id);
            for (let i = 0; i < this.files.length; i++) {
                let file = this.files[i];

                formData.append('files[' + i + ']', file)
            }

            axios.post('/api/V1/files', formData, {
                headers: {
                    'Content-type': 'multipart/form-data'
                }
            }).then(function () {
                router.push({name: 'file.index'})
            })
                .catch(error => {
                    if (error.response) {
                        this.status = error.response.status;
                        this.errors = error.response.data.errors;
                    } else if (error.request) {
                        this.errors = error.request.errors
                    } else {
                        console.log('Error', error.message);
                    }
                })
        },
        onChange(e) {
            this.files = e.target.files;
        },
    }
}
</script>

<style scoped>

</style>
