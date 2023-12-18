<script setup>
import { ref, inject, onMounted } from 'vue';
import { useUserStore } from "@/stores/user.js";
import { useRouter } from "vue-router";

const axios = inject("axios");
const userStore = useUserStore();
const router = useRouter();

const isAdmin = ref(false);
const totalVCardBalance = ref(0);
const totalVCards = ref(0);

const TotalBalance = async () => {
    try {
        // Certifique-se de que o usuário está logado
        if (!userStore.user) {
            console.error("Usuário não logado.");
            return;
        }

        // Buscar o VCard do usuário logado
        const response = await axios.get(`vcards/${userStore.user.username}`);
        const currentUserVCard = response.data.data;

        // Certifique-se de que o saldo é um número antes de atribuir
        if (currentUserVCard && !isNaN(currentUserVCard.balance)) {
            totalVCardBalance.value = parseFloat(currentUserVCard.balance).toFixed(2);
            totalVCards.value += 1;
        } else {
            console.error("Balanço inválido.");
            totalVCardBalance.value = 0;
        }
    } catch (error) {
        console.error(error);
    }
};

const calculateTotalVCardBalance = async () => {
    try {
        const response = await axios.get('vcards');
        const vCards = response.data.data;

        let totalBalance = 0;

        for (const vCard of vCards) {
            // Certifique-se de que o saldo é um número antes de somar
            if (!isNaN(vCard.balance)) {
                totalBalance += parseFloat(vCard.balance);
                totalVCards.value += 1;
            }
        }

        totalVCardBalance.value = totalBalance.toFixed(2); // Arredonda para duas casas decimais
    } catch (error) {
        console.error(error);
    }
}

onMounted(async () => {
    if (!userStore.user) {
        router.push('/');
    } else {
        userStore.loadUser();

        // Verifica se o utilizador é um administrador
        isAdmin.value = userStore.user.user_type;

        // Carrega estatísticas
        if (isAdmin.value == "A") {
            await calculateTotalVCardBalance();
        } else if (userStore.user.user_type == "V") {
            await TotalBalance();
        }
        
    }
})
</script>

<template>
    <div>
      <h2>Hi, {{userStore.user.name}}</h2>
      <p></p>
      <hr>
      <h5>Balance: {{totalVCardBalance}} €</h5>
      <p></p>
      <button type="button" class="btn btn-dark px-4 btn-addtask">
        <router-link class="nav-link" :to="{ name: 'NewTransaction' }">
          Send Money
        </router-link>
      </button>
    </div>
</template>


  