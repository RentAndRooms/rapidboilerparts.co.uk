<template>
  <CustomerLayout>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-5">

      <!-- Single Product Card -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow hover:shadow-lg transition p-4 flex flex-col">
        <img src="https://via.placeholder.com/300x200" alt="Product" class="rounded-xl object-cover h-48 w-full">

        <div class="mt-4 flex-1">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Nike Air Max</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400">Brand: Nike</p>
        </div>

        <div class="mt-4 flex items-center justify-between">
          <span class="text-xl font-bold text-indigo-600">$120</span>
          <button class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
            Add to Cart
          </button>
        </div>
      </div>

      <!-- Another Product -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow hover:shadow-lg transition p-4 flex flex-col">
        <img src="https://via.placeholder.com/300x200" alt="Product" class="rounded-xl object-cover h-48 w-full">

        <div class="mt-4 flex-1">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Adidas Ultraboost</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400">Brand: Adidas</p>
        </div>

        <div class="mt-4 flex items-center justify-between">
          <span class="text-xl font-bold text-indigo-600">$140</span>
          <button class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
            Add to Cart
          </button>
        </div>
      </div>

    </div>
  </CustomerLayout>
</template>

<script>
import CustomerLayout from "@/Layouts/CustomerLayout.vue";
import { Link } from "@inertiajs/vue3";
import AOS from "aos";
import "aos/dist/aos.css";
import "aos/dist/aos.css";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import timezone from "dayjs/plugin/timezone";

dayjs.extend(utc);
dayjs.extend(timezone);

export default {
  name: "Home",
  components: {
    CustomerLayout,
    Link,
  },
  props: {
    branches: {
      type: Array,
      required: true,
    },
    locals: {
      type: Array,
      required: true,
    },
  },
  mounted() {
    AOS.init({
      duration: 1000,
      once: true,
      offset: 50,
      delay: 50,
      easing: "ease-in-out",
    });
    this.filterLocal = this.locals;
    this.filteredBranches = this.branches;
    this.branches.forEach((branch) => {
      if (branch.opening_hours?.length > 0) {
        this.selectedDay[branch.id] = branch.opening_hours[0].day;
      }
    });
  },
  data() {
    return {
      searchQuery: "",
      showDropDown: false,
      filteredBranches: [],
      filterLocal: [],
      selectedDay: {},
      floatingItems: [
        {
          icon: "fas fa-pizza-slice",
          class: "top-1/4 left-1/4 animate-float-slow",
        },
        {
          icon: "fas fa-hamburger",
          class: "top-1/3 right-1/4 animate-float-slower",
        },
        {
          icon: "fas fa-ice-cream",
          class: "bottom-1/4 left-1/3 animate-float",
        },
        {
          icon: "fas fa-coffee",
          class: "top-1/2 right-1/3 animate-float-slow",
        },
        {
          icon: "fas fa-utensils",
          class: "bottom-1/3 right-1/4 animate-float-slower",
        },
      ],
    };
  },
  methods: {
    check() {
      this.showDropDown = true;
      this.filteredBranches = this.branches;
      const query = this.searchQuery.toLowerCase();
      this.filterLocal = this.locals.filter((local) =>
        local.name.toLowerCase().includes(query)
      );
    },
    getDirectionsUrl(branch) {
      const destination = `${branch.latitude},${branch.longitude}`;
      return `https://www.google.com/maps/dir/?api=1&destination=${destination}`;
    },
    isOpen(hours) {
      if (!hours) return false;
      const now = new Date();
      const day = now.toLocaleDateString("en-US", { weekday: "long" });
      const time = now.toLocaleTimeString("en-US", { hour12: false });
      const todayHours = hours.find((h) => h.day === day);
      return todayHours
        ? time >= todayHours.open && time <= todayHours.close
        : false;
    },
    formatDistance(distance) {
      if (!distance) return "Distance unknown";
      return `${distance.toFixed(1)} km away`;
    },
    formatTime(time) {
      return dayjs(`1970-01-01 ${time}`).tz("Asia/Dhaka").format("hh:mm A");
    },
    selectedArea(value) {
      this.searchQuery = value.name;
      this.showDropDown = false;
      console.log(value.division_id);
      if (value.division_id <= 0) {
        console.log(value);
        this.filteredBranches = this.branches.filter(
          (branch) => branch.local_id === value.own_id
        );
      } else {
        this.filteredBranches = this.branches.filter(
          (branch) => branch.thana_id === value.id
        );
      }
    },
  },
};
</script>


