<template>
  <div class="p-4">
    <button @click="analizarOferta" :disabled="cargando" class="btn btn-primary" style="background-color: blue; color: white; padding: 10px; border-radius: 5px;">
      {{ cargando ? 'Analizando...' : 'Analizar con IA' }}
    </button>

    <div v-if="resultado" class="mt-3" style="margin-top: 15px; color: green; font-weight: bold;">
      Resultado: {{ resultado }}
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const resultado = ref(null);
const cargando = ref(false);

const analizarOferta = () => {
  cargando.value = true;
  setTimeout(() => {
    resultado.value = "¡La IA dice que esta oferta es perfecta para ti!";
    cargando.value = false;
  }, 1500);
};
</script>