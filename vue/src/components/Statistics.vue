<script setup>
import { ref, inject, onMounted } from 'vue';
import { useUserStore } from "@/stores/user.js";
import { useRouter } from "vue-router";

const axios = inject("axios");
const userStore = useUserStore();
const router = useRouter();

const isAdmin = ref(false);
const totalVCardBalance = ref(0);  // Para armazenar o total do saldo de vCards
const totalVCards = ref(0);  // Para armazenar o total de vCards
const totalVCardBalance30 = ref(0);  // Para armazenar o total do saldo de vCards
const totalVCards30 = ref(0);

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

const TotalBalanceFor30Days = async () => {
    try {
        // Certifique-se de que o usuário está logado
        if (!userStore.user) {
            console.error("Usuário não logado.");
            return;
        }

        // Defina a data inicial como sendo 30 dias atrás
        const startDate = new Date();
        startDate.setDate(startDate.getDate() - 30);

        // Buscar as transações do usuário no período de 30 dias
        const response = await axios.get(`transactions/${transaction.id}`, {
            params: {
                startDate: startDate.toISOString(),
                endDate: new Date().toISOString(),
            },
        });

        const transactions = response.data.data;

        // Contar o número de transações e calcular o saldo total
        let totalTransactions = 0;
        let totalBalance = 0;

        transactions.forEach(transaction => {
            // Certifique-se de que o saldo da transação é um número
            if (!isNaN(parseFloat(transaction.amount))) {
                totalTransactions += 1;
                totalBalance += parseFloat(transaction.amount);
            }
        });

        // Atualize os valores conforme necessário
        totalVCards30.value = totalTransactions;
        totalVCardBalance30.value = totalBalance.toFixed(2);

    } catch (error) {
        console.error("Erro ao calcular saldo e número de transações:", error);
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
        } else if (userStore.user.user_type == "V") {
            await TotalBalance();
            await TotalBalanceFor30Days(); 
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
                <h6>X: {{ totalVCardBalance30 }}€</h6>
                <h6>X: {{ totalVCards30 }}€</h6>
                
            </div>
            
            <div v-if="userStore.user.user_type=='V'">
                <h2>para vCard</h2>
                <h6>Total de vCards: {{ totalVCards }}</h6>
                <h6>Contagem de balanço total: {{ totalVCardBalance }}€</h6>
                <h6>X: {{ totalVCardBalance30 }}€</h6>
                <h6>X: {{ totalVCards30 }}€</h6>
                
            </div>
            <div v-else>
                <p>Carregando estatísticas...</p>
            </div>
        </div>
    </div>
</template>
