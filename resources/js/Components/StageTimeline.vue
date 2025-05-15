<template>
  <div class="stage-timeline">
    <div class="timeline-container">
      <!-- Barre de progression avec pourcentage -->
      <div class="progress-container">
        <div class="timeline-bar">
          <div class="timeline-progress" :style="{ width: progressPercentage + '%' }">
            <div class="progress-indicator" v-if="progressPercentage > 0 && progressPercentage < 100">
              <div class="progress-percentage">{{ Math.round(progressPercentage) }}%</div>
            </div>
          </div>
        </div>
        <div class="progress-status">
          <span class="progress-text">Progression: {{ Math.round(progressPercentage) }}%</span>
          <span class="progress-days" v-if="daysRemaining > 0">
            {{ daysRemaining }} jour{{ daysRemaining > 1 ? 's' : '' }} restant{{ daysRemaining > 1 ? 's' : '' }}
          </span>
          <span class="progress-days completed" v-else-if="progressPercentage >= 100">
            Stage terminé
          </span>
          <span class="progress-days not-started" v-else>
            Stage non commencé
          </span>
        </div>
      </div>

      <!-- Points de repère -->
      <div class="timeline-markers">
        <div
          v-for="(marker, index) in markers"
          :key="index"
          class="timeline-marker"
          :class="{ 'active': marker.active, 'completed': marker.completed }"
          :style="{ left: marker.position + '%' }"
        >
          <div class="marker-point"></div>
          <div class="marker-label" :class="{ 'top': index % 2 === 0, 'bottom': index % 2 !== 0 }">
            <div class="marker-date">{{ marker.date }}</div>
            <div class="marker-title">{{ marker.title }}</div>
          </div>
        </div>
      </div>

      <!-- Dates de début et fin -->
      <div class="timeline-dates">
        <div class="start-date">
          <span class="date-label">Début:</span>
          {{ formatDate(startDate) }}
        </div>
        <div class="end-date">
          <span class="date-label">Fin:</span>
          {{ formatDate(endDate) }}
        </div>
      </div>
    </div>

    <!-- Légende -->
    <div class="timeline-legend">
      <div class="legend-item">
        <div class="legend-marker completed"></div>
        <div class="legend-text">Étape terminée</div>
      </div>
      <div class="legend-item">
        <div class="legend-marker active"></div>
        <div class="legend-text">Étape actuelle</div>
      </div>
      <div class="legend-item">
        <div class="legend-marker"></div>
        <div class="legend-text">Étape à venir</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  startDate: {
    type: [Date, String],
    required: true
  },
  endDate: {
    type: [Date, String],
    required: true
  },
  currentDate: {
    type: [Date, String],
    default: () => new Date()
  },
  events: {
    type: Array,
    default: () => []
  }
});

// Convertir les dates en objets Date
const start = computed(() => new Date(props.startDate));
const end = computed(() => new Date(props.endDate));
const current = computed(() => new Date(props.currentDate));

// Calculer le nombre de jours restants
const daysRemaining = computed(() => {
  if (current.value > end.value) return 0;
  if (current.value < start.value) return Math.ceil((end.value - start.value) / (1000 * 60 * 60 * 24));

  const remainingTime = end.value - current.value;
  return Math.ceil(remainingTime / (1000 * 60 * 60 * 24));
});

// Calculer le pourcentage de progression
const progressPercentage = computed(() => {
  if (current.value < start.value) return 0;
  if (current.value > end.value) return 100;

  const totalDuration = end.value - start.value;
  const elapsed = current.value - start.value;

  // Calculer le pourcentage avec une précision de 2 décimales pour une animation plus fluide
  return Math.min(100, Math.max(0, parseFloat(((elapsed / totalDuration) * 100).toFixed(2))));
});

// Générer les marqueurs pour la timeline
const markers = computed(() => {
  // Commencer avec les dates de début et fin
  const baseMarkers = [
    {
      date: formatDate(start.value),
      title: 'Début du stage',
      position: 0,
      active: false,
      completed: current.value >= start.value
    },
    {
      date: formatDate(end.value),
      title: 'Fin du stage',
      position: 100,
      active: false,
      completed: current.value >= end.value
    }
  ];

  // Ajouter les événements personnalisés
  const eventMarkers = props.events.map(event => {
    const eventDate = new Date(event.date);
    const position = calculatePosition(eventDate);

    return {
      date: formatDate(eventDate),
      title: event.title,
      position: position,
      active: isSameDay(eventDate, current.value),
      completed: eventDate < current.value
    };
  });

  // Ajouter un marqueur pour la date actuelle si elle est dans la période du stage
  let allMarkers = [...baseMarkers, ...eventMarkers];

  if (current.value >= start.value && current.value <= end.value) {
    const todayPosition = calculatePosition(current.value);

    // Vérifier si un marqueur existe déjà pour aujourd'hui
    const todayExists = allMarkers.some(marker =>
      Math.abs(marker.position - todayPosition) < 1 && marker.active
    );

    if (!todayExists) {
      allMarkers.push({
        date: formatDate(current.value),
        title: 'Aujourd\'hui',
        position: todayPosition,
        active: true,
        completed: false
      });
    }
  }

  // Trier les marqueurs par position
  return allMarkers.sort((a, b) => a.position - b.position);
});

