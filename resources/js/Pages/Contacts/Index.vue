<template>
    <div class="p-8">
        <h1 class="text-2xl font-bold mb-4">Lista de Contatos</h1>
        <table class="w-full border mb-8">
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
                <td class="p-2 border">{{ formatPhone(contact.phone) }}</td>
                <td class="p-2 border space-x-2">
                    <button class="text-blue-500" @click="edit(contact)">Editar</button>
                    <button class="text-red-500" @click="destroy(contact.id)">Excluir</button>
                </td>
            </tr>
            </tbody>
        </table>
        <h2 class="text-xl font-semibold mb-2">Novo Contato</h2>
        <form @submit.prevent="submit" class="space-y-2">
            <div>
                <input
                    v-model="form.name"
                    type="text"
                    placeholder="Nome"
                    class="border p-2 w-full"
                />
                <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
            </div>
            <div>
                <input
                    v-model="form.email"
                    type="email"
                    placeholder="Email"
                    class="border p-2 w-full"
                />
                <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
            </div>
            <div>
                <input
                    v-model="form.phone"
                    v-mask="'(##) #####-####'"
                    type="text"
                    placeholder="Telefone"
                    class="border p-2 w-full"
                />
                <p v-if="form.errors.phone" class="text-red-500 text-sm mt-1">{{ form.errors.phone }}</p>
            </div>
            <button class="bg-green-500 text-white px-4 py-2 rounded">Salvar</button>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({ contacts: Object })

const form = useForm({
    id: null,
    name: '',
    email: '',
    phone: ''
})

function submit() {
    if (form.id) {
        form.put(`/contacts/${form.id}`, {
            onSuccess: () => resetForm()
        })
    } else {
        form.post('/contacts', {
            onSuccess: () => resetForm()
        })
    }
}

function edit(contact) {
    form.id = contact.id
    form.name = contact.name
    form.email = contact.email
    form.phone = contact.phone
    form.clearErrors()
}

function destroy(id) {
    if (confirm('Deseja excluir este contato?')) {
        form.delete(`/contacts/${id}`)
    }
}

function resetForm() {
    form.reset()
    form.clearErrors()
}

function formatPhone(phone) {
    if (!phone) return ''
    const digits = phone.replace(/\D/g, '')
    if (digits.length === 11) {
        return digits.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3')
    } else if (digits.length === 10) {
        return digits.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3')
    }
    return phone
}
</script>
