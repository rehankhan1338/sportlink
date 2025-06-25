<template>
    <section class="py-8">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-6">
          <h1 class="text-white text-3xl font-semibold mb-4">Support</h1>
        </div>
  
        <!-- Athlete Section -->
        <div class="flex flex-col md:flex-row items-center gap-4 mb-6 border border-1 p-6 rounded-lg shadow-lg">
          <div class="flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
            </svg>
          </div>
          <div class="flex-grow text-center md:text-left">
            <h2 class="text-white text-xl font-semibold mb-2">Athlete?</h2>
            <p class="text-gray-400 text-sm mb-4">Contact your event organizer for questions about the event.</p>
            <div class="flex flex-col md:flex-row gap-2 justify-center md:justify-start">
              <a href="#" target="_blank" class="btn-sm bg-blue-600 text-white rounded-md px-4 py-2 hover:bg-blue-700">
                Knowledge base
              </a>
              <a href="#" class="btn-sm bg-transparent border border-blue-600 text-blue-600 rounded-md px-4 py-2 hover:bg-blue-700 hover:text-white">
                Contact your organizer
              </a>
            </div>
          </div>
        </div>
  
        <!-- Organizer Section -->
        <div class="flex flex-col md:flex-row items-center gap-4 mb-6 border border-1 p-6 rounded-lg shadow-lg">
          <div class="flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z" />
            </svg>
          </div>
          <div class="flex-grow text-center md:text-left">
            <h2 class="text-white text-xl font-semibold mb-2">Organizer?</h2>
            <p class="text-gray-400 text-sm mb-4">Start using Sportlink for your tournaments. Submit a ticket or explore the resources below.</p>
            <div class="flex flex-wrap gap-2 justify-center md:justify-start">
              <a href="#" target="_blank" class="btn-sm bg-blue-600 text-white rounded-md px-4 py-2 hover:bg-blue-700">
                Knowledge base
              </a>
              <a href="#" target="_blank" class="btn-sm bg-blue-600 text-white rounded-md px-4 py-2 hover:bg-blue-700">
                Create organizer account
              </a>
              <a href="#" target="_blank" class="btn-sm bg-blue-600 text-white rounded-md px-4 py-2 hover:bg-blue-700">
                View agreements
              </a>
            </div>
          </div>
        </div>
  
        <!-- Technical Support Section -->
        <div class="flex flex-col md:flex-row items-center gap-4 mb-6 border border-1 p-6 rounded-lg shadow-lg">
          <div class="flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
              <path d="M20 14h-2v-4h2v4zm-2-10h-2V2h-4v2H6V2H2v2H0v14h2v2h4v-2h8v2h4v-2h2V4h-2V2zm-2 14H4v-8h12v8z" />
            </svg>
          </div>
          <div class="flex-grow text-center md:text-left">
            <h2 class="text-white text-xl font-semibold mb-2">Technical support</h2>
            <p class="text-gray-400 text-sm mb-4">Have questions about the Sportlink system? Let us help you out.</p>
            <a href="javascript:void(0)" onclick="Beacon('open')" class="btn-sm bg-blue-600 text-white rounded-md px-4 py-2 hover:bg-blue-700">
              Submit ticket
            </a>
          </div>
        </div>
  
        <!-- Press and Media Section -->
        <div class="flex flex-col md:flex-row items-center gap-4 border border-1 p-6 rounded-lg shadow-lg">
          <div class="flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2C5.92 2 1 6.92 1 13c0 4.78 3.29 8.82 7.76 9.76L12 22l3.24-.24C19.71 21.82 23 17.78 23 13c0-6.08-4.92-11-11-11zm0 20c-5.52 0-10-4.48-10-10S6.48 2 12 2s10 4.48 10 10-4.48 10-10 10z" />
            </svg>
          </div>
          <div class="flex-grow text-center md:text-left">
            <h2 class="text-white text-xl font-semibold mb-2">Press and media</h2>
            <p class="text-gray-400 text-sm mb-4">High-resolution downloads for Sportlink assets.</p>
            <div class="flex flex-wrap gap-2 justify-center md:justify-start">
              <a href="#" class="btn-sm bg-blue-600 text-white rounded-md px-4 py-2 hover:bg-blue-700">
                Download Sportlink logo
              </a>
              <a href="#" class="btn-sm bg-blue-600 text-white rounded-md px-4 py-2 hover:bg-blue-700">
                Download Smoothstreamer logo
              </a>
              <a href="#" class="btn-sm bg-blue-600 text-white rounded-md px-4 py-2 hover:bg-blue-700">
                Download press images
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <style scoped>
  .bg-darkblue {
    background-color: #1a1e2d;
    color: #d7d9dd;
  }
  
  .text-white {
    color: #fff;
  }
  
  .text-gray-400 {
    color: #9ca3af;
  }
  
  .btn-sm {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    display: inline-block;
    text-align: center;
    font-weight: 500;
    line-height: 1.25rem;
    transition: background-color 0.3s ease;
  }
  
  .btn-sm:hover {
    background-color: #0056b3;
  }
  
  .shadow-lg {
    box-shadow: 0px 10px 15px -3px rgba(0, 0, 0, 0.1), 0px 4px 6px -4px rgba(0, 0, 0, 0.1);
  }
  </style>
  