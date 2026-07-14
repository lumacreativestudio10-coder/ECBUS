require('./bootstrap');

import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.data('bookingForm', () => ({
        from: '',
        to: '',
        date: '',
        passengers: 1,
        errors: {},

        validate() {
            this.errors = {};
            
            if (!this.from) this.errors.from = 'Departure city is required.';
            if (!this.to) this.errors.to = 'Destination city is required.';
            if (this.from && this.to && this.from === this.to) {
                this.errors.to = 'Destination cannot be the same as departure.';
            }

            if (!this.date) {
                this.errors.date = 'Travel date is required.';
            } else {
                const selectedDate = new Date(this.date);
                const today = new Date();
                today.setHours(0,0,0,0);
                if (selectedDate < today) {
                    this.errors.date = 'Travel date cannot be in the past.';
                }
            }

            if (this.passengers < 1) {
                this.errors.passengers = 'At least 1 passenger is required.';
            }

            if (Object.keys(this.errors).length === 0) {
                alert('Searching for buses from ' + this.from + ' to ' + this.to + ' on ' + this.date);
            }
        }
    }));
});

Alpine.start();
