<script setup>
import { ref, inject, onMounted } from 'vue';
import { useUserStore } from "@/stores/user.js";
import { useRouter } from "vue-router";

const axios = inject("axios");
const userStore = useUserStore();
const router = useRouter();

const vCardStats = ref(null);  // Para armazenar as estatísticas dos vCards
const isAdmin = ref(false);
const totalVCardBalance = ref(0);  // Para armazenar o total do saldo de vCards
const totalVCards = ref(0);  // Para armazenar o total de vCards

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

const calcuteCenas = async () => {
    try {
        const endDate = new Date();  // Data atual
        const startDate = new Date();
        startDate.setDate(startDate.getDate() - 30);  // Subtrai 30 dias da data atual

        const response = await axios.get("transactions", {
            params: {
                vCardId: userStore.user.usarname,  // Substitua pelo ID real da vCard
                startDate: startDate.toISOString(),  // Formata a data para string ISO
                endDate: endDate.toISOString(),
            },
        });

        vCardStats.value = response.data;
    } catch (error) {
        console.error(error);
    }
};

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
            await calcuteCenas(); 
        } else if (userStore.user.user_type == "V") {
            await TotalBalance();
        }
    }
})
</script>

<template>
    <div>
        <h1>Estatísticas</h1>
        <div>
            
            <div v-if=" userStore.user.user_type=='A'">
                <h2>para Admin</h2>
                <h6>Total de vCards: {{ totalVCards }}</h6>
                <h6>Contagem de balanço total: {{ totalVCardBalance }}€</h6>
                <li v-for="(stat, index) in vCardStats" :key="index">
                        {{ stat.date }}: {{ stat.amount }}€
                    </li>
            </div>
            
            <div v-if="userStore.user.user_type=='V'">
                <h2>para vCard</h2>
                <h6>Total de vCards: {{ totalVCards }}</h6>
                <h6>Contagem de balanço total: {{ totalVCardBalance }}€</h6>
                <li v-for="(stat, index) in vCardStats" :key="index">
                        {{ stat.date }}: {{ stat.amount }}€
                    </li>
            </div>
            <div v-else>
                <p>Carregando estatísticas...</p>
            </div>
        </div>
    </div>
</template>
