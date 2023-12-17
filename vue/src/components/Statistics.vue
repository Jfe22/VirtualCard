<script setup>
import { ref, inject, onMounted } from 'vue'
import { useUserStore } from "@/stores/user.js";
import { useRouter } from "vue-router"

const axios = inject("axios");
const userStore = useUserStore();
const router = useRouter();

const vCardStats = ref(null);  // Para armazenar as estatísticas dos vCards
const isAdmin = ref(false);

const fetchVCardStats = async () => {
    try {
          const response = await axios.get(`vcards/${userStore.vcardNmr}/transactions`);
        //const response = await axios.get(`vcards/${userStore.user?.customer[0].id}/stats`);
        vCardStats.value = response.data;
    } catch (error) {
        console.error(error);
    }
};

onMounted(() => {
    if (!userStore.user) {
        router.push('/stats');
    } else {
        userStore.loadUser();

        // Verifica se o utilizador é um administrador
        isAdmin.value = userStore.user.user_type

        // Carrega estatísticas
        if (isAdmin.value == "A") {
            fetchVCardStats();
        }
    }
});
</script>

<template>
  <div>
    <h1>Estatísticas</h1>

    <template v-if="userStore.user">
      <div v-if="userStore.user.type === 'vCard'">
        <h2>Estatísticas para vCards</h2>
        <div v-if="vCardStats">
          <h6>Contagem atual de vCards ativos: {{ vCardStats.activeVCardCount }}</h6>
          <h6>Soma dos saldos atuais de vCard: {{ vCardStats.totalVCardBalance }}</h6>
          <h6>Contagem ou soma de todas as transações em um período específico: {{ vCardStats.transactionsInTimeFrame }}</h6>
          <h6>Total de transações por tipo de pagamento: {{ vCardStats.transactionsByPaymentType }}</h6>
        </div>
        <div v-else>
          <p>Carregando estatísticas...</p>
        </div>
      </div>
    </template>
  </div>
</template>
