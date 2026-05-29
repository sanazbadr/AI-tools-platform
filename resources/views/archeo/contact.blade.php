<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Archeo.ai</title>
    <meta name="description" content="Get in touch with Archeo.ai for AI solutions for everyone">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-blue-600">Archeo.ai</a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-gray-700 hover:text-blue-600 transition">Home</a>
                    <a href="/about" class="text-gray-700 hover:text-blue-600 transition">About</a>
                    <a href="/services" class="text-gray-700 hover:text-blue-600 transition">Services</a>
                    <a href="/contact" class="text-blue-600 font-semibold">Contact</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-20 pb-16 bg-gradient-to-br from-blue-50 to-indigo-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    Get In <span class="text-blue-600">Touch</span>
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Ready to accelerate your work with AI? Contact us to learn more about our solutions and how we can help your project succeed.
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Send Us a Message</h2>
                    <form class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                                <input type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                                <input type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Organization</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                            <select required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select a subject</option>
                                <option value="artifact-analysis">Artifact Analysis</option>
                                <option value="site-survey">Site Survey</option>
                                <option value="data-management">Data Management</option>
                                <option value="training">Training & Education</option>
                                <option value="partnership">Partnership Opportunities</option>
                                <option value="general">General Inquiry</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                            <textarea rows="6" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tell us about your project and how we can help..."></textarea>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="newsletter" class="mr-3">
                            <label for="newsletter" class="text-sm text-gray-600">
                                Subscribe to our newsletter for updates on new features and research insights
                            </label>
                        </div>
                        
                        <div>
                            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Contact Information -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Contact Information</h2>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-envelope text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">Email</h3>
                                <p class="text-gray-600">hello@archeo.ai</p>
                                <p class="text-gray-600">support@archeo.ai</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-phone text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">Phone</h3>
                                <p class="text-gray-600">+1 (555) 123-4567</p>
                                <p class="text-sm text-gray-500">Monday - Friday, 9:00 AM - 6:00 PM EST</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-map-marker-alt text-purple-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">Office</h3>
                                <p class="text-gray-600">123 AI Research Park</p>
                                <p class="text-gray-600">Cambridge, MA 02142</p>
                                <p class="text-gray-600">United States</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 p-6 bg-blue-50 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Response Time</h3>
                        <p class="text-gray-600 text-sm mb-3">
                            We typically respond to all inquiries within 24 hours during business days.
                        </p>
                        <p class="text-gray-600 text-sm">
                            For urgent matters, please call us directly or mention "URGENT" in your email subject.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Find answers to common questions about our services and how we can help your research.
                </p>
            </div>
            
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">How quickly can you process artifact analysis requests?</h3>
                    <p class="text-gray-600">
                        Standard artifact analysis is typically completed within 24-48 hours. For urgent requests, 
                        we offer expedited processing with results available in 6-12 hours.
                    </p>
                </div>
                
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Do you work with international institutions?</h3>
                    <p class="text-gray-600">
                        Yes! We work with individuals, teams, and organizations worldwide. Our platform supports multiple languages and international data standards.
                    </p>
                </div>
                
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Can you handle large-scale projects?</h3>
                    <p class="text-gray-600">
                        Absolutely. Our enterprise solutions are designed for large-scale deployments across teams and workflows. Contact us for custom pricing.
                    </p>
                </div>
                
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Do you provide training for your AI tools?</h3>
                    <p class="text-gray-600">
                        Yes, we offer comprehensive training programs including workshops, online courses, 
                        and on-site training sessions for research teams.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="text-2xl font-bold text-blue-400 mb-4">Archeo.ai</div>
                    <p class="text-gray-400">
                        Empowering everyone with practical, privacy-first AI tools.
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Services</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/services" class="hover:text-white transition">Artifact Analysis</a></li>
                        <li><a href="/services" class="hover:text-white transition">Site Survey</a></li>
                        <li><a href="/services" class="hover:text-white transition">Data Management</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Company</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/about" class="hover:text-white transition">About</a></li>
                        <li><a href="#" class="hover:text-white transition">Team</a></li>
                        <li><a href="#" class="hover:text-white transition">Careers</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Connect</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-github"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 Archeo.ai. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html> 