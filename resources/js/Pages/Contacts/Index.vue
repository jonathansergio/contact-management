<template>
    <div class="p-8">
        <h1 class="text-2xl font-bold mb-4">Lista de Contatos</h1>

        <table class="w-full border">
            <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">ID</th>
                <th class="p-2 border">Nome</th>
                <th class="p-2 border">Email</th>
                <th class="p-2 border">Telefone</th>
                <th class="p-2 border">Ações</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="contact in contacts.data" :key="contact.id" class="hover:bg-gray-50">
                <td class="p-2 border">{{ contact.id }}</td>
                <td class="p-2 border">{{ contact.name }}</td>
                <td class="p-2 border">{{ contact.email }}</td>
                <td class="p-2 border">{{ contact.phone }}</td>
                <td class="p-2 border space-x-2">
                    <button class="text-blue-500" @click="edit(contact)">Editar</button>
                    <button class="text-red-500" @click="destroy(contact.id)">Excluir</button>
                </td>
            </tr>
            </tbody>
        </table>

        <h2 class="text-xl font-semibold mt-8 mb-2">Novo Contato</h2>
        <form @submit.prevent="submit" class="space-y-2">
            <input v-model="form.name" type="text" placeholder="Nome" class="border p-2 w-full" />
            <input v-model="form.email" type="email" placeholder="Email" class="border p-2 w-full" />
            <input v-model="form.phone" type="text" placeholder="Telefone" class="border p-2 w-full" />
            <button class="bg-green-500 text-white px-4 py-2 rounded">Salvar</button>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({ contacts: Object })

const form = ref({ id: null, name: '', email: '', phone: '' })

function submit() {
    if (form.value.id) {
        router.put(`/api/contacts/${form.value.id}`, form.value, {
            onSuccess: () => resetForm(),
        })
    } else {
        router.post('/api/contacts', form.value, {
            onSuccess: () => resetForm(),
        })
    }
}

function edit(contact) {
    form.value = { ...contact }
}

function destroy(id) {
    if (confirm('Deseja excluir este contato?')) {
        router.delete(`/api/contacts/${id}`)
    }
}

function resetForm() {
    form.value = { id: null, name: '', email: '', phone: '' }
}
</script>
