<template>
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
        <tr v-for="file in files">
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
</template>

<script>
export default {
    name: "Index",

    props: [
      'files'
    ],
}
</script>

<style scoped>
table {
    border: 2px solid black;
}

th{
    font-size: 12pt;
}
td {
    border: 2px solid black;
    text-align-all: center;
    font-size: 10pt;
    white-space: normal;
}
</style>