<style scoped>
/* Background Grid Pattern */
.bg-grid-pattern {
  background-image: radial-gradient(circle, #ea580c 0.5px, transparent 0.5px);
  background-size: 24px 24px;
}

/* Enhanced Floating Animations */
@keyframes float {

  0%,
  100% {
    transform: translateY(0) rotate(0deg);
    opacity: 0.15;
  }

  50% {
    transform: translateY(-20px) rotate(2deg);
    opacity: 0.2;
  }
}

@keyframes float-slow {

  0%,
  100% {
    transform: translateY(0) rotate(0deg);
    opacity: 0.15;
  }

  50% {
    transform: translateY(-30px) rotate(-2deg);
    opacity: 0.2;
  }
}

@keyframes float-slower {

  0%,
  100% {
    transform: translateY(0) rotate(0deg);
    opacity: 0.15;
  }

  50% {
    transform: translateY(-40px) rotate(3deg);
    opacity: 0.2;
  }
}

.animate-float {
  animation: float 4s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}

.animate-float-slow {
  animation: float-slow 6s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}

.animate-float-slower {
  animation: float-slower 8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}

/* Enhanced Gradient Animation */
@keyframes gradient {
  0% {
    background-position: 0% 50%;
  }

  50% {
    background-position: 100% 50%;
  }

  100% {
    background-position: 0% 50%;
  }
}

.animate-gradient {
  background-size: 200% auto;
  animation: gradient 6s linear infinite;
}

/* Card Hover Effects */
.feature-card {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.feature-card:hover {
  transform: translateY(-4px);
}

/* Category Card Hover Effects */
.category-card {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.category-card:hover {
  transform: scale(1.05);
}

/* Button Hover Effects */
.btn-hover-effect {
  position: relative;
  overflow: hidden;
}

.btn-hover-effect::after {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  width: 120%;
  height: 120%;
  background: radial-gradient(circle,
      rgba(255, 255, 255, 0.2) 0%,
      transparent 70%);
  transform: translate(-50%, -50%) scale(0);
  transition: transform 0.5s;
}

.btn-hover-effect:hover::after {
  transform: translate(-50%, -50%) scale(1);
}

/* Smooth Scrolling */
html {
  scroll-behavior: smooth;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: transparent;
}

::-webkit-scrollbar-thumb {
  background: #ea580c;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #c2410c;
}

/* Dark Mode Scrollbar */
.dark ::-webkit-scrollbar-thumb {
  background: #f97316;
}

.dark ::-webkit-scrollbar-thumb:hover {
  background: #ea580c;
}

/* Image Loading States */
.image-loading {
  position: relative;
  overflow: hidden;
}

.image-loading::before {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(90deg,
      transparent,
      rgba(255, 255, 255, 0.1),
      transparent);
  animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
  0% {
    transform: translateX(-100%);
  }

  100% {
    transform: translateX(100%);
  }
}

/* Section Transitions */
.section-fade-up {
  opacity: 0;
  transform: translateY(20px);
  transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.section-fade-up.visible {
  opacity: 1;
  transform: translateY(0);
}

/* Responsive Adjustments */
@media (max-width: 640px) {
  .hero-content {
    padding-top: 2rem;
    padding-bottom: 2rem;
  }

  .floating-items {
    display: none;
  }
}

@media (max-width: 768px) {
  .category-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (min-width: 1024px) {
  .hero-content {
    padding-top: 4rem;
    padding-bottom: 4rem;
  }
}
</style>
