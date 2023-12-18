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
const transactions = ref([]);
const transactions30 = ref([]);
const transactions30Sum = ref([]);
const averageTransactionValue = ref(0);
const minTransactionValue = ref(0);
const maxTransactionValue = ref(0);

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

const getAllTransactionsAvarage = async () => {
    try {
        const response = await axios.get('transactions');
        transactions.value = response.data.data;

        let totalValue = 0;
        let validTransactionCount = 0;

        for (const transaction of transactions.value) {
            const transactionValue = parseFloat(transaction.value);

            if (!isNaN(transactionValue)) {
                totalValue += transactionValue;
                validTransactionCount++;
            }
        }

        const averageValue = validTransactionCount > 0 ? (totalValue / validTransactionCount).toFixed(2) : 0;
        
        averageTransactionValue.value = averageValue;

        console.log('Average Value:', averageValue);
    } catch (error) {
        console.error(error);
    }
};

const getAllTransactionsMin = async () => {
    try {
        const response = await axios.get('transactions');
        transactions.value = response.data.data;

        if (transactions.value.length === 0) {
            console.log('No transactions available.');
            return 0;
        }

        let minTransaction = parseFloat(transactions.value[0].value);

        for (const transaction of transactions.value) {
            const transactionValue = parseFloat(transaction.value);

            if (!isNaN(transactionValue) && transactionValue < minTransaction) {
                minTransaction = transactionValue;
            }
        }

        console.log('Minimum Transaction Value:', minTransaction);
        minTransactionValue.value = minTransaction.toFixed(2);
        return minTransactionValue.value;
    } catch (error) {
        console.error(error);
        return 0;
    }
};

const getAllTransactionsMax = async () => {
    try {
        const response = await axios.get('transactions');
        transactions.value = response.data.data;

        if (transactions.value.length === 0) {
            console.log('No transactions available.');
            return 0;
        }

        let maxTransaction = parseFloat(transactions.value[0].value);

        for (const transaction of transactions.value) {
            const transactionValue = parseFloat(transaction.value);

            if (!isNaN(transactionValue) && transactionValue > maxTransaction) {
                maxTransaction = transactionValue;
            }
        }

        maxTransactionValue.value = maxTransaction.toFixed(2);
        return maxTransactionValue.value;
    } catch (error) {
        console.error(error);
        return 0;
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

const getAllTransactions30Date = async () => {
    try {
        const response = await axios.get('transactions');
        transactions30.value = response.data.data;

        // Filtrar transações para os últimos 30 dias
        const today = new Date();
        const thirtyDaysAgo = new Date(today);
        thirtyDaysAgo.setDate(today.getDate() - 30);

        transactions30.value = transactions30.value.filter(transaction => {
            const transactionDate = new Date(transaction.date);
            return transactionDate >= thirtyDaysAgo && transactionDate <= today;
        });
    } catch (error) {
        console.error(error);
    }
}

const getAllTransactions30DateSum = async () => {
    try {
        const response = await axios.get('transactions');
        const allTransactions = response.data.data;

        const today = new Date();
        const thirtyDaysAgo = new Date();
        thirtyDaysAgo.setDate(today.getDate() - 30);

        let sumOfValues = 0;

        for (const transaction of allTransactions) {
            const transactionDate = new Date(transaction.date);

            if (transactionDate.getTime() >= thirtyDaysAgo.getTime() && transactionDate.getTime() <= today.getTime()) {
                sumOfValues += parseFloat(transaction.value);
            }
        }

        transactions30Sum.value = sumOfValues.toFixed(2);
        console.log('transactions30Sum.value:', transactions30Sum.value);
    } catch (error) {
        console.error(error);
    }
};

const filterTransactionsByUsername = () => {
    if (userStore.user) {
        transactions.value = transactions.value.filter(transaction => transaction.vcard === userStore.user.username);

        transactions30.value = transactions30.value.filter(transaction => transaction.vcard === userStore.user.username);
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
            await getAllTransactions30DateSum();
            await getAllTransactionsAvarage();
            await getAllTransactionsMin();
            await getAllTransactionsMax();
        } else if (userStore.user.user_type == "V") {
            await TotalBalance();
            await getAllTransactions();
            await getAllTransactions30Date();
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
                <h2>Para Admin</h2>
                <h6>Total de vCards: {{ totalVCards }}</h6>
                <h6>Contagem de balanço total: {{ totalVCardBalance }}€</h6>
                <h6>Contagem das transações dos ultimos 30 dias: {{ transactions30Sum }}€</h6>
                <h6>Média de valores das transações: {{ averageTransactionValue }}€</h6>
                <h6>Valor mínimo das transações: {{ minTransactionValue }}€</h6>
                <h6>Valor máximo das transações: {{ maxTransactionValue }}€</h6>
            </div>
            <div v-if="userStore.user.user_type=='V'">
                <h2>Para vCard</h2>
                <h6>Contagem de balanço total: {{ totalVCardBalance }}€</h6>
                <div v-if="transactions.length > 0">
                <h3>Transações:</h3>
                <ul>
                    <li v-for="transaction in transactions" :key="transaction.id">
                        ID:{{ transaction.id }} 
                        Valor:{{ transaction.value }}€
                    </li>
                </ul>
                <h3>Transações dos Ultimos 30 dias:</h3>
                <ul>
                    <li v-for="transaction30 in transactions30" :key="transaction30.id">
                        ID:{{ transaction30.id }} 
                        Valor:{{ transaction30.value }}€
                        Date: {{ transaction30.date }}
                    </li>
                </ul>
            </div>
            <div v-else>
                <p>Sem transações disponíveis.</p>
            </div>
            <div v-else>
                <p>Carregando estatísticas...</p>
            </div>
        </div>
    </div>
    </div>
</template>
