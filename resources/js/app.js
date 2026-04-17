import './bootstrap';
import { createApp } from 'vue';
import BotonIA from './components/BotonIA.vue';
// Quitamos la alerta para que no moleste más
console.log("Iniciando montaje de Vue...");

try {
    const app = createApp({});
    
    // Lo registramos con el nombre exacto
    app.component('boton-ia', BotonIA);
    
    app.mount('#app');
    console.log("¡Vue se ha montado en #app!");
} catch (e) {
    console.error("ERROR DE VUE:", e);
}