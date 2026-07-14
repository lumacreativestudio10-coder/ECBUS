@extends('layouts.app')

@section('title', 'Contact Us - ECBUS')

@section('content')

<!-- Hero Section -->
<section class="relative pt-24 pb-20 lg:pt-32 lg:pb-28 overflow-hidden bg-dark-maroon">
    <div class="absolute inset-0 z-0 opacity-20">
        <img src="https://images.unsplash.com/photo-1596524430615-b46475ddff6e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Contact Us" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-dark-maroon to-transparent"></div>
    </div>
    <div class="max-w-7xl mx-auto px-6 relative z-10 text-center text-white">
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 animate-fade-in-up">
            CONTACT <span class="text-primary-gold">US</span>
        </h1>
        <p class="text-lg md:text-xl font-light opacity-90 max-w-2xl mx-auto animate-fade-in-up" style="animation-delay: 0.2s;">
            We're here to help you 24/7. Reach out to us for any inquiries, support, or feedback.
        </p>
    </div>
</section>

<!-- Contact Info & Form -->
<section class="py-20 bg-cream relative z-20 -mt-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- Contact Information -->
            <div class="lg:col-span-1 space-y-8">
                <div class="bg-white p-8 rounded-3xl shadow-xl border border-gray-100">
                    <h3 class="text-2xl font-extrabold text-dark-text mb-8 border-b border-gray-100 pb-4">Get In Touch</h3>
                    
                    <div class="flex items-start mb-8">
                        <div class="w-12 h-12 bg-primary-gold/20 rounded-full flex items-center justify-center text-primary-maroon mr-4 flex-shrink-0 mt-1">
                            <i data-lucide="map-pin" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-dark-text mb-1 text-lg">Head Office</h4>
                            <p class="text-gray-600 leading-relaxed">123, Galle Road,<br>Colombo 03,<br>Sri Lanka.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start mb-8">
                        <div class="w-12 h-12 bg-primary-gold/20 rounded-full flex items-center justify-center text-primary-maroon mr-4 flex-shrink-0 mt-1">
                            <i data-lucide="phone-call" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-dark-text mb-1 text-lg">Hotlines</h4>
                            <p class="text-gray-600 leading-relaxed">+94 77 123 4567<br>+94 77 765 4321</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start mb-8">
                        <div class="w-12 h-12 bg-primary-gold/20 rounded-full flex items-center justify-center text-primary-maroon mr-4 flex-shrink-0 mt-1">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-dark-text mb-1 text-lg">Email</h4>
                            <p class="text-gray-600 leading-relaxed">support@ecbus.lk<br>info@ecbus.lk</p>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100">
                        <h4 class="font-bold text-dark-text mb-4">Follow Us</h4>
                        <div class="flex space-x-4">
                            <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 hover:bg-primary-maroon hover:text-white transition duration-300">
                                <i data-lucide="facebook" class="w-5 h-5"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 hover:bg-primary-maroon hover:text-white transition duration-300">
                                <i data-lucide="instagram" class="w-5 h-5"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 hover:bg-primary-maroon hover:text-white transition duration-300">
                                <i data-lucide="twitter" class="w-5 h-5"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white p-10 md:p-12 rounded-3xl shadow-xl border border-gray-100 h-full">
                    <h3 class="text-3xl font-extrabold text-dark-text mb-2">Send us a Message</h3>
                    <p class="text-gray-500 mb-10">Have a question about your booking? Fill out the form below and we'll get back to you immediately.</p>
                    
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-dark-text mb-2">Full Name</label>
                                <input type="text" placeholder="John Doe" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-maroon focus:border-primary-maroon font-medium text-gray-700 outline-none transition">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-dark-text mb-2">Email Address</label>
                                <input type="email" placeholder="john@example.com" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-maroon focus:border-primary-maroon font-medium text-gray-700 outline-none transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-dark-text mb-2">Phone Number</label>
                                <input type="tel" placeholder="+94 77 XXX XXXX" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-maroon focus:border-primary-maroon font-medium text-gray-700 outline-none transition">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-dark-text mb-2">Subject</label>
                                <input type="text" placeholder="Booking Inquiry" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-maroon focus:border-primary-maroon font-medium text-gray-700 outline-none transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-dark-text mb-2">Your Message</label>
                            <textarea rows="5" placeholder="How can we help you?" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-maroon focus:border-primary-maroon font-medium text-gray-700 outline-none transition resize-none"></textarea>
                        </div>

                        <button type="button" class="bg-primary-maroon text-white font-bold py-4 px-8 rounded-xl hover:bg-dark-maroon transition duration-300 shadow-md flex items-center justify-center w-full md:w-auto">
                            SEND MESSAGE <i data-lucide="send" class="w-5 h-5 ml-2"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@push('scripts')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
    }
</style>
@endpush
