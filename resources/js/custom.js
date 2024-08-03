import { Gradient } from './gradient.js'

// Create your instance
const gradient = new Gradient()

document.addEventListener('livewire:navigated', function(event) {

    console.log('Livewire navigation ');
    // Call `initGradient` with the selector to your canvas
    gradient.initGradient('#gradient-canvas')
});
