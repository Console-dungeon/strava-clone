<script setup lang="ts">
import type { Map, Polyline } from 'leaflet';
import { onMounted, onUnmounted, ref, watch } from 'vue';

interface RoutePoint {
  lat: number;
  lng: number;
  ele?: number;
}

const props = defineProps<{
  routePoints: RoutePoint[];
}>();

const mapEl = ref<HTMLElement | null>(null);
let map: Map | null = null;
let polyline: Polyline | null = null;

onMounted(async () => {
  const L = await import('leaflet');
  await import('leaflet/dist/leaflet.css');

  if (!mapEl.value) return;
  map = L.map(mapEl.value);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
  }).addTo(map);

  drawRoute(L);
});

onUnmounted(() => {
  map?.remove();
  map = null;
});

watch(
  () => props.routePoints,
  async () => {
    if (!map) return;
    const L = await import('leaflet');
    drawRoute(L);
  },
);

async function drawRoute(L: typeof import('leaflet')) {
  if (!map || props.routePoints.length === 0) return;

  polyline?.remove();

  const latlngs = props.routePoints.map(
    (p) => [p.lat, p.lng] as [number, number],
  );

  polyline = L.polyline(latlngs, { color: '#f97316', weight: 3 }).addTo(map);

  map.fitBounds(polyline.getBounds(), { padding: [20, 20] });
}
</script>

<template>
  <div ref="mapEl" class="h-64 w-full rounded-lg" />
</template>