// Calculer la position en pourcentage pour une date donnée
function calculatePosition(date) {
  const totalDuration = end.value - start.value;
  const elapsed = date - start.value;

  return Math.max(0, Math.min(100, Math.round((elapsed / totalDuration) * 100)));
}

// Formater une date
function formatDate(date) {
  return new Date(date).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  });
}

// Vérifier si deux dates sont le même jour
function isSameDay(date1, date2) {
  return (
    date1.getDate() === date2.getDate() &&
    date1.getMonth() === date2.getMonth() &&
    date1.getFullYear() === date2.getFullYear()
  );
}
</script>

<style scoped>
.stage-timeline {
  margin: 2rem 0;
  padding: 1.5rem;
  background-color: #f9fafb;
  border-radius: 0.75rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.timeline-container {
  position: relative;
  padding: 1.5rem 0;
}

.progress-container {
  margin-bottom: 2rem;
}

.timeline-bar {
  position: relative;
  height: 8px;
  background-color: #e5e7eb;
  border-radius: 4px;
  margin: 1rem 0;
  overflow: hidden;
}

.timeline-progress {
  position: absolute;
  height: 100%;
  background: linear-gradient(90deg, #10b981, #3b82f6);
  border-radius: 4px;
  transition: width 0.8s ease;
  box-shadow: 0 0 5px rgba(59, 130, 246, 0.5);
}

.progress-indicator {
  position: absolute;
  right: 0;
  top: -12px;
  transform: translateX(50%);
}

.progress-percentage {
  background-color: #3b82f6;
  color: white;
  font-size: 0.75rem;
  font-weight: bold;
  padding: 2px 6px;
  border-radius: 10px;
  white-space: nowrap;
}

.progress-status {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 0.5rem;
  font-size: 0.875rem;
}

.progress-text {
  font-weight: 600;
  color: #4b5563;
}

.progress-days {
  color: #6b7280;
  font-weight: 500;
}

.progress-days.completed {
  color: #10b981;
  font-weight: 600;
}

.progress-days.not-started {
  color: #9ca3af;
}

.timeline-markers {
  position: relative;
  height: 120px;
  margin-top: 2rem;
}

.timeline-marker {
  position: absolute;
  transform: translateX(-50%);
  z-index: 10;
}

.marker-point {
  width: 18px;
  height: 18px;
  border-radius: 50%;
  background-color: #e5e7eb;
  border: 3px solid #fff;
  box-shadow: 0 0 0 2px #e5e7eb;
  margin: 0 auto;
  transition: all 0.3s ease;
}

.timeline-marker.active .marker-point {
  background-color: #3b82f6;
  box-shadow: 0 0 0 2px #93c5fd, 0 0 10px rgba(59, 130, 246, 0.5);
  transform: scale(1.1);
}

.timeline-marker.completed .marker-point {
  background-color: #10b981;
  box-shadow: 0 0 0 2px #a7f3d0, 0 0 10px rgba(16, 185, 129, 0.5);
}

.marker-label {
  position: absolute;
  width: 140px;
  text-align: center;
  font-size: 0.875rem;
  color: #4b5563;
  transform: translateX(-50%);
  background-color: white;
  padding: 0.5rem;
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.marker-label.top {
  bottom: 30px;
}

.marker-label.bottom {
  top: 30px;
}

.timeline-marker:hover .marker-label {
  transform: translateX(-50%) scale(1.05);
  z-index: 20;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.marker-date {
  font-size: 0.75rem;
  color: #6b7280;
  margin-bottom: 0.25rem;
}

.marker-title {
  font-weight: 600;
}

.timeline-dates {
  display: flex;
  justify-content: space-between;
  margin-top: 1.5rem;
  font-size: 0.875rem;
  color: #6b7280;
}

.date-label {
  font-weight: 600;
  color: #4b5563;
}

.timeline-legend {
  display: flex;
  justify-content: center;
  gap: 2rem;
  margin-top: 2rem;
  padding-top: 1rem;
  border-top: 1px solid #e5e7eb;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.875rem;
  color: #4b5563;
}

.legend-marker {
  width: 14px;
  height: 14px;
  border-radius: 50%;
  background-color: #e5e7eb;
  border: 2px solid #fff;
  box-shadow: 0 0 0 2px #e5e7eb;
}

.legend-marker.active {
  background-color: #3b82f6;
  box-shadow: 0 0 0 2px #93c5fd;
}

.legend-marker.completed {
  background-color: #10b981;
  box-shadow: 0 0 0 2px #a7f3d0;
}
</style>
