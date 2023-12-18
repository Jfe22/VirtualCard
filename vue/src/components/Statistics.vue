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
const totalVCardBalance30 = ref(0);
const totalVCards30 = ref(0);
const transactions = ref([]);

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

const getAllTransactions = async () => {
    try {
        const response = await axios.get('transactions');
        transactions.value = response.data.data;
    } catch (error) {
        console.error(error);
    }
}

const filterTransactionsByUsername = () => {
    if (userStore.user) {
        transactions.value = transactions.value.filter(transaction => transaction.vcard === userStore.user.username);
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
            await getAllTransactions();
            filterTransactionsByUsername(); 
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
                <div v-if="transactions.length > 0">
                <h3>Transações:</h3>
                <ul>
                    <li v-for="transaction in transactions" :key="transaction.id">
                        ID:{{ transaction.id }} 
                        Valor:{{ transaction.value }}€
                    </li>
                </ul>
            </div>
            <div v-else>
                <p>Sem transações disponíveis.</p>
            </div>
            </div>
            <div v-else>
                <p>Carregando estatísticas...</p>
            </div>
        </div>
    </div>
</template>
