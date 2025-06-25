<template>
    <div class="cms-menu sc-event-submenu px-4 py-1 border-t border-b border-[#2e2e2e] bg-transparent">
      <div class="container mx-auto">
      <ul class="sc-nav sc-nav-sm sc-nav-pills sc-nav-container py-2 pt-[8px] pb-[8px] mt-[6px] mb-[6px]">
        <li :class="{'active bg-white text-black': activeTab === 'info', 'not-active text-white': activeTab !== 'info'}" class="noChilds rounded-md h-[34px] p-[17px]">
          <a href="#" @click.prevent="setActiveTab('info')" class="flex items-center">
            <span>Information</span>
          </a>
        </li>
        <li :class="{'active': activeTab === 'location', 'not-active': activeTab !== 'location'}" class="noChilds text-white h-[34px] p-[17px]">
          <a href="#" @click.prevent="setActiveTab('location')" class="flex items-center">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M5 9.81081C5 13.7003 8.93256 18.0452 10.9025 19.9778C11.5343 20.5975 11.9643 20.9692 11.9979 20.9982C11.9997 20.9998 12 21.0006 12 21.0006C12 21.0006 12.0002 20.9998 12.0021 20.9982C12.0357 20.9692 12.4657 20.5975 13.0975 19.9778C15.0674 18.0452 19 13.7003 19 9.81081C19 6.0493 15.866 3 12 3C8.13401 3 5 6.0493 5 9.81081ZM16.7189 18.2299C14.9956 20.3587 13.2613 21.891 13.0081 22.1112C12.9764 22.1401 12.9408 22.17 12.9013 22.1996C12.7324 22.3266 12.4231 22.5005 12.0002 22.5006C11.5772 22.5006 11.2678 22.3267 11.0988 22.1997C11.0593 22.17 11.0237 22.1401 10.9919 22.1112C10.7387 21.891 9.00442 20.3587 7.28115 18.2299C6.37395 17.1092 5.44349 15.7912 4.73445 14.3843C4.03063 12.9877 3.5 11.4153 3.5 9.81081C3.5 5.1824 7.34459 1.5 12 1.5C16.6554 1.5 20.5 5.1824 20.5 9.81081C20.5 11.4153 19.9694 12.9877 19.2655 14.3843C18.5565 15.7912 17.6261 17.1092 16.7189 18.2299ZM12 8C13.1046 8 14 8.89543 14 10C14 11.1046 13.1046 12 12 12C10.8954 12 10 11.1046 10 10C10 8.89543 10.8954 8 12 8Z" :fill="activeTab === 'location' ? '#000000' : '#F4F6FB'" fill-opacity="0.9"></path>
            </svg>
            <span>Location &amp; Accommodation</span>
          </a>
        </li>
      </ul>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const activeTab = ref('info');
  
  const emit = defineEmits(['tab-changed']);
  
  const setActiveTab = (tab) => {
    activeTab.value = tab;
    emit('tab-changed', tab);
  };
  </script>
  
  <style scoped>
  .sc-nav {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin: 0;
    padding: 0;
    list-style: none;
    white-space: nowrap;
  }
  
  .sc-nav li {
    display: flex;
  }
  
  .sc-nav li a {
    display: flex;
    align-items: center;
  }
  
  .sc-nav li.active {
    background-color: white;
    color: black;
    border-radius: 25px;
  }
  
  
  .sc-nav li span {
    margin-left: 0.5rem;
  }
  </style>
  